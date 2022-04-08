<?php

class EspiTransaction extends MY_Model {

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
      $this->load->model('Transaction');
      $this->load->model('Wallet');
     
	}
    
    const DEBIT = 'debit';
    const CREDIT = 'credit';
    const PERCENT = 100;
    const PENDING = 'pending';

    const STATUS_PENDING ='pending';
    const STATUS_COMPLETED ='completed';
    const STATUS_FAILED ='failed';

    public static function getTableName(){
        return 'espi_transaction';
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

    public static function getUniqueRequest(){
        $seedbroker = '';
        if(func_num_args() > 0){
            $seedbroker = implode('_',func_get_args());
            return 'ESPI_'.$seedbroker.'_'.uniqid();
        }
        return 'ESPI__'.uniqid();
    } 

    public static function isRefExistReValue($request){
        return SELF::isExistwithValue(['request' => $request]);
    }

    public function createAndGetId($data){
		$this->db->insert('espi_transaction', $data);
		return $this->db->insert_id();
    }
    
    public static function _update($column,$value,$data){
        $espitran = new static();
        $espitran->db->where($column,$value);
        return $espitran->db->update(SELF::getTableName(), $data);
    }

    public static function isavingsEpsiCredit($recipient_id,$isavings_qty,$reference,$sender_id,$description = "",$is_charge = true){
        $iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
		$isavings =Transaction::charges_proccess($isavings_qty,$iSaving_charge->value);
		$espiResult = SELF::isavingsEspiWalletSingleProcess($recipient_id,$isavings_qty,$reference,$sender_id,$description,$is_charge);
		return ['userBalance'=>$isavings['userBalance'], 'result'=>$espiResult];
    }

    public static function depositUiciWallet($request,$amount,$description = ""){
        
    }

    public static function bulktransferForIsavings(Array $bulk_transfer =[]){
      
    }

    public static function depositOnEspiAndbulktransfer($user_id,$request,$reference,$amount,Array $bulk_transfer =[],$description = "",$commission =0){
         $espResult =SELF::depositUiciWallet($request,$amount,$description)? SELF::bulktransferForIsavings($bulk_transfer): false;
        //return SELF::depositUiciWallet($request,$amount,$description);
         //SELF::bulktransferForIsavings($bulk_transfer);
        
        log_message('info','Reference ====>'.$reference);
        log_message('info','Amount ====>'.$amount);
        log_message('info','bulk_transfer ====>'.print_r($bulk_transfer,true));
        log_message('info','Description ====>'.$description);
        log_message('info','Isavings commission===>'.$commission);
        $data=[
            'status'=>($espResult)?'completed':'failed',
            'updated_at'=>date('Y-m-d H:i:s'),
        ];
        $result = SELF::_update('request',$request,$data);
        //if bolt are true
        if($espResult){
            $system_user = User::systemAccount();
            $system_commission_wallet = Wallet::walletByName(I_SAVINGS_EARNING);
          return  Transaction::credit($system_user->id, $commission, $system_commission_wallet->id, $request = '', $description, $user_id, FALSE);
        }else{
            return false;
        }
        
    }
    // Single espi deposit and credit wallet process
    public static function isavingsEspiWalletSingleProcess($user_id,$quantity,$payment_ref,$sender_id,$description,$is_charge){

        $user = User::findByPk($user_id);
        $wallet_isavings = Wallet::walletByName(I_SAVINGS);
		$iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
						$isavings =Transaction::charges_proccess($quantity,$iSaving_charge->value);
						$amount =($is_charge)? $isavings['userBalance']:$quantity;
						$commission = $isavings['adminPercent'];
						
						$bulk_transfer = [
							'recipient'=>[
								'phone'=>$user->mobile_number,
								'walletId'=> $wallet_isavings->id,
								'walletType'=> $wallet_isavings->name
                                ],
							'amount'=>(float)$amount,
							'ref'=>$payment_ref
                        ];
                        

                            $request= EspiTransaction::getUniqueRequest();
                            $insert = EspiTransaction::insert([
                                'request'=>$request,
                                'reference'=>$payment_ref,
                                'status'=>'pending',
                                'value'=>(float)$quantity,
                                'amount'=>(float)$amount,
                                'recipient_count'=>count($bulk_transfer),
                                //'type'=>$type,
                                'type'=>'credit',
                                'sender'=>$sender_id,
                                'description'=>$description,
                                'created_at'=>date('Y-m-d H:i:s'),
                            ]);
                            $processed = SELF::depositOnEspiAndbulktransfer($user_id,$request,$payment_ref,(float)$amount,$bulk_transfer,$description,$commission);
						
						return ($processed && $insert)? true:false;
    }
    // Batch espi deposit and credit wallet process
    public static function isavingsEspiWalletBatchProcess($user_id,$payment_ref,$quantity,Array $bulk_transfer =[],$description="",$commission,$type="credit"){
        $request= SELF::getUniqueRequest();
        $insert = SELF::insert([
                                'request'=>$request,
                                'reference'=>$payment_ref,
                                'status'=>'pending',
                                'value'=>(float)$quantity+$commission,
                                'amount'=>(float)$quantity,
                                'recipient_count'=>count($bulk_transfer),
                                'type'=>$type,
                                'sender'=>$user_id,
                                'description'=>$description,
                                'created_at'=>date('Y-m-d H:i:s'),
                            ]);
                            $processed = SELF::depositOnEspiAndbulktransfer($user_id,$request,$payment_ref,(float)$quantity,$bulk_transfer,$description,$commission);
						
						return ($processed && $insert)? true:false;
    }


    public static function calculatePercentage($isavingsQty){
        $iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
		$isavings = Transaction::charges_proccess($isavingsQty,$iSaving_charge->value);
		return ['iSavingsBalance'=>$isavings['userBalance'], 'commissionBalance'=>$isavings['adminPercent']];
    }

    // this function is doing two process, transfer and deposit on espi
    public static function transferOrDepositIsavingsOnEspi($transactionType='transfer',$senderId,$recipientId,$quantity,$payment_ref = '',$description, $is_commission = FALSE){
        $recipient = User::findById($recipientId);
      return ($transactionType =='transfer')? SELF::transferToEspiWallet($senderId,$recipient->mobile_number,$quantity,$description):
                                              SELF::depositProcess($senderId,$recipient->mobile_number,$payment_ref,$quantity,1,'credit',$description, $is_commission);
    }

    // this function can transfer from isavings to isavings
    public static function transferToEspiWallet($sender_id,$recipientPhone,$amount,$description){
        $sender = User::findById($sender_id);
        log_message('ERROR','GETTING User Phone===== '.$recipientPhone);
        $url = ESPI_URL.'api/users/'.$sender->mobile_number;
        $result =  IsavingsPortToEspiWallet::checkIsavingsBalance($url);
        //var_dump('<pre>',$result['status']);
        //var_dump('<pre>',$result['data']['wallets']['fela']['balance']);
        $walletId = $result['data']['wallets']['isavings']['id'] ?? false;
        if($result['status'] != 'succeeded' || !$walletId){
            // send faild message
            log_message('ERROR','ESPI failed to get user wallet_id ====|');
            return false;
        }
        $url= ESPI_URL.'api/ewallets/'.$walletId.'/transfer';
        $request= SELF::getUniqueRequest($amount,'credit',$recipientPhone);
        log_message('INFO','Amount before Round==========='.$amount);
        $amount = round($amount, 2, PHP_ROUND_HALF_DOWN);
        $data = [
            'reference'=> $request,// it using request for a reference , cus request is unique
            //'recipient' => ['phone' => $recipientPhone,'walletId' =>$walletId],
            'recipient' => ['phone' => $recipientPhone,'walletType' =>strtolower(I_SAVINGS)],
            'accessToken'=> ESPI_ACCESSTOKEN,
            'amount'=> $amount,
            'description'=> $description,
        ];

        $insert = SELF::insert([
            'request'=>$request,
            'reference'=>$request,
            'status'=>'pending',
            'value'=>$amount,
            'amount'=>$amount,
            'recipient_count'=>1,
            'type'=>'credit',
            'sender'=>$sender_id,
            'description'=>$description,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
      $transferResult = IsavingsPortToEspiWallet::espiInterceptorPost($url,$data);

      if($transferResult){
        log_message('info','ESPI was successfully transferred with this request=========|'.$request);
        $data=[
            'status'=>'completed',
            'updated_at'=>date('Y-m-d H:i:s'),
        ];
        return SELF::_update('request',$request,$data);  
      }else{
          //The system should send message to admin
          log_message('ERROR','ESPI failed to transfer with this request=========|'.$request);
          //admin notification
            // $info['report_type'] = PRODUCT_SUBSCRIPTION;
            // $info['frequency'] = MONTHLY;
            // $info['dispatcher_type'] = GROUP;
            // $reports = ReportSubscription::getReportSubscription($db,$info);
            // //dump sql file in a path and return part url
            // $emails =  array_column($reports, 'email');
            // unset($reports);
            // $variable = array('','');// name, email , number of records, sum-up qty, request id
            // MessageQueue::messageCommit($emails, MESSAGE_EMAIL,REGISTER, $variable);
        $data=[
            'status'=>'failed',
            'updated_at'=>date('Y-m-d H:i:s'),
        ];
         SELF::_update('request',$request,$data);
         return false;
      }
    }

    // this function process deposit or credit a wallet on espi and keep the transaction record on ipoints 
    public static function depositProcess($user_id,$recipient_mob_number,$payment_ref,$quantity,$recipient_count,$type='deposit',$description, $is_commission = FALSE){
        //log_message('info', 'function args: '. print_r(arguments, true));
        $iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
		$isavings =Transaction::charges_proccess($quantity,$iSaving_charge->value);
        $amount =($is_commission)?round($isavings['userBalance'], 2, PHP_ROUND_HALF_DOWN):$quantity;
        //$amount = round($amount, 2, PHP_ROUND_HALF_DOWN);
        log_message('INFO','Amount after Round==========='.$amount);
        log_message('INFO','Total Amount User Balance on commission ==========='.$amount);
        log_message('INFO','Amount after Round==========='.$amount);
		$commission = $isavings['adminPercent'];
        $request= SELF::getUniqueRequest($user_id,$quantity,$type,$recipient_count);
        $insert = SELF::insert([
            'request'=>$request,
            'reference'=>$payment_ref,
            'status'=>'pending',
            'value'=>$quantity,
            'amount'=>$amount,
            'recipient_count'=>$recipient_count,
            'type'=>$type,
            'sender'=>$user_id,
            'description'=>$description,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        $deposiResult = SELF::depositOnEspiWallet($request,$recipient_mob_number,$amount,$description);
       
        if($deposiResult && $insert){
            log_message('info','ESPI was deposited successful with this request=========|'.$request);
            $data=[
                'status'=>'completed',
                'updated_at'=>date('Y-m-d H:i:s'),
            ];
            $result = SELF::_update('request',$request,$data);
            if(!$is_commission){
                return true;
            }
           return (SELF::walletCommissionCharge($user_id,$commission,$request,$description) && $result)?? false;
        }else{
            log_message('ERROR','ESPI failed to deposit with this request=========|'.$request);
            //$info['report_type'] = PRODUCT_SUBSCRIPTION;
            // $info['frequency'] = MONTHLY;
            // $info['dispatcher_type'] = GROUP;
            // $reports = ReportSubscription::getReportSubscription($db,$info);
            // //dump sql file in a path and return part url
            // $emails =  array_column($reports, 'email');
            // unset($reports);
            // $variable = array('','');// name, email , number of records, sum-up qty, request id
            // MessageQueue::messageCommit($emails, MESSAGE_EMAIL,REGISTER, $variable);
            $data=[
                'status'=>'failed',
                'updated_at'=>date('Y-m-d H:i:s'),
            ];
            $result = SELF::_update('request',$request,$data);
            return false;
        }
    }

    public static function depositOnBulkTransferProcess($user_id,$recipient_mob_number,$payment_ref,$amount,$commission,$recipient_count,$type='deposit',$description, $is_commission = FALSE){
        //log_message('info', 'function args: '. print_r(arguments, true));
        $amount = round($amount, 2, PHP_ROUND_HALF_DOWN);
        $commission = round($commission, 2, PHP_ROUND_HALF_DOWN);
        $request= SELF::getUniqueRequest($user_id,$commission,$amount,$type,$recipient_count);
        $insert = SELF::insert([
            'request'=>$request,
            'reference'=>$payment_ref,
            'status'=>'pending',
            'value'=>$commission+$amount,
            'amount'=>$amount,
            'recipient_count'=>$recipient_count,
            'type'=>$type,
            'sender'=>$user_id,
            'description'=>$description,
            'created_at'=>date('Y-m-d H:i:s'),
        ]);
        $deposiResult = SELF::depositOnEspiWallet($request,$recipient_mob_number,$amount,$description);
       
        if($deposiResult && $insert){
            log_message('info','ESPI was deposited successful with this request=========|'.$request);
            $data=[
                'status'=>'completed',
                'updated_at'=>date('Y-m-d H:i:s'),
            ];
            $result = SELF::_update('request',$request,$data);
            if(!$is_commission){
                return true;
            }
           return (SELF::walletCommissionCharge($user_id,$commission,$request,$description) && $result)?? false;
        }else{
            log_message('ERROR','ESPI failed to deposit with this request=========|'.$request);
            //$info['report_type'] = PRODUCT_SUBSCRIPTION;
            // $info['frequency'] = MONTHLY;
            // $info['dispatcher_type'] = GROUP;
            // $reports = ReportSubscription::getReportSubscription($db,$info);
            // //dump sql file in a path and return part url
            // $emails =  array_column($reports, 'email');
            // unset($reports);
            // $variable = array('','');// name, email , number of records, sum-up qty, request id
            // MessageQueue::messageCommit($emails, MESSAGE_EMAIL,REGISTER, $variable);
            $data=[
                'status'=>'failed',
                'updated_at'=>date('Y-m-d H:i:s'),
            ];
            $result = SELF::_update('request',$request,$data);
            return false;
        }
    }
   
    // this function can do bulk deposit, you can also use de-same function to transfer from ipoints to isavings
    public static function depositOnEspiWallet($request,$recipient_mob_number,$amount,$description){
        if(empty($recipient_mob_number)){
            $system_user = User::systemAccount();
            $recipient_mob_number = $system_user->mobile_number;
        }
        log_message('INFO','Amount After Round Deposit==========='.$amount);
        $amount = round($amount, 2, PHP_ROUND_HALF_DOWN);
        $url= ESPI_URL."api/ewallets/deposit";
        $data = [
            'reference'=> $request,// it using request for a reference , cus request is unique
            'amount'=> $amount,
            'description'=> $description,
            'recipient' => ['phone' => $recipient_mob_number]
        ];
      return  IsavingsPortToEspiWallet::espiInterceptorPost($url,$data);
    }
    // this function can credit bulk isavings wallets
    public static function bulkTransferOnEspiWallet($user_id,$amount,$payment_ref,$commission,Array $bulkTransfer = [],$description){
        
        $system_user = User::systemAccount();
        $url = ESPI_URL.'api/users/'.$system_user->mobile_number;
        $result =  IsavingsPortToEspiWallet::checkIsavingsBalance($url);
        //var_dump('<pre>',$result['status']);
        //var_dump('<pre>',$result['data']['wallets']['fela']['balance']);
        if($result['status'] != 'succeeded'){
            // send faild message
            log_message('ERROR','ESPI can not get user wallet_id ====|');
            return false;
        }
        $walletId = $result['data']['wallets']['isavings']['id'];

        log_message('INFO','Amount before Round==========='.$amount);
        $url=ESPI_URL."api/ewallets/bulk-transfer";
        $request= SELF::getUniqueRequest($user_id,$amount);
        $data = [
            'batchId'=>$request,
            'walletId'=>$walletId,
            'accessToken'=> ESPI_ACCESSTOKEN,
            'data'=> $bulkTransfer
        ];
        $amount = round($amount, 2, PHP_ROUND_HALF_DOWN);
        $insert = SELF::insert([
            'request'=>$request,
            'reference'=>$payment_ref,
            'status'=>'pending',
            'value'=>round($amount+$commission, 2, PHP_ROUND_HALF_DOWN),
            'amount'=>$amount,
            'recipient_count'=>count($bulkTransfer),
            'type'=>'credit',
            'sender'=>$user_id,
            'description'=>$description,
            'created_at'=>date('Y-m-d H:i:s'),
            ]);
        $bulkTransferResult = IsavingsPortToEspiWallet::espiInterceptorPost($url,$data);
        if($bulkTransferResult && $insert){
            log_message('info','ESPI was successful process Bulk-Transfer with this request=========|'.$request);
            $data=[
                'status'=>'completed',
                'updated_at'=>date('Y-m-d H:i:s'),
            ];
            return SELF::_update('request',$request,$data);
        }else{
            log_message('ERROR','ESPI failed to process Bulk-Transfer with this request=========|'.$request);
            $data=[
                'status'=>'failed',
                'updated_at'=>date('Y-m-d H:i:s'),
            ];
            $result = SELF::_update('request',$request,$data);

            //$info['report_type'] = PRODUCT_SUBSCRIPTION;
            // $info['frequency'] = MONTHLY;
            // $info['dispatcher_type'] = GROUP;
            // $reports = ReportSubscription::getReportSubscription($db,$info);
            // //dump sql file in a path and return part url
            // $emails =  array_column($reports, 'email');
            // unset($reports);
            // $variable = array('','');// name, email , number of records, sum-up qty, request id
            // MessageQueue::messageCommit($emails, MESSAGE_EMAIL,REGISTER, $variable);
            return false;
        }
    }
    // this function take percentage set for isavings
    private static function walletCommissionCharge($user_id,$commission,$ref,$description){
        $system_user = User::systemAccount();
        $system_commission_wallet = Wallet::walletByName(I_SAVINGS_EARNING);
      return  Transaction::credit($system_user->id, $commission, $system_commission_wallet->id, $ref, $description, $user_id, FALSE);
    }

    public static function fitterEspiTransaction(PDO $db,$data,$isExport = false){
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  et.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['request'])){
            $where[] =" et.request = '".$data['request']."'";
        }if(!empty($data['reference'])){
            $where[] =" et.reference = '".$data['reference']."'";
        }if(!empty($data['type'])){
            $where[] =" et.type = '".$data['type']."'";
        }if(!empty($data['status'])){
            $where[] =" et.status = '".$data['status']."'";
        }if(!empty($data['value'])){
            $where[] =" et.value = '".$data['value']."'";
        }if(!empty($data['sender'])){
            $where[] =" et.sender = ".$data['sender'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $countWhere = $where;
        $where = $where .= (!$isExport)?" GROUP BY et.created_at LIMIT ".$data['limit']." OFFSET ".$data['offset'] :" GROUP BY et.created_at ";

        try{
            $query = " SELECT et.*, CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `sender_name`
            FROM espi_transaction as et LEFT JOIN `users` as `u` ON `u`.`id` = `et`.`sender` ".$where;
            if ($isExport) {
                return $query;
            }

            $fromAndJoin = " FROM espi_transaction et 
            LEFT JOIN `users` as `u` ON `u`.`id` = `et`.`sender` ";

        $countQuery = " SELECT COUNT(*) AS allCount {$fromAndJoin} {$countWhere}";
            if(true){
                //var_dump($countQuery);
                $stmt= $db->query($countQuery);
                 SELF::$result_count = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            //var_dump($query);
                $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }  
    }


} 