<?php

class Transaction extends MY_Model {

    public function __construct()
	{
	  parent::__construct();
      $this->load->library('Untils');
      $this->load->model('User');
      $this->load->library('paymentProcessors/IsavingsPortToEspiWallet');
      $this->load->model('Setting_m');
      $this->load->model('ServicesLog');
      $this->load->model('Uici_levies');
      $this->load->model('UserBalance');
      $this->load->model('Wallet');
     
	}
    
    const DEBIT = 'debit';
    const CREDIT = 'credit';
    const PERCENT = 100;
    const PENDING = 'pending';
    public static $result_count = null;

    public static function getTableName(){
        return 'transactions';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    } 

    public function beforeSave(){
        $this->created_at = date('Y-m-d H:i:s');
    }


    public static function roudUpDecimal($value){
        return ceil($value*SELF::PERCENT)/SELF::PERCENT;
    }

    public static function getUniqueReference(){
        $seedbroker = '';
        if(func_num_args() > 0){
            $seedbroker = implode('_',func_get_args());
            return 'TXN_'.$seedbroker.'_'.uniqid();
        }
        return 'TXN_'.uniqid();
    } 

    public static function debit($user_id, $val, $wallet_id, $reference = '', $description = '', $for = NULL, $isolated = FALSE){
        $transaction = new static();
        $value = round($val, 2, PHP_ROUND_HALF_DOWN);
        if((-1 * $value) < 0){
            $value *= -1;
        }
        if(empty($reference)){
            $reference = SELF::getUniqueReference($user_id,$wallet_id);
        }
        if(!is_null($for)){
            if(empty($description)){
                $description = 'TRANSFER TO '.$for;
            }
            $transaction->receiver_id = $for;
            $transaction->sender_id = $user_id;
        }
        $transaction->reference = $reference;
        $transaction->type = SELF::DEBIT;
        $transaction->value = $value;
        $transaction->user_id = $user_id;
        $transaction->wallet_id = $wallet_id;
        $transaction->description = $description;
        $transaction->load->model('UserBalance');
        if($isolated){
            SELF::startDbTransaction();
        }
        try
        {
            $debit = UserBalance::transact($user_id,$value,$wallet_id);
            $done = false;
            if(!is_null($debit)){
                $transaction->current_balance = $debit['current_balance'];
                $done = $transaction->save();
            }
            if($isolated){
                SELF::endDbTransaction($done);
            }
        } catch(Exception $ex){
            if($isolated){
                SELF::endDbTransaction(FALSE);
            }
            throw $ex;
        }
        return $done;
    }

    public static function credit($user_id, $val, $wallet_id, $reference = '', $description = '', $from = NULL, $isolated = FALSE){
        $transaction = new static();
        $value = round($val, 2, PHP_ROUND_HALF_DOWN);
        if(empty($reference)){
            $reference = SELF::getUniqueReference($user_id,$wallet_id);
        }
        if(!is_null($from)){
            if(empty($description)){
                $description = 'TRANSFER FROM '.$from;
            }
            $transaction->sender_id = $from;
            $transaction->receiver_id = $user_id;
        }
        $transaction->reference = $reference;
        $transaction->type = SELF::CREDIT;
        $transaction->value = $value;
        $transaction->user_id = $user_id;
        $transaction->wallet_id = $wallet_id;
        $transaction->description = $description;
        $transaction->load->model('UserBalance');
        if($isolated){
            SELF::startDbTransaction();
        }
        try
        {
            $credit = UserBalance::transact($user_id,$value,$wallet_id);
            $done = false;
            if(!is_null($credit)){
                $transaction->current_balance = $credit['current_balance'];
                $done = $transaction->save();
            }
            if($isolated){
                SELF::endDbTransaction($done);
            }
        } catch(Exception $ex){
            if($isolated){
                SELF::endDbTransaction(FALSE);
            }
            throw $ex;
        }
        return $done;
    }


