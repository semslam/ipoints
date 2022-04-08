<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Base_Controller {
	


	public function __construct()
	{
	  parent::__construct();
	  $this->load->library("pdolib");
	  $this->load->library('Exports');
	  $this->load->model('dashboard_m');
	  $this->load->model('UserBalance');
	  $this->load->model('Transaction');
	  $this->load->model('Wallet');
	  $this->load->model('Setting_m');
	  $this->load->library('utilities/ExcelFactory');
	 
	}

	/**
     * Dashboard
     *
     * @access private
     * @param   
     * @return view
     */
	
	public function index()
	{

		
		$db = $this->pdolib->getPDO();
		//subscriber using
		$userId = $this->session->userdata('active_user')->id;
		$group_name = $this->session->userdata('active_user')->group_name;

		if($group_name == SUBSCRIBER){
			$this->subscriber();
		}elseif($group_name == MERCHANT){
			$this->merchant();
		}else{
			//subscriber using
			$wallet_id =  Wallet::get_iSavings_wallet()->id;
			// subscriber using
			$iSavings_charge =  $this->Setting_m->findSystemConstantByKey(ISAVINGS_CHARGES_KEY)->meta_value;
			$this->data['subscribers'] = User::count(['group_id'=>4, 'status'=>1]);
			$this->data['merchants'] = User::count(['group_id'=>3, 'status'=>1]);
			$this->data['underwriters'] = User::count(['group_id'=>5, 'status'=>1]);
			$this->data['users'] = User::count(['status'=>1]);
			$this->data['products'] = $this->dashboard_m->get_products();
			$this->data['ipoint'] = $this->dashboard_m->get_ipoints();

			$this->data['new_users'] = $this->dashboard_m->getNewUser()->count; // issue
			$this->data['incompleted_kyc'] = $this->dashboard_m->getNotCompleteKYC()->count;
			$this->data['userServices'] = $this->dashboard_m->getUserService($userId);
			//subscriber using
			$this->data['total_balance'] = $this->dashboard_m->getAllUserBalance($userId);
			$this->data['transfers'] = $this->dashboard_m->getWalletTransferInfo($userId);
			//subscriber using
			$this->data['userWalletsBalance'] = $this->dashboard_m->userWalletsBalance($userId);

			//begin admin
			$this->data['UserSubscription'] = UserSubscription::get_sub_sumup_and_count();
			//end admin

			//Sales Commision
			//$wallet_info = Wallet::walletByName(I_SALES_COMMISSION);
			//$this->data['sales_commission'] = $this->UserBalance->get_balance_by_name($wallet_info->id);
			$this->data['sales_commission'] = $this->UserBalance->getBalanceByName(I_SALES_COMMISSION);
			$this->data['iSaving_charges'] = $this->UserBalance->getBalanceByName(I_SAVINGS_EARNING);
			$this->data['iInsurance_charges'] = $this->UserBalance->getBalanceByName(I_INSURANCE_EARNING);
			$this->data['underwriter_commission'] = $this->UserBalance->getSumUpOfWalletBalanceByName(I_INCOME);
			$this->data['all_iSavings'] = $this->UserBalance->get_all_iSavings_balances($wallet_id);

			$this->data['all_iSavings_naira'] = Transaction::charges_proccess($this->data['all_iSavings']->balance,$iSavings_charge)['userBalance'];
			$this->data['title'] = 'Dashboard';
			$this->data['end_date'] = date('Y-m-d'); 
			$this->data['start_date'] = date('Y-m-d', strtotime('today - 30 days'));
			//var_dump($this->data['sales_commission']);exit;
			$this->data['headStyles'] = [
				'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
				BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
			];

			$this->data['footerScripts'] = [
				'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
				BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
				BACKOFFICE_HTML_PATH . '/js/dashboard.js'
			];
			$this->data['subview'] = 'dashboard/main';
			$this->load->view('components/main', $this->data);
		}
		
	}


	public function subscriber(){
		$userId = $this->session->userdata('active_user')->id;
		$wallet_id =  Wallet::get_iSavings_wallet()->id;
		$iSavings_charge =  $this->Setting_m->findSystemConstantByKey(ISAVINGS_CHARGES_KEY)->meta_value;
		$this->data['total_balance'] = $this->dashboard_m->getAllUserBalance($userId);
		$iSavingBalance = $this->UserBalance->get_iSavings_balance($userId,$wallet_id);
		$this->data['subscriber_iSavings'] = $iSavingBalance ? $iSavingBalance->balance : 0.00;
		
		$this->data['sub_iSavings_naira'] =  Transaction::charges_proccess($this->data['subscriber_iSavings'],$iSavings_charge)['userBalance'];
		$this->data['userWalletsBalance'] = $this->dashboard_m->userWalletsBalance($userId);

		$this->data['headStyles'] = [
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		  ];
		
		$this->data['footerScripts'] = [
			'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
			BACKOFFICE_HTML_PATH . '/js/dashboard.js'
		  ];
		$this->data['title'] = 'Dashboard';
		$this->data['subview'] = 'dashboard/main';
		$this->load->view('components/main', $this->data);

	}
	public function admin(){

	}
	public function underwriter(){}
	public function merchant(){
		$userId = $this->session->userdata('active_user')->id;
		
		$this->data['userWalletsBalance'] = $this->dashboard_m->userWalletsBalance($userId);
		$this->data['title'] = 'Dashboard';
		$this->data['subview'] = 'dashboard/main';
		$this->load->view('components/main', $this->data);
	}

	public function getuserscount(){
		$this->load->model('dashboard_m');
		header('Content-Type: application/json');
		echo json_encode($this->dashboard_m->get_count_of_each_user());
	}

	public function fetchgroupUserByWallet(){
		header('Content-Type: application/json');
		$gorup_user_by_wallets = $this->dashboard_m->getGroupUsersByWallet();
		echo json_encode(['value'=>'success','gorup_user_by_wallets'=> $gorup_user_by_wallets]);	
	}

	public function fetchNumOfProductSubUsers(){
		header('Content-Type: application/json');
		$num_of_subscrier_on_products = $this->dashboard_m->getUsersBySubscribeServices();
		echo json_encode(['value'=>'success','num_of_subscrier_on_products'=> $num_of_subscrier_on_products]);	
	}


	public function getFitterUsers($rowno = 0){
		$rowperpage = SELF::$rowperpage;
		$count = $rowno;
		$rowno = $this->rowperpage_and_rowno($rowperpage,$rowno);
		$data['id'] = $this->input->post('customerId');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] 	= $this->input->post('end_date');
		$data['kyc_status'] = $this->input->post('kyc_status');
		$data['states'] = $this->input->post('states');
		$data['status'] = $this->input->post('status');
		$data['limit'] = $rowperpage;
		$data['offset'] = $rowno;

		$fitter	= $this->input->post('fitter');

		$rules = [];
		$rules = [
			[
				'field' => 'fitter',
				'label' => 'Fitter',
				'rules' => 'trim'
			]
		];
		if($fitter == 'date_range'){
			$rules = [
				[
					'field' => 'start_date',
					'label' => 'Start Date',
					'rules' => 'trim|required'
				],[
					'field' => 'end_date',
					'label' => 'End Date',
					'rules' => 'trim|required'
				]
			];
		}else if($fitter == 'kyc'){
			$rules = [
				[
					'field' => 'kyc_status',
					'label' => 'KYC Status',
					'rules' => 'trim|required'
				]
			];
		}else if($fitter == 'state'){
			$rules = [
				[
					'field' => 'states',
					'label' => 'State',
					'rules' => 'trim|required'
				]
			];
		}else if($fitter == 'status'){
			$rules = [
				[
					'field' => 'status',
					'label' => 'User Satus',
					'rules' => 'trim|required'
				]
			];
		}
		
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
			$this->load->model('dashboard_m');
			$this->load->library("pdolib");
			$db = $this->pdolib->getPDO();
			header('Content-Type: application/json');
			$user = Dashboard_m::getUsers($db,$data);
			$allCount  = Dashboard_m::$result_count['allCount'];
			
			$result['pagination'] = $this->pagination('Dashboard/getFitterUsers',(int)$allCount,$rowperpage,$count);
			$result['users'] = $user;
			$result['row'] = $rowno;
            echo json_encode(['value'=>'success', 'result'=> $result]);
		}else {
            header("Content-type:application/json");
            echo json_encode($this->form_validation->get_all_errors());
        }
	}

	public function userExportReport(){
      
        	$db = $this->pdolib->getPDO();
			$data =[];
			$fitter	= $this->input->get('fitter');
			$start_date = $this->input->get('start_date');
			$end_date	= $this->input->get('end_date');
			$data['id'] = $this->input->get('customerId');
        //if(!(empty($start_date) && empty($end_date))){
			$data['start_date'] = $this->input->get('start_date');
			$data['end_date'] 	= $this->input->get('end_date');
        // }else if(empty($fitter)){
		// 	$data['start_date'] = $this->input->get('hidden_start_date');
		// 	$data['end_date'] 	= $this->input->get('hidden_end_date');
		// }
		$data['kyc_status'] = $this->input->get('kyc_status');
		$data['states'] = $this->input->get('states');
		$data['lga'] = $this->input->get('lga');
		$data['status'] = $this->input->get('status');
        $report = Dashboard_m::getUsers($db,$data,$isExport=true);
		//return $this->exports->exportExcel($report, []);
		return ExcelFactory::createExcel($report,[],[],'SubscriberUsers');
  }

	public function getFitterServicesProducts($rowno= 0){
		$rowperpage = SELF::$rowperpage;
		$count = $rowno;
		$rowno = $this->rowperpage_and_rowno($rowperpage,$rowno);

		$data['pro_start_date'] = $this->input->post('pro_start_date');
		$data['pro_end_date'] = $this->input->post('pro_end_date');
		$data['fitter'] = $this->input->post('fitter');
		$data['product'] = $this->input->post('product');
		$data['period'] = $this->input->post('period');
		$data['value'] = $this->input->post('value');
		$data['id'] = $this->input->post('customerId');
		$data['limit'] = $rowperpage;
		$data['offset'] = $rowno;
		$rules = [];
		$rules = [
			[
				'field' => 'pro_start_date',
				'label' => 'Start Date',
				'rules' => 'trim|regex_match[/\d{4}\-\d{2}-\d{2}/]'
			],[
				'field' => 'pro_end_date',
				'label' => 'End Date',
				'rules' => 'trim|regex_match[/\d{4}\-\d{2}-\d{2}/]'
			],[
				'field' => 'fitter',
				'label' => 'Fitter',
				'rules' => 'in_list[=,>=,<=,!=]'
			],[
				'field' => 'value',
				'label' => 'Value',
				'rules' => 'greater_than[0]'
			]
		];

		if(!empty($data['fitter'])){
			$rules = [
				[
					'field' => 'fitter',
					'label' => 'Fitter',
					'rules' => 'in_list[=,>=,<=,!=]'
				],[
					'field' => 'value',
					'label' => 'Value',
					'rules' => 'trim|required|greater_than[0]'
				]
			];
		}else if(!empty($data['value'])){
			$rules = [
				[
					'field' => 'fitter',
					'label' => 'Fitter',
					'rules' => 'trim|required|in_list[=,>=,<=,!=]'
				],[
					'field' => 'value',
					'label' => 'Value',
					'rules' => 'trim|required|greater_than[0]'
				]
			];
		}

		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
			$this->load->model('dashboard_m');
			$this->load->library("pdolib");
			$db = $this->pdolib->getPDO();
			header('Content-Type: application/json');
			$product = Dashboard_m::getServicesProduct($db,$data);
	
			$allCount  = Dashboard_m::$result_count['allCount'];
			
			$result['pagination'] = $this->pagination('Dashboard/getFitterUsers',(int)$allCount,$rowperpage,$count);
			$result['products'] = $product;
			$result['row'] = $rowno;
            echo json_encode(['value'=>'success', 'result'=> $result]);
		}else {
            header("Content-type:application/json");
            echo json_encode($this->form_validation->get_all_errors());
        }
	}

