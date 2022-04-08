<?php 
include_once __DIR__.'/../BaseController.php';

class Auth extends BaseController 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('UserBalance', 'uBal');
    $this->load->library("pdolib");
  }
  
  public function login_post()
  {
    return parent::login_post();
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


  public function iPointBalance_post()
  {
    try {
      if (!$this->_checkUserAccess()) {
        $this->response(
          Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
          self::HTTP_FORBIDDEN
        );
      }
      // do job here
      $userId = $this->payload['userId'];
      $userBalance = $this->uBal->findUserBalanceByUserIdAndWalletName($userId,'iPoints');

      $balance = (empty($userBalance)) ? 0 : $userBalance->balance;
      // return response
      $this->response(
        Apilib::getSuccessResponse('Success', ['balance' => $balance]),
        self::HTTP_OK
      );
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }

  public function userWalletBalance_post(){
    try {
      if (!$this->_checkUserAccess()) {
        $this->response(
          Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
          self::HTTP_FORBIDDEN
        );
      }
      // do job here
      $userId = $this->payload['userId'];
      $wallet = $this->payload['wallet'];
      $userBalance = $this->uBal->findUserBalanceByUserIdAndWalletName($userId,$wallet);

      $balance = (empty($userBalance)) ? 0 : $userBalance->balance;
      // return response
      $this->response(
        Apilib::getSuccessResponse('Success', ['wallet'=> $wallet,'balance' => $balance,]),
        self::HTTP_OK
      );
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }


  public function bockIPointTransfer_post()
  {
    try {
      if (!$this->_checkUserAccess()) {
        $this->response(
          Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
          self::HTTP_FORBIDDEN
        );
      }
      // do job here
      $request = $this->payload['body'];
      $success = WIPBulkTransferRequest::load($request,$verbose=true);
      if($success){
        $verbose = 1;
        $request_id = $request['request_id'];
        Untils::execInBackground("php index.php cli Utilities process $request_id  $verbose ");
      }
      // return response
      $this->response(
        Apilib::getSuccessResponse('Success',['response' => $success] ),
        self::HTTP_OK
      );
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }

}