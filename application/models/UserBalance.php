<?php

//namespace application\models;

//use \MY_Model;

class UserBalance extends MY_Model {   
const ONE_HUNDRED = 100;

    public static function getTableName(){
        return 'user_balance';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    }
    
    public static function getWalletBalance($key='',$data){
        $isavingApiBalance = new static();
      $result = $isavingApiBalance->findUserBalanceByUserIdAndWalletName($key,$data['contact'],$data['wallet_name']);
      if(empty($result)){
          throw new UserException('You record may not have been updated');
      }elseif(!password_verify($data['password'],$result->password)){
        throw new UserException('Incorrect Password');
      }else{
          return $result;
      }
    
    }

    public  function findUserBalanceByUserIdAndWalletName($key,$contact,$Wallet_name){
       
		
        try{
            $query = $this->db->from('user_balance ub')
            ->select('`ub`.`balance`,`u`.`password`,`w`.`name`')
            ->where($key, $contact)
            ->where('`w`.`name`', $Wallet_name)
            ->join('`wallets` as `w`', '`w`.`id` = `ub`.`wallet_id`', 'left')
            ->join('users as u', '`u`.`id` = `ub`.`user_id`', 'left')
            ->get();
            return $query->row();
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }


    public function isEnoughBalance($data){
        $response = false;
        $getBalance =  $this->checkUserBalance($data);
        $response = ($getBalance->balance >= $data['amount'])? true : false;
        return $response;
    }

    public static function findUserBalanceByUserIdAndStatus($user_id,$wallet_id){
        return SELF::findOne(['user_id'=>$user_id,'wallet_id'=>$wallet_id]);
     }

     public static function findById($id){
        return SELF::findOne(['id'=>$id]);
     }

    public function creditUserBalance($data){
        $system_user = User::systemAccount();
        $system_wallet = Wallet::getForSubscription();
        $response = Transaction::transfer(
            $system_user->id, 
            $system_wallet->id,
            $getBalance->user_id, 
            $data['wallet_id'],  
            $data['amount'], 
            $data['desc'],  
            FALSE 
        );
        return $response;
    }

    public function isavingsDebit($data){
        $debitUser =  new static();
        
		$debitUser->load->model('User');
		$debitUser->load->model('Uici_levies');
		$debitUser->load->model('Wallet');
		$debitUser->load->model('EspiTransaction');
		$debitUser->load->model('Transaction');
       
        $user = User::findByPhoneNumber($data['mobile_number']);
      
        //get wallet id
        $iSavingsWallet = Wallet::walletByName(I_SAVINGS);
       

        $dts['mobile_number'] = $data['mobile_number'];
        $dts['wallet_id'] = $iSavingsWallet->id;
        $getBalance =  $debitUser->checkUserBalance($dts);
        if(empty($getBalance)){
            throw new UserException('The user does not have iSavings wallet');
        }

        if(EspiTransaction::isExist(['request'=>$data['payment_reference']])){
            log_message('info','Duplicate Request Entry ====>');
            return TRUE;
        }
       
        
        $response = ($getBalance->balance  >= $data['amount'])? true : false;
        
        if($response){
            SELF::startDbTransaction();
            //1, debit base on giving percent commission in the wallet
            $system_user = User::systemAccount();
            //2, transfer payload amount from the user account to vandor account
                $debit_tran = Transaction::debit($getBalance->user_id, $data['amount'], $getBalance->wallet_id, $data['payment_reference'], $data['description'], $system_user->id,FALSE);
           
                $reference= EspiTransaction::getUniqueRequest($getBalance->user_id,$getBalance->user_id,$system_user->id);
                $espi_tran = EspiTransaction::insert([
                    'request'=>$data['payment_reference'],
                    'reference'=> $reference,
                    'status'=>'completed',
                    'value'=>(float)-$data['amount'],
                    'amount'=>(float)-$data['amount'],
                    'recipient_count'=>1,
                    'type'=>'debit',
                    'sender'=>$system_user->id,
                    'description'=>$data['description'],
                    'created_at'=>$data['request_date'],
                    'updated_at'=>date('Y-m-d H:i:s'),
                ]);      
            $commit = ($espi_tran && $debit_tran)? true : false;
            SELF::endDbTransaction($commit);
            return $commit;
        }else{
            throw new UserException('You don\'t have enough balance in your wallet to carry out this transaction');
        }
        
    }

    public function debitUserBalance($data){
        $debitUser =  new static();
        //3 actors
        //user
        //admin
        //vendor
		$debitUser->load->model('User');
		$debitUser->load->model('Uici_levies');
		$debitUser->load->model('Wallet');
		$debitUser->load->model('Transaction');
        $is_reference_exist = Transaction::isRefExist($data['payment_reference']);
        if($is_reference_exist){
            throw new UserException('Duplicate reference entry');
        }
        $response = false;
        //$client = User::findById($data['user_id']);
        //get user info
        $user = User::findByPhoneNumber($data['mobile_number']);

        // if(!password_verify($data['password'],$user->password)){
        //     throw new UserException('Incorrect pin');
        // }
    
        //get vandor wallet
        $vendor_wallet = Wallet::walletByName(I_INCOME);
        //get vendor wallet
        $vendor_info = User::getVendor($data['vendor_id']);
        
        //get commission of isavings
        $iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
        
        //get wallet id
        $iSavingsWallet = Wallet::walletByName(I_SAVINGS);
       

        $dts['mobile_number'] = $data['mobile_number'];
        $dts['wallet_id'] = $iSavingsWallet->id;
        $getBalance =  $debitUser->checkUserBalance($dts);
        if(empty($getBalance)){
            throw new UserException('The user does not have iSavings wallet');
        }
       
        //check if the percentage balance is grather than or equal to the amount needed
        $response = (($getBalance->balance - $balance['adminPercent']) >= $data['amount'])? true : false;
        
        if($response){
            SELF::startDbTransaction();
            //1, debit base on giving percent commission in the wallet
            $system_user = User::systemAccount();
           
           // $system_wallet = Wallet::walletByName(I_SAVINGS_EARNING);
            
            // $commission = Transaction::walletCommissionCharge(
            //     $getBalance->user_id, 
            //     $getBalance->wallet_id,
            //     $system_user->id,
            //     $data['amount'],
            //     $iSaving_charge->value,
            //     $system_wallet->id,
            //     FALSE
            // );
            //2, transfer payload amount from the user account to vandor account
            $vandor_transfer = Transaction::transfer(
                    $getBalance->user_id, 
                    $getBalance->wallet_id, 
                    $vendor_info->id, 
                    $vendor_wallet->id, 
                    $data['amount'],  
                    $data['payment_reference'], 
                    FALSE 
                );

                $request= EspiTransaction::getUniqueRequest();
                $insert = EspiTransaction::insert([
                    'request'=>$request,
                    'reference'=>$payment_ref,
                    'status'=>'pending',
                    'value'=>(float)$quantity,
                    'amount'=>(float)$isavings['userBalance'],
                    'recipient_count'=>count($bulk_transfer),
                    //'type'=>$type,
                    'type'=>'debit',
                    'sender'=>$sender_id,
                    'description'=>$description,
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);    
            
          
            $commit = (($commission != false) && $vandor_transfer)? true : false;
            SELF::endDbTransaction($commit);
            return $commit;
        }else{
            throw new UserException('You don\'t have enough balance in your wallet to carry out this transaction');
        }
        
    }

    public static function findInfo($user_id,$wallet_id){
        return SELF::findOne("user_id = {$user_id} AND wallet_id = {$wallet_id}");
    }
    //withdrawer process
    public static function withdrawerProcess($data,$password=true){
        $debitUser =  new static();
        $is_reference_exist = Transaction::isRefExist($data['reference']);
        if($is_reference_exist){
            throw new UserException('Duplicate reference entry');
        }
        //get user info
        //$user = User::findByPhoneNumber($data['mobile_number']);
        $user = User::findByPhoneNumberOrEmail($data['contact']);
        if(empty($user)){
            throw new UserException("You don't have account Uici");
        }
        if($password){
            if(!password_verify($data['password'],$user->password)){
                throw new UserException('Incorrect Password');
            }
        }
        $wallet_name = '';
        $withdrawer_wallet;
        $user_group = User::fetchUserGroup($user->id);
        $wallet_type = "";
        if($user_group['group_name'] == SUBSCRIBER){
            $wallet_name = I_SAVINGS;
            $wallet_type = "iSavings wallet";
        }else{
            $wallet_name = I_INCOME;
            $wallet_type = "iIncome wallet";
        }
         //get commission of isavings
         //get wallet id
         $withdrawer_wallet = Wallet::walletByName($wallet_name);

        $dts['id'] = $user->id;
        $dts['wallet_id'] = $withdrawer_wallet->id;
        $getBalance =  $debitUser->getUserBalanceByWalletAndUserId($dts);
        if(empty($getBalance)){
            throw new UserException("You don't have ".$wallet_type);
        }
    
        //get the user details
        //calculate the commission on isavings percentage
        $commission =false;
        $system_user = User::systemAccount();
        SELF::startDbTransaction();


        if($user_group['group_name'] == SUBSCRIBER){
            $threshold = Setting_m::findByReference('iSavings_threshold')->meta_value;
            if((int)$threshold > $data['amount']){
                throw new UserException("The minimum cash-out threshold is ".$threshold.", Please try again later");
            }
            $iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
            // get user percentage
            $balance = Transaction::charges_proccess($data['amount'],$iSaving_charge->value);
            //check if the percentage balance is grather than or equal to the amount needed
            $response = (($getBalance->balance - $balance['adminPercent']) >= $data['amount'])? true : false;
            
            if($response){
                //1, debit base on giving percent commission in the wallet
                $system_commission_wallet = Wallet::walletByName(I_SAVINGS_EARNING);
                
                $commission = Transaction::transfer(
                    $getBalance->user_id,
                    $getBalance->wallet_id,
                    $system_user->id,
                    $system_commission_wallet->id,
                    $balance['adminPercent'],
                    '', 
                    TRUE
                );
                
            }else{
                throw new UserException("You don't have enough balance in your wallet to carry out this transaction");
            }

        }else{
            $commission =true;
        }

        //get system iWithdrawer wallet
        $iWithdrawerWallet = Wallet::walletByName(I_WITHDRAWER);
        $reference = ($data['reference']??false) 
            ?: Transaction::getUniqueReference($getBalance->user_id,$getBalance->wallet_id,$system_user->id,$iWithdrawerWallet->id);
        $withdraw = Transaction::withdrawerRequest($getBalance->user_id, $getBalance->wallet_id,$data['bank_name'],$data['account_number'], $data['amount'], $system_user->id, $iWithdrawerWallet->id, $reference, TRUE);
        $commit = ($commission && $withdraw)? true : false;
        SELF::endDbTransaction($commit);
        return $commit;
        

    }

    public static function withdrawerReverse($data){
        // lock a row table
        //SELECT * FROM table_name WHERE id=10 LOCK IN SHARE MODE
        $debitUser =  new static();
        $is_reference_exist = Transaction::isRefExist($data['reference']);
       //var_dump($is_reference_exist,$data['reference']);
        if(!$is_reference_exist){
            throw new UserException("The transaction reference doesn't exist.");
        }
        if(Transaction::count(['reference'=>$data['reference']]) >= 4){
            throw new UserException("The transaction reference can't longer be process"); 
        }
        // Actors:: 1,Subscriber; 2,Underwriter; 3,Vendor
        // check if the transation reference exist.
        // check if the transation reference records is equal to 4 or greater than.
        // check if the use_balance exist.
        // check if the revers has charge
        
        $getBalance = SELF::findById($data['user_balance_id']);
        if(empty($getBalance)){
            throw new UserException("You don't have This wallet.");
        }
        //get user info
        $user = User::findById($getBalance->user_id);
        $authorize_user = User::fetchUserGroup($getBalance->user_id);
        
        $system_user = User::systemAccount();
        $commission = false;
        $vandor_transfer = true;
       
            SELF::startDbTransaction();
         //1, debit base on giving percent commission in the wallet
         
        if($data['status'] == 'cancel'){
           
            if($authorize_user['group_name'] == SUBSCRIBER){
                 //get commission of isavings
                $iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);

                //get wallet id
                $iSavingsWallet = Wallet::walletByName(I_SAVINGS);
                //get the user details
                //calculate the commission on isavings percentage
                $system_commission_wallet = Wallet::walletByName(I_SAVINGS_EARNING);
                $system_user_balance = SELF::findInfo($system_user->id, $system_commission_wallet->id);

                // get user percentage
                $balance = Transaction::charges_proccess($data['amount'],$iSaving_charge->value);
                //check if the percentage balance is grather than or equal to the amount needed
                $charge_commission = (float)$system_user_balance->balance - (float)$balance['adminPercent'];
                $response = ($charge_commission >= (float)$balance['adminPercent'])? true : false;
               
                if($response){
                    $commission = Transaction::transfer(
                        $system_user_balance->user_id, 
                        $system_user_balance->wallet_id, 
                        $getBalance->user_id, 
                        $getBalance->wallet_id, 
                        $balance['adminPercent'],
                        '', 
                        TRUE 
                    );
                }else{
                    throw new UserException("You don't have enough balance in your wallet to carry out this transaction");
                }
        
            }else{
                $commission = true;
            }

              //get system iWithdrawer wallet
              $iWithdrawerWallet = Wallet::walletByName(I_WITHDRAWER);

              $vandor_transfer = Transaction::transfer(
                  $system_user->id,
                  $iWithdrawerWallet->id,
                  $getBalance->user_id,
                  $getBalance->wallet_id,
                  $data['amount'],// get the amount
                  $data['reference'], 
                  TRUE 
              );
        }     
            $user_balance = UserBalance::findInfo( $getBalance->user_id,$getBalance->wallet_id);  
            $withdraw_request['user_balance_id'] = $user_balance->id;
            $withdraw_request['transaction_reference'] = $data['reference'];
            $withdraw_request['amount'] = $data['amount'];
            $withdraw_request['status'] = $data['status'];
            $withdraw_request['author_by'] = $data['authorize_id'];
            $withdraw_request['created_at'] = date('Y-m-d H:i:s');
            $withdraw = WithdrawRequest::updateByPk($data['id'],$withdraw_request);
    
            $commit = ($commission && $vandor_transfer && $withdraw)? true : false;
            SELF::endDbTransaction($commit);
            return $commit;
        
    }

    public function checkUserBalance($data){
        $query = $this->db->from('user_balance ub')
        ->select('ub.balance,ub.id, ub.wallet_id,ub.user_id')
        ->where('u.mobile_number',$data['mobile_number'])
        ->where('ub.wallet_id',$data['wallet_id'])
        ->join('users as u','u.id = ub.user_id','left')
        ->get();
		return $query->row();
    }

    public function getUserBalanceByWalletAndUserId($data){
        $query = $this->db->from('user_balance ub')
        ->select('ub.balance,ub.id, ub.wallet_id,ub.user_id')
        ->where('ub.user_id',$data['id'])
        ->where('ub.wallet_id',$data['wallet_id'])
        ->get();
		return $query->row();
    }


    public function getUserBalanceList($mobile_number){
        $query = $this->db->from('user_balance ub');
        $this->db->select('w.name, ub.balance');
        $this->db->where('u.status', 1);
        $this->db->where('u.mobile_number', $mobile_number);
        $this->db->join('wallets as w', 'w.id = ub.wallet_id', 'left');
        $this->db->join('users as u', 'u.id = ub.user_id', 'left');
        return $this->db->get()->result();
    }

    public function getWalletList(){
        $query = $this->db->from('wallets w');
        $this->db->select('w.id, w.name');
        $this->db->where('w.type', 'product');
        return $this->db->get()->result();
    }

    public function beforeSave(){
        $this->updated = date('Y-m-d H:i:s');
    }

    public static function transact($user_id, $value, $wallet_id){
        $previous_value = 0;
        $current_value = $value;
        $success = false;
        $b_instance = new static();
        $query = $b_instance->db->select()
        ->from($b_instance->getTableName())
        ->where(['user_id'=>$user_id,'wallet_id'=>$wallet_id])
        ->get_compiled_select().' FOR UPDATE';
        $user_balance = $b_instance->db->query($query)->result('UserBalance');
        if(!empty($user_balance)){
            $user_balance = $user_balance[0];
            $user_balance->isNew = false;
            $previous_value = floatval($user_balance->balance);
            if($value < 0) {
                if (($user_balance->balance + $value) < 0) {
                    if(!$user_balance->can_overdraft){
                        throw new UserException('Insufficient balance in wallet');
                    }
                    if(is_null($user_balance->overdraft_limit) || $user_balance->overdraft_limit == 0){
                        throw new InvalidConfigException('Overdraft limit is not configured for this wallet');
                    }
                    $current_value += $user_balance->balance;
                    if(abs($current_value) > $user_balance->overdraft_limit){
                        throw new UserException('Overdraft limit reached. Please settle outstanding debt to continue');
                    }
                }
            } 
            $user_balance->balance += $value;
            $success = $user_balance->save();
            $current_value = $user_balance->balance;
        } else {
            if($value < 0){
                throw new UserException('Insufficient balance in wallet 2: '.$wallet_id);
            }
            $user_balance = $b_instance;
            $user_balance->user_id = $user_id;
            $user_balance->wallet_id = $wallet_id;
            $user_balance->balance = $value;
            $user_balance->overdraft_limit = 0;
            $user_balance->updated = date('Y-m-d H:i:s');
            $user_balance->prepareAttributes();
            $query = $user_balance->db->insert_string($user_balance->getTableName(),$user_balance->getAttributes());
            $query .= ' ON DUPLICATE KEY update balance = balance + VALUES(balance), updated = VALUES(updated)';
            $success = $user_balance->db->query($query);
        }
        if(!$success){
            return NULL;
        }
        return [
            'previous_balance'=>$previous_value,
            'current_balance'=>$current_value
        ];
    }

    public static function getUserBalance(PDO $db, $data, $isExport=false){

        $status = isset($data['can_overdraft']) ?''.$data['can_overdraft'] :'';

        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  ub.update_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if($status != null ||  $status != ''){
            $where[] =' ub.can_overdraft = '.$data['can_overdraft'];
        }if(!empty($data['user_id'])){
            $where[] =' ub.user_id = '.(int)$data['user_id'];
        }if(!empty($data['group'])){
            $where[] = " `g`.`group_name` = '".$data['group']."'";
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $where = $where .= (!$isExport)?' ORDER BY u.created_at DESC LIMIT 150':'';

        try{
            $query = "SELECT ub.*, 
            CONCAT(COALESCE(IF(`u`.`name` = '', NULL, `u`.`name`), `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `user_name`, 
            `w`.`name`  as `wallet_name`,`g`.`group_name`
            FROM user_balance ub
            LEFT JOIN `users` as `u` ON `u`.`id` = `ub`.`user_id` 
            LEFT JOIN `wallets` as `w` ON `w`.`id` = `ub`.`wallet_id`
            LEFT JOIN `groups` as `g` ON `g`.`id` = `u`.`group_id` ".$where;
            
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }  
    }


    public static function users_count(PDO $db, $data){
        $where = [];
        if(!empty($data['wallet'])){
            $where[] ='ub.wallet_id = '.(int)$data['wallet'];
        }if(!(empty($data['filter']) && empty($data['point']))){
            $where [] =' ub.balance '. $data['filter'].' '.(int)$data['point'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
       
        try{
            $query = " SELECT COUNT(*) count_users
            FROM `user_balance` `ub`
            INNER JOIN `users` as `u` ON `u`.`id` = `ub`.`user_id`
            INNER JOIN `wallets` as `w` ON `w`.`id` = `ub`.`wallet_id` ".$where;
           
            $stmt = $db->query($query);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }


    public static function filterSubscriber(PDO $db, $data){ 
        $where = [];
        if(!empty($data['wallet'])){
            $where[] ='ub.wallet_id = '.(int)$data['wallet'];
        }if(!(empty($data['filter']) && empty($data['point']))){
            $where [] =' ub.balance '. $data['filter'].' '.(int)$data['point'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
       
        try{
            $query = " SELECT ub.*,`u`.`name`, `u`.`mobile_number`, w.name as wallet_name
            FROM `user_balance` `ub`
            INNER JOIN `users` as `u` ON `u`.`id` = `ub`.`user_id`
            INNER JOIN `wallets` as `w` ON `w`.`id` = `ub`.`wallet_id` ".$where;
           
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

    public function get_user_balance($id){

        $this->db->from('user_balance ub'); 
        $this->db->select('ub.*, if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name, w.name AS product_name');
        $this->db->where('u.status', 1);
        $this->db->where('ub.user_id',$id);
        $this->db->where('w.can_user_inherit',1);
        $this->db->join('users as u','u.id = ub.user_id','inner');
        $this->db->join('wallets as w', 'w.id = ub.wallet_id', 'inner');
		return $this->db->get()->result();
    }

    public static function fetchUserBalanceInfoById($id){
        $balance = new static();
        $query = $balance->db->from('user_balance ub') 
        ->select('ub.*, if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name, u.mobile_number, u.email, w.name AS wallet_name')
        ->where('u.status', 1)
        ->where('ub.id',$id)
        ->where('w.can_user_inherit',1)
        ->join('users as u','u.id = ub.user_id','inner')
        ->join('wallets as w', 'w.id = ub.wallet_id', 'inner')
        ->get();
		return $query ->row();
    }

    public function get_balance_info($wallet_id,$user_id){
        $query = $this->db->from('user_balance ub')
        ->select('ub.*, w.name AS wallet_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name')
        ->where('ub.wallet_id',$wallet_id)
        ->where('ub.user_id',$user_id)
        ->where('w.can_user_inherit',1)
        ->join('users as u','u.id = ub.user_id','inner')
        ->join('wallets as w', 'w.id = ub.wallet_id', 'inner')
        ->get();
		return $query->row();
    }

    public function get_wallet_info($id,$wallet){
        $query = $this->db->from('user_balance ub')
        ->select('ub.*, w.name AS product_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name')
        ->where('ub.user_id',$id)
        ->where('ub.wallet_id',$wallet)
        ->join('wallets as w', 'w.id = ub.wallet_id', 'inner')
        ->get();
		return $query->row();
    }

    public function get_wallets(){
        $this->db->from('wallets w'); 
        $this->db->select('w.*');
        $this->db->where('w.can_user_inherit',1);
        $this->db->where('w.can_topup',1);
		return $this->db->get()->result();
    }

    public function get_balances_sum($id){
        $query = $this->db->from('user_balance ub')
        ->select('SUM(ub.balance)balance')
        ->where('ub.user_id',$id)
        ->get();
		return $query->row();
    }

    public function get_iSavings_balance($user_id,$wallet_id){
        $query = $this->db->from('user_balance ub')
        ->select('ub.balance')
        ->where('ub.user_id',$user_id)
        ->where('ub.wallet_id',$wallet_id)
        ->get();
		return $query->row();
    }

    public function get_all_iSavings_balances($wallet_id){
        $query = $this->db->from('user_balance ub')
        ->select('SUM(ub.balance)balance, COUNT(*) users')
        ->where('ub.wallet_id',$wallet_id)
        ->get();
		return $query->row();
    }

    public function get_balance_by_name($wallet_id){
        $query = $this->db->from('user_balance ub')
        ->select('ub.id,ub.balance, w.name AS wallet_name')
        ->where('ub.wallet_id',$wallet_id)
        ->join('wallets as w', 'w.id = ub.wallet_id', 'inner')
        ->get();
		return $query->row();
    }

    public function getBalanceByName($wallet_name){
        $query = $this->db->from('user_balance ub')
        ->select('ub.id,ub.balance, w.name AS wallet_name')
        ->where('w.name',$wallet_name)
        ->join('wallets as w', 'w.id = ub.wallet_id', 'inner')
        ->get();
		return $query->row();
    }

    public function getSumUpOfWalletBalanceByName($wallet_name){
        $query = $this->db->from('user_balance ub')
        ->select('SUM(ub.balance)balance, COUNT(*) users')
        ->where('w.name',$wallet_name)
        ->join('wallets as w', 'w.id = ub.wallet_id', 'inner')
        ->get();
		return $query->row();
    }

    public static function getSubscriberInfo(PDO $db, $data){ 
        $where = [];
        if(!empty($data['user_id'])){
            $where[] ='u.id = '.(int)$data['user_id'];
        }if(!empty($data['mobile_number'])){
            $where[] ='u.mobile_number = "'.$data['mobile_number'].'"';
        }if(!empty($data['product'])){
            $where [] =' w.product_id = '. (int)$data['product'];
        }if(!empty($data['amount'])){
            $where [] =' ub.balance >= '. $data['amount'];
        }

        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
       
        try{
            $query = "SELECT u.id, u.mobile_number, ub.balance ,ub.wallet_id, w.name as wallet_name, p.id as product_id, p.price as product_price, p.allowable_tenure as cover_period, p.provider_id, ul.value as commission_percent
            FROM users u
            LEFT JOIN user_balance AS ub ON ub.user_id = u.id 
            LEFT JOIN wallets AS w ON w.id = ub.wallet_id 
            LEFT JOIN products AS p ON p.id = w.product_id
            LEFT JOIN uici_levies AS ul ON ul.id = p.charge_commission_id ".$where;

            $stmt = $db->query($query);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

   
}