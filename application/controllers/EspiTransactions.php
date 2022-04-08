<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class EspiTransactions extends Base_Controller{

	public function __construct(){
	  parent::__construct();
	  $this->load->library("pdolib");
	  $this->load->model("User_m");
	  $this->load->model("EspiTransaction");
	  $this->load->library('utilities/ExcelFactory');
    }


    public function index(){
		$this->data['headStyles'] = [
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		  ];
		$this->data['footerScripts'] = [
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
		  ];
		$this->data['completed']  = EspiTransaction::count(['status'=>'completed']);
		$this->data['pending']  = EspiTransaction::count(['status'=>'pending']);
		$this->data['failed']  = EspiTransaction::count(['status'=>'failed']);
		
	
        $this->data['title'] = 'Espi Isavings Transaction Queue';
		$this->data['subview'] = 'transaction/espi_transaction';
		$this->load->view('components/main', $this->data);
	}

	private function getRecipient($recipient){
		if(!empty($recipient)){
			$user = $this->User_m->get_user($recipient);
		return (!empty($user->mobile_number))? $user->mobile_number: $user->email;

		}
		 return '';
	}
	
	public function filterEpsiTransaction($rowno = 0){
		$rowperpage = SELF::$rowperpage;
		$count = $rowno;
        $rowno = $this->rowperpage_and_rowno($rowperpage,$rowno);
        
		$data['sender']  = $this->input->post('customerId');
		$data['request'] = $this->input->post('request');
		$data['reference'] = $this->input->post('et-reference');
        $data['type'] = $this->input->post('type');
        $data['charge'] = $this->input->post('charge');
        $data['value'] = $this->input->post('value');
		$data['status'] = $this->input->post('et-status');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');
		if(empty(array_filter($data))){
			$data['status']	= EspiTransaction::STATUS_COMPLETED;
		}
		$data['limit'] = $rowperpage;
		$data['offset'] = $rowno;
		header('Content-Type: application/json');
		
			$db = $this->pdolib->getPDO();
			$transactionQueue= EspiTransaction::fitterEspiTransaction($db, $data);
			//1854
			$allCount  = EspiTransaction::$result_count['allCount'];
			
			$result['pagination'] = $this->pagination('EspiTransaction/filterEpsiTransaction',(int)$allCount,$rowperpage,$count);
			$result['transactionQueue'] = $transactionQueue;
			$result['row'] = $rowno;
            echo json_encode(['value'=>'success', 'result'=> $result]);	
	}


	public function espiTransactionExportReport(){

		$data['sender']  = $this->input->get('customerId');
		$data['request'] = $this->input->get('request');
		$data['reference'] = $this->input->get('reference');
        $data['type'] = $this->input->get('type');
        $data['charge'] = $this->input->get('charge');
        $data['value'] = $this->input->get('value');
		$data['status'] = $this->input->get('status');
		$data['start_date'] = $this->input->get('start_date');
		$data['end_date'] = $this->input->get('end_date');
        if(empty(array_filter($data))){
			$data['status']	= EspiTransaction::STATUS_COMPLETED;
		}
		$db = $this->pdolib->getPDO();
		$transactionQueue= EspiTransaction::fitterEspiTransaction($db, $data,$isExport=true);
        return ExcelFactory::createExcel($transactionQueue,[],[],'EspiIsavingsTransaction');	
	}



}