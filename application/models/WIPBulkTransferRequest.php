<?php

class WIPBulkTransferRequest extends MY_Model {   

    protected $_mapCount = 0;
    protected $WIPTransaction;
    const BULK_PROCESS_LIMIT = 1000;

    public function __construct()
	{
	  parent::__construct();
      $this->load->library('Untils');
      $this->load->model('Setting_m');
      $this->load->model('Wallet');
      $this->load->model('Uici_levies');
      $this->load->library("pdolib");
	}

    public static function getTableName(){
        return 'wip_bulk_transfer_requests';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    } 

    public function loadWIPTransaction(){
        $this->load->model('WIPTransaction');
        $this->WIPTransaction = new WIPTransaction;
    }

    protected function __map($value){
        $data = clone $this->WIPTransaction;
        $this->total_transaction_value += $value['qty'];
        $data->setAttributes($value);
        $data->request_id = $this->request_id;
        $data->wallet_id = $this->wallet_id;
        $data->client_id = $this->user_id;
        $data->created = $this->created_at;
        $data->beforeSave();
        $data->prepareAttributes();
        $this->_mapCount++;
        //echo $this->_mapCount."".PHP_EOL;
        
        //return [];
        return $data->getAttributes();    
    }

    /*expected data

    [
        request_id: string,
        client_id: string,
        recipients: [
            recipient_phone: string,
            qty: number,
            service_id: number
        ]
    ]

    */

    public static function load($data, $verbose = FALSE){
        try
        {
            if(empty($data)){
                throw new Exception('The system can not process empty record');
            }
            $loop = 1;
            foreach($data["recipients"] as $dt){
                if(is_float($dt['qty']) || !$dt['qty'] > 0){
                    log_message('info',"The system can't accept Zero or Decimal number as qty value, At Line: ".$loop);
                    throw new Exception("The system can't accept Zero or Decimal number as qty value, At Line: ".$loop);
                }
                $loop ++;
            }
            $request = new static();
            $request->request_id = $data['request_id'];
            $request->wallet_id = $data['wallet'];
            $request->user_id = $data['client_id'];
            $request->created_at = date('Y-m-d H:i:s');
            $request->total_transaction_value = 0;
            log_message('info',$verbose?"Mapping Recipients...":"");
            $request->loadWIPTransaction();
            
            log_message('info',print_r($data,true));
            
            $recipients = array_map([$request,'__map'],$data['recipients']);
            unset($data['recipients']);
            log_message('info',$verbose?"Recipients Mapping Complete...":"");
            $request->recipients_count = count($recipients);
            $request->load->model('WIPTransaction');
            $request->load->model('Transaction');
            $request->load->model('Wallet');
            // $ci =& get_instance();
            // $db = $ci->pdolib->getPDO();
            // //$wallets = Wallet::getAllWallets($db);
            // //exit();

            $error_occured = false;
            try
            {
                SELF::startDbTransaction();
                $request->txn_reference = Transaction::getUniqueReference($request->user_id,rand(10,99));
                $main_wallet = Wallet::getMain();
                $system_user = User::getSystemUser();
                log_message('info',$verbose?'Doing debit of '.$request->total_transaction_value."...":"");
                $debit = Transaction::debit(
                    $request->user_id,
                    $request->total_transaction_value,
                    $main_wallet->id,
                    $request->txn_reference,
                    'BULK TRANSFER',
                    $system_user->id,
                    false
                    );
                if(!$debit || !$request->save()){
                    throw new SystemException('Could not debit or Request could not be saved');
                }
                log_message('info',$verbose?"Debit successful":"");
                log_message('info',$verbose?"Loading recipients...":"");
                $chunks = array_chunk($recipients,5000);
                unset($recipients);
                $counter = 0;
                foreach($chunks as $chunk){
                    if(!WIPTransaction::loadBulk($chunk)){
                        throw new SystemException('Loading recipient chunk failed');
                    }
                    log_message('info',$verbose?"...Inserted....":"");
                }
                log_message('info',$verbose?"Loaded Recipients!":"");
                SELF::endDbTransaction($commit = true);
                $client = User::findById($request->user_id);

                $contact = $client->email;
                $type =  MESSAGE_EMAIL;
                //Untils::encryptedMessage($info);
                $variable = array($request->total_transaction_value,$request->txn_reference,$request->recipients_count);
                MessageQueue::messageCommit($contact, $type, BULK_TRANSFER, $variable);
                    } catch(Exception $ex){
                        SELF::endDbTransaction($commit = false);
                        throw $ex;
                    }
            log_message('info',$verbose?"Done!":"");
            return true;
        } catch(Exception $ex){
            throw $ex;
            return false;
        }
    }

    public static function getGiftingConfig($user_id,$wallet_id)
	{
        $giftConf = new static();
		$query = $giftConf->db->from('gifting_config gc')
						->select('gc.*')
						->where('gc.user_id', $user_id)
						->where('gc.wallet_id', $wallet_id)
						->get();
		    return $query->row();
    }

    public static function getUserCharges($user_id){
        $userCharges = new static();
        $query = $userCharges->db->from('user_charges uc');
        $userCharges->db->select('uc.*,ul.name,ul.value');
        $userCharges->db->where('uc.user_id', $user_id);
        $userCharges->db->join('uici_levies as ul', 'ul.id = uc.uici_levies', 'left');
        return $userCharges->db->get()->result();
    }

