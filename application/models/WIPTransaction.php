<?php

class WIPTransaction extends MY_Model {   

    const PROCESSING_STATUS = 'processing';
    const PENDING_STATUS = 'pending';
    const COMPLETED_STATUS = 'completed';
    const INVALID_STATUS = 'invalid';
    const FAILED_STATUS = 'failed';
    const MESSAGE_NEW = 'new';
    const MESSAGE_OLD = 'old';
    const MESSAGE_DONE = 'done';

    public function __construct()
	{
	  parent::__construct();
      $this->load->library('Untils');
      $this->load->model('Setting_m');
      $this->load->model('Wallet');
      $this->load->model('Uici_levies');
      $this->load->model('WIPBulkTransferRequest');
      $this->load->library("pdolib");
	}

    public static function getTableName(){
        return 'wip_transaction';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    } 

    public function beforeSave(){
        if($this->isNew){
            $this->created??date('Y-m-d H:i:s');
            $this->updated = $this->created;
            if(empty($this->status)){
                $this->status = SELF::PENDING_STATUS;
            }
        } else {
            $this->updated = date('Y-m-d H:i:s');
        }
    }

    public static function loadBulk($data){
        return SELF::insert($data,true);
    }

    public static function updateInvalidToCancel(Array $query = [], $data){
       return SELF::update($query,$data);
    }

    public static function batchUpdate($data){
        $wip = new static();
        var_dump($data);
        $re = $wip->db->update_batch('wip_transaction',$data, 'id');
        echo 'Result Batch Update:=== '.$re;
        return $re;
    }

    // public static function retryFailedBulkDepositTransaction(){
    //     //The system should get the list of
    //     $wIPTransaction = SELF::findOne(['status'=>SELF::FAILED_STATUS]);
    //     if(!empty($wIPTransaction)){
    //         // update wip_bulk_transfer status to processing with request id
    //         // $processing = WIPBulkTransferRequest::updateByPk($request->id,[
    //         //     'status'=>SELF::PROCESSING_STATUS,
    //         //     'updated_at'=> date('Y-m-d H:i:s')
    //         // ]);

    //         // if(!$processing){
    //         //     //SELF::endDbTransaction($processing);
    //         //     log_message('error','WipBulkTransfer failed to update the status of processing');
    //         //     return false;
    //         // }
    //         log_message('info',"Failed Transaction Request_id=====".$wIPTransaction->request_id);
    //         $totalFailed = SELF::count([
    //             'request_id'=>$wIPTransaction->request_id,
    //             'status'=>SELF::FAILED_STATUS
    //         ]);

    //         $wallet_isavings = Wallet::walletById($wIPTransaction->wallet_id);
    //         $request = WIPBulkTransferRequest::findOne(['request_id'=>$wIPTransaction->request_id]);
           
    //         log_message('info',"Number of Failed Transaction Need To Be Process ===== ".$totalFailed);
    //         $loops =0;
    //         $loops = ceil($totalFailed/WIPBulkTransferRequest::BULK_PROCESS_LIMIT);

    //         while($loops >= 1){

    //             echo "While processing is ".$loops.PHP_EOL;

    //             $transactions = SELF::find([
    //                                 'request_id'=>$wIPTransaction->request_id,
    //                                 'status'=>SELF::FAILED_STATUS
    //                             ])
    //                             ->limit(WIPBulkTransferRequest::BULK_PROCESS_LIMIT)
    //                             ->asArray()
    //                             ->all();
               
