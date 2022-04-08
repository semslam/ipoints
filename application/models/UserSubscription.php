<?php

class UserSubscription extends MY_Model
{

    public function __construct()
	{
	  parent::__construct();
      $this->load->model('WIPBulkTransferRequest');
      $this->load->model('WIPTransaction');
      $this->load->model('Transaction');
      $this->load->model('Wallet');
      $this->load->model('User');
    }
    
    const PROCESS_LIMIT = 2;
    public static function getTableName(){
        return 'user_subscriptions';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public static function findActive($user_id){
        //return SELF::findOne("user_id = {$user_id} AND is_active = 1 AND end_date > NOW()");
        return SELF::findOne("user_id = {$user_id} AND is_active = 1 AND DATE_FORMAT(end_date,'%Y-%m-%d') > DATE_FORMAT(NOW(),'%Y-%m-%d')");
    }

    public static function findActiveArreas($user_id,$cost){
        //SELECT * FROM `user_subscriptions` WHERE user_id = 5 AND total_paid < 60 AND is_active = 1 AND DATE_FORMAT(end_date,'%Y-%m-%d') > DATE_FORMAT(NOW(),'%Y-%m-%d')
        return SELF::findOne("user_id = {$user_id} total_paid < {$cost} AND is_active = 1 AND DATE_FORMAT(end_date,'%Y-%m-%d') > DATE_FORMAT(NOW(),'%Y-%m-%d')");
    }

    public static function create($reference,$cost, $allow_incomplete = false, $start = NULL, $end = NULL){
        if(is_null($start) && is_null($end)){
            $start = date('Y-m-d H:i:s');
            $end = date('Y-m-d H:i:s', strtotime('+1 years'));
        } elseif(is_null($start) || is_null($end) || (strtotime($start) > strtotime($end))) {
            throw new InvalidArgumentException('end date and start date must be set correctly');
        }
        $duplicate_sub = SELF::findOne(['txn_reference'=>$reference]);
        if(!is_null($duplicate_sub)){
            throw new InvalidActionException('This transaction reference has already been used');
        }
        $system = User::getSystemUser();
        $sub_wallet = Wallet::getForSubscription();
        $transaction = Transaction::findOne(['reference'=>$reference,'type'=>TRANSACTION::CREDIT, 'wallet_id'=>$sub_wallet->id,'user_id'=>$system->id]);
        if(is_null($transaction)){
            throw new InvalidArgumentException('Supplied reference does not belong to a subscription transaction');
        }
        $active_sub = SELF::findActive($transaction->sender_id);
        if(!is_null($active_sub)){
            throw new InvalidActionException('New subscription cannot be created while one is active or not expired');
        }
        $sub = new static();
        $sub->txn_reference = $transaction->reference;
        $sub->user_id = $transaction->sender_id;
        $sub->cost = $cost;
        $sub->is_complete = 1;
        $sub->is_active = 1;
        $sub->is_latest = 1;
        $sub->start_date = $start;
        $sub->end_date = $end;
        if($transaction->value < $cost){
            if(!$allow_incomplete){
                throw new InvalidActionException('Attempt to set subscription amount less than standard amount');
            }
            $sub->is_complete = 0; 
        }
        $sub->total_paid = $transaction->value;
        $created = $sub->save();
        if($created){
            $switch = SELF::update([
                'is_latest'=>1,
                'user_id'=>$sub->user_id,
                'id !='=>$sub->id
            ],[
                'is_latest'=>0
            ]);
        }
        return $created;
    }

    public static function anuualsub($user_id,$reference,$amount,$type,$benchmark= 60){
        //if amount is greater than 100 take 100 percent or 50 percent if less than 100
        //test against decimal
        if($amount < 2){
            return ['userbalance'=>$amount,'amountpaid'=>0];
        }
        $percent_amount = ($amount >= 100)? ($amount - $benchmark) : ($amount * 50)/100;
        $balanceAmount = $amount - $percent_amount;
        // echo 'Percent Amount======>>  '.$percent_amount. PHP_EOL;
        $total_paid =0;
        $process = 'old';
        $subId =0;
        if($type == 'old'){
            // echo 'Old Process';
            $annualSubscrib = SELF::findActive($user_id);
            var_dump('<pre>',print_r($annualSubscrib,true));
            if(!is_null($annualSubscrib)){
                // echo 'Old user Amount=====>'.$annualSubscrib->total_paid. PHP_EOL;
                $total_paid = $annualSubscrib->total_paid;
                $subId = $annualSubscrib->id;
                //check if is active
                // echo "End Date===> ".date('Y-m-d', strtotime($annualSubscrib->end_date));
                // echo "Current Date===> ".date('Y-m-d');
                $process =  (date('Y-m-d', strtotime($annualSubscrib->end_date)) < date('Y-m-d'))? 'new' : 'old';
                //if the sub return new distractive current sub 
                //
                if($process == 'new'){
                    echo 'dis-activating annual record';
                    $switch = SELF::update([
                        'user_id'=>$annualSubscrib->user_id,
                        'id'=>$subId
                    ],[
                    'is_latest'=>0,
                    'is_active'=>0
                    ]);
                }
                
            }else{
                $process = 'new';
            }
        }
        
        //insert new annual fee
        //$process = (is_null($annualSubscrib))? 'new' : 'old';
       // $remainder = SELF::isRemainder($total_paid,$percent_amount,$benchmark);

        $paid  = $total_paid + $percent_amount;
        $remainder = 0;$deposit = $paid;
        if($paid >= $benchmark){
         $remainder = $paid - $benchmark;
         $paid = $amount - ($balanceAmount + $remainder);
         $deposit =  $total_paid + $paid;
        }
        // sum remainder and percentage balance to get userbalance
        $userBalance =  $balanceAmount + $remainder;
        $paid = $deposit - $total_paid;
        // $userBalance =  ($total_paid + $amount) - $remainder;
        

        //$annual = Transaction::charges_process($amount,$percentage);
        if($type == 'new' || $process == 'new'){
            // echo 'creating new annual record';
            $start = date('Y-m-d H:i:s');
            $end = date('Y-m-d H:i:s', strtotime('+1 years'));

            $sub = new static();
            $sub->txn_reference = $reference;
            $sub->user_id = $user_id;
            $sub->cost = 60;
            $sub->is_complete =($deposit>=60)? 1:0;
            $sub->is_active = 1;
            $sub->is_latest = 1;
            $sub->start_date = $start;
            $sub->end_date = $end; 
            $sub->total_paid =  $deposit;
            $sub->start_date = date('Y-m-d H:i:s');
            $sub->end_date = date('Y-m-d H:i:s', strtotime('+1 years'));
            $created = $sub->save();
            }else{
                // echo 'Update annual record';
                $switch = SELF::update([
                    'user_id'=>$user_id,
                    'id'=>$subId
                ],[
                    'is_complete'=>($deposit>=60)? 1:0,
                    'total_paid'=>$deposit,
                ]);
            }
        
        //return subamount and balance
        echo 'Annual Amount Paid::===>'.$paid;
        echo 'Annual Amount Deposit::===>'.$deposit;
        echo 'User Remainder Balance::===>'.$userBalance;
        return ['userbalance'=>$userBalance,'amountpaid'=>$paid];
    }


    public static function creditAnnualWallet(){
          //The system should get the list of
              $totalFailed = WIPTransaction::count([
                  'is_annual_active'=>1,
                  'status'=>WIPTransaction::COMPLETED_STATUS
              ]);

              if($totalFailed > 0){

                $annual_sub_wallet = Wallet::getForSubscription();
                $system_user = User::getSystemUser();
                $loops =0;
                $loops = ceil($totalFailed/SELF::PROCESS_LIMIT);
    
                log_message('info',"Number of annual fee to be credited ===== ".$totalFailed);

                while($loops >= 1){
    
                    echo "While processing is ".$loops.PHP_EOL;
    
                    $transactions = WIPTransaction::find([
                                        'is_annual_active'=>1,
                                        'status'=>WIPTransaction::COMPLETED_STATUS
                                    ])
                                    ->limit(SELF::PROCESS_LIMIT)
                                    ->asArray()
                                    ->all();
                   
                    $count = 0;
                    SELF::startDbTransaction();
                    foreach($transactions as $transaction){
                       
                        $inTransaction = false;
                        try{
                            // the creditOnEachBulkGifting function is a temp 
                            $process = Transaction::credit(
                              $system_user->id,
                              $transaction['is_annual_fee'],
                              $annual_sub_wallet->id,
                              Transaction::getUniqueReference('AnnualSub',$transaction['recipient_id'],rand(10,99)),
                              'Ipoints platform annual subscription fee',
                              $transaction['recipient_id'],
                              false
                          );
                          $batch_wip[] = array(
                            'id'=>$transaction['id'],
                            'is_annual_active'=> ($process)?  0 : 1
                            );

                        } catch(Throwable $ex){
                            log_message('error',"Error processing this request...".$ex->getMessage());
                            if($inTransaction){
                                SELF::endDbTransaction($commit = false);
                            }
                        }
                        $count++;
                    }
                    $loops--;
                    $transactionResult = WIPTransaction::batchUpdate($batch_wip);
                    unset($batch_wip);
                    SELF::endDbTransaction($transactionResult? true : false);
                    log_message('info',$transactionResult?"Succeeded credit platform subscription wallet===....":"Failed to credit platform subscription wallet===...".PHP_EOL);
              }
                    log_message('info',"Completed processing annual fee..=====...");
              }else{
                log_message('info',"No annual fee to be process ..=====...");
              }
              
    }

    private static function isRemainder($total_paid,$amount,$benchmark){
        $paid  = $total_paid + $amount;
        $remainder = 0;
        if($paid >= $benchmark){
         $remainder = $paid - $benchmark;
        }
        return $remainder; 
    }

    public static function get_sub_sumup_and_count($fee = 60){
        $sub = new static();
        $query = $sub->db->from('user_subscriptions ss')
        ->select('COUNT(*) users,SUM(ss.total_paid) as paid_sumup')
        ->where('ss.total_paid =', $fee)
        ->get();
		return $query->row();
    }
    
}