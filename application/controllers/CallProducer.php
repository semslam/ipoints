<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CallProducer extends CI_Controller{
    function __construct()
    {
        parent::__construct();
    }
    
    public function index(){
        $filename = $this->input->post('name');
        echo $filename;
    }

    // public function callBackGroundJobs() {}

}