    //             $count = 0;
    //             $total_amount = 0;$total_commission = 0;
    //             foreach($transactions as $transaction){
    //                 //$process = Transaction::debit()  
    //                 $inTransaction = false;
    //                 try{
    //                     $total_amount += $transaction['credited'];
    //                     $total_commission += $transaction['commission'];
    //                     $iSavings_batches[] = array(
    //                         'recipient'=>array(
    //                         'phone'=>$transaction['recipient_phone'],
    //                         'walletType'=> strtolower($wallet_isavings->name)
    //                         ),
    //                         'amount'=> $transaction['credited'],
    //                         'ref'=> $transaction['unq_reference']
    //                     );
    //                     $batch_wip[] = array(
    //                         'id'=>$transaction['id'],
    //                         'status'=>SELF::COMPLETED_STATUS,
    //                         'updated'=> date('Y-m-d H:i:s')
    //                             );
    //                 log_message('info','Start To Process User Record===================____'.$transaction['recipient_phone']);
    //                 log_message('info','Balance before append to total ===='.$transaction['credited']);
    //                 } catch(Throwable $ex){
    //                     log_message('error',"Error processing this request...".$ex->getMessage());
    //                     if($inTransaction){
    //                         SELF::endDbTransaction(false);
    //                     }
    //                 }
    //                 $count++;
    //             }
    //             $loops--;

    //                 $isEspiPro = WIPBulkTransferRequest::depositTransaction($wIPTransaction->wallet_id,$request->user_id,$request->txn_reference,$total_amount,$total_commission,count($batch_wip));
    //                 if(!$isEspiPro){
    //                     log_message('error','The Espi Deposit endpoint failed to deposit =====>'.$request->request_id);
    //                     $transactionResult = SELF::batchUpdate(WIPBulkTransferRequest::changeStatus($batch_wip, SELF::FAILED_STATUS));//WIPTransaction::FAILED_STATUS
    //                     log_message('info', $transactionResult?"Succeeded change status to processing on ESPI===....":"Failed change status to processing on ESPI===...".PHP_EOL);
    //                 }else{
    //                     $description = "Bulk iPoints Transfer With Ref ".$request->txn_reference;
    //                     $depositResponse = EspiTransaction::bulkTransferOnEspiWallet($request->user_id,$total_amount,$request->txn_reference,$total_commission,$iSavings_batches,$description);
    //                     if($depositResponse){
    //                         log_message('info',"bulk Transfer On Espi Wallet request...=====...".$depositResponse);
    //                         $transactionResult = SELF::batchUpdate($batch_wip);
    //                         log_message('info', $transactionResult?"Succeeded Process isavings on ESPI===....":"Failed To Process isavings on ESPI===...".PHP_EOL);
    //                     }else{
    //                         log_message('info',"bulk Transfer On Espi Wallet request...=====...".$depositResponse);
    //                         $transactionResult = SELF::batchUpdate(WIPBulkTransferRequest::changeStatus($batch_wip, SELF::PROCESSING_STATUS));
    //                         log_message('info', $transactionResult?"Succeeded update the status to processing on ESPI===....":"Failed to update status to processing on ESPI===...".PHP_EOL);
    //                     }
                       
    //                 }
    //                 unset($iSavings_batches);unset($total_amount);unset($total_commission);unset($batch_wip);
    //         }

    //         echo "Completed failed transaction..=====...";
    //         log_message('info',"Completed failed transaction..=====...");
    //         //check if wip bulk transfer status is failed or processing
    //         // if true update to compiled
    //         // check if the there is pending or processing using request_id if there is none and the parent status is on processing change it completed
    //        // $request = SELF::findOne(['request_id'=>$wIPTransaction->request_id])->while(`status= {SELF::PROCESSING_STATUS} OR status= {SELF::PROCESSING_STATUS}`);
    //     }else{
    //         log_message('info',"No transaction to be process..=====...");
    //        // $request = WIPBulkTransferRequest::findOne(['request_id'=>$wIPTransaction->request_id])->while('status={}');
    //     }
        
    // }


