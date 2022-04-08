<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserConfigSetting extends Base_Controller {

    public function __construct(){
		parent::__construct();
		 $this->load->model('user_m');
		 $this->load->model('dashboard_m');
		 $this->load->model('Transaction');
		 $this->load->library("pdolib");
		
	}
	
    
    public function index(){
        
		
	}

	public function config($userId = null){

		$this->data['headStyles'] = [
            BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css',
			BACKOFFICE_HTML_PATH . '/css/summernote.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		  ];
		$this->data['footerScripts'] = [
            BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js',
			BACKOFFICE_HTML_PATH . '/js/summernote.js',
			
		  ];

		$this->data['title'] = 'User Setting Configuration';
		$userObj = null;$user= null;
		if(empty($userId)){
			$userObj = $this->session->userdata('active_user');
			$user =['userId'=>$userObj->id,'on_session'=> 1,'group'=>$userObj->group_name,'group_id'=>$userObj->group_id];
		}else{
			$userObj = $this->user_m->get_user($userId);
			$user= ['userId'=>$userObj->id,'on_session'=> 0,'group'=>$userObj->group_name,'group_id'=>$userObj->group_id];
		}
		$this->data['user'] = $user;
		$this->data['subview'] = 'settings/user_config_setting';
		$this->load->view('components/main', $this->data);
	}


	public function userDetails($userId){
		header('Content-Type: application/json');
		$userDetail = $this->user_m->get_user($userId);
		$userDetail->created_at = date("F d, Y",strtotime($userDetail->created_at));
		echo json_encode(['value'=>'success','userDetail'=>$userDetail]);
	}

	public function userBalance($userId){
		header('Content-Type: application/json');
		$userBalance = $this->dashboard_m->userWalletsBalance($userId);
		echo json_encode(['value'=>'success','userBalance'=>$userBalance]);
	}


    public function userSettingManager($userId){
		header('Content-Type: application/json');
				$userLevies = Uici_levies::getUsersUiciLevies($userId);
        echo json_encode(['value'=>'success','userLevies'=>$userLevies]);
	}
	

	public function updateAndCreateCharge(){
		header("Content-type:application/json");

		$data['status'] =  $this->input->post('isChecked');
		$chargeId =  $this->input->post('charge');
		$data['user_id']=  $this->input->post('user');
		$data['uici_levies']=  $this->input->post('uici_levies');
		$data['modified_by']    = $this->session->userdata('active_user')->id;
		try{
			if(!empty($chargeId)){
				//update
				$this->db->where('id', $chargeId);
				$result = $this->db->update('user_charges', $data);
				echo json_encode(['value' => ($result)?'success':'Failed']);
			}else{
				//insert
				$result = $this->db->insert('user_charges', $data);
				echo json_encode(['value' => ($result)?'success':'Failed']);
			} 
		}catch(Throwable $ue){
			echo json_encode(['value'=> $ue->getMessage()]);
		}
                
	}

	public function createAndUpdateGiftingConfig(){
		header("Content-type:application/json");
		$id = $this->input->post('id');
		$data['user_id'] = $this->input->post('user_id');
		$data['wallet_id']   	= $this->input->post('wallet');
		$data['process_type']  = $this->input->post('process_type');
		$data['message_designate']   	= $this->input->post('message_designate');
		$message_temp_type_1   = $this->input->post('message_temp_type_1');
		$message_temp_type_2    = $this->input->post('message_temp_type_2');
		$message_temp_id_1    = $this->input->post('message_temp_id_1');
		$message_temp_id_2   = $this->input->post('message_temp_id_2');
		$data['config_type']    = 'bulk';
		$data['send_message']    = $this->input->post('send_message');
		$data['modified_by']    = $this->session->userdata('active_user')->id;

		if(empty($id) && GiftingConfiguration::isExist(['wallet_id'=>$data['wallet_id'],'user_id'=>$data['user_id'],'config_type'=>$data['config_type']])){
			exit(json_encode(['wallet'=>'This gifting configuration already exist']));
		}

		$rules = [
			
			[
				'field' => 'wallet',
				'label' => 'Wallet',
				'rules' => 'required'
			],
			[
				'field' => 'message_temp_type_1',
				'label' => 'Old Template Type',
				'rules' => 'trim|required|in_list[old]'
			],
			[
				'field' => 'message_temp_type_2',
				'label' => 'New Template Type',
				'rules' => 'trim|required|in_list[new]'
			],
			[
				'field' => 'message_temp_id_1',
				'label' => 'Message Template Old',
				'rules' => 'required'
			],
			[
				'field' => 'message_temp_id_2',
				'label' => 'Message Template New',
				'rules' => 'required'
			],
			[
				'field' => 'message_designate',
				'label' => 'Message Designate',
				'rules' => 'trim|required|in_list[all,old,new]'
			],
			[
				'field' => 'process_type',
				'label' => 'Process Type',
				'rules' => 'trim|required|in_list[default,espi]'
			],
			[
				'field' => 'send_message',
				'label' => 'Send Message',
				'rules' => 'trim|required|in_list[1,0]'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$message_temp = [
				['id'=>$message_temp_id_1,'type'=>$message_temp_type_1],
				['id'=>$message_temp_id_2,'type'=>$message_temp_type_2]
			];
			$data['message_temp'] = json_encode($message_temp);
			// var_dump($data);exit();
			 $giftingConfig = GiftingConfiguration::createAndUpdate($id,$data);
			$result = ($giftingConfig)?['value'=>'success']:['message_subject'=>'Gifting config wasn\'t save successful, Please try again'];
			echo json_encode($result);
		}else{
			echo json_encode($this->form_validation->get_all_errors());
		}

	}

	public function giftingConfigs($user_id){
    header('Content-Type: application/json');
    $giftingConf = GiftingConfiguration::getGiftingConfigs($user_id);
    echo json_encode(['value'=>'success','giftingConfs'=>$giftingConf]);
	}
	
	public function getFitterTransaction($userId,$rowno = 0){
		header('Content-Type: application/json');
		$rowperpage = SELF::$rowperpage;
		$count = $rowno;
		$rowno = $this->rowperpage_and_rowno($rowperpage,$rowno);
		$data['user_id'] = $userId;
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] 	= $this->input->post('end_date');
		$data['reference'] = $this->input->post('tran_reference');
		$data['type'] = $this->input->post('transaction_type');
		$data['wallet_id'] = $this->input->post('wallet_id');
		$data['limit'] = $rowperpage;
		$data['offset'] = $rowno;

		$rules = [];
		
		// $this->form_validation->set_rules($rules);
    //     if ($this->form_validation->run()) {
			
			$db = $this->pdolib->getPDO();
			$transactions = Transaction::fetchTransactionHistory($db,$data);
			$allCount  = Transaction::$result_count['allCount'];
			$result['pagination'] = $this->pagination('UserConfigSetting/getFitterTransaction',(int)$allCount,$rowperpage,$count);
			$result['transactions'] = $transactions;
			$result['row'] = $rowno;
        echo json_encode(['value'=>'success', 'result'=> $result]);
		// }else {
    //     echo json_encode($this->form_validation->get_all_errors());
    //     }
	}

	public function totalCreditTransactions($user_id){
		header("Content-type:application/json");
		$data['type'] =  'credit';
		$data['user_id']=  $user_id;
		try{
				$credits = $this->Transaction->totalTransactionByType($data);
				echo json_encode(['credits' => $credits]);
		}catch(Throwable $ue){
			echo json_encode(['credits'=> $ue->getMessage()]);
		}
	}
	
	public function totalDebitTransactions($user_id){
		header("Content-type:application/json");
		$data['type'] =  'debit';
		$data['user_id']=  $user_id;
		try{
				$debits = $this->Transaction->totalTransactionByType($data);
				echo json_encode(['debits' => $debits]);
		}catch(Throwable $ue){
			echo json_encode(['debits'=> $ue->getMessage()]);
		}
	}

	public function lastTransactions($user_id){
		header("Content-type:application/json");
		$data['user_id']=  $user_id;
		try{
				$last_t = $this->Transaction->getLastTransactionByUserId($data);
				$last_t->created_at = date("d F Y, h:i:s",strtotime($last_t->created_at));
				echo json_encode(['last_t' => $last_t]);
		}catch(Throwable $ue){
			echo json_encode(['last_t'=> $ue->getMessage()]);
		}
	}

}