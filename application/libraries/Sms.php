<?php

class Sms
{
  const
  INFOBIP_API_KEY = "06d8e810143d60306d83cb978934e83c-c43d065e-6fd7-4f3c-afb3-f1218af5054f",
  INFOBIP_USERNAME = "FishBone",
  INFOBIP_PASSWORD = "//F&L//",
  INFOBIP_SEND_SMS_URL = "http://api2.infobip.com/api/sendsms/plain?user={username}&password={password}&type=longsms&sender={sender}&gsm={recipient}&smstext={message}&appid={msgid}",
  INFOBIP_CHECK_BALANCE_URL = "http://api.infobip.com/api/command?username={username}&password={password}&cmd=CREDITS",
  //ROUTE_SMS_URL = "http://smsplus4.routesms.com:8080/bulksms/bulksms?username=rubyngn&password=1EsmFOCw&type=0&dlr=1&destination={recipient}&source={sender}&message={message}",
  //ROUTE_SMS_URL = "http://103.16.101.158:8080/bulksms/bulksms?username=celd-messenger&password=//CeLD//&type=0&dlr=1&destination={recipient}&source={sender}&message={message}",
  ROUTE_SMS_URL_OLD = "http://ngnr.connectbind.com/bulksms/bulksms?username=celd-messenger&password=//CeLD//&type=0&dlr=1&destination={recipient}&source={sender}&message={message}",
  ROUTE_USERNAME = SMS_USERNAME,
  ROUTE_PASSWORD = SMS_PASSWORD,
  ROUTE_SMS_URL = " http://ngnr.connectbind.com:8080/bulksms/bulksms?username={username}&password={password}&type=0&dlr=1&destination={recipient}&source={sender}&message={message}",
  ROUTE_CHECK_BALANCE_URL = "https://ngn.rmlconnect.net/CreditCheck/checkcredits?username={username}&password={password}",
  //ROUTE_SMS_URLNEW = "http://ngnr.connectbind.com:8080/bulksms/bulksms?username=uicinnovations&password=HdnGYFlR&type=0&dlr=1&destination={recipient}&source={sender}&message={message}",
  SMS_PAGE = 160,
  SMS_SENDER_ID = "iPoints",
  NIGERIA_COUNTRY_CODE = "234";

  private $ci;

  public function __construct()
  {
    $this->ci =& get_instance();
  }


  /*This method sends OTP to the phone number provided and updates the user record */
  public function sendOTP($phoneNo, $msg = ""){
    try{
      
      //Validate phone number
      if ($this->validatePhoneNumber($phoneNo)){
        $phoneNo = $this->cleanPhoneNumber($phoneNo);
      }

      $URL = str_replace(
        array("{username}", "{password}", "{recipient}","{sender}", "{message}"),
        array(
          self::ROUTE_USERNAME,
          urlencode(self::ROUTE_PASSWORD),
          $phoneNo,
          self::SMS_SENDER_ID,
          urlencode($msg)
        ),
        self::ROUTE_SMS_URL
      );
    
          $now = date("Y-m-d H:i:s");
          $resp = @file_get_contents(trim($URL));
        //$resp = "1701|2348094227050";
        $result = explode ("|",$resp);
        $response =(!is_bool($resp))?($result[0]== 1701)? true: false : $resp;
        if (!$response){
          log_message("error", $phoneNo." Failed to send SMS ==> ".$resp." ==> ".$msg." ==>URL body==> ".$URL);
          throw new Exception("SMS Response:: ". $resp);
         // return false;
        } else if($response){
            //log response
          log_message("INFO", $phoneNo." Successful to sent SMS ==> ".$resp." ==> ".$msg);
          return true;
        }else{
          log_message("error", $phoneNo." Failed to send SMS ==> ".$resp." ==> ".$msg." ==>URL body==> ".$URL);
          throw new Exception("SMS Response:: ". $resp);
        }
          
    } catch (Exception $ex){
      log_message("error", $ex->getMessage());
      throw $ex;
    }
  }

  /*This method sends OTP to the phone number provided and updates the user record */
  public function sendOTPInfoBip($phoneNo, $msg = ""){
    try{
      
      //Validate phone number
      if ($this->validatePhoneNumber($phoneNo)){
        $phoneNo = $this->cleanPhoneNumber($phoneNo);
      }

      $msgId = $phoneNo . "-" . time(). "-" . rand(1000,9999);
      $URL = str_replace(
        array("{username}", "{password}", "{sender}", "{recipient}", "{message}", "{msgid}"),
        array(
          self::INFOBIP_USERNAME,
          urlencode(self::INFOBIP_PASSWORD),
          self::SMS_SENDER_ID,
          $phoneNo,
          urlencode($msg),
          $msgId
        ),
        //self::INFOBIP_SEND_SMS_URL
        self::ROUTE_SMS_URL
      );
      //First check that SMS balance is sufficient
      //$this->checkSMSBalance() == 0)
      if (true){
        //update user table with send OTP
        //$this->ci->useraccountmodel->updatePhoneVerificationStatus($phoneNo, $otp, PHONE_NOT_VERIFIED))
        if (true){
          $now = date("Y-m-d H:i:s");
          $resp = @file_get_contents($URL);
          if (! $resp){
            log_message("error", $phoneNo." Failed to send SMS ==> ".$resp." ==> ".$msg);
           
            return false;
          } else{
            //log response
            log_message("error", $phoneNo." Successful to sent SMS ==> ".$resp." ==> ".$msg);
            return true;
          }
          
        } else{
          throw new Exception("Error sending SMS. Please try again later.");
        }
      } else{
        throw new Exception("SMS balance is insufficient.");
      }
    } catch (Exception $ex){
      log_message("error", $ex->getMessage());
      throw $ex;
    }
  }

  public function validatePhoneNumber($phoneNo)
    { 
      try
      {
        if(!preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/',
        $phoneNo))
        {
          throw new Exception($phoneNo . ' is not a valid Nigerian Mobile Phone Number.');
        }
      } catch (Exception $ex)
      {
        throw $ex;
      }
	    // If the phone number format is ok, then return true
      return true;
    }

    public function cleanPhoneNumber($phoneNo){
	     $phone = ltrim($phoneNo, '+');
	     $phone = ltrim($phone, '234');
	     $phone = ltrim($phone, '0');

       $newPhoneNumberFormat = '234' . $phone;

       return $newPhoneNumberFormat;
	  }

  public function appendCountryCode($ccode, $phoneNo)
  {
    try
    {
      if (strlen($phoneNo) > 13)
      {
        throw new Exception("Phone number longer than 13 digits.");
      }
      if (strlen($phoneNo) < 10)
      {
        throw new Exception("Phone number less than 10 digits.");
      }
      if (substr($phoneNo, 0, 3) != self::NIGERIA_COUNTRY_CODE)
      {
        $phoneNo = $ccode . ltrim($phoneNo, "0");
      }
      return $phoneNo;
    } catch (Exception $ex)
    {
      throw $ex;
    }
  }
  public function checkSMSBalance(){
    try{
      $url = str_replace(
        array("{username}", "{password}"),
        array(
          self::INFOBIP_USERNAME,
          urlencode(self::INFOBIP_PASSWORD)
        ),
        //self::INFOBIP_CHECK_BALANCE_URL
        self::ROUTE_CHECK_BALANCE_URL
      );
      //var_dump($url);
      $resp = @file_get_contents($url);
      log_message("info", $url . " ==>> " . $resp);

      return $resp;

    } catch (Exception $ex){
      log_message("error", $url . " ==>> ".$ex->getMessage());
      throw $ex;
    }
  }
}
?>