    public static function retryBulkTransferOnProcessTransaction(){
        //The system should get the list of
        $isOnProcess = WIPBulkTransferRequest::findOne(['status'=>SELF::PROCESSING_STATUS]);
        if(!empty($isOnProcess)){
            log_message('error','WIPBulkTransferRequest is currently on proccess');
            return false;
        }
        $wIPTransaction = SELF::findOne(['status'=>SELF::PROCESSING_STATUS]);
        if(!empty($wIPTransaction)){
            $processing = WIPBulkTransferRequest::update(["request_id" => $wIPTransaction->request_id],[
                'status'=>SELF::PROCESSING_STATUS,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);
            if(!$processing){
                log_message('error','WipBulkTransfer failed to update the status of processing');
                return false;
            }
            
            log_message('info',"Failed Transaction Request_id=====".$wIPTransaction->request_id);
            $totalFailed = SELF::count([
                'request_id'=>$wIPTransaction->request_id,
                'status'=>SELF::PROCESSING_STATUS
            ]);

            $wallet_isavings = Wallet::walletById($wIPTransaction->wallet_id);
            $request = WIPBulkTransferRequest::findOne(['request_id'=>$wIPTransaction->request_id]);
            //var_dump('<pre>',print_r($request,true));
            $loops =0;
            $loops = ceil($totalFailed/WIPBulkTransferRequest::BULK_PROCESS_LIMIT);

            log_message('info',"Number of Failed Transaction Need To Be Process ===== ".$totalFailed);
            while($loops >= 1){

                echo "While processing is ".$loops.PHP_EOL;

                $transactions = SELF::find([
                                    'request_id'=>$wIPTransaction->request_id,
                                    'status'=>SELF::PROCESSING_STATUS
                                ])
                                ->limit(WIPBulkTransferRequest::BULK_PROCESS_LIMIT)
                                ->asArray()
                                ->all();
               
                $count = 0;
                $total_amount = 0;$total_commission = 0;
                foreach($transactions as $transaction){
                    //$process = Transaction::debit()
                    
                    $inTransaction = false;
                    try{
                        
                        $total_amount += $transaction['credited'];
                        $total_commission += $transaction['commission'];
                        $iSavings_batches[] = array(
                            'recipient'=>array(
                            'phone'=>$transaction['recipient_phone'],
                            'walletType'=> strtolower($wallet_isavings->name)
                            ),
                            'amount'=> $transaction['credited'],//unq_reference
                            'ref'=>$transaction['unq_reference']
                        );
                        $batch_wip[] = array(
                            'id'=>$transaction['id'],
                            'status'=>WIPTransaction::COMPLETED_STATUS,
                            'updated'=> date('Y-m-d H:i:s')
                                );
                        log_message('info','Start To Process User Record===================____'.$transaction['recipient_phone']);
                        log_message('info','Balance before append to total ===='.$transaction['credited']);
                        
                    } catch(Throwable $ex){
                        log_message('error',"Error processing this request...".$ex->getMessage());
                        if($inTransaction){
                            SELF::endDbTransaction($commit = false);
                        }
                    }
                    $count++;
                }
                $loops--;

                $description = "Bulk iPoints Transfer With Ref ".$request->txn_reference;
                $creditResponse = EspiTransaction::bulkTransferOnEspiWallet($request->user_id,$total_amount,$request->txn_reference,$total_commission,$iSavings_batches,$description);
                if($creditResponse){
                    log_message('info',"bulk Transfer On Espi Wallet request...=====...".$creditResponse);
                    $transactionResult = SELF::batchUpdate($batch_wip);
                    log_message('info', $transactionResult?"Succeeded Process isavings on ESPI===....":"Failed To Process isavings on ESPI===...".PHP_EOL);
                }else{
                    log_message('info',"bulk Transfer On Espi Wallet request...=====...".$creditResponse);
                    $transactionResult = SELF::batchUpdate(WIPBulkTransferRequest::changeStatus($batch_wip, SELF::PROCESSING_STATUS));
                    log_message('info', $transactionResult?"Succeeded update the status to processing on ESPI===....":"Failed to update status to processing on ESPI===...".PHP_EOL);
                }
                unset($iSavings_batches);unset($total_amount);unset($total_commission);unset($batch_wip);
            }
            echo "Completed processing transaction..=====...";
            log_message('info',"Completed processing transaction..=====...");
            //check if wip bulk transfer status is failed or processing
            // if true update to completed

            $processing = WIPBulkTransferRequest::update(["request_id" => $wIPTransaction->request_id],[
                'status'=>SELF::COMPLETED_STATUS,
                'updated_at'=> date('Y-m-d H:i:s')
            ]);

            return (!$processing)? false : true;

        }else{
            log_message('info',"No transaction to be process..=====...");
        }
        
    }

}