    public static function walletCharge($walletName){
        $walletCharges = [
            ['wallet' => I_SAVINGS, 'name' => ISAVINGS_CHARGES_KEY,'commission_wallet' => I_SAVINGS_EARNING],
            ['wallet' => I_INSURANCE, 'name' => IINSURANCE_CHARGES_KEY, 'commission_wallet' => I_INSURANCE_EARNING],//
            ['wallet' => I_LIFE, 'name' => IINSURANCE_CHARGES_KEY, 'commission_wallet' => I_INSURANCE_EARNING],
            ['wallet' => I_PENSION, 'name' => IPENSION_CHARGES_KEY, 'commission_wallet' => I_PENSION_EARNING],
            ['wallet' => I_HEALTH, 'name' => IINSURANCE_CHARGES_KEY, 'commission_wallet' => I_INSURANCE_EARNING],
        ];
        foreach($walletCharges as $walletCharge){
            if($walletCharge['wallet'] == $walletName){
                return $walletCharge;
                break;
            }
        }
    }

    public static function fetchUserChargesByName($charges,$name){
        if(!is_null($charges)){
            foreach($charges as $charge){
                if($charge->name == $name){
                return $charge;
                }
            }
            return null; 
        }
        return null;
    }
    public static function chooseTemplate($message_templates,$message_temp_id){
        foreach($message_templates as $message_template){
            if($message_temp_id == $message_template->id){
                return $message_template;
            }
        }
        return null;
    }

    private static function process($request, $verbose = FALSE){

        try {
           
            $giftConfig = SELF::getGiftingConfig($request->user_id,$request->wallet_id);
            //$MerchantCharges = SELF::getUserCharges($request->user_id);
            if(is_null($giftConfig)){
                log_message('error',"The merchant didn't have gifting configuration");
                throw new Exception("The merchant didn't have gifting configuration");
            }
            var_dump($giftConfig);
            switch ($giftConfig->process_type) {
                case 'default':
                    return SELF::defaultBulkGiftingProcess($request,$giftConfig);
                    break;
                case 'espi':
                    return SELF::espiBulkGiftingProcess($request,$giftConfig);
                    break;
                default:
                    log_message('info',"The gifting configuration process type those not exist...");
                    throw new Exception("The gifting configuration process type those not exist...");
            }
           
        } catch (Throwable $e){
            throw $e;
        }
      
    }

