<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class MyQueue extends CI_Controller{
    private $connection;

    function __construct()
    {
        parent::__construct();
        $this->connection = new AMQPStreamConnection('139.59.197.222', 5672, 'guest', '!!universal_queue!!');
    }

    public function index(){
        $data['title'] = "My Real Title";
        $data['heading'] = "Upload A File";
        $this->load->view('upload_practice/upload', $data);
    }

 function do_upload(){
    if(isset($_FILES["image_file"]["name"]))  
           {  
                $config['upload_path'] = './uploads/';  
                $config['allowed_types'] = 'xls|xlsx|csv';  
                $this->load->library('upload', $config);  
                if(!$this->upload->do_upload('image_file'))  
                {  
                     echo $this->upload->display_errors();  
                }  
                else  
                {
                $channel = $this->connection->channel(); 
                $data = $this->upload->data();
                $inputFileName = $data['full_path'];
                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($inputFileName);
                // /**  Load $inputFileName to a Spreadsheet Object  **/
                $spreadsheet = $reader->load($data['full_path']);
                // read excel data and store it into an array
                $xls_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $channel->queue_declare('task_queue', false, true, false, false);
                foreach ($xls_data as $message) {
                    $msg = new AMQPMessage(json_encode($message), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
                    $channel->basic_publish($msg, '', 'task_queue');
                }
                // var_dump($xls_data);
                echo count($xls_data) . " records were Successfully Uploaded!";
                }
                } 
           }
 }
 