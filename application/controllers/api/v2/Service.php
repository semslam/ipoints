<?php 
include_once __DIR__.'/../BaseController.php';

class Service extends BaseController 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('UserBalance', 'uBal');
    $this->load->library("Utilities");
    $this->load->library("pdolib");
    
  }
  // get iSaving Balance for subscriber only
  public function iSavingsBalance_post()
  {
    try {//openUrlAuth
      // if (!$this->_checkUserAccess()) {
      //   $this->response(
      //     Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
      //     self::HTTP_FORBIDDEN
      //   );
      // }

      $request = $this->payload['data'];
      
      $data['contact'] = $request['mobile_number'];
      // $data['password'] = $request['pin'];
      // $data['wallet_name'] = 'iSavings';
     
      // $userBalance = UserBalance::getWalletBalance('`u`.`mobile_number`',$data);

      // $balance = (empty($userBalance)) ? 0 : $userBalance->balance;

      $link = base_url('/OpenUrlAuth/isavingsBalance/'.$data['contact']);
      // return response
      $this->response(
        Apilib::getSuccessResponse('Success', ['open_url' => $link]),
        self::HTTP_OK
      );
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }

// get iPoint Balance for marchant only
  public function iPointBalance_post()
  {
    try {
      // if (!$this->_checkUserAccess()) {
      //   $this->response(
      //     Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
      //     self::HTTP_FORBIDDEN
      //   );
      // }

      $request = $this->payload['data'];
     
      $data['contact'] = $request['userEmail'];
      $data['password'] = $request['pin'];
      $data['wallet_name'] = 'iPoints';
      
      $userBalance = UserBalance::getWalletBalance('`u`.`email`',$data);

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

  public function getWalletBalanceByName_post(){
    try {
      // if (!$this->_checkUserAccess()) {
      //   $this->response(
      //     Apilib::getErrorResponse('Access forbidden! It seems you don\'t have an active session'),
      //     self::HTTP_FORBIDDEN
      //   );
      // }
      // do job here
      $request = $this->payload['data'];
      $data['contact'] = $request['mobile_number'];
      $data['password'] = $request['pin'];
      $data['wallet_name'] =  $request['wallet'];
     
      $userBalance = UserBalance::getWalletBalance('`u`.`mobile_number`',$data);

      $balance = (empty($userBalance)) ? 0 : $userBalance->balance;
      // return response
      $this->response(
        Apilib::getSuccessResponse('Success', ['wallet'=> $data['wallet_name'],'balance' => $balance,]),
        self::HTTP_OK
      );
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }


  public function bulkIPointTransfer_post()
  {
    try {
      // if (!$this->_checkUserAccess()) {
      //   $this->response(
      //     Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
      //     self::HTTP_FORBIDDEN
      //   );
      // }
      // do job here
      $request = $this->payload['data'];
      $success = WIPBulkTransferRequest::load($request,$verbose=true);
      if($success){
        $verbose = 1;
        $request_id = $request['request_id'];
       // Untils::execInBackground("php index.php cli Utilities process $request_id  $verbose ");
        //WIPBulkTransferRequest::process($request_id,$verbose);
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

// get all balances by phone number
  public function getBalancesByPhoneNo_post()
  {
    try {
      // if (!$this->_checkUserAccess()) {
      //   $this->response(
      //     Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
      //     self::HTTP_FORBIDDEN
      //   );
      // }
      $userPhoneNo = $this->payload['userPhoneNo'];
      $balanceList = $this->uBal->getUserBalanceList($userPhoneNo);
      // return response
      $this->response(
        Apilib::getSuccessResponse('Success',['balances' => $balanceList]),
        self::HTTP_OK
      );
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }

  public function walletList_get()
  {
    try {
      // if (!$this->_checkUserAccess()) {
      //   $this->response(
      //     Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
      //     self::HTTP_FORBIDDEN
      //   );
      // }

        
      $walletList = $this->uBal->getWalletList();
      // return response
      $this->response(
        Apilib::getSuccessResponse('Success',['wallets' => $walletList]),
        self::HTTP_OK
      );
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }

  // public function refundUserBalance_post()
  // {
  //   try {
  //     if (!$this->_checkUserAccess()) {
  //       $this->response(
  //         Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
  //         self::HTTP_FORBIDDEN
  //       );
  //     }
  //     $data['mobile_number'] = $this->payload['mobile_number'];
  //     $data['wallet_id'] = $this->payload['wallet_id'];
  //     $data['amount'] = $this->payload['amount'];
  //     $data['amount'] = $this->payload['amount'];
  //     $data['desc'] = $this->payload['desc'];
  //     $data['payment_reference'] = $this->payload['payment_reference'];
  //     $balance = $this->uBal->creditUserBalance($data);
  //     // return response
  //     if($response){
  //       // return response
  //       $this->response(
  //         Apilib::getSuccessResponse('Success',['response' => $response]),
  //         self::HTTP_OK
  //       );
  //     }else{
  //       $this->response(
  //         Apilib::getErrorResponse('Transaction Error'),
  //         self::HTTP_FORBIDDEN
  //       );
  //     }
  //   } catch (Exception $e) {
  //     $this->response(
  //       Apilib::getErrorResponse($e->getMessage()),
  //       self::HTTP_BAD_REQUEST
  //     );
  //   }
  // }

// debit subscriber while purchase item on fela
  public function debitiSavingsWallet_post()
  {
    try {
      // if (!$this->_checkUserAccess()) {
      //   $this->response(
      //     Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
      //     self::HTTP_FORBIDDEN
      //   );
      // }
      
      $request = $this->payload['data'];
      $data['vendor_id'] = $request['vendor_id'];
      $data['payment_reference'] = $request['reference'];
      $data['mobile_number'] = $request['mobile_number'];
      $data['password'] = $request['pin'];
      $data['amount'] = $request['amount'];
      $response = $this->uBal->debitUserBalance($data);
      if($response){
        // return response
        $this->response(
          Apilib::getSuccessResponse('Success',['response' => $response]),
          self::HTTP_OK
        );
      }else{
        $this->response(
          Apilib::getErrorResponse('Transaction Faild'),
          self::HTTP_FORBIDDEN
        );
      }
      //Insufitient Balance
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }
// withdrawer ipoint
  public function withdrawer_post(){
    try {
      // if (!$this->_checkUserAccess()) {
      //   $this->response(
      //     Apilib::getErrorResponse('Access forbidden! It seems you not have an active session'),
      //     self::HTTP_FORBIDDEN
      //   );
      // }
      
      $request = $this->payload['data'];
      // $data['client_id'] = $request['client_id'];
      $data['contact'] = $request['mobile_number'];
      // $data['reference'] = $request['reference'];
      // $data['bank_name'] = $request['bank_name'];
      // $data['account_number'] = $request['account_number'];
      // $data['amount'] = $request['amount'];
      //$data['password'] = $request['pin'];

      //get user info
      //generate token
      //generate wip request
      //encode date to json
      //generate a link with token
      // $dat ='';$key= '';
			// 	if($this->usernameVerification($data['contact'])){
			// 		$username = $this->sms->cleanPhoneNumber($username);	
			// 		$dat = $username;$key= 'mobile_number';
			// 	}else{
			// 		$dat = $username;$key= 'email';
			// 	}
			// 	$this->load->model('user_m');
      //   $attempt = $this->user_m->attempt($key,$dat);

      // $access_token  = $this->setUserAccessToken($attempt->id);
      //$access_token  = '9V5D4s3FNuAzQQ?RpPD4n8un4nnIxzx1kcQ39qHBD5xtKdyvkOd6g6V5RqybLMzHT';
      
      // check if the access token is empty
      //if empty insert new access token
       // $data['access_token']= $access_token;
        // insert the payload into wip_process
        // $request['access_token_id'] = $access_token->id;
        // $request['status'] = 'pending';
        // $request['type'] = 'withdrawer';
        // $request['data'] = json_encode($data);
        //if(WIPProcessor::accesptRequest($request)){

          $link = base_url('/OpenUrlAuth/iSavingsCashOut/'.$data['contact']);      
          //$response = UserBalance::withdrawerProcess($data);
         
            // return response
            $this->response(
              Apilib::getSuccessResponse('Success',['open_url' => $link]),
              self::HTTP_OK
            );
       // }
      
  
      //Insufitient Balance
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }  

}