    public static function defaultBulkGiftingProcess($request,$giftConfig){
        echo 'Default Bulk Gifting Process================='.PHP_EOL;

        $wallet = Wallet::walletById($request->wallet_id);
        $merchantCharges = SELF::getUserCharges($request->user_id);
       
        
         $walletCharge = SELF::walletCharge($wallet->name);
         $userChargesByName = SELF::fetchUserChargesByName($merchantCharges,$walletCharge['name']);
         //var_dump($giftConfig->message_temp);
         
         $wallet_charge = (empty($userChargesByName))? false: Uici_levies::getUiciLevieValue($userChargesByName->name);
         //var_dump($wallet_charge);
               
         log_message('info','WIPTransaction WaLLet=======> ');

                $user = User::findByPk($request->user_id);
                $desc = 'TRANSFER FROM '.$request->user_id;
                if(!is_null($user)){
                    $desc = 'TRANSFER FROM '.$user->getIdentity();
                }
                $untils = new Untils;
                log_message('info','Get count of pending transaction with request_id____');
                $totalPending = WIPTransaction::count([
                    'request_id'=>$request->request_id,
                    'status'=>WIPTransaction::PENDING_STATUS
                ]);
                echo $totalPending." found!".PHP_EOL;
                $loops =0;
                $loops = ceil($totalPending/SELF::BULK_PROCESS_LIMIT);
                echo "5000 Bash Records need to be processed ".$loops.PHP_EOL;
                $system_user = User::getSystemUser();
                $annual_sub_wallet = Wallet::getForSubscription();
               
                $system_commission_wallet = Wallet::walletByName($walletCharge['commission_wallet']);//commission_wallet
                SELF::startDbTransaction();
                $messageCommit = new static();
                $message_temp = json_decode($giftConfig->message_temp, true);
                // var_dump($message_temp[1]['type']);
                // select message templates using message ids
                $message_templates = $messageCommit->messagetemplate_m->getTemplateByIds(['idOne' => $message_temp[0]['id'],'idTwo' => $message_temp[1]['id']]);
                //var_dump($message_templates);
                // temporary comment

                $processing = SELF::updateByPk($request->id,[
                    'status'=>WIPTransaction::PROCESSING_STATUS,
                    'updated_at'=> date('Y-m-d H:i:s')
                ]);

            
                if(!$processing){
                    SELF::endDbTransaction($processing);
                    log_message('error','WipBulkTransfer failed to update the status of processing');
                    return false;
                }
                SELF::endDbTransaction(true);
                
               while($loops >= 1){

                    $defaultPass = $untils->autoGeneratorPwd(8);
                    $defaultPassHash = password_hash($defaultPass, PASSWORD_DEFAULT);
                   

                    echo "Processing batch...".PHP_EOL;
                    $transactions = WIPTransaction::find([
                                        'request_id'=>$request->request_id,
                                        'status'=>WIPTransaction::PENDING_STATUS
                                    ])
                                    ->limit(SELF::BULK_PROCESS_LIMIT)
                                    ->asArray()
                                    ->all();
                    echo "Processing...".PHP_EOL;
                    $count = 0;
                    $total_amount = 0;$total_commission = 0;
                    try {
                        SELF::startDbTransaction();
                        //for ($x = 0; $x < count($batch); $x++) {
                      foreach($transactions as $transaction){    
                            //$process = Transaction::debit()
                            echo "Processing...count ".$count.PHP_EOL;
                            $ver = "Processing...count ".$count.PHP_EOL;
                            $inTransaction = false;
                            try{
                                log_message('info',$ver.' Start To Process User Record===================____'.$transaction['recipient_phone']);
                                echo "Getting user id...".PHP_EOL;
                                echo microtime().PHP_EOL;
                                $user = Untils::bulk_transfer_auto_create_user($transaction['recipient_phone'],$defaultPassHash);
                                if($user['process']){
                                    echo microtime().PHP_EOL;
                                    $inTransaction = true;
                                    echo "Crediting user...".PHP_EOL;
                                    echo microtime().PHP_EOL;
                                    // Isavings espi deposit process
                                    
                                    //$isavings = 0;$commission = 0;
                                    $_reference =Transaction::getUniqueReference($user['id'],$transaction['qty'], $transaction['client_id']);
                                    // check if the merchant charges annual fee is true
                                    $amountpaid = 0; $commission = 0;
                                    $userAnnualCharges = SELF::fetchUserChargesByName($merchantCharges,ANNUAL_CHARGES_KEY);
                                    // var_dump($userChargesByName);
                                    if(!empty($userAnnualCharges) && $userAnnualCharges->status){
                                        
                                        $annualResult = UserSubscription::anuualsub($user['id'],$_reference,$transaction['qty'],$user['status'],$userAnnualCharges->value);
                                        $amountpaid = round($annualResult['amountpaid'], 2, PHP_ROUND_HALF_DOWN);
                                        $process = Transaction::credit(
                                            $system_user->id,// get system user_id
                                            $amountpaid,
                                            $annual_sub_wallet->id,// get commission wallet
                                            $_reference,
                                            'Annual fee charges',
                                            $user['id'],
                                            false
                                        );
                                        $transaction['qty'] = $annualResult['userbalance'];
                                        //var_dump($annualResult);
                                        echo 'Annual charges taking place============'.$amountpaid.PHP_EOL;
                                    }
                                    
                                    // check if the merchant charges commission is true
    
                                    if(!empty($wallet_charge) && $userChargesByName->status){
                                        
                                        $isavings = Transaction::charges_proccess($transaction['qty'],$userChargesByName->value);
                                        $userBalance = round($isavings['userBalance'], 2, PHP_ROUND_HALF_DOWN);
                                        
                                        $commission = round($isavings['adminPercent'], 2, PHP_ROUND_HALF_DOWN);
                                        $process = Transaction::credit(
                                            $system_user->id,// get system user_id
                                            $commission,
                                            $system_commission_wallet->id,// get commission wallet
                                            $_reference,
                                            'Commission charges',
                                            $user['id'],
                                            false
                                        );
                                        $transaction['qty'] = $userBalance;
                                        echo 'Wallet commission taking place============'.$userBalance.PHP_EOL;
                                    }
                                    echo 'Amount credited========================'.$transaction['qty'].PHP_EOL;
                                    $process = Transaction::credit(
                                        $user['id'],
                                        $transaction['qty'],
                                        $transaction['wallet_id'],
                                        $_reference,
                                        $desc,
                                        $transaction['client_id'],
                                        false
                                    );
                                    $template =null;
                                    //  var_dump($message_temp);
                                    //  var_dump($user['status']);
                                    // var_dump($message_templates[0]);
                                    // var_dump($message_templates[1]);
                                    
                        
                                    
                                    if($user['status'] == WIPTransaction::MESSAGE_OLD){
                                        var_dump($message_templates,$message_temp[0]['id']);
                                        $template = SELF::chooseTemplate($message_templates,$message_temp[0]['id']);
                                        echo 'Old Template====================='.PHP_EOL;
                                    }else{
                                        $template = SELF::chooseTemplate($message_templates,$message_temp[1]['id']);
                                        echo 'New Template====================='.PHP_EOL;
                                    }
                                    //var_dump($template);
                                       
                                    // $template = ($user['status'] == WIPTransaction::MESSAGE_OLD)? $message_template_old : $message_template_new;
                                    (empty($template))?false:($giftConfig->send_message)?SELF::messageProcess($giftConfig,$user['id'],$process,$transaction['id'],$user['status'],$transaction['recipient_phone'],$transaction['qty'],$amountpaid, $commission,$template,$defaultPass):false;
                                    
                                    unset($template);
    
                                    $batch_wip[] = array(
                                        'id'=>$transaction['id'],
                                        'status'=>($process)?WIPTransaction::COMPLETED_STATUS : WIPTransaction::PENDING_STATUS,
                                        'message'=> ($user['status'] == WIPTransaction::MESSAGE_OLD)?  WIPTransaction::MESSAGE_OLD : WIPTransaction::MESSAGE_NEW,
                                        'is_annual_fee'=> round($amountpaid, 2, PHP_ROUND_HALF_DOWN),
                                        'is_annual_active'=> ($amountpaid> 0)?  1 :0,
                                        'credited'=> $transaction['qty'],
                                        'commission'=> round($commission, 2, PHP_ROUND_HALF_DOWN),
                                        'recipient_id'=> $user['id'],
                                        'unq_reference'=> $_reference,
                                        'updated'=> date('Y-m-d H:i:s')
                                    );
                                    //var_dump($batch_wip);
                                    //SELF::endDbTransaction(true);
                                
                                    // exit();
                                    // break;
                                }else{
                                     // invalid
                                    $process = WIPTransaction::updateByPk($transaction['id'],[
                                        'status'=>WIPTransaction::INVALID_STATUS,
                                            //'message'=>((time()-(60*60*24)) < strtotime(date('Y-m-d H:i:s'))) ? WIPTransaction::MESSAGE_NEW : WIPTransaction::MESSAGE_OLD, 
                                            'is_annual_active'=> 0,
                                            'message'=> WIPTransaction::MESSAGE_NEW, 
                                            'updated'=> date('Y-m-d H:i:s')
                                    ]);
                                }
                                
                            } catch(Throwable $ex){
                                log_message('info',"Error processing this request...".$ex->getMessage());
                                if($inTransaction){
                                    SELF::endDbTransaction(false);
                                }
                            }
                            $count++;
                        }
                        $transactionResult = WIPTransaction::batchUpdate($batch_wip);
                        log_message('info',$transactionResult?"Succeeded Bulk Gifting Process ===....":"Failed Bulk Gifting Process===...".PHP_EOL);
                        SELF::endDbTransaction($transactionResult);
                        unset($batch_wip);
                    } catch (Throwable $error){
                        log_message('info',"Something happened during transaction...".PHP_EOL.$error->getMessage());
                    }
                    //foreach($transactions as $transaction){
                    
                    $loops--;

                   
               }
                echo "Done...";
                $process = SELF::updateByPk($request->id,[
                    'status'=>WIPTransaction::COMPLETED_STATUS,
                    'updated_at'=> date('Y-m-d H:i:s')
                ]);
               
                log_message('info',"Bulk Transfer successfully completed With Request Id++++++:: ".$request->request_id);
                echo $process?"Completed...":"".PHP_EOL;
                return true;
    }


