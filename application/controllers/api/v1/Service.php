<?php 
include_once __DIR__.'/../BaseController.php';

class Service extends BaseController 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('UserBalance', 'uBal');
    $this->load->model('EspiTransaction');
    $this->load->library("Utilities");
    $this->load->library("pdolib");
    
  }
  // get iSaving Balance for subscriber only
  public function iSavingsBalance_post()
  {
    try {

      $request = $this->payload['data'];
      
      $data['contact'] = $request['mobile_number'];

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
      $request = $this->payload['data'];
      $data['payment_reference'] = $request['reference'];
      $data['mobile_number'] = $request['mobile_number'];
      $data['amount'] = $request['amount'];
      $data['description'] = $request['description'];
     // $data['request_date'] = $request['date'];
      $data['request_date'] = date("Y-m-d H:i:s", $request['date']);;
      log_message('info','DebitiSavingsWallet  Request====>'.print_r($data,true));
      $response = $this->uBal->isavingsDebit($data);
      if($response){
        // return response
        $this->response(
          Apilib::getSuccessResponse('Success',['response' => $response]),
          self::HTTP_OK
        );
      }else{
        $this->response(
          Apilib::getErrorResponse('Transaction Failed'),
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
          $request = $this->payload['data'];
          $data['contact'] = $request['mobile_number'];
           $link = base_url('/OpenUrlAuth/iSavingsCashOut/'.$data['contact']);      
            $this->response(
              Apilib::getSuccessResponse('Success',['open_url' => $link]),
              self::HTTP_OK
            );
      //Insufficient Balance
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }
  
  public function loadingVoucher_post(){
    try {
    
      
      $request = $this->payload['data'];
      $data['contact'] = $request['mobile_number'];
      $data['voucher'] = $request['voucher'];
      
          $user = User::findByPhoneNumber($request['mobile_number']);
          $ipinVoucher = IpinGeneration::ipinVoucherProcess($request['voucher'],$user->id);
          $message="";
			   if($ipinVoucher){
				    $result = IpinGeneration::getVoucherWithWalletAndValue($data['voucher']);
				    $message = "The voucher given by {$result->merchant_name} was successfully loaded with the value of {$result->ipin_value} {$result->wallet_name}, Your current {$result->wallet_name} balance is {$result->balance}";
			   }
         
            $this->response(
              Apilib::getSuccessResponse('Success',['result' => $message]),
              self::HTTP_OK
            );
      
  
      //Insufitient Balance
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }

  public function verification_post(){

    try {
        
        $request = $this->payload['data'];
        log_message('info','ESPI Verification Request =====>'.print_r($request, true));
        $data['ref'] = $request['reference'];
         if(!empty($data['ref'])){
          $result =  EspiTransaction::isRefExistReValue($data['ref']);
          log_message('info','ESPI Verification Response =====>'.print_r($result, true));
            $this->response(
              Apilib::getSuccessResponse('Success',['result' => $result]),
              self::HTTP_OK
          );
          }else{
            $this->response(
              Apilib::getErrorResponse("Payment reference can't be empty"),
              self::HTTP_FORBIDDEN
            );
          }
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }

  }

}