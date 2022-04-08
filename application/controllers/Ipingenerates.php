<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipingenerates extends Base_Controller{

    private $userId;
    private $merchant_wallet;

    public function __construct()
	{
	  parent::__construct();
	  $this->load->library("pdolib");
      //$this->load->model('Users');
      $this->load->model('UserBalance');
      $this->load->model('Wallet');
      $this->load->model('IpinGeneration');
      $this->load->library('Exports');
      $this->load->library('utilities/ExcelFactory');

      $this->userId = $this->session->userdata('active_user')->id;
      $this->merchant_wallet = Wallet::walletByName(I_POINT);
	 
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

        $this->data['title'] = 'Merchant Generating Ipins';
		$this->data['subview'] = 'ipin/generate_pins';
		$this->load->view('components/main', $this->data);

    }

    public function loadIpinGenerations(){
        header('Content-Type: application/json');
        try{
		$db = $this->pdolib->getPDO();
        $data['merchant_id'] = $this->userId;

        $ipins = IpinGeneration::generateIpinBatchReports($db,$data);
        echo json_encode(['value'=>'success','ipins'=> $ipins]);
        }catch(Throwable $ex){
            echo json_encode(['value'=>$ex->getMessage()]);
        }	
    }

    public function getIpointBalance(){
        header('Content-Type: application/json');
        $user_balance = $this->UserBalance->getUserBalanceByWalletAndUserId(['id'=>$this->userId,'wallet_id'=>$this->merchant_wallet->id]);
        echo json_encode(['value'=>'success','ipoints'=>$user_balance->balance]);
    }
    
    public function generateIpinsInBatch(){

        $data['ipoints'] = $this->input->post('ipoints');
        $batch_quantities = $this->input->post('batch_quantities');
        $ipin_value = $this->input->post('ipin_value');
        $wallet = $this->input->post('wallet');
		$merchant_id = $this->userId;
		
			$rules = [
                [
                    'field' => 'batch_quantities',
                    'label' => 'Batch Quantities',
                    'rules' => 'trim|required|greater_than[0]'
                ],[
                    'field' => 'ipin_value',
                    'label' => 'Ipin Value',
                    'rules' => 'trim|required|greater_than[0]'
                ],[
                    'field' => 'wallet',
                    'label' => 'Wallet',
                    'rules' => 'trim|required'
                ]
            ];
    
		
        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{
                $user_balance = $this->UserBalance->getUserBalanceByWalletAndUserId(['id'=>$this->userId,'wallet_id'=>$this->merchant_wallet->id]);
                //floatval($user_balance->balance)
                $balance = $user_balance->balance - ($batch_quantities * $ipin_value);
                if($balance < 0){
                    exit(json_encode(['ipoints'=>'You Don\'t Have Enough Ipoints To Generate iPin']));
                }
                $ipoints = $batch_quantities * $ipin_value; // number of ipoints met to debited from merchant wallet
                $ipinResult = true;
                Untils::execInBackground("php index.php cli Utilities ipinGeneratingProcess $batch_quantities $ipin_value $ipoints $wallet $merchant_id ");
                //$ipinResult = IpinGeneration::generateIpin($batch_quantities,$ipin_value,$ipoints,$wallet,$user_balance);
                $result = ($ipinResult)?['value'=>'success']:['batch_quantities'=>'iPin was not successful generate, Please Try Again'];
                echo json_encode($result);	
            }catch(Throwable $ex){
                echo json_encode(['value'=>$ex->getMessage()]);
            }
            
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }

    }
	public function cancelOrActivateIpinProcess(){
		
		$batch_id = $this->input->post('id');
		$status = $this->input->post('status');
		
		
			$rules = [
                [
                    'field' => 'id',
                    'label' => 'Batch Id',
                    'rules' => 'trim|required'
                ],[
                    'field' => 'status',
                    'label' => 'Status',
                    'rules' => 'trim|required|in_list[1,0]'
                ]
            ];
    
		
        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{
                $user_balance = $this->UserBalance->getUserBalanceByWalletAndUserId(['id'=>$this->userId,'wallet_id'=>$this->merchant_wallet->id]);
                $cancelResult = IpinGeneration::cancelOrActivateIpin($status,$batch_id,$user_balance);
                $result = ($cancelResult)?['value'=>'success']:['value'=>'The cancelation wasn\'t successful, Please Try Again'];
                echo json_encode($result);
            }catch(Throwable $ex){
                echo json_encode(['value'=>$ex->getMessage()]);
            }
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


    public function loadIpinVoucher(){

        $this->data['headStyles'] = [
			BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
		  ];
		$this->data['footerScripts'] = [
			BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
			
		  ];

        $this->data['title'] = 'Use Ipins';
		$this->data['subview'] = 'ipin/load_ipin_voucher';
		$this->load->view('components/main', $this->data);

    }

    public function useIpinVoucher(){
        $voucher = $this->input->post('voucher');
        header('Content-Type: application/json');
        
			$rules = [
                [
                    'field' => 'voucher',
                    'label' => 'Voucher',
                    'rules' => 'trim|required'
                ]
            ];

		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{
               $ipinVoucher = IpinGeneration::ipinVoucherProcess($voucher,$this->userId);
               $result = ($ipinVoucher)?['value'=>'success']:['voucher'=>'Loading the voucher wasn\'t successful, Please Try Again'];
               echo json_encode($result);
            }catch(Throwable $ex){
                echo json_encode(['voucher'=>$ex->getMessage()]);
            }
        }else {
            echo json_encode($this->form_validation->get_all_errors());
        }

    }

    public function filterIpinBatch(){
        $db = $this->pdolib->getPDO();
        header('Content-Type: application/json');
        $data['merchant_id'] = $this->userId;
        $data['batch_id'] =  $this->input->post('batch_id');
        $data['ipin_value'] =  $this->input->post('ipin_values');
        $data['wallet_id'] =  $this->input->post('wallet_id');
        $data['start_date'] =  $this->input->post('start_date');
        $data['end_date'] =  $this->input->post('end_date');
        $ipins = IpinGeneration::generateIpinBatchReports($db,$data);
		echo json_encode(['value'=>'success','ipins'=> $ipins]);	
    }

    public function iPinBatchExportReport(){
        $db = $this->pdolib->getPDO();
        $data['merchant_id'] = $this->userId;
        $data['batch_id'] =  $this->input->get('batch_id');
        $data['ipin_value'] =  $this->input->get('ipin_value');
        $data['wallet_id'] =  $this->input->get('wallet');
        $data['start_date'] =  $this->input->get('start_date');
        $data['end_date'] =  $this->input->get('end_date');
        
        $ipins = IpinGeneration::generateIpinBatchReports($db,$data,$isExport=true);
        
        return ExcelFactory::createExcel($ipins,[],[],'ipinVoucherInBatch');
    }

    public function listOfBatchExportReport(){
        $db = $this->pdolib->getPDO();
        $data['merchant_id'] = $this->userId;
        $data['batch_id'] =  $this->input->get('batch_id');
        if(empty($data['batch_id'])){
            echo json_encode(['value'=>'Batch id can\'t be empty']);
        }
        $ipins = IpinGeneration::generateReports($db,$data,$isExport=true);

        //return $this->exports->exportExcel($ipins, []);
        return ExcelFactory::createExcel($ipins,[],[],'ipinVoucherInBatch');
    }
}