public function serviesProductExportReport(){	
	  $data =[];
	  $data['start_date'] = $this->input->get('pro_start_date', true);
	  $data['end_date'] = $this->input->get('pro_end_date', true);
	  $data['fitter'] = $this->input->get('fitter');
	  $data['product'] = $this->input->get('product');
	  $data['period'] = $this->input->get('period');
	  $data['value'] = $this->input->get('value');
	  $data['id'] = $this->input->get('customerId');
	  $db = $this->pdolib->getPDO();
	$report = Dashboard_m::getServicesProduct($db,$data,$isExport=true);
    return ExcelFactory::createExcel($report,[],[],'productServices');
}

	function object_to_array($objects){
		$data = [];
		foreach($objects as $object){
			$data[] = (array)$object;
		}
		return $data;
	}

	// public function groupOption(){
	// 	$this->load->model('dashboard_m');
	// 	header('Content-Type: application/json');
	// 	$group = $this->dashboard_m->getActorsGroup();
	// 	echo json_encode(['groups'=> $group]);
	// }

	public function productOption(){
		$this->load->model('dashboard_m');
		header('Content-Type: application/json');
		$product = $this->dashboard_m->getProducts();
		echo json_encode(['products'=> $product]);
	}

	public function periodOption(){
		$this->load->model('dashboard_m');
		header('Content-Type: application/json');
		$period = $this->dashboard_m->getPeriod();
		echo json_encode(['periods'=> $period]);
	}

	public function getProductServices(){
		$userId = $this->session->userdata('active_user')->id;
		$this->load->model('dashboard_m');
		header('Content-Type: application/json');
		$productService = $this->dashboard_m->getUserService($userId);
		echo json_encode(['productServices'=> $productService]);
	}
	public function getTransactionLog(){
		$userId = $this->session->userdata('active_user')->id;
		$this->load->model('dashboard_m');
		header('Content-Type: application/json');
		$transfers = $this->dashboard_m->getWalletTransferInfo($userId);
		echo json_encode(['transfers'=> $transfers]);
	}


	

	
}
