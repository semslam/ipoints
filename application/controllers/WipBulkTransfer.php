<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WipBulkTransfer extends Base_Controller{

    private $userId;
    private $merchant_wallet;

    public function __construct()
	{
	  parent::__construct();
	  $this->load->library("pdolib");
    
      $this->load->model('UserBalance');
      $this->load->model('Wallet');
      $this->load->library('Exports');
      $this->load->library('utilities/ExcelFactory');
	 
	}


    public function index(){

        $this->data['headStyles'] = [
            BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		  ];
		$this->data['footerScripts'] = [
            BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
			
		  ];

        $this->data['title'] = 'WIP Bulk Ipoint Transfer';
		$this->data['subview'] = 'wip/wip_transfer';
		$this->load->view('components/main', $this->data);

    }


    public function bulkTransfer(){

        $this->data['headStyles'] = [
            BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		  ];
		$this->data['footerScripts'] = [
            BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
			
		  ];

        $this->data['title'] = 'WIP Bulk Ipoint Transfer';
		$this->data['subview'] = 'wip/wip_bulk_transfer';
		$this->load->view('components/main', $this->data);

    }



    public function filterWipTransfer($rowno = 0){
        $db = $this->pdolib->getPDO();
        header('Content-Type: application/json');
        $rowperpage = SELF::$rowperpage;
		$count = $rowno;
        $rowno = $this->rowperpage_and_rowno($rowperpage,$rowno);
        
        $data['request_id'] =  $this->input->post('request_id');
        $data['start_date'] =  $this->input->post('start_date');
        $data['end_date'] =  $this->input->post('end_date');
        $data['limit'] = $rowperpage;
		$data['offset'] = $rowno;

        $group_name = $this->session->userdata('active_user')->group_name;
        if($group_name == MERCHANT){
            $data['user_id'] = $this->session->userdata('active_user')->id;
        }else{
            $data['user_id'] =  $this->input->post('customerId');
        }
       
        $WipBulkTransfer = WIPBulkTransferRequest::fetchBulkTransfer($db,$data);
        $allCount  = WIPBulkTransferRequest::$result_count['allCount'];

        $result['pagination'] = $this->pagination('wipBulkTransfer/filterWipTransfer',(int)$allCount,$rowperpage,$count);
		$result['WipBulkTransfers'] = $WipBulkTransfer;
        $result['row'] = $rowno;
        
		echo json_encode(['value'=>'success','result'=> $result]);	
    }

    public function groupWipTransferExportReport(){
        $db = $this->pdolib->getPDO();
        $data['request_id'] =  $this->input->get('request_id');
        $data['user_id'] =  $this->input->get('customerId');
        $data['start_date'] =  $this->input->get('start_date');
        $data['end_date'] =  $this->input->get('end_date');
        //var_dump('<pre>',$data);exit;
        $WipBulkTransfer = WIPBulkTransferRequest::fetchBulkTransfer($db,$data,$isExport=true);
        //var_dump('<pre>',$WipBulkTransfer);exit;
        return ExcelFactory::createExcel($WipBulkTransfer,[],[],'WipBulkTransfer');
    }

    public function cancelIvalidBulkTransfer(){
		
		$request_id = $this->input->post('request_id');
		$mercahnt_id = $this->session->userdata('active_user')->id;
		$email =$this->session->userdata('active_user')->email;
		
			$rules = [
                [
                    'field' => 'request_id',
                    'label' => 'Request ID',
                    'rules' => 'trim|required'
                ]
            ];
    
		
        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{
                $cancelResult = WIPBulkTransferRequest::cancelIvalidNumberAndRefundWallet($mercahnt_id, $request_id, $email);
                $result = ($cancelResult)?['value'=>'success']:['value'=>'The cancelation wasn\'t successful, Please Try Again'];
                echo json_encode($result); 
            }catch(Throwable $ex){
                echo json_encode(['value'=>$ex->getMessage()]);
            }
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    public function wipTransferExportReport(){
        $db = $this->pdolib->getPDO();
       
        $data['client_id'] = $this->session->userdata('active_user')->id;
        $data['request_id'] =  $this->input->get('request_id');
        $data['status'] =  $this->input->get('status');
       
        if(empty($data['request_id'])){
            echo json_encode(['value'=>'Request id can\'t be empty']);
        }
        //var_dump($data);
        $bulkTransferByStatus = WIPBulkTransferRequest::fetchBulkTransferByStatus($db,$data,$isExport=true);
        //var_dump($bulkTransferByStatus);exit;
        return ExcelFactory::createExcel($bulkTransferByStatus,[],[],'bulkTransferByStatus');
    }
}