    public static function espiBulkGiftingProcess($request,$giftConfig){

        echo 'ESPI Bulk Gifting Process================='.PHP_EOL;

        $wallet = Wallet::walletById($request->wallet_id);
        $merchantCharges = SELF::getUserCharges($request->user_id);
       
        
         $walletCharge = SELF::walletCharge($wallet->name);
         $userChargesByName = SELF::fetchUserChargesByName($merchantCharges,$walletCharge['name']);
         //var_dump($giftConfig->message_temp);
         
         $wallet_charge = (empty($userChargesByName))? false: Uici_levies::getUiciLevieValue($userChargesByName->name);
         //var_dump($wallet_charge);
               
         log_message('info','WIPTransaction WaLLet=======> ');

                $user = User::findByPk($request->user_id);
                $desc = 'TRANSFER FROM '.$request->user_id;
                if(!is_null($user)){
                    $desc = 'TRANSFER FROM '.$user->getIdentity();
                }
                $untils = new Untils;
                log_message('info','Get count of pending transaction with request_id____');
                $totalPending = WIPTransaction::count([
                    'request_id'=>$request->request_id,
                    'status'=>WIPTransaction::PENDING_STATUS
                ]);
                echo $totalPending." found!".PHP_EOL;
                $loops =0;
                $loops = ceil($totalPending/SELF::BULK_PROCESS_LIMIT);
                echo "5000 Bash Records need to be processed ".$loops.PHP_EOL;
                $system_user = User::getSystemUser();
                $annual_sub_wallet = Wallet::getForSubscription();
               
                $system_commission_wallet = Wallet::walletByName($walletCharge['commission_wallet']);//commission_wallet
                SELF::startDbTransaction();
                $messageCommit = new static();
                $message_temp = json_decode($giftConfig->message_temp, true);
                // var_dump($message_temp[1]['type']);
                // select message templates using message ids
                $message_templates = $messageCommit->messagetemplate_m->getTemplateByIds(['idOne' => $message_temp[0]['id'],'idTwo' => $message_temp[1]['id']]);
                //var_dump($message_templates);
                // temporary comment

                $processing = SELF::updateByPk($request->id,[
                    'status'=>WIPTransaction::PROCESSING_STATUS,
                    'updated_at'=> date('Y-m-d H:i:s')
                ]);

            
                if(!$processing){
                    SELF::endDbTransaction($processing);
                    log_message('error','WipBulkTransfer failed to update the status of processing');
                    return false;
                }
                SELF::endDbTransaction(true);
                
                while($loops >= 1){

                    $defaultPass = $untils->autoGeneratorPwd(8);
                    $defaultPassHash = password_hash($defaultPass, PASSWORD_DEFAULT);
                   // echo "While processing is ".$loops.PHP_EOL;

                    echo "Processing batch...".PHP_EOL;
                    $transactions = WIPTransaction::find([
                                        'request_id'=>$request->request_id,
                                        'status'=>WIPTransaction::PENDING_STATUS
                                    ])
                                    ->limit(SELF::BULK_PROCESS_LIMIT)
                                    ->asArray()
                                    ->all();
                    echo "Processing...".PHP_EOL;
                    SELF::startDbTransaction();
                    $count = 0;
                    $total_amount = 0;$total_commission = 0;
                    foreach($transactions as $transaction){
                    //for ($x = 0; $x < count($batch); $x++) {    
                        //$process = Transaction::debit()
                        echo "Processing...count ".$count.PHP_EOL;
                        $ver = "Processing...count ".$count.PHP_EOL;
                        $inTransaction = false;
                        try{
                            log_message('info',$ver.' Start To Process User Record===================____'.$transaction['recipient_phone']);
                            echo "Getting user id...".PHP_EOL;
                            echo microtime().PHP_EOL;
                            $user = Untils::bulk_transfer_auto_create_user($transaction['recipient_phone'],$defaultPassHash);
                            if($user['process']){
                                echo microtime().PHP_EOL;
                                $inTransaction = true;
                                echo "Crediting user...".PHP_EOL;
                                echo microtime().PHP_EOL;
                                // Isavings espi deposit process
                                
                                //$isavings = 0;$commission = 0;
                                $_reference =Transaction::getUniqueReference($user['id'],$transaction['qty'],$transaction['client_id']);
                                // check if the merchant charges annual fee is true
                                $amountpaid = 0; $commission = 0;
                                $userAnnualCharges = SELF::fetchUserChargesByName($merchantCharges,ANNUAL_CHARGES_KEY);
                                // var_dump($userChargesByName);
                                if(!empty($userAnnualCharges) && $userAnnualCharges->status){
                                    
                                    $annualResult = UserSubscription::anuualsub($user['id'],$_reference,$transaction['qty'],$user['status'],$userAnnualCharges->value);
                                    $amountpaid = round($annualResult['amountpaid'], 2, PHP_ROUND_HALF_DOWN);
                                    $process = Transaction::credit(
                                        $system_user->id,// get system user_id
                                        $amountpaid,
                                        $annual_sub_wallet->id,// get commission wallet
                                        $_reference,
                                        'Annual fee charges',
                                        $user['id'],
                                        false
                                    );
                                    $transaction['qty'] = $annualResult['userbalance'];
                                    //var_dump($annualResult);
                                    echo 'Annual charges taking place============'.$amountpaid.PHP_EOL;
                                }
                                
                                // check if the merchant charges commission is true

                                if(!empty($wallet_charge) && $userChargesByName->status){
                                    
                                    $isavings = Transaction::charges_proccess($transaction['qty'],$userChargesByName->value);
                                    $userBalance = round($isavings['userBalance'], 2, PHP_ROUND_HALF_DOWN);
                                    
                                    $commission = round($isavings['adminPercent'], 2, PHP_ROUND_HALF_DOWN);
                                    $process = Transaction::credit(
                                        $system_user->id,// get system user_id
                                        $commission,
                                        $system_commission_wallet->id,// get commission wallet
                                        $_reference,
                                        'Commission charges',
                                        $user['id'],
                                        false
                                    );
                                    $transaction['qty'] = $userBalance;
                                    echo 'Wallet commission taking place============'.$userBalance.PHP_EOL;
                                }
                                echo 'Amount credited========================'.$transaction['qty'].PHP_EOL;
                                $process = Transaction::credit(
                                    $user['id'],
                                    $transaction['qty'],
                                    $transaction['wallet_id'],
                                    $_reference,
                                    $desc,
                                    $transaction['client_id'],
                                    false
                                );
                                $template =null;
                                
                                
                                if($user['status'] == WIPTransaction::MESSAGE_OLD){
                                    $template = SELF::chooseTemplate($message_templates,$message_temp[0]['id']);
                                    var_dump($template);
                                    echo 'Old Template====================='.PHP_EOL;
                                }else{
                                    $template = SELF::chooseTemplate($message_templates,$message_temp[1]['id']);
                                    echo 'New Template====================='.PHP_EOL;
                                }
                                //var_dump($template);
                                   
                                // $template = ($user['status'] == WIPTransaction::MESSAGE_OLD)? $message_template_old : $message_template_new;
                                (empty($template))?false:($giftConfig->send_message)?SELF::messageProcess($giftConfig,$user['id'],$process,$transaction['id'],$user['status'],$transaction['recipient_phone'],$transaction['qty'],$amountpaid, $commission,$template,$defaultPass):false;
                                
                                unset($template);
                                $total_amount += $transaction['qty'];
                                $total_commission += $commission;

                                $espi_bulk_batches[] = array(
                                    'recipient'=>array(
                                    'phone'=>$transaction['recipient_phone'],
                                    'walletType'=> strtolower($wallet->name)
                                    ),
                                    'amount'=>round($transaction['qty'], 2, PHP_ROUND_HALF_DOWN),
                                    'ref'=>$_reference
                                );
                                $batch_wip[] = array(
                                    'id'=>$transaction['id'],
                                    'status'=>($process)?WIPTransaction::COMPLETED_STATUS : WIPTransaction::PENDING_STATUS,
                                    'message'=> ($user['status'] == WIPTransaction::MESSAGE_OLD)?  WIPTransaction::MESSAGE_OLD : WIPTransaction::MESSAGE_NEW,
                                    'is_annual_fee'=> round($amountpaid, 2, PHP_ROUND_HALF_DOWN),
                                    'is_annual_active'=> ($amountpaid> 0)?  1 :0,
                                    'credited'=> $transaction['qty'],
                                    'commission'=> round($commission, 2, PHP_ROUND_HALF_DOWN),
                                    'recipient_id'=> $user['id'],
                                    'unq_reference'=> $_reference,
                                    'updated'=> date('Y-m-d H:i:s')
                                );
                                //var_dump($batch_wip);
                                //SELF::endDbTransaction(true);
                            
                                //exit();
                                // break;
                            }else{
                                 // invalid
                                $process = WIPTransaction::updateByPk($transaction['id'],[
                                    'status'=>WIPTransaction::INVALID_STATUS,
                                        //'message'=>((time()-(60*60*24)) < strtotime(date('Y-m-d H:i:s'))) ? WIPTransaction::MESSAGE_NEW : WIPTransaction::MESSAGE_OLD, 
                                        'is_annual_active'=> 0,
                                        'message'=> WIPTransaction::MESSAGE_NEW, 
                                        'updated'=> date('Y-m-d H:i:s')
                                ]);
                            }
                            
                        } catch(Throwable $ex){
                            log_message('info',"Error processing this request...".$ex->getMessage());
                            if($inTransaction){
                                SELF::endDbTransaction($commit = false);
                            }
                        }
                        $count++;
                    }
                    $loops--;

                    if($total_amount != 0){
                        SELF::endDbTransaction(true);
                        echo 'Total Amount deposit on ESPI ======='.$total_amount.'=======Amount of Commission===='.$total_commission.'=== Loop Level'.$loops;
                        log_message('info','Total Amount deposit on ESPI ======='.$total_amount.'=======Amount of Commission===='.$total_commission.'=== Loop Level'.$loops);
                        $isEspiPro = SELF::depositTransaction($request->wallet_id,$request->user_id,$request->txn_reference,$total_amount, $total_commission,count($batch_wip));
                        if(!$isEspiPro){
                            log_message('error','The Espi Deposit endpoint failed to deposit =====>'.$request->request_id);
                            $transactionResult = WIPTransaction::batchUpdate(SELF::changeStatus($batch_wip, WIPTransaction::FAILED_STATUS));//WIPTransaction::FAILED_STATUS
                            log_message('info', $transactionResult?"Succeeded change status to processing on ESPI===....":"Failed change status to processing on ESPI===...".PHP_EOL);
                        }else{
                            log_message('info','Total Amount Bulk Transfer on ESPI ======='.$total_amount.'=======Amount of Commission===='.$total_commission.'=== Loop Leavel'.$loops);
                            $description = "Bulk iPoints Transfer With Ref ".$request->txn_reference;
                            $depositResponse = EspiTransaction::bulkTransferOnEspiWallet($request->user_id,$total_amount,$request->txn_reference,$total_commission,$espi_bulk_batches,$description);
                            if($depositResponse){
                                log_message('info',"bulk Transfer On Espi Wallet request...=====...".$depositResponse);
                                $transactionResult = WIPTransaction::batchUpdate($batch_wip);
                                log_message('info',$transactionResult?"Succeeded Process isavings on ESPI===....":"Failed To Process isavings on ESPI===...".PHP_EOL);
                            }else{
                                log_message('info',"bulk Transfer On Espi Wallet request...=====...".$depositResponse);
                                $transactionResult = WIPTransaction::batchUpdate(SELF::changeStatus($batch_wip, WIPTransaction::PROCESSING_STATUS));
                                log_message('info', $transactionResult?"Succeeded change status to processing on ESPI===....":"Failed change status to processing on ESPI===...".PHP_EOL);
                            }
                           
                        }
                        
                        unset($espi_bulk_batches);unset($total_amount);unset($total_commission);unset($batch_wip);
                    }
                }
                echo "Done...";
                $process = SELF::updateByPk($request->id,[
                    'status'=>WIPTransaction::COMPLETED_STATUS,
                    'updated_at'=> date('Y-m-d H:i:s')
                ]);
                
                log_message('info',"Bulk Transfer successfully completed With Request Id++++++:: ".$request->request_id);
                echo $process?"Completed...":"".PHP_EOL;
                return true;
    }




