<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer extends CI_Controller {

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
		$this->load->library('Untils');
		header('Access-Control-Allow-Origin: *');
	}
	

	/*
		9) This method shall be used to authenticate users login deatails :
		The iPoints transfer/entry API shall be called to give user and access to the platform.
    */
    
    public function userAuthentication(){
		header("Content-type:application/json");

		// $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
		// $req = json_decode($stream_clean, true);
		// $mreq = !empty($req) ? $req : [];

		$username =  $this->input->post('username');
		$password =  $this->input->post('password');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');
		$rules = [
			[
				'field' => 'username',
				'label' => 'Email Or Password',
				'rules' => 'required'
			 ]
			,
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required'
			]
		];

		$this->form_validation->set_rules($rules);
			
		if ($this->form_validation->run()) {

			try{
				// if (!Untils::verifyRecaptcha($recaptchaResponse)){
				// 	exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
				// }
				$data ='';$key= '';
				if(IpointTransfer::usernameVerification($username)){
					$username = $this->sms->cleanPhoneNumber($username);	
					$data = $username;$key= 'mobile_number';
				}else{
					$data = $username;$key= 'email';
				}
				$this->load->model('user_m');
				$attempt = $this->user_m->attempt($key,$data);
				if ($attempt === null) {
					exit(json_encode(['username' => 'You have no account with UICI, Please create an account today.']));
				}
				$user_group = User::fetchUserGroup($attempt->id);
				if (!$attempt->status) {

					exit(json_encode(['username' => 'Your account is not active']));
				}elseif($user_group['group_name'] != SUBSCRIBER && $user_group['group_name'] != MERCHANT){
					exit(json_encode(['username' => $user_group['group_name'].' is not authorize to transfer']));
				}
				else {
					if(password_verify($password,$attempt->password)){
						$this->session->set_userdata('active_user', $attempt);
						echo json_encode(['username' => 'success']);
					}else{
						echo json_encode(['password' => 'Wrong Password']);
					}
				}
			}catch(Throwable $ue){
				echo json_encode(['username'=> $ue->getMessage()]);
			}
		} else {
			echo json_encode($this->form_validation->get_all_errors());
		}
                
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
		echo IpointTransfer::fetchTransferForm($id,$user_id,$email,$phone);
	}

	private function get_wallets(){
		$id =$this->session->userdata('active_user')->id;
		if(!empty($id)){
			$wallets = $this->wallet_m->get_wallets();
			if(!empty($wallets)){
				echo json_encode($wallets);
			}else{
				echo json_encode([]);;
			}
		}
		
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
				//$this->session->unset_userdata('active_user');
				echo json_encode(['value'=>'success']);// details of the transfer with successful message
			
			}else{
				echo json_encode(['value'=>'Transaction was not successful, Please try again later']);
			}
			
		}catch(Throwable $ue){
			echo json_encode(['value'=> $ue->getMessage()]);
		}
		
				
	}

	public function loadIpinVoucher(){
        $voucher = $this->input->post('voucher');
		$username = $this->input->post('phone_number');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');
        header('Content-Type: application/json');
        
			$rules = [
                [
                    'field' => 'voucher',
                    'label' => 'Voucher',
                    'rules' => 'trim|required'
                ],
				[
					'field' => 'phone_number',
					'label' => 'Mobile Number',
					'rules' => 'required|regex_match[/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/]'
				]
            ];

		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{

				// if (!Untils::verifyRecaptcha($recaptchaResponse)){
				// 	exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
				// }
				
				if(!$this->usernameVerification($username)){
					exit(json_encode(["phone_number"=>"Invalid phone number format, Please try again"]));
				}

				$defaultPass = $this->untils->autoGeneratorPwd(8);
                $defaultPassHash = password_hash($defaultPass, PASSWORD_DEFAULT);
				$user = Untils::bulk_transfer_auto_create_user($this->sms->cleanPhoneNumber($username),$defaultPassHash);
				
				$ipinVoucher = IpinGeneration::ipinVoucherProcess($voucher,$user['id']);
			   $message="";
			   if($ipinVoucher){
				   $result =IpinGeneration::getVoucherWithWalletAndValue($voucher);
					$message = "The voucher given by {$result->merchant_name} was successfully loaded with the value of {$result->ipin_value} {$result->wallet_name}, Your current {$result->wallet_name} balance is {$result->balance}";
					if($user['status'] == 'new'){
						$contact = $username;
						$type = MESSAGE_SMS;
						$variable = array($result->ipin_value,$defaultPass);
						MessageQueue::messageCommit($contact, $type, NEW_WIP_TRANSACTION, $variable);
					}
			   }
               $result = ($ipinVoucher)?['value'=>'success', 'message'=> $message]:['voucher'=>'Loading the voucher wasn\'t successful, Please Try Again'];
               echo json_encode($result);
            }catch(Throwable $ex){
                echo json_encode(['voucher'=>$ex->getMessage()]);
            }
        }else {
            echo json_encode($this->form_validation->get_all_errors());
        }

	}
	
	private function usernameVerification($value){
		// phone return true while email return false
		if(preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/', $value)) {
			// $phone is valid
			return true;
		}else if(filter_var($value, FILTER_VALIDATE_EMAIL)){
			// $email is valid
			return false;
		}else{
			header("Content-type:application/json");
			exit(json_encode(['phone_number' => 'Wrong format Email Or Phone']));
		}
	}


}