    public static function transfer($from, $from_wallet, $to, $to_wallet, $value, $reference = '', $isolated = FALSE ){
		
        User::validateCrossUserTransfer($from,$to);
        Wallet::validateCrossWalletSpending($from_wallet,$to_wallet);
        if($from == $to && $from_wallet ==$to_wallet){
            throw new UserException('You cannot transfer to yourself using same wallet');
        }
        if(empty($reference)){
            $reference = SELF::getUniqueReference($from,$from_wallet,$to,$to_wallet);
        }
        $from_user = User::findAndThrow(['id'=>$from],[
            'exception_class'=>'InvalidActionException',
            'exception_message'=>'Payee cannot be found'
        ])->one();
        $to_user = User::findAndThrow(['id'=>$to],[
            'exception_class'=>'InvalidActionException',
            'exception_message'=>'Beneficiary cannot be found'
        ])->one();
        if($isolated){
            SELF::startDbTransaction();
        }
        try
        {
            $txn = SELF::debit($from,$value,$from_wallet,$reference,'IPOINTS TRANSFER TO '.$to_user->getIdentity(),$to);
            if($txn){
                $txn = SELF::credit($to,$value,$to_wallet,$reference,'IPOINTS TRANSFER FROM '.$from_user->getIdentity(),$from);
            }
            if($isolated){
                SELF::endDbTransaction($txn);
            }
        } catch(Exception $ex){
            if($isolated){
                SELF::endDbTransaction(FALSE);
            }
            throw $ex;
        }
        return $txn;
    }


    // ammual payment subscription fee process
    // it accept perameters of user_id, wallet_id and in_bash (if the process is more than one set true)

    public static function paySubscription($user_id,$wallet=NULL, $in_batch=false){
       
        $active_sub = UserSubscription::findActive($user_id);
        if(!is_null($active_sub) && $in_batch){
            return false;
        }else if(!is_null($active_sub)){
            throw new InvalidActionException('New subscription cannot be created while one is active or not expired');
        }
        $system = User::getSystemUser();
        $sub_wallet = Wallet::getForSubscription();
        $skip_enforcing = true;
        if(is_null($wallet)){
            $main_wallet = Wallet::getMain();
            $wallet = $main_wallet->id;
            $skip_enforcing = false;
        }
        //get annual levies from uici_levie table
        $sub_levy = Uici_levies::getUiciLevieValue(ANNUAL_CHARGES_KEY);
        //apply percetage calculation hear
        $reference = SELF::getUniqueReference('SUB',$user_id);
        SELF::startDbTransaction();
        try
        {
            $transfer = SELF::transfer($user_id,$wallet,$system->id,$sub_wallet->id,$sub_levy->value,$reference,FALSE);
            $create = FALSE;
            if($transfer){
                $create = UserSubscription::create($reference,$sub_levy->value);
            }
            SELF::endDbTransaction($create);
        } catch(Exception $ex){
            SELF::endDbTransaction(FALSE);
            throw $ex;
        }
        return $create;
    }

    public static function remitIncompleteSubscription($user_id,$amount,$cost,$start,$end){
        $system = User::getSystemUser();
        $sub_wallet = Wallet::getForSubscription();
        $main_wallet = Wallet::getMain();
        $reference = SELF::getUniqueReference('SUB',$user_id);
        SELF::startDbTransaction();
        try
        {
            $transfer = SELF::transfer($user_id,$main_wallet->id,$system->id,$sub_wallet->id,$amount,$reference,FALSE);
            $create = FALSE;
            if($transfer){
                $create = UserSubscription::create($reference,$cost,true,$start,$end);
            }
            SELF::endDbTransaction($create);
        } catch(Exception $ex){
            SELF::endDbTransaction(FALSE);
            throw $ex;
        }
        return $create;
    }

    public static function payForProduct($user_id, $product_id, $wallet_id, $batch_id = NULL){
        return false;
    }
    
    //return false if fail, return userbalance if success
    public static function walletCommissionCharge(
    $user_id,
    $wallet_id,
    $credit_user_id,
    $value,
    $charge_commission_percent,
    $commission_wallet_id,
    $isolated 
    ){
        // balance = 3600 - 
             $charges_proccess = SELF::charges_proccess($value,$charge_commission_percent);
            // user_id, user_wallet_id, commission charges, admin_id, commission_wallet_id            
            $process = SELF::transfer(
                $user_id, 
                $wallet_id, 
                $credit_user_id, 
                $commission_wallet_id, 
                $charges_proccess['adminPercent'], 
                $isolated 
            );
           
            $info = ($process)? 'Transaction Successful' : 'Transaction Failed';
            log_message('info',$info);
            return ($process)? $charges_proccess['userBalance'] : false;

    }
    //iPoint charges balance
    public static function offline_charge_amount($actual_amount,$iPoint_value){
        return $actual_amount - $iPoint_value;
    }

    
    public static function calculate_percentage($point_qty,$percent){
        $balance = ($point_qty * $percent)/100;
           return $balance;
    }
    // This function calculate the ipoint charge percentage for system user and subscriber user
    // the function accept two parameter: ipoint_qty, percentage amount
    public static function charges_proccess($point_qty,$point_charge){
        if(!empty($point_qty)){
            $adminPercent = ($point_qty * $point_charge)/100;
            $userBalance = $point_qty - $adminPercent;
            return ['adminPercent'=>$adminPercent, 'userBalance'=>$userBalance];
        }
        return ['adminPercent'=>0, 'userBalance'=>0];
    }

