<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransferIpoint extends Base_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('sms');
		//$this->load->library('emailer');
		$this->load->library('Untils');
		$this->load->model('MessageTemplate_m', 'messagetemplate_m');
		$this->load->model('user_m');
		$this->load->model('User');
		$this->load->model('IpointTransfer');
		$this->load->model('userBalance');
		$this->load->model('product_m');
		$this->load->model('Transaction');
		$this->load->model('MessageQueue');
	}
	
    
    public function index(){
        
		$this->data['title'] = 'Ipoint Transfer';
		$this->data['ipoint_id'] = Wallet::walletByName(I_POINT)->id;
		$this->data['subview'] = 'services_log/ipoint_transfer';
		$this->load->view('components/main', $this->data);
	}
	
	public function transferValidate(){
		header("Content-type:application/json");

		$wallet_id =  $this->input->post('wallet_id');
		$sender_wallet_id =  $this->input->post('sender_wallet_id');
		$recipient_contact =  $this->input->post('recipient_contact');
		$value =  $this->input->post('value');

		$rules = [
			[
				'field' => 'wallet_id',
				'label' => 'Wallet List',
				'rules' => 'required'
			 ],
			[
				'field' => 'sender_wallet_id',
				'label' => 'Sender wallet id',
				'rules' => 'trim|required'
			],
			[
				'field' => 'recipient_contact',
				'label' => 'Recipient Phone Or Email',
				'rules' => 'trim|required'
			],
			[
				'field' => 'value',
				'label' => 'Transfer Value',
				'rules' => 'trim|required'
			]
		];

		$this->form_validation->set_rules($rules);
			
		if ($this->form_validation->run()) {
			try{
				IpointTransfer::usernameVerification($recipient_contact);
				echo json_encode(['value' => 'success']);
			}catch(Throwable $ue){
				echo json_encode(['value'=> $ue->getMessage()]);
			}
			
		} else {
			echo json_encode($this->form_validation->get_all_errors());
		}
                
	}

	public function passwordValidate(){
		header("Content-type:application/json");

		$wallet_id =  $this->input->post('wallet_id');
		$sender_wallet_id =  $this->input->post('sender_wallet_id');
		$recipient_contact =  $this->input->post('recipient_contact');
		$value =  $this->input->post('value');

		$rules = [
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required'
			]
		];

		$this->form_validation->set_rules($rules);
			
		if ($this->form_validation->run()) {
				echo json_encode(['value' => 'success']);
		} else {
			echo json_encode($this->form_validation->get_all_errors());
		}
                
	}
	
	public function user_balance_wallets(){
		$id =$this->session->userdata('active_user')->id;
		echo IpointTransfer::fetchUserBalancesView($id);
	}

	public function transferForm($id){
		$user_id =$this->session->userdata('active_user')->id;
		$email =$this->session->userdata('active_user')->email;
		$phone =$this->session->userdata('active_user')->mobile_number;
		$group_name =$this->session->userdata('active_user')->group_name;
		echo IpointTransfer::fetchTransferForm($id,$user_id,$email,$phone);
	}


	public function transferWalletBalance(){
		header("Content-type:application/json");
		// transfer product to another
		//   fatch recipient user info from users table
		//need recipient_wallet_id, sender_wallet_id, transfer_value and recipient_contacts from font end
		$sender_id = $this->session->userdata('active_user')->id;// check if user is on session
		$email = $this->session->userdata('active_user')->email;// check if user is on session
		$phone = $this->session->userdata('active_user')->mobile_number;// check if user is on session
		$name = $this->session->userdata('active_user')->name;// check if user is on session
		$business_name = $this->session->userdata('active_user')->business_name;// check if user is on session
		$sender_group =$this->session->userdata('active_user')->group_name;
		$sender_walletId = $this->input->post('sender_wallet_id');
		$recipient_wallet = $this->input->post('wallet_id');
		$transfer_value = $this->input->post('value');
		$recipient_username =$this->input->post('recipient_contact');
		try{
			$transferResult = IpointTransfer::transferProcess($sender_id,
			$email,$phone,$name,$business_name,$sender_group,
			$sender_walletId,$recipient_wallet,$transfer_value,
			$recipient_username );
			if($transferResult){
				echo json_encode(['value'=>'success']);// details of the transfer with successful message
			}else{
				echo json_encode(['value'=>'Transaction was not successful, Please try again later']);
			}
			
		}catch(Throwable $ue){
			echo json_encode(['value'=> $ue->getMessage()]);
		}
		
				
	}


}
