<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Producer extends CI_Controller{
    private $connection;

    function __construct()
    {
        parent::__construct();
        $this->connection = new AMQPStreamConnection('192.168.99.100', 5672, 'guest', 'guest');
    }

    function sendToRabbitMq (){
        $channel = $this->connection->channel();
        $filename = $this->input->post('name');
        // $filename = $data['upload_data']['orig_name'];
        $inputFileName = "/var/www/html/uploads/" . $filename;
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($inputFileName);
        //read excel data and store it into an array
        $xls_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $channel->queue_declare('task_queue', false, true, false, false);
        foreach ($xls_data as $message) {
            $msg = new AMQPMessage($message, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
            // $this->sendToRabbitMq(json_encode($message), $channel);
            $channel->basic_publish($msg, '', 'task_queue');
        }
        echo "[x]All records were succesfully published to the Queue \n";
        $channel->close();
        $this->connection->close();
     }

     
    
    
    // $xls_data = json_encode($xls_data);
    // var_dump($xls_data);
    /* $xls_data contains this array:
    [1=>['A'=>'Domain', 'B'=>'Category', 'C'=>'Nr. Pages'], 2=>['A'=>'CoursesWeb.net', 'B'=>'Web Development', 'C'=>4000], 3=>['A'=>'MarPlo.net', 'B'=>'Courses & Games', 'C'=>15000]]
     */
    //now it is created a html table with the excel file data
    // $html_tb ='<table border="1"><tr><th>'. implode('</th><th>', $xls_data[1]) .'</th></tr>';
    // $nr = count($xls_data); //number of rows
    // for($i=2; $i<=$nr; $i++){
    // $html_tb .='<tr><td>'. implode('</td><td>', $xls_data[$i]) .'</td></tr>';
    // }
    // $html_tb .='</table>';

    //  echo $html_tb;
    //  var_dump($spreadsheet);
}