    // this process any withdrawer request that hit the system
    public static function withdrawerRequest($user_id, $wallet_id, $bank_name,$account_number,$amount, $system_id, $system_withdrawer_wallet_id, $reference='', $isolated=FALSE){
        //information needed:: user_id, wallet_id; amount; system_id, system_wallet_id, isolated
        //get the actor info
        // transfer the actor ipoint into system withdrawer wallet
        // keep the transaction record in a withdrawer_request table and set the status to pending
        $vandor_transfer = SELF::transfer(
            $user_id, 
            $wallet_id, 
            $system_id, 
            $system_withdrawer_wallet_id, 
            $amount,
            $reference, 
            $isolated 
        );

        $user_balance = UserBalance::findInfo($user_id,$wallet_id);  
        $withdraw_request = new WithdrawRequest();
        $withdraw_request->user_balance_id = $user_balance->id;
        $withdraw_request->transaction_reference = $reference;
        $withdraw_request->bank_name = $bank_name;
        $withdraw_request->account_number = $account_number;
        $withdraw_request->amount = $amount;
        $withdraw_request->status = SELF::PENDING;
        $withdraw_request->author_by = $user_id;
        $withdraw_request->created_at = date('Y-m-d H:i:s');
        $withdraw =  $withdraw_request->save();

        $commit = ($vandor_transfer && $withdraw)? true : false;
        SELF::endDbTransaction($commit);
        return $commit;

}
    public static function isRefExist($reference,$type='debit'){
        return SELF::isExist(['reference' => $reference, 'type' => $type]);
    }

    public static function isRefExistReValue($reference,$type='debit'){
        return SELF::isExistwithValue(['reference' => $reference, 'type' => $type]);
    }
    

    // public static function getCurrentRef($user_id, $wallet_id, $system_id, $system_withdrawer_id, $amount,$type='debit'){
    //     return SELF::findOne(['user_id' => $user_id, 'wallet_id'=> $wallet_id,'receiver_id'=>$system_withdrawer_id,'value'=> $amount,'type' => $type]);
    // }

    public static function fetchTransactionHistory(PDO $db, $data, $isExport=false){
            
        
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  t.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['reference'])){
            $where[] ="t.reference ='".$data['reference']."'";
        }if(!empty($data['type'])){
                $where[] ="t.type ='".$data['type']."'";
        }if(!empty($data['user_id'])){
            $where[] ='t.user_id = '.(int)$data['user_id'];
        }if(!empty($data['wallet_id'])){
            $where[] ='t.wallet_id = '.(int)$data['wallet_id'];
        }

        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $countWhere = $where;

        $where = $where .= (!$isExport)?" ORDER BY t.created_at DESC LIMIT ".$data['limit']." OFFSET ".$data['offset']:"";
        
        $fromAndJoin = " FROM `transactions` `t`
        LEFT JOIN `users` as `usd` ON `usd`.`id` = `t`.`sender_id`
        LEFT JOIN `users` as `urv` ON `urv`.`id` = `t`.`receiver_id` 
        LEFT JOIN `wallets` as `w` ON `w`.`id` = `t`.`wallet_id`
         ";
         
        try{
            $query = " SELECT `t`.`reference`, `t`.`type`, `t`.`value`, `w`.`name` as wallet_name,if( length(usd.name), usd.name, if(length(usd.business_name), usd.business_name, if(length(usd.mobile_number), usd.mobile_number, usd.email))) as sender, `t`.`created_at`, if( length(urv.name), urv.name, 
            if(length(urv.business_name), urv.business_name, if(length(urv.mobile_number), urv.mobile_number, urv.email))) as receiver,`t`.`current_balance`, `t`.`description`
            {$fromAndJoin} {$where} ";
            if ($isExport) {
                return $query;
            }
            
            $countQuery = " SELECT COUNT(*) AS allCount {$fromAndJoin} {$countWhere}";
            if(true){
                $stmt= $db->query($countQuery);
                SELF::$result_count = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
}

public function totalTransactionByType($input){
    $query = $this->db->from('transactions t')
                    ->select('SUM(t.value) as total_amount, COUNT(*) as number,t.type')
                    ->where('t.user_id',$input['user_id'])
                    ->where('t.type', $input['type'])
                    ->get();
        return $query->row();
}

public function getLastTransactionByUserId($input){
    $query = $this->db->from('transactions t')
                    ->select('t.value as amount, t.type, t.created_at')
                    ->where('t.user_id',$input['user_id'])
                    ->order_by("t.id", "DESC")
                    ->get();
        return $query->row();
}

}