    public static function backgroundBulkProcess(){
        // $request = SELF::findOne(['status'=>WIPTransaction::PROCESSING_STATUS]);
        // if(!empty($request)){
        //     echo $request->request_id.' Currently on processing====>';
        //     log_message('info',$request->request_id.' Currently on processing====>');
        //     exit();
        // }
        $request = SELF::findOne(['status'=>WIPTransaction::PENDING_STATUS]);
        if(!empty($request)){
            echo 'Currently processing request id:: '.$request->request_id;
            $process = SELF::process($request, $verbose = FALSE);
            echo $process?"Transaction was successfully complete...":"Transaction was failed to complete...".PHP_EOL;
            log_message('info',$process?"Transaction was successfully complete...".$request->request_id:"Transaction was failed to complete...".$request->request_id);
        }else{
            echo "You have no current bulk transfer to be process";
            log_message('info',"You have no current bulk transfer to be process");
        } 
       
    }

    public static function messageProcess($giftConfig,$user_id,$process,$transactionId,$status,$recipient_phone,$userbalance,$amountpaid, $commission,$template,$defaultPass){

        //batch message
        //echo microtime().PHP_EOL;
        // if($process && !$isBatch){
        //     log_message('info',"Credit on recipient_phone:: ".$recipient_phone." was successful_________");
        //     echo microtime().PHP_EOL;
        //     $process = WIPTransaction::updateByPk($transactionId,[
        //         'status'=>WIPTransaction::COMPLETED_STATUS,
        //         'message'=>($status == WIPTransaction::MESSAGE_OLD) ?  WIPTransaction::MESSAGE_OLD : WIPTransaction::MESSAGE_NEW, 
        //         'is_annual_fee'=> $amountpaid,
        //         'is_annual_active'=> 0,//($annualResult['amountpaid'] > 0)?  1 :0,
        //         'credited'=> $userbalance,
        //         'commission'=> $commission,
        //         'recipient_id'=> $user_id,
        //         'updated'=> date('Y-m-d H:i:s')
        //     ]);
        //     echo microtime().PHP_EOL;
        //     log_message('info','Completed Transaction ========____');
        // }
        $phone_number = $recipient_phone;
        $message_type = MESSAGE_SMS;
        if($userbalance >=50){
            echo "About To Process Message++++++".$userbalance;
            if($status == WIPTransaction::MESSAGE_OLD && ($giftConfig->message_designate == WIPTransaction::MESSAGE_OLD || $giftConfig->message_designate == 'all')){
                echo " Process Old User Message++++++".$userbalance;
                $message_variable = array($userbalance);
                $message_action = $template->action;// OLD_WIP_TRANSACTION;
                MessageQueue::messageCommitWithExternalTemplate($template, $phone_number,$message_type, $message_action, MessageQueue::arrayToStringWithComma($message_variable));
                echo "Old Message Process Message++++++".$phone_number;
            }else if($status == WIPTransaction::MESSAGE_NEW && ($giftConfig->message_designate == WIPTransaction::MESSAGE_NEW || $giftConfig->message_designate == 'all')){
                echo "Process New User Message++++++".$userbalance;
                $message_variable = array($userbalance,$defaultPass);
                $message_action = $template->action;//NEW_WIP_TRANSACTION;
                MessageQueue::messageCommitWithExternalTemplate($template, $phone_number,$message_type, $message_action, MessageQueue::arrayToStringWithComma($message_variable));
                echo "New Message Process Message++++++".$phone_number;
            }
        }
    }

