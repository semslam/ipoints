<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Untils extends CI_Model{
// template_process($array,$string)

public function __construct(){
	parent::__construct();
	$this->load->library('sms');
	$this->load->library('emailer');
	$this->load->library('PinGenerator');
	$this->load->model('Transaction');
	$this->load->model('MessageTemplate_m', 'messagetemplate_m');
	$this->load->model('user_m');
}


    public function template_process($values,$template){
		$placeholder = [];
		foreach($values as $key=>$val) 
			$placeholder[] = "{{$key}}";
		    $bodytag = str_replace(
			$placeholder,
			$values, 
			$template);
		
		return $bodytag;
    }


    public function OtpSms($info){
		$info['message_channel'] = 'Sms';
		//var_dump($info);exit;
		$msg = $this->messagetemplate_m->get_template($info);
		if(empty($msg)){
			throw new Exception('The system can not found message template');
		}else{
			$message =  $this->template_process(
				$info['values'],
				$msg->message_template
			);
			$info['message'] = $message;
			return ($info['charge'])? $this->chargeSms($info):$this->freeSms($info);
		}
	}

	public function OtpMesg($info, Array $attachment = []){
		$info['message_channel'] = 'Email';
		$msg = $this->messagetemplate_m->get_template($info);
		if(empty($msg)){
			throw new Exception('The system can not found message template');
		}else{

			$this->emailer->addRecipient($info['email']);
			
			$this->emailer->subject = $msg->message_subject;
			$message =  $this->template_process(
				$info['values'],
				$msg->message_template
			);
			if(!empty($attachment)){
				$this->emailer->addAttachment($attachment['filename'],$attachment['newname'],$attachment['mime']);
			}
			$this->emailer->addMessage($message);
			return $this->emailer->send();
		}
	}

	public function defaultMesg($info, Array $attachment = []){

			$this->emailer->addRecipient($info['email']);

			$this->emailer->subject = $info['email_subject'];
			if(!empty($attachment)){
				$this->emailer->addAttachment($attachment['filename'],$attachment['newname'],$attachment['mime']);
			}
			$this->emailer->addMessage($info['message']);
			return $this->emailer->send();
	}

	public function defaultMesgDir($info,  $directory_path = ''){
		
		$this->emailer->addRecipient($info['email']);

		$this->emailer->subject = $info['email_subject'];
		if(!empty($directory_path)){
			$this->emailer->addAttachmentDir($directory_path);
		}
		$this->emailer->addMessage($info['message']);
		return $this->emailer->send();
}
// This function accept 7 array length , 
	private function chargeSms($info){
		$this->db->trans_start();
		$usersystem = User::getSystemUser();
		$wallet = Wallet::findByName('SmsCharge');
		$pass = Transaction::transfer($info['user'],$info['wallet'],$usersystem->id,$wallet->id,$info['value'],'',FALSE);
		//$pass = Transaction::debit($info['user'],$info['wallet'],$usersystem->id,$wallet->id,$info['value'],'',$info['description'],$info['user'],FALSE);
		if($pass){
			$result = $this->sms->sendOTP($info['mobile_number'],$info['message']);
			if($result){
				$this->db->trans_commit();
				return $result;
			}else{
				$this->db->trans_rollback();
				return $result;
			}
		}else{
			$this->db->trans_rollback();
			return $pass;
		}
	}

	public function freeSms($info){
		return  $this->sms->sendOTP($info['mobile_number'],$info['message']);
	}

	public static function bulk_transfer_auto_create_user($username, $defaultPassHash = NULL){
		$auto_user = new static();
		$clean_phone_no ='';
		$dts= [];
		$users = []; 
		if(preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/', $username)){
            $clean_phone_no = $auto_user->sms->cleanPhoneNumber($username);
			$dts['mobile_number'] = $clean_phone_no;
			$user = User::findOne(['mobile_number'=>$clean_phone_no]);
			if(is_null($user)){
				$dts['password'] = $defaultPassHash;
				$dts['group_id'] = 4;
				$dts['status'] = 1;
				
				$user = new User;
				$user->setAttributes($dts);
				if(!$user->save()){
					$users['process']  = false;
				}
				$users['status']  = WIPTransaction::MESSAGE_NEW;
				$users['process']  = true;
			}else{
				$users['process']  = true;
				$users['status']  = WIPTransaction::MESSAGE_OLD;
			}
			$users['id']  = $user->id;
		}else{
			$users['process']  = false;
		}
		
		return $users;
    }
    
//This function create subscriber user and return object, only accept mobile number
public function auto_create_user($username, $defaultPassHash = NULL){
		$data ='';
		$key= '';
		$dts= [];
		if(preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/', $username)){
            $data = $this->sms->cleanPhoneNumber($username);
			$dts['mobile_number'] = $data;
		}else{
			throw new Exception('Incorrect phone number');
		}
		$user = User::findOne(['mobile_number'=>$data]);
		if(is_null($user)){
			if(!is_null($defaultPassHash)){
				$dts['password'] = $defaultPassHash;
			} else {
				$pwd = $this->autoGeneratorPwd(8);
				$dts['password'] = password_hash($pwd, PASSWORD_DEFAULT);
			}
			// get the id from group table instead of passing the id direct
            $dts['group_id'] = 4;
			$dts['status'] = 1;
			$dts['created_at']   = date('Y-m-d H:i:s');
			//$dts['updated_at']   = date('Y-m-d H:i:s');
			$user = new User;
			$user->setAttributes($dts);
			if(!$user->save()){
				return NULL;
			}
		}
		return $user;
    }
    
    public function otpgen(){
		return random_int(100000, 9999999);	
	}

	public  function otpGenerator($length = 10) {
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return strtoupper($randomString);
	}
	
	public  function numericGenerator($length = 10) {
		$characters = '1234567890';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
    }
    
    public  function autoGeneratorPwd($length = 10) {
		$characters = 'ab1cd2e!f3gh4jk5m@n6op7qrs8tuv#w9xyzAB1CD2EF$3GH4IJ5KL?6MN7OPQ8RST9UVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public static function voucherPinGenerator($length = 15){
		return (strtoupper(substr(md5(uniqid().mt_rand(1000,9999)), 0, $length)));
	}


	public function usernameVerification($value,$error_key = ''){
		// phone return true while email return false
		if(preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/', $value)) {
			// $phone is valid
			return true;
		}else if(filter_var($value, FILTER_VALIDATE_EMAIL)){
			// $email is valid
			return false;
		}else{
			if(empty($error_key)){
				throw new UserException('Wrong format Email Or Phone');
			}
			header("Content-type:application/json");
			exit(json_encode([$error_key => 'Wrong format Email Or Phone']));
		}
	}

	public function isMobileNumber($value){
		// phone return true while email return false
		if(preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/', $value)) {
			// $phone is valid
			return true;
		}else{
			return false;
		}
	}

	  //$values = array('71','seco','thi');
	  //echo generate($values);

	  public static function execInBackground($cmd) {
		  $ci =& get_instance();
		  $logPath = $ci->config->config['log_path'].'log-'.date('Y-m-d').'-background-wrkr.log';
		  log_message('info', 'execInBackground log path ==> '.$logPath);
		  if (!file_exists($logPath)) {
			  touch($logPath);
		  }
		if (substr(php_uname(), 0, 7) == "Windows"){ 
			$handle = popen("start /B ". $cmd . " > $logPath 2>&1", "r");
			pclose($handle);
		} 
		else { 
			$read =	exec($cmd . " > /dev/null 2>&1 &");
			log_message('info',  $cmd);
			log_message('debug', $read);   
		} 
	} 
	
	public static function get_object_of_array($list,$value,$place_holder){
		
        return array_search($value, array_column($list, $place_holder));
	}
	
	private static function object_to_array($objects){
		$data = [];
		foreach($objects as $object){
			$data[] = (array)$object;
		}
		return $data;
	}

	public static function daysBetween($start_date,$end_date) {
		try{
			return (int)date_diff(
				date_create($start_date),  
				date_create($end_date)
			)->format('%R%a');
		}catch(Exceptions $ex){
			throw $ex;
		}
	}


	public static function encryptedMessage($decryptedMessage = ''){
		date_default_timezone_set('UTC');
		if(empty( $decryptedMessage)){
			throw new Exception('Token secret value is empty');
		}
      	$decryptedMethod = "AES-256-CBC";
      	$secret = SECRET;  //must be 32 char length
      	$iv = substr($secret, 0, 16);

     // $encryptedMessage = 'HSkWIGMLlRCXEYwvsSEmsw==';
      $encryptedMessage = openssl_encrypt($decryptedMessage, $decryptedMethod, $secret,0,$iv);

      return $encryptedMessage;
	}

	public static function decryptedMessage($encryptedMessage = ''){
		date_default_timezone_set('UTC');
		if(empty( $encryptedMessage)){
			throw new Exception('Token secret value is empty');
		}
      	$encryptionMethod = "AES-256-CBC";
      	$secret = SECRET;  //must be 32 char length
      	$iv = substr($secret, 0, 16);

     // $encryptedMessage = 'HSkWIGMLlRCXEYwvsSEmsw==';
      $decryptedMessage = openssl_decrypt($encryptedMessage, $encryptionMethod, $secret,0,$iv);

      return $decryptedMessage;
	}

	public static function generateActivationCode()
   {
       return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
       // 32 bits for "time_low"
       mt_rand(0, 0xffff), mt_rand(0, 0xffff),
       // 16 bits for "time_mid"
       mt_rand(0, 0xffff),
       // 16 bits for "time_hi_and_version",
       // four most significant bits holds version number 4
       mt_rand(0, 0x0fff) | 0x4000,
       // 16 bits, 8 bits for "clk_seq_hi_res",
       // 8 bits for "clk_seq_low",
       // two most significant bits holds zero and one for variant DCE1.1
       mt_rand(0, 0x3fff) | 0x8000,
       // 48 bits for "node"
       mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
       );
   }


   public static function verifyRecaptcha($recaptchaResponse_input){

		$recaptchaResponse = trim($recaptchaResponse_input);
        //$userIp=$this->input->ip_address();
        $secret = RE_CAPTCHA_SECRET_KEY;
   
        //$url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;
        $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse;
 
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch);       
		$status= json_decode($output, true);
		log_message('info', 'Recaptcha Response ==> '.print_r($status, true));
  		return ($status['success'])? true :false;
   }



}