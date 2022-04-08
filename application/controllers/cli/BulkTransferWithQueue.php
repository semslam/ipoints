<?php
// use PhpAmqpLib\Connection\AMQPStreamConnection;
// use PhpAmqpLib\Message\AMQPMessage;

// class BulkTransferWithQueue extends CI_Controller{
//     private $connection;

//     function __construct()
//     {
//         parent::__construct();
//         $this->connection = new AMQPStreamConnection('139.59.197.222', 5672, 'guest', '!!universal_queue!!');
        
//     }

//     public function waitOnQueue(){
//         $channel = $this->connection->channel(); 
//         $channel->queue_declare('task_queue', false, true, false, false);
//         echo "[*] Slave worker waiting for messages.\n";
//         log_message('info', "[*] Slave worker waiting for messages. \n");
//         $callback = function ($msg) {
            
//             log_message('info', "[*] Received message task_queue ");
//              $batch = json_decode($msg->body);
//             $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
//             $queryTranscationReference = WIPBulkTransferRequest::findOne([
//                 'request_id'=>$batch[0]->request_id
//             ]);
//                 $count = 0;
//                 $total_amount = 0;$total_commission = 0;
//                 $batchWalletId = $batch[0]->wallet_id;
//                 // WIPBulkTransferRequest::startDbTransaction();
//                 $messageCommit = new static();
//                 $message_template_new = $messageCommit->messagetemplate_m->get_template(['message_channel'=> MESSAGE_SMS,'action'=>NEW_WIP_TRANSACTION]);
//                 $message_template_old = $messageCommit->messagetemplate_m->get_template(['message_channel'=> MESSAGE_SMS,'action'=>OLD_WIP_TRANSACTION]);
//                 $message_template_old_isavings = $messageCommit->messagetemplate_m->get_template(['message_channel'=> MESSAGE_SMS,'action'=>OLD_WIP_TRANSACTION_ISAVING]);
//                 $message_template_new_isavings = $messageCommit->messagetemplate_m->get_template(['message_channel'=> MESSAGE_SMS,'action'=>NEW_WIP_TRANSACTION_ISAVING]);
//                 // WIPBulkTransferRequest::endDbTransaction(true);
//             for ($x = 0; $x < count($batch); $x++) {
//                 echo "=================================start trans========================================================================================\n";
//                 echo microtime(true).PHP_EOL;
//                 WIPBulkTransferRequest::startDbTransaction();
//                 // echo "[*] Slave worker processing ". $x ." of ".  count($batch) ."\n";
//                 // WIPBulkTransferRequest::process($batch[$x]->request_id, $batch[$x]->wallet_id, $batch[$x]->client_id);
//                 // $wIPTransaction = WIPTransaction::findOne(['request_id'=>$request->request_id]);
//                 $wallet_isavings = Wallet::walletById($batch[$x]->wallet_id);
//                 $iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
//                 // $desc = 'TRANSFER FROM '.$batch[$x]->client_id;
//                 // $iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
//                 // log_message('info','WIPTransaction WaLLet=======> '. $batch[$x]->wallet_id);
//                 // echo "WIPTransaction WaLLet=======>  " . $batch[$x]->wallet_id."  ".PHP_EOL;
//                 echo "==queryTranscationReference==" . $queryTranscationReference;
//                 $user = User::findByPk($queryTranscationReference->user_id);
//                 $desc = 'TRANSFER FROM '.$queryTranscationReference->user_id;
//                 echo "==user==" . $user;
//                 if(!is_null($user)){
//                     $desc = 'TRANSFER FROM '.$user->getIdentity();
//                     echo "==desc==" . $desc;
//                 }
//                 $untils = new Untils;                
//                 $defaultPass = $untils->autoGeneratorPwd(8);
//                 $defaultPassHash = password_hash($defaultPass, PASSWORD_DEFAULT);
//                 $inTransaction = false;
//                         try{
//                             log_message('info',$ver.'Start To Process User Record===================____'.$batch[$x]->recipient_phone);
//                             // echo $verbose?"Getting user id...".PHP_EOL:"";
//                             // echo microtime().PHP_EOL;
//                             $user = Untils::bulk_transfer_auto_create_user($batch[$x]->recipient_phone,$defaultPassHash);
//                             // echo "----------------------------------------------------------------------------------- \n";
//                             //     print_r($user);
//                             // echo "----------------------------------------------------------------------------------- \n";
//                             if($user['process']){
//                                 // echo microtime().PHP_EOL;
//                                 $inTransaction = true;
//                                 // echo $verbose?"Crediting user...".PHP_EOL:"".PHP_EOL;
//                                 // echo $verbose?"Crediting user...".PHP_EOL:"".PHP_EOL;
//                                 // echo microtime().PHP_EOL;
//                                 // Isavings espi deposit process             
//                                 //$isavings = 0;$commission = 0;
//                                 $_reference =Transaction::getUniqueReference($user['id'], $batch[$x]->qty, $batch[$x]->client_id);
//                                 $annualResult = UserSubscription::anuualsub($user['id'], $_reference, $batch[$x]->qty, $user['status'], 60);
//                                 if($wallet_isavings->name == I_SAVINGS){
//                                      $isavings = Transaction::charges_proccess($annualResult['userbalance'],$iSaving_charge->value);
//                                      $userBalance = round($isavings['userBalance'], 2, PHP_ROUND_HALF_DOWN);
//                                     //  log_message('info','Balance before append to total ===='.$userBalance);
//                                     //  echo 'Balance before append to total ==== '. $userBalance . PHP_EOL;
//                                      $total_amount += $userBalance;
//                                      $total_commission += round($isavings['adminPercent'], 2, PHP_ROUND_HALF_DOWN);
//                                     $iSavings_batches[] = array(
//                                         'recipient'=>array(
//                                         'phone'=>$batch[$x]->recipient_phone,
//                                         'walletType'=> strtolower($wallet_isavings->name)
//                                         ),
//                                         'amount'=>$userBalance,
//                                         'ref'=>$_reference
//                                     );
//                 //                    // the creditOnEachBulkGifting function is a temp 
//                                     $process = Transaction::creditOnEachBulkGifting(
//                                         $user['id'],
//                                         $userBalance,
//                                         $batch[$x]->wallet_id,
//                                         $_reference,
//                                         $desc,
//                                         $batch[$x]->client_id,
//                                         false
//                                     );

