<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OpenUrlAuth extends CI_Controller {


	public function __construct(){
		parent::__construct();
		$this->load->library('sms');
		$this->load->library('emailer');
		$this->load->library('Untils');
		$this->load->model('MessageTemplate_m', 'messagetemplate_m');
		$this->load->model('user_m');
		$this->load->model('product_m');
		$this->load->model('Wallet');
		$this->load->model('User');
		$this->load->model('MessageQueue');
		$this->load->model('UserBalance');
		$this->load->library("pdolib");
		header('Access-Control-Allow-Origin: *');
	}

	/**
     * Login Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function isavingsBalance()
	{
		$data['title'] = 'UICI Checking Isaving Balance';
		$data['subview'] = 'apis/isavings_balance';
		$data['token_secret'] = $this->input->get('token');
		$this->load->view('components/layout', $data);
	}

	/**
     * Validate and Login User
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function checkIsavingsBalance(){
		$secret =  $this->input->post('token_secret');
		$password =  $this->input->post('password');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');
		$rules = [
			[
				'field' => 'token_secret',
				'label' => 'Token',
				'rules' => 'required'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required'
			]
		];

		$this->form_validation->set_rules($rules);
		header("Content-type:application/json");
		if ($this->form_validation->run()) {
			try{
					if (!Untils::verifyRecaptcha($recaptchaResponse)){
						exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
					}
					$username = Untils::decryptedMessage($secret);
					$data['password'] = $password;
					$data['wallet_name'] = I_SAVINGS;

					$key= '';
					if($this->usernameVerification($username)){
						$username = $this->sms->cleanPhoneNumber($username);	
						$key= '`u`.`mobile_number`';
						$data['contact'] = $username;
					}else{
						$key= '`u`.`email`';
						$data['contact'] = $username;
					}
			
				$userBalance = UserBalance::getWalletBalance($key,$data);

				$iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
				$percentage =  Transaction::charges_proccess($userBalance->balance,$iSaving_charge->value);
				$balance_view = SELF::balanceResult($userBalance->balance,$percentage['userBalance']);
				echo json_encode(['status'=>'success','balance' => $balance_view]);
			}catch(Throwable $ue){
				echo json_encode(['password'=> $ue->getMessage()]);
			}

		} else {
		
			echo json_encode($this->form_validation->get_all_errors());
		}
	}


	private static function balanceResult($isavings,$in_naira){
		$data = '';
	$data .=	'<div class="card">';
		$data .=	'<h1>iSavings Balance</h1>';
			// $data .='<strong> iSavings';
			// 	$data .='<p class="price">&#8369; '.$isavings.'</p>';
			// 	$data .='</strong>';
				$data .='<strong>';
				$data .='<p class="price">&#x20A6; '.number_format((int)$in_naira, 0, '.', ',').'</p>';
				$data .='</strong>';
				//$data .='<p>You can use your iSavings to buy any avoidable item on fela, You can also make a cash out request.</p>';
				//$data .='<p style="margin-bottom: 19px; margin-top: 10px; "><a href="#" style="width: 50%; ">Back to Fela</a></p>';
				$data .='<p style="margin-bottom: 19px; margin-top: 10px; ">'.SELF::mediaButton().'</p>';
		return	$data .='</div>';
	}


	public function iSavingsCashOut()
	{
		$data['title'] = 'UICI iSavings Cash Out';
		$data['subview'] = 'apis/cash_out';
		$data['threshold'] = Setting_m::findByReference('iSavings_threshold')->meta_value;
		$data['banks'] = BANKS;
		$data['token_secret'] = $this->input->get('token');
		$this->load->view('components/layout', $data);
	}



	public function makeiSavingsCashOutRequest(){
		$secret =  $this->input->post('token_secret');
		$data['password'] =  $this->input->post('password');
		$data['amount'] = $this->input->post('amount');
        $data['account_number'] = $this->input->post('account_number');
		$data['bank_name'] = $this->input->post('bank_name');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');

		$rules = [
			[
				'field' => 'token_secret',
				'label' => 'Token',
				'rules' => 'required'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required'
			],
			[
                'field' => 'bank_name',
                'label' => 'Bank Name',
                'rules' => 'trim|required'
            ],[
                'field' => 'account_number',
                'label' => 'Account Number',
                'rules' => 'trim|required|min_length[10]|max_length[10]'
            ],[
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'trim|required'
            ]
		];

		$this->form_validation->set_rules($rules);
		header("Content-type:application/json");
		if ($this->form_validation->run()) {
			try{
					if (!Untils::verifyRecaptcha($recaptchaResponse)){
						exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
					}
					$username = Untils::decryptedMessage($secret);
					$key= '';
					if($this->usernameVerification($username)){
						$username = $this->sms->cleanPhoneNumber($username);	
						$key= '`u`.`mobile_number`';
						$data['contact'] = $username;
					}else{
						$key= '`u`.`email`';
						$data['contact'] = $username;
					}
					//$user_group = User::fetchUserGroup($user->id);
					// if($user_group == SUBSCRIBER){
					// 	$wallet = I_SAVINGS;
					// }elseif($user_group == UNDERWRITER){
					// 	$wallet = I_INCOME;
					// }

					$wallet = I_SAVINGS;

					$iSavingsWallet = Wallet::walletByName($wallet);
					$dts['mobile_number'] = $data['contact'];
					$dts['wallet_id'] = $iSavingsWallet->id;
					$getBalance =  $this->UserBalance->checkUserBalance($dts);

					$data['reference'] ='';
                	$withdrawerResponse =  UserBalance::withdrawerProcess($data,true);
                if($withdrawerResponse){
                    $db = $this->pdolib->getPDO();
                    $action = WITHDRAW_REQUEST;
                    $info['report_type'] = WITHDRAWER_REQUEST;
                    // $info['frequency'] = DAILY;
                    // $info['dispatcher_type'] = INDIVIDUAL;
                    $reports = ReportSubscription::getReportSubscription($db,$info);
                    //dump sql file in a path and return part url
                    $contact =  array_column($reports, 'email');
                    $type = MESSAGE_EMAIL;
                    unset($reports);
                    $variable = array($data['contact'],$wallet,$data['amount']);
					MessageQueue::messageCommit($contact, $type, $action, $variable);
					
					$iSaving_charge = Uici_levies::getUiciLevieValue(ISAVINGS_CHARGES_KEY);
					$percentage =  Transaction::calculate_percentage($getBalance->balance,$iSaving_charge->value);

					$cash_out_view = SELF::cashOutResult($getBalance->balance,$data['amount']);
					echo json_encode(['status'=>'success','cash_out' => $cash_out_view]);
                }
			
				
					

			}catch(Throwable $ue){
				echo json_encode(['password'=> $ue->getMessage()]);
			}

		} else {
		
			echo json_encode($this->form_validation->get_all_errors());
		}
	}

	private static function cashOutResult($previous,$current){
		$data = '';
	$data .=	'<div class="card">';
		$data .=	'<h1>iSavings Cash-Out</h1>';
			$data .='<strong> Ledger Balance';
				$data .='<p class="price">&#x20A6; '.number_format($previous, 0, '.', ',').'</p>';
				$data .='</strong>';
				$data .='<strong>Available Balance';
				$data .='<p class="price">&#x20A6; '.number_format(((float)$previous - (float)$current), 0, '.', ',').'</p>';
				$data .='</strong>';
				$data .='<p>Your request for cash-out has been received, You will receive credit in your account in T+1</p>';
				$data .='<p style="margin-bottom: 19px; margin-top: 10px; ">'.SELF::mediaButton().'</p>';
	return	$data .='</div>';
	}


	public static function mediaButton(){
			$data = '';
			$data .='<div class="btn-group btn-group-sm">';
				$data .='<div class="col-sm-4"><a title="Click to go back to Fela WhatsApp" style="padding: 4px;" class="btn icon-btn btn-success" href="https://wa.me/+2349078888288?text=cashtoken" target="_blank"><i class="fa fa-whatsapp img-circle " aria-hidden="true"></i> WhatsApp</a></div>';
				$data .='<div class="col-sm-4"><a title="Click to go back to Fela Telegram" style="padding: 4px;" class="btn icon-btn btn-primary" href="https://t.me/myfela_ngbot" target="_blank"><i class="fa fa-telegram img-circle " aria-hidden="true"></i> Telegram</a></div>';
				$data .='<div class="col-sm-4"><a title="Click to go back to Fela Web" style="padding: 4px;" class="btn icon-btn btn-warning" href="https://myfela.ng" target="_blank"><i class="fa fa-android img-circle" aria-hidden="true"></i> Fela Web</a></div>';
	return	$data .='</div>';
	}

	/**
     * Logout User
     *
     * @access 	public
     * @param 	
     * @return 	redirect
     */

	// public function logout() {
	// 	$this->session->unset_userdata('active_user');
	// 	redirect(base_url());
	// }
	
	


	// public function activate_form(){
	// 	$data['title'] = 'UICI OTP Activation';
	// 	$data['subview'] = 'login/otp_api';
	// 	$this->load->view('components/layout', $data);
	// }


	private function usernameVerification($value){
		// phone return true while email return false
		if(preg_match('((0?|(\+?234))[7-9][01]\d{8})', $value)) {
			// $phone is valid
			return true;
		}else if(filter_var($value, FILTER_VALIDATE_EMAIL)){
			// $email is valid
			return false;
		}else{
			throw new Exception('Wrong format Email Or Phone');
		}
	}


	// public function activate(){
	// 	$otp =  $this->input->post('otp');

	// 		$rules = [
	// 			[
	// 				'field' => 'otp',
	// 				'label' => 'Activation',
	// 				'rules' => 'trim|required|max_length[7]|regex_match[/([a-zA-Z]|\s)+/]'
	// 			]
	// 		];
	
	// 		$this->form_validation->set_rules($rules);
				
	// 		if ($this->form_validation->run()) {
	// 			$attempt = $this->user_m->userinfo('otp',$otp);
	// 			$otps = '';

	// 			if($attempt !== null){$otps = $attempt->otp;}
	// 			if ($otps != $otp && $attempt === null) {
	// 				header("Content-type:application/json");
	// 				echo json_encode(['otp' => 'Wrong  OTP']);
	// 			} else {
	// 				if(round((strtotime(date('Y-m-d H:i:s')) - strtotime($attempt->updated_at))/(60*60))>1){
	// 					header("Content-type:application/json");
	// 					echo json_encode(['otp' => 'OTP Expired, Please click to Regenerate Opt on the link bellow']);
	// 				}else{
	// 					$data['otp'] = '';
	// 					$data['status'] = 1;
	// 					$this->user_m->update($attempt->id,$data);
	// 					$this->session->set_userdata('active_user', $attempt);
	// 					header("Content-type:application/json");
	// 					echo json_encode(['status' => 'success']);
	// 				}
	// 			}
	// 		} else {
	// 			header("Content-type:application/json");
	// 			echo json_encode($this->form_validation->get_all_errors());
	// 		}
	// }

	// /**
    //  * Reset Password
    //  *
    //  * @access  public
    //  * @param   
    //  * @return  view
    //  */

	// public function forgot_password(){	
	// 	$this->data['title'] = 'Reset Password';
	// 	$this->data['subview'] = 'login/forget_password_api';
	// 	$this->load->view('components/layout', $this->data);
	// }


	// public function forgot_pass_process(){

	// 	$username =  $this->input->post('username');

	// 		$rules = [
	// 			[
	// 				'field' => 'username',
	// 				'label' => 'Email Or Phone',
	// 				'rules' => 'trim|required'
	// 			]
	// 		];
	
	// 		$this->form_validation->set_rules($rules);
				
	// 		if ($this->form_validation->run()) {
	// 			$contact ='';
	// 			$key= '';
	// 			if($this->usernameVerification($username)){
	// 				$contact = $this->sms->cleanPhoneNumber($username);
	// 				$key= 'mobile_number';
	// 			}else{
	// 				$contact = $username;
	// 				$key= 'email';
	// 			}

	// 			$this->load->model('user_m');
	// 			$attempt = $this->user_m->attempt($key,$contact);
	// 		if ($attempt === null ) {
	// 			header("Content-type:application/json");
	// 			echo json_encode(['username' => 'The email or phone number does not exist']);
	// 		} else {
	// 			try{
	// 				$id = $attempt->id;
	// 				$password =  $this->untils->autoGeneratorPwd(8);
	// 				$dts['password'] = password_hash($password,PASSWORD_BCRYPT,['cost'=> 12]);
	// 				// call ipoint value of the user debit 2 points from his or her points only if its sms
	// 				// if grater dan 2 life zero if 1 remove the -1 if is 0 add -2
	// 				$dts['updated_at'] = date('Y-m-d H:i:s');
	// 				$result = $this->user_m->update($id,$dts);
	// 				$dts[$key] = $contact;

	// 				if($result){

	// 					$contact = (!empty($dts['email']))?  $dts['email'] : $dts['mobile_number'];
	// 					$type = (!empty($dts['email']))?  MESSAGE_EMAIL : MESSAGE_SMS;
	// 					$variable = array($password);
	// 					MessageQueue::messageCommit($contact, $type, FORGOT_PASSWORD, $variable);
	// 					echo json_encode(['status' => 'success']);
	// 				}else{
	// 					echo json_encode(['username' => 'Password rest was not successful, Please try again later']);
	// 				}
	// 			}catch(UserException $ue){
	// 				echo json_encode(['username' => $ue->getMessage()]);
	// 			}
	// 		}
	// 		} else {
	// 			header("Content-Type: application/json");
	// 			echo json_encode($this->form_validation->get_all_errors());
	// 		}

	// }
}