    public static function changeStatus($bulks, $status){
        foreach($bulks as $bulk){
            $bulk['status'] = $status;
            $data[] = $bulk;
        }
        return $data;
    }


    // public static function bulkCredit($bulkCreditWallets,$desc,$defaultPass,$old_template,$new_template){
    //     SELF::startDbTransaction();
    //     try{
    //         foreach($bulkCreditWallets as $bulkCreditWallet){
    //             $process = Transaction::creditOnEachBulkGifting(
    //                 $bulkCreditWallet['recipient_id'],
    //                 $bulkCreditWallet['credited'],
    //                 $bulkCreditWallet['wallet_id'],
    //                 $bulkCreditWallet['unq_reference'],
    //                 $desc,
    //                 $bulkCreditWallet['client_id'],
    //                 false
    //             );
    //             log_message('info',"Credit user's wallet ======...".$bulkCreditWallet['recipient_id']);
    //             $template = ($bulkCreditWallet['message'] == WIPTransaction::MESSAGE_OLD)? $old_template : $new_template;
    //             SELF::messageProcess(true,$bulkCreditWallet['recipient_id'],$process,$bulkCreditWallet['id'], $bulkCreditWallet['message'],$bulkCreditWallet['recipient_phone'], $bulkCreditWallet['credited'], $template,$defaultPass);
    //             unset($template);
    //         }
    //     }catch(Exception $ex){
    //         log_message('error',"Error processing this request...".$ex->getMessage());         
    //         SELF::endDbTransaction($commit = false);
    //         return false;             
    //     }

    //     SELF::endDbTransaction(true); 
    //     return true;
    // }


    public static function fetchBulkTransfer(PDO $db, $data, $isExport=false){ 
        $where = [];

        if(!empty($data['request_id'])){
            $where[] =" wb.request_id = '".$data['request_id']."'";
        }if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  `wb`.`created_at`  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['user_id'])){
            $where[] =' wb.user_id = '.(int)$data['user_id'];
        }
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        $countWhere = $where;
        $where = $where .= (!$isExport)?" GROUP BY wb.request_id LIMIT ".$data['limit']." OFFSET ".$data['offset'] :' GROUP BY wb.request_id ';
        
        $fromAndJoin = " FROM `wip_bulk_transfer_requests` wb
            LEFT JOIN  `wip_transaction` AS wt ON wt.request_id = wb.request_id 
            LEFT JOIN `users` AS u ON u.id = wb.user_id
             ";
        try{


            $query = " SELECT 
            `wb`.request_id,
            CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `name`,
            `wb`.total_transaction_value,
            `wb`.recipients_count, wb.status,
            SUM(wt.status = 'completed') AS completed,
            SUM(wt.status = 'pending') AS pending, 
            SUM(wt.status = 'invalid') AS invalid,
            SUM(wt.status = 'cancel') AS cancel,
            `wb`.created_at,
            `wb`.updated_at,
            `wb`.txn_reference
             {$fromAndJoin} {$where} ";

            if($isExport) {
               
                return $query;
            }

            $countQuery = " SELECT COUNT(DISTINCT wb.request_id) AS allCount  FROM `wip_bulk_transfer_requests` wb {$where}";
            if(true){
                   // var_dump($countQuery);
                $stmt= $db->query($countQuery);
                SELF::$result_count = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $ex){
            throw $ex;
        }
    }

    public static function fetchBulkTransferByStatus(PDO $db, $data, $isExport=false){ 
        $where = [];

        if(!empty($data['request_id'])){
            $where[] =" wt.request_id = '".$data['request_id']."'";
        }if(!empty($data['client_id'])){
            $where[] =' wt.client_id = '.(int)$data['client_id'];
        }if(!empty($data['status'])){
            $where[] =" wt.status = '".$data['status']."'";
        }
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        try{
            $query = " SELECT wt.*
            FROM `wip_transaction` wt ".$where;

            if($isExport) {
               
                return $query;
            }

            $countQuery = " SELECT COUNT(DISTINCT wb.request_id) AS allCount  FROM `wip_bulk_transfer_requests` wb {$where}";
            if(true){
                   // var_dump($countQuery);
                $stmt= $db->query($countQuery);
                SELF::$result_count = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $ex){
            throw $ex;
        }
    }


    public static function cancelIvalidNumberAndRefundWallet($mercahnt_id, $request_id, $email){
        //get all invalid records by merchant id
         $invalidRecord = SELF::getInvalidRecordWitUserId($mercahnt_id, $request_id);
        if(is_null($invalidRecord)){
            throw new Exception("No invalid bulk transfer found ");
        }
        
        $main_wallet = Wallet::getMain();
        $system_user = User::getSystemUser();
        SELF::startDbTransaction();
        $reference = Transaction::getUniqueReference('BulkTransferInvalid',$mercahnt_id, $request_id);
        $creditResult = Transaction::credit(
            $invalidRecord->client_id, 
            $invalidRecord->sumup_value,  
            $main_wallet->id, 
            $reference, 
            'Invalid Bulk Transfer Cancellation With Request Id:: '.$request_id,
            $system_user->id, 
            false);

        $data['status'] = 'cancel';
        $data['updated'] = date('Y-m-d H:i:s');

        $cancelResult = WIPTransaction::updateInvalidToCancel(['request_id'=>$invalidRecord->request_id, 'status'=>'invalid','client_id'=>$invalidRecord->client_id],$data);

         $bulkTransferRequest = SELF::findOne(['request_id'=>$invalidRecord->request_id,'user_id'=>$invalidRecord->client_id]);
         $bulk['total_transaction_value'] = $bulkTransferRequest->total_transaction_value - $invalidRecord->sumup_value;
         $bulk['recipients_count'] = $bulkTransferRequest->recipients_count - $invalidRecord->invalid_count;
         $bulk['updated_at'] = date('Y-m-d H:i:s');
         $bulkUpdateResult =SELF::update(['request_id'=>$invalidRecord->request_id,'user_id'=>$invalidRecord->client_id],$bulk);
         $result = ($cancelResult && $creditResult && $bulkUpdateResult)? true : false;
        SELF::endDbTransaction($result);
        if($result){
            // admin notification
            // $info['report_type'] = PRODUCT_SUBSCRIPTION;
            // $info['frequency'] = MONTHLY;
            // $info['dispatcher_type'] = GROUP;
            // $reports = ReportSubscription::getReportSubscription($db,$info);
            //dump sql file in a path and return part url
            // $emails =  array_column($reports, 'email');
            // unset($reports);
            // $variable = array('','');// name, email , number of records, sum-up qty, request id
            // MessageQueue::messageCommit($emails, MESSAGE_EMAIL,REGISTER, $variable);
        }
        return $result;
        // sum up the ipoint value
        // credit the merchant wallet
        // set the status to cancel
        // remove the number of count out of the bulk transfer table
        // notify admin the process process by message
    }


    public static function depositTransaction($walletId,$senderId,$payment_ref,$amount,$commission,$recipient_count){
            $description = "Bulk iSavings Transfer Deposit";
           return EspiTransaction::depositOnBulkTransferProcess($senderId,'',$payment_ref,$amount,$commission,$recipient_count,'deposit',$description,TRUE);
    }
    
    public static function getInvalidRecordWitUserId($mercahnt_id, $request_id){
        $record = new static();
        $query = $record->db->from('wip_transaction wt')
        ->select("COUNT(*) as invalid_count, SUM(wt.qty) as sumup_value, wt.wallet_id, wt.client_id, wt.request_id")
        ->where('wt.client_id', $mercahnt_id)
        ->where('wt.request_id', $request_id)
        ->where('wt.status', 'invalid')
        ->get();
        return $query->row();
    }


    

    private function findMsgType($messageType){
        $query = $this->db->from('wip_transaction wt')
        ->select('wt.id ,wt.recipient_phone, wt.qty, wt.message, w.name,u.name as client_name')
        ->where('wt.message', $messageType)
        ->where('wt.status', WIPTransaction::COMPLETED_STATUS)
        ->limit(SELF::BULK_PROCESS_LIMIT)
        ->join('wallets as w', 'w.id = wt.wallet_id', 'left')
        ->join('users as u', 'u.id = wt.client_id', 'left')->get();
        return $query->result_object();
    }


}