//                                     $batch_wip[] = array(
//                                         'id'=>$batch[$x]->id,
//                                         'status'=>($process)?WIPTransaction::COMPLETED_STATUS : WIPTransaction::PENDING_STATUS,
//                                         'message'=> ($user['status'] == WIPTransaction::MESSAGE_OLD)?  WIPTransaction::MESSAGE_OLD : WIPTransaction::MESSAGE_NEW,
//                                         'is_annual_fee'=> round($annualResult['amountpaid'], 2, PHP_ROUND_HALF_DOWN),
//                                         'is_annual_active'=> ($annualResult['amountpaid']> 0)?  1 :0,
//                                         'credited'=> $userBalance,
//                                         'commission'=> round($isavings['adminPercent'], 2, PHP_ROUND_HALF_DOWN),
//                                         'recipient_id'=> $user['id'],
//                                         'unq_reference'=> $_reference,
//                                         'updated'=> date('Y-m-d H:i:s')
//                                     );
//                                 //    $template = ($user['status'] == WIPTransaction::MESSAGE_OLD)? $message_template_old_isavings : $message_template_new_isavings;
//                                 //    SELF::messageProcess(true,$user['id'],$process,$transaction['id'],$user['status'],$transaction['recipient_phone'],$userBalance, $template,$defaultPass);
//                                 //    unset($template);
//                                 }else{
//                 //                     //do something
//                                     $process = Transaction::credit(
//                                         $user['id'],
//                                         $batch[$x]->qty,
//                                         $batch[$x]->wallet_id,
//                                         $_reference,
//                                         $desc,
//                                         $batch[$x]->client_id,
//                                         false
//                                     );
//                                     $template = ($user['status'] == WIPTransaction::MESSAGE_OLD)? $message_template_old : $message_template_new;
//                                     SELF::messageProcess(false, $user['id'], $process, $batch[$x]->id, $user['status'], $batch[$x]->recipient_phone, $annualResult, $template, $defaultPass);
//                                     unset($template);
//                                 }
//                             }else{
//                 //                  // invalid
//                                 $process = WIPTransaction::updateByPk($batch[$x]->id,[
//                                     'status'=>WIPTransaction::INVALID_STATUS,
//                                         //'message'=>((time()-(60*60*24)) < strtotime(date('Y-m-d H:i:s'))) ? WIPTransaction::MESSAGE_NEW : WIPTransaction::MESSAGE_OLD, 
//                                         'message'=> WIPTransaction::MESSAGE_NEW, 
//                                         'updated'=> date('Y-m-d H:i:s')
//                                 ]);
//                             }
                            

//                         } catch(Throwable $ex) {
//                             log_message('info',"Error processing this request... ".$ex->getMessage());
//                             if($inTransaction){
//                                 WIPBulkTransferRequest::endDbTransaction($commit = false);
//                                 fwrite(STDERR, "Error processing this request... ".$ex->getMessage());
//                             }
//                             break;
//                         }
//                         $count++;
//                     WIPBulkTransferRequest::endDbTransaction(true);
//                     echo microtime(true).PHP_EOL;
//                     echo "==============================Ended trans===========================================================================================\n";
//                     }
                    
