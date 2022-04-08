<?php
// use PhpAmqpLib\Connection\AMQPStreamConnection;
// use PhpAmqpLib\Message\AMQPMessage;
// define('AMQP_WITHOUT_SIGNALS', true);
// class MasterWorker extends CI_Controller{
//     private $connection;
//     const CHUNK_SIZE = 500;

//     function __construct()
//     {
//         parent::__construct();
//         $this->connection = new AMQPStreamConnection('139.59.197.222', 5672, 'guest', '!!universal_queue!!');
//     }

//     public function waitOnQueue(){

//         $channel = $this->connection->channel(); 
//         $channel->queue_declare('ref_queue', false, true, false, false);
//         echo "[*] Master Worker waiting for messages.\n";
//         log_message('info', "[*] Master Worker waiting for messages. \n");
//         $callback = function ($msg) {
//             $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
//             echo '[x] Master Worker received ', $msg->body, "\n";
//             log_message('info', "[x] Received. \n");
//                 $queue_message = json_decode($msg->body);
//                 $num_of_records = $queue_message->num_records;
//                 $query = WIPTransaction::find([
//                     'request_id'=>$queue_message->request_id,
//                     'status'=>WIPTransaction::PENDING_STATUS
//                 ])
//                 ->order_by('id', 'ASC')
//                 // ->limit(SELF::BULK_PROCESS_LIMIT)
//                 ->asArray()
//                 ->all();
//                 if (count($query) == 0) {
//                     $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);   
//                 } else {
//                     $chunked_array = 0;
//                     $secondChannel = $this->connection->channel();
//                     $secondChannel->queue_declare('task_queue', false, true, false, false);
//                     echo "Chunking data into batches of ". SELF::CHUNK_SIZE."............. \n";
//                         $chunked_array = array_chunk($query, SELF::CHUNK_SIZE);
//                         echo "Chunking completed! " . count($chunked_array) . " chunks were produced! \n";
//                         echo "Sending batches of ". SELF::CHUNK_SIZE ." to RabbitMQ!  \n";
//                         // print_r($chunked_array);
//                         log_message('info','chunks====================================== '. $chunked_array);
//                         array_walk($chunked_array, [$this,"sendToQueue"], $secondChannel);
//                         echo "All Batches Successfully sent to RabbitMQ!  \n";
//                         $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
//                 }            
           
            
//         };
//         $channel->basic_qos(null, 1, null);
//         $channel->basic_consume('ref_queue', '', false, false, false, false, $callback);
//         while (count($channel->callbacks)) {
//             $channel->wait();
//         }
//         $channel->close();
//         $connection->close();
// }

//     public function sendToQueue($array_element_value, $array_element_key, $channel){
//     $message_for_queue = new AMQPMessage(json_encode($array_element_value), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
//     $channel->basic_publish($message_for_queue, '', 'task_queue');
//     usleep(5);
//     }

// }
