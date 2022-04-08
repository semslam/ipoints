<?php 
//ini_set("max_execution_time", 0);
class Utilities extends CI_Controller
{
    const MESSAGE_PROCESS_LIMIT = 100;
    const PRODUCT_SERVICE_PROCESS_LIMIT = 500;
    
    // getting bulk transfer file via cli and process it
    public function loadBulkTransfer($file){
        $this->load->model('WIPBulkTransferRequest');
        $data = json_decode(file_get_contents($file),true);
        $success = WIPBulkTransferRequest::load($data,$verbose=true);
        var_dump($success);
    }

    // process bulk iPoint transfer in WIP_transfer table
    public function process($request,$verbose=true){
        $this->load->model('WIPBulkTransferRequest');
        $success = WIPBulkTransferRequest::process($request,$verbose);
        echo ($success);
    }

    public function processQueueMessages($type = MESSAGE_EMAIL,$priority = 9){
        MessageQueue::processQueueMessage(MESSAGE_EMAIL,$priority);
        //MessageQueue::processQueueMessage($type,$priority);
        echo "cron number " . $priority . " is working...\n";
        log_message('INFO', "cron number " . $priority . " is working...\n");
    }

    public function dualProcessQueueMessages($priority = 9){
         MessageQueue::processQueueMessage(MESSAGE_SMS,$priority);
         MessageQueue::processQueueMessage(MESSAGE_EMAIL,$priority);
         echo "cron number " . $priority . " is working...\n";
    }

    public function ipinGeneratingProcess($batch_quantities, $ipin_value, $ipoints,$wallet, $merchant_id){
        $this->load->model('UserBalance');
        $this->load->model('Wallet');
        $this->load->model('IpinGeneration');
        $merchant_wallet = Wallet::walletByName(I_POINT);
        $user_balance = $this->UserBalance->getUserBalanceByWalletAndUserId(['id'=>$merchant_id,'wallet_id'=>$merchant_wallet->id]);
        $ipinResult = IpinGeneration::generateIpin($batch_quantities,$ipin_value,$ipoints,$wallet,$user_balance);
        log_message('INFO',"ipin generating result...".($ipinResult)?'Transaction complete Successfully' : 'Failed to complete the transaction');
    }
// this process 
    public function productSubscriptionProcess($product_id, $periodStart, $periodEnd, $subscribers, $billing_price,$percentage){
        $subsriptionResult = ServicesLog_m::processProductSubscription($product_id, $periodStart, $periodEnd, $subscribers, $billing_price,$percentage);
        log_message('INFO',"product subscription result...".($subsriptionResult)?'Transaction complete Successfully' : 'Failed to complete the transaction');
    }

    // process pending new message in a wIP_transfer table, run via cron job
    public function transactionMsg($msg){
        $this->load->model('WIPBulkTransferRequest');
       $this->WIPBulkTransferRequest->msgProcess($msg);
    }

    // bulk-transfer, this will process the pending ipoint that are met to gift to subscribers
    // The function runs ever 3 hours in a days
    public function bulkTransferProcess(){
         $this->load->model('WIPBulkTransferRequest');
         WIPBulkTransferRequest::backgroundBulkProcess();
        // $this->load->model('WIPBulkGiftingProcess');
        // WIPBulkGiftingProcess::backgroundBulkProcess();
    }

    public function creditAnnualSubscriptionWalletInWip(){
        $this->load->model('UserSubscription');
        UserSubscription::creditAnnualWallet();
   }

    // public function retryOnDeposit(){
    //     $this->load->model('WIPTransaction');
    //     WIPTransaction::retryFailedBulkDepositTransaction();
    // }

    public function retryOnBulkTransfer(){
        $this->load->model('WIPTransaction');
        WIPTransaction::retryBulkTransferOnProcessTransaction();
    }

    
    // charging subscriber on System annual fee, running via cron job
    // this process runs once in a week at 3:00 AM
    public function massSubscribe(){
        // $this->load->model('Uici_levies');
        // $this->load->library('utilities/Subscription');
        // $benchmark = $this->Uici_levies->getUiciLevieValue(ANNUAL_CHARGES_KEY)->value;
        // Subscription::massSubscribe((int)$benchmark);
        // exit;
    }
    //This process dis-activate expired product
    //this cron job will run every 12: AM
    public function isProductActive(){
        $current_date = time();
        //get all active subscription
        $this->load->model('ServicesLog');
        $this->load->library("pdolib");
        $db = $this->pdolib->getPDO();
        $activeProducts = ServicesLog::getAllActiveProduct($db);
        //enhance the workflow process
        $loops = ceil(count($activeProducts)/SELF::PRODUCT_SERVICE_PROCESS_LIMIT);
         if(!empty($activeProducts)){

            while($loops >= 1){
                try{        
                    foreach($activeProducts as $activeProduct){
                        //var_dump($activeProduct);
                        $id = $activeProduct['id'];
                        $data['is_active'] = 0;
                        if (strtotime($activeProduct['expiring_date']) < $current_date) {
                            ServicesLog::updateByPk($id, $data);
                            log_message('INFO',$id.' Product Expired its now is_active: '+0);
                        }
                    }
                } catch(Exception $ex){
                    log_message('error','Product service dis_activate process faild  >>>>>'.$ex->getMessage()); 
                } 
                $loops--;
            }   
             
         }else{
            log_message('INFO','No Expiring Product Service Found');
         }
        
        
    }
    //sending standout message to subscriber via background job
    public function subscriberSMSProcess($filter,$wallet,$point,$subscribers_count,$message){
        $this->load->model("UserBalance");
        $this->load->library("pdolib");
        $data['filter'] = urldecode($filter);
		$data['wallet'] = $wallet;
        $data['point'] = $point;
        log_message('INFO','START BACKGROUND JOB=============================');
        log_message('INFO',urldecode($filter));
        log_message('INFO',$wallet);
        log_message('INFO',$point);
        log_message('INFO',$subscribers_count);
        log_message('INFO',urldecode($message));
        log_message('INFO',print_r($data, TRUE));
        $db = $this->pdolib->getPDO();
        $subscribers = UserBalance::filterSubscriber($db,$data);
        $successful = 0;
        $failure = 0;
        $loops =0;
        $loops = ceil($subscribers_count/SELF::MESSAGE_PROCESS_LIMIT);
        log_message('INFO','Loop count: '.$loops);
        while($loops >= 1){
            try{
                foreach($subscribers as $subscriber){
                    log_message('INFO','Processing.........'.$subscriber['mobile_number']);
                    $info['mobile_number'] = $subscriber['mobile_number'];
                    $info['message'] = urldecode($message);
                    //$response = $this->untils->freeSms($info);
                    $response = true;
                    if($response){
                        $successful++;
                    }else{
                        $failure++;
                    }
                    
                }
            } catch(Exception $ex){
                log_message('INFO','Message faild'); 
            }
            $loops--;
        }
        
        log_message('INFO','Users '.count($subscribers).' Messages process successful:: '. $successful.' failure:: '.$failure);
    }
    //Processing vendor withdrawer by moving the vendor balance to system wallet called withdrawer
    public function vendorWithdrawerProcess(){
        $this->load->library("pdolib");
            //get vandor wallet
          $vendor_wallet = Wallet::walletByName(I_INCOME);

          //1, debit base on giving percent commission in the wallet
          $system_user = User::systemAccount();
          echo 'System User======='.print_r($system_user);
          $withdrawer_wallet = Wallet::walletByName(I_WITHDRAWER);
          echo'Withdrawer wallet======='.print_r($withdrawer_wallet);

        $db = $this->pdolib->getPDO();
        $vendorConfigs = WithdrawRequest::isSettleAutomatically($db,$vendor_wallet->id);
        echo'Vendor Info======='.print_r($vendorConfigs);
        $loops = ceil(count($vendorConfigs)/SELF::PRODUCT_SERVICE_PROCESS_LIMIT);
         if(!empty($vendorConfigs)){

            while($loops >= 1){
                try{        
                    foreach($vendorConfigs as $vendorConfig){
                        if($vendorConfig['balance'] >= 1000){
                            SELF::startDbTransaction();
                            $reference = Transaction::getUniqueReference($vendorConfig['vendor_id'],$vendorConfig['wallet_id'],$system_user->id,$withdrawer_wallet->id);
                            $withdraw = Transaction::withdrawerRequest($vendorConfig['vendor_id'], $vendorConfig['wallet_id'], $vendorConfig['balance'], $system_user->id, $withdrawer_wallet->id,$reference, TRUE);
                            $commit = ($withdraw)? true : false;
                            SELF::endDbTransaction($commit);
                            echo ($withdraw)?'Vendor withdrawer transfer completely processed': 'Vendor withdrawer transfer failed';
                        }else{
                            echo'You don\'t have enough balance to carry out withdrawer request';
                        }
                        
                    }
                } catch(Exception $ex){
                    echo'Vendor withdrawer process faild'; 
                } 
                $loops--;
            }
            
    }
    }
}