//                     if($wallet_isavings->name == I_SAVINGS && $total_amount != 0 ){
//                         log_message('info','Total Amount deposit on ESPI ======= '.$total_amount.' ======= Amount of Commission ==== '.$total_commission.'=== Loop Level'.$loops);
//                         echo 'Total Amount deposit on ESPI ======='.$total_amount.' ======= Amount of Commission ==== '.$total_commission.'=== Loop Level'.$loops.PHP_EOL;
//                         $isEspiPro = WIPBulkTransferRequest::depositTransaction($batchWalletId, $queryTranscationReference->user_id, $queryTranscationReference->txn_reference, $total_amount, $total_commission,count($batch_wip));
//                         if(!$isEspiPro){
//                             log_message('error','Espi Deposit failed =====> '.$queryTranscationReference->request_id);
//                             fwrite(STDERR, "Espi Deposit failed =====> ".$queryTranscationReference->request_id);
//                             $transactionResult = WIPTransaction::batchUpdate(SELF::changeStatus($batch_wip, WIPTransaction::FAILED_STATUS));//WIPTransaction::FAILED_STATUS
//                             log_message('info', $transactionResult?"Succeeded change status to processing on ESPI===....":"Failed change status to processing on ESPI===...".PHP_EOL);
//                             echo $transactionResult?"Succeeded change status to processing on ESPI===....":"Failed change status to processing on ESPI===...".PHP_EOL;
//                         }else{
//                             log_message('info','Total Amount Bulk Transfer on ESPI ======='.$total_amount.'=======Amount of Commission===='.$total_commission.'=== Loop Leavel'.$loops);
//                             echo 'Total Amount Bulk Transfer on ESPI ======='.$total_amount.'=======Amount of Commission===='.$total_commission.'=== Loop Leavel'.$loops.PHP_EOL;
//                             $description = "Bulk iPoints Transfer With Ref ".$queryTranscationReference->txn_reference;
//                             $depositResponse = EspiTransaction::bulkTransferOnEspiWallet($queryTranscationReference->user_id,$total_amount,$queryTranscationReference->txn_reference,$total_commission,$iSavings_batches,$description);
//                             if($depositResponse){
//                                 log_message('info',"bulk Transfer On Espi Wallet request...=====...".$depositResponse);
//                                 echo "bulk Transfer On Espi Wallet request...=====...".$depositResponse.PHP_EOL;
//                                 $transactionResult = WIPTransaction::batchUpdate($batch_wip);
//                                 log_message('info',$transactionResult?"Succeeded Process isavings on ESPI===....":"Failed To Process isavings on ESPI===...".PHP_EOL);
//                                 echo $transactionResult?"Succeeded Process isavings on ESPI===....":"Failed To Process isavings on ESPI===...".PHP_EOL;
//                             }else{
//                                 log_message('info',"bulk Transfer On Espi Wallet request...=====...".$depositResponse);
//                                 echo "bulk Transfer On Espi Wallet request...=====...".$depositResponse.PHP_EOL;
//                                 $transactionResult = WIPTransaction::batchUpdate(SELF::changeStatus($batch_wip, WIPTransaction::PROCESSING_STATUS));
//                                 log_message('info', $transactionResult?"Succeeded change status to processing on ESPI===....":"Failed change status to processing on ESPI===...".PHP_EOL);
//                                 echo $transactionResult?"Succeeded change status to processing on ESPI===....":"Failed change status to processing on ESPI===...".PHP_EOL;
//                             }
                           
//                         }
                        
//                         unset($iSavings_batches);unset($total_amount);unset($total_commission);unset($batch_wip);
//                         $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
//                     }
//                     // echo $verbose?"Done...":"".PHP_EOL;
//                     echo "Done...".PHP_EOL;
//                 $process = WIPBulkTransferRequest::updateByPk($queryTranscationReference->id,[
//                     'status'=>WIPTransaction::COMPLETED_STATUS,
//                     'updated_at'=> date('Y-m-d H:i:s')
//                 ]);
//                 // echo "------------------------------------------Update------------------------------------------------------- \n";
//                 // // print_r($process.PHP_EOL);
//                 // var_dump($this->db->database);
//                 // // print_r($queryTranscationReference->id. PHP_EOL);
//                 // echo "------------------------------------------Update------------------------------------------------------- \n";
//                 // log_message('info',"Bulk Transfer successfully complated With Request Id++++++:: ".$queryTranscationReference->request_id);
//                 echo "Bulk Transfer successfully completed With Request Id++++++:: " .$queryTranscationReference->request_id. PHP_EOL;
//                 // echo $process?"Completed...":"".PHP_EOL;
//                 // return true;
           
//         };
//         $channel->basic_qos(null, 1, null);
//         $channel->basic_consume('task_queue', '', false, false, false, false, $callback);
//         while (count($channel->callbacks)) {
//             $channel->wait();
//         }
//         $channel->close();
//         $connection->close();
// }


// private static function changeStatus($bulks, $status){
//     foreach($bulks as $bulk){
//         $bulk['status'] = $status;
//         $data[] = $bulk;
//     }
//     return $data;
// }


// }
