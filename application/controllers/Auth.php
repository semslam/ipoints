<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {


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
		header('Access-Control-Allow-Origin: *');
	}

	/**
     * Login Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function login()
	{
		$data['title'] = 'Login';
		$data['subview'] = 'login/main';
		$this->load->view('components/layout_front_forms', $data);
	}

	/**
     * Validate and Login User
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function login_attempt(){
		$username =  $this->input->post('username');
		$password =  $this->input->post('password');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');
		//emcript password
		header("Content-type:application/json");
		$rules = [
			[
				'field' => 'username',
				'label' => 'Email Or Password',
				'rules' => 'required'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'trim|required'
			]
		];

		$this->form_validation->set_rules($rules);
			
		if ($this->form_validation->run()) {
	
			// if (!Untils::verifyRecaptcha($recaptchaResponse))
			// {
			// 	exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
			// }

			// $where = [ 'mobile_number' => $this->sms->cleanPhoneNumber($emailorphone)];
			// $or = [ 'email' => $emailorphone ];
			// $user = User::find($where)->or_where($or)->one();
				
				$data ='';$key= '';
				if($this->usernameVerification($username)){
					$username = $this->sms->cleanPhoneNumber($username);	
					$data = $username;$key= 'mobile_number';
				}else{
					$data = $username;$key= 'email';
				}
				$this->load->model('user_m');
				$attempt = $this->user_m->attempt($key,$data);

			
			if ($attempt === null) {
				echo json_encode(['username' => 'This Username or Password can not be found']);
			}
			elseif (! $attempt->status) {
				echo json_encode(['username' => 'Your account is not active']);
			}
			else {
				if(password_verify($password,$attempt->password)){
					$this->session->set_userdata('active_user', $attempt);
					echo json_encode(['status' => 'success']);
				}else{
					echo json_encode(['password' => 'Wrong Password']);
				}
				
			}

		} else {
			echo json_encode($this->form_validation->get_all_errors());
		}
	}

	/**
     * Logout User
     *
     * @access 	public
     * @param 	
     * @return 	redirect
     */

	public function logout() {
		$this->session->unset_userdata('active_user');
		redirect(base_url());
	}

	public function industry(){
		
		$query = trim($this->input->get('industry',TRUE));
		$this->db->select('id, name'); 
		$this->db->like('name', $query);
		$data = $this->db->get("industry")->result();
		$industries = [];
		if(!empty($data)){
            foreach($data as $dt){
                $industries[$dt->id] = $dt->name;
            }
        }
		header('Content-Type: application/json');
        echo json_encode( $industries);
	}

	public function lga(){
		
		$query = trim($this->input->get('name',TRUE));
		$this->db->select('id, name'); 
		$this->db->like('name', $query);
		$data = $this->db->get("local_govts")->result();
		$lgas = [];
		if(!empty($data)){
            foreach($data as $dt){
                $lgas[$dt->id] = $dt->name;
            }
        }
		header('Content-Type: application/json');
        echo json_encode( $lgas);
	}


	public function states(){
		
		$query = trim($this->input->get('name',TRUE));
		$this->db->select('state_id, state_name'); 
		$this->db->like('state_name', $query);
		$data = $this->db->get("state_tbl")->result();
		$states = [];
		if(!empty($data)){
            foreach($data as $dt){
                $states[$dt->state_id] = $dt->state_name;
            }
        }
		header('Content-Type: application/json');
        echo json_encode( $states);
	}


	public function state(){
		$this->db->select('state_id, state_name'); 
		$data = $this->db->get("state_tbl")->result();
		header('Content-Type: application/json');
        echo json_encode($data);
	}

	public function local_govts(){
		$query = $this->input->post('state_id',TRUE);
		$this->db->select('id, name'); 
		$this->db->where('state_id', $query);
		$data = $this->db->get("local_govts")->result();
		header('Content-Type: application/json');
        echo json_encode($data);
	}
	
	

	/**
     * Register
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function register(){	
		$this->data['title'] = 'Register';
		$this->data['subview'] = 'register/main';
		$this->load->view('components/layout', $this->data);
	}

	public function processor(){
		$group_id		= $this->input->post('acct_type');
		$data['name'] 		= $this->input->post('fName');
		$data['email']   	= $this->input->post('email');
		$data['gender']   	= $this->input->post('gender');
		$data['mobile_number'] 		= $this->input->post('user_phone');
		$data['business_name']   	= $this->input->post('biz_name');
		$data['birth_date']   = $this->input->post('dob');
		$data['rc_number']   = $this->input->post('rc_number');
		$data['referrer']   = $this->input->post('referrer');
		$data['address']   = $this->input->post('biz_address');
		$data['lga']   = $this->input->post('lga');
		$data['states']   = $this->input->post('states');
		$data['industry']   = $this->input->post('industries');
		$data['next_of_kin']   = $this->input->post('nok_name');
		$data['next_of_kin_phone']   = $this->input->post('nok_phone');
		$data['created_at']   = date('Y-m-d H:i:s');
		$data['updated_at']   = date('Y-m-d H:i:s');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');
		header("Content-type:application/json");

		$rules = [];

		if($group_id == '4'){
			//Subscriber
			//var_dump($data);exit;
			$data['group_id'] = $group_id;
			$rules = [
				[
					'field' => 'fName',
					'label' => 'Full Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'gender',
					'label' => 'Gender',
					'rules' => 'trim|required|in_list[male,female]'
				],
				[
					'field' => 'user_phone',
					'label' => 'Mobile Number',
					'rules' => 'required|regex_match[/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/]'
				],
				[
					'field' => 'email',
					'label' => 'Email',
					'rules' => "trim|valid_email"
				],
				[
					'field' => 'lga',
					'label' => 'Local Government of Residence',
					'rules' => 'required|numeric|is_natural_no_zero|max_length[737]'
				],
				[
					'field' => 'states',
					'label' => 'State',
					'rules' => 'required|numeric|is_natural_no_zero|max_length[37]'
				],
				[
					'field' => 'nok_name',
					'label' => 'Next of Kin Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'nok_phone',
					'label' => 'Next of Kin Mobile Number',
					'rules' => 'required|regex_match[/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/]'
				],
				[
					'field' => 'dob',
					'label' => 'Date Of Birth',
					'rules' => 'trim|required|regex_match[/\d{4}\-\d{2}-\d{2}/]'
				],
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[8]'
				],
				[
					'field' => 'conf_password',
					'label' => 'Confirm Password',
					'rules' => 'trim|required|matches[password]'
				]
			];
		}else if($group_id == '3'){
			//Merchant
			$data['group_id'] = $group_id;
			$rules = [
				[
					'field' => 'biz_name',
					'label' => 'Business Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'email',
					'label' => 'Email',
					'rules' => "trim|required|valid_email"
				],
				[
					'field' => 'biz_address',
					'label' => 'Business Address',
					'rules' => 'trim|required|min_length[2]|max_length[40]'
				],
				[
					'field' => 'rc_number',
					'label' => 'RC Number',
					'rules' => 'max_length[25]'
				],
				[
					'field' => 'referrer',
					'label' => 'Referrer',
					'rules' => 'trim|max_length[30]'
				],
				[
					'field' => 'industries',
					'label' => 'Industry',
					'rules' => 'required|numeric|is_natural_no_zero|max_length[181]'
				],
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[8]'
				],
				[
					'field' => 'conf_password',
					'label' => 'Confirm Password',
					'rules' => 'trim|required|matches[password]'
				]
			];

		} else if($group_id == '5'){
			//Under Writer
			$data['group_id'] = $group_id;
			$rules = [
				[
					'field' => 'biz_name',
					'label' => 'Business Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'email',
					'label' => 'Email',
					'rules' => "trim|required|valid_email"
				],
				[
					'field' => 'biz_address',
					'label' => 'Business Address',
					'rules' => 'trim|required|min_length[2]|max_length[40]'
				],
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[8]'
				],
				[
					'field' => 'conf_password',
					'label' => 'Confirm Password',
					'rules' => 'trim|required|matches[password]'
				]
			];

		}else if($group_id == '6'){
			//Partner
			$data['group_id'] = $group_id;
			$rules = [
				[
					'field' => 'biz_name',
					'label' => 'Business Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'email',
					'label' => 'Email',
					'rules' => "trim|required|valid_email"
				],
				[
					'field' => 'biz_address',
					'label' => 'Business Address',
					'rules' => 'trim|required|min_length[2]|max_length[40]'
				],
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[8]'
				],
				[
					'field' => 'conf_password',
					'label' => 'Confirm Password',
					'rules' => 'trim|required|matches[password]'
				]
			];

		}
		
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
			
			// if (!Untils::verifyRecaptcha($recaptchaResponse)){
			// 	exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
			// }
			//if subscriber provide email and phone number check if the two exit
			$exist;
			$phone_number = $this->sms->cleanPhoneNumber($data['mobile_number']);
			$exist = (!empty($data['mobile_number']))? $this->user_m->userexist('mobile_number',$phone_number) : $this->user_m->userexist('email',$data['email']);
			if(!empty($exist)){
				exit(json_encode(['acct_type' => 'This account already exist']));
			}
			
			$data['password'] = password_hash( $this->input->post('password'),PASSWORD_BCRYPT,['cost'=> 12]);
			$data['otp'] = $this->untils->otpGenerator(7);
			if(!empty($data['mobile_number'])){
				$data['mobile_number'] = $phone_number;	
			}
			$this->create($data);
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
	}

	private function create(Array $data = []){
		header("Content-type:application/json");
		$alert = false;
		$data['group_id'];
		$this->db->trans_start();
		$user_id = $this->user_m->auto_create_user($data);
		
		$create_wallet = true;
		if($data['group_id'] == 4){
			$create_wallet = true;
		}elseif($data['group_id'] == 3) {
			$main_wallet = Wallet::getMain();
			$create_wallet = User::initWallet($user_id,$main_wallet->id);
		}
		
		if(!$create_wallet && empty($user_id)){
			$this->db->trans_rollback();
    		exit(json_encode(['acct_type' => 'User account can not be create, Please try again later']));
		}
		$this->db->trans_commit();
		$contact = (!empty($data['email']))?  $data['email'] : $data['mobile_number'];
		$type = (!empty($data['email']))?  MESSAGE_EMAIL : MESSAGE_SMS;
		//Untils::encryptedMessage($info);
		$variable = array($data['otp'],);
		MessageQueue::messageCommit($contact, $type, REGISTER, $variable);
		echo json_encode(['status' => 'success']);
	}

	public function activate_form(){
		$data['title'] = 'OTP Activation';
		$data['subview'] = 'otp/main';
		$this->load->view('components/layout', $data);
	}
	
	public function otpregen_form(){
		$data['title'] = 'Regenerate Otp';
		$data['subview'] = 'otpregen/main';
		$this->load->view('components/layout', $data);
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
			exit(json_encode(['username' => 'Wrong format Email Or Phone']));
		}
	}


	public function otpregen(){
		header("Content-type:application/json");
		$username =  $this->input->post('username');
		$password =  $this->input->post('password');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');

			$rules = [
				[
					'field' => 'username',
					'label' => 'Email Or Phone',
					'rules' => 'trim|required'
				],
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[8]'
				]
			];
	
			$this->form_validation->set_rules($rules);
				
			if ($this->form_validation->run()) {
				// if (!Untils::verifyRecaptcha($recaptchaResponse)){
				// 	exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
				// }
				$data ='';$key= '';
				if($this->usernameVerification($username)){
					$data = $this->sms->cleanPhoneNumber($username);
					$key= 'mobile_number';
				}else{
					$data = $username;
					$key= 'email';
				}

				$user = $this->user_m->userinfo($key,$data);
			if (empty($attempt) || (!password_verify($password,$user->password))) {
				echo json_encode(['otp' => 'Wrong  Username and Password']);
			} else {
				try{
					$id = $user->id;
					$dts['otp'] = $this->untils->otpGenerator(7);
					$dts['updated_at'] = date('Y-m-d H:i:s');
					$result = $this->user_m->update($id,$dts);
					
					if(empty($result)){
						$dts['email'] = $user->email;
						$dts['mobile_number'] = $user->mobile_number;
				
						$contact = (!empty($dts['email']))?  $dts['email'] : $dts['mobile_number'];
						$type = (!empty($dts['email']))?  MESSAGE_EMAIL : MESSAGE_SMS;
						$variable = array($dts['otp']);
						MessageQueue::messageCommit($contact, $type, RE_GENERATE_OTP, $variable);
						echo json_encode(['status' => 'success']);
					}else{
						echo json_encode(['username' => 'OTP re-generate was not successful, Please try again later']);
					}
				}catch(UserException $ue){
					echo json_encode(['username' => $ue->getMessage()]);
				}
				
			}
			} else {
				echo json_encode($this->form_validation->get_all_errors());
			}

	}


	public function activate(){
		$otp =  $this->input->post('otp');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');
		header("Content-type:application/json");
			$rules = [
				[
					'field' => 'otp',
					'label' => 'Activation',
					'rules' => 'trim|required|max_length[7]|regex_match[/([a-zA-Z]|\s)+/]'
				]
			];
	
			$this->form_validation->set_rules($rules);
				
			if ($this->form_validation->run()) {
				// if (!Untils::verifyRecaptcha($recaptchaResponse)){
				// 	exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
				// }
				$attempt = $this->user_m->userinfo('u.otp',$otp);
				$otps = '';
				if(empty($attempt)){
					exit(json_encode(['otp' => 'Wrong  OTP']));
				}
				if($otp != $attempt->otp){
					exit(json_encode(['otp' => 'Wrong  OTP']));
				}
				
					if(round((strtotime(date('Y-m-d H:i:s')) - strtotime($attempt->updated_at))/(60*60))>720){// Expired in a month now, it was 1hour before
						echo json_encode(['otp' => 'OTP Expired, Please click to Regenerate Opt on the link bellow']);
					}else{
						$data['otp'] = '';
						$data['status'] = 1;
						$this->user_m->update($attempt->id,$data);
						$this->session->set_userdata('active_user', $attempt);
						echo json_encode(['status' => 'success']);
					}
			} else {
				
				echo json_encode($this->form_validation->get_all_errors());
			}
	}

	/**
     * Reset Password
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function forgot_password(){	
		$this->data['title'] = 'Reset Password';
		$this->data['subview'] = 'forgot_password/main';
		$this->load->view('components/layout_front_forms', $this->data);
	}


	public function forgot_pass_process(){

		$username =  $this->input->post('username');
		$recaptchaResponse = $this->input->post('g-recaptcha-response');
		header("Content-type:application/json");

			$rules = [
				[
					'field' => 'username',
					'label' => 'Email Or Phone',
					'rules' => 'trim|required'
				]
			];
	
			$this->form_validation->set_rules($rules);
				
			if ($this->form_validation->run()) {
				// if (!Untils::verifyRecaptcha($recaptchaResponse)){
				// 	exit(json_encode(['g-recaptcha'=>'You did not complete the Recaptcha successfully']));
				// }
				$contact ='';$key= '';
				if($this->usernameVerification($username)){
					$contact = $this->sms->cleanPhoneNumber($username);
					$key= 'mobile_number';
				}else{
					$contact = $username;
					$key= 'email';
				}

				$this->load->model('user_m');
				$attempt = $this->user_m->attempt($key,$contact);
			if ($attempt === null ) {
				echo json_encode(['username' => 'The email or phone number does not exist']);
			} else {
				try{
					$id = $attempt->id;
					$password =  $this->untils->autoGeneratorPwd(8);
					$dts['password'] = password_hash($password,PASSWORD_BCRYPT,['cost'=> 12]);
					// call ipoint value of the user debit 2 points from his or her points only if its sms
					// if grater dan 2 life zero if 1 remove the -1 if is 0 add -2
					$dts['updated_at'] = date('Y-m-d H:i:s');
					$result = $this->user_m->update($id,$dts);
					$dts[$key] = $contact;

					if($result){

						$contact = (!empty($dts['email']))?  $dts['email'] : $dts['mobile_number'];
						$type = (!empty($dts['email']))?  MESSAGE_EMAIL : MESSAGE_SMS;
						$variable = array($password);
						MessageQueue::messageCommit($contact, $type, FORGOT_PASSWORD, $variable);
						echo json_encode(['status' => 'success']);
					}else{
						echo json_encode(['username' => 'Password rest was not successful, Please try again later']);
					}
				}catch(UserException $ue){
					echo json_encode(['username' => $ue->getMessage()]);
				}
			}
			} else {
				echo json_encode($this->form_validation->get_all_errors());
			}

	}

	

	// public function autouser(){
	// 	echo $this->untils->auto_create_user('07038144776');
	// }
}
