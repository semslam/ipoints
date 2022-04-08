<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/Format.php';

class OAuthProcess extends REST_Controller {
  private $ci;
  public function __construct(){
    parent::__construct();
     $this->load->model('UserBalance', 'uBal');
     $this->load->library("Utilities");
     $this->load->library("pdolib");
     $this->load->library("WIPProcessor");
     $this->load->library("utilities/Apilib");
     $this->ci =& get_instance();
  }

  // public function aouthpage_post(){
  //     try {
  //       $this->openUrlAuth();
          
  //       $walletList = $this->uBal->getWalletList();
  //       $request = $this->payload['data'];
  //       $data['contact'] = $request['mobile_number'];
  //       $data['password'] = $request['pin'];
  //       $data['wallet_name'] =  $request['wallet'];
  //       // return response
  //       $this->response(
  //         Apilib::getSuccessResponse('Success',['wallets' => $data]),
  //         self::HTTP_OK
  //       );
  //     } catch (Exception $e) {
  //       $this->response(
  //         Apilib::getErrorResponse($e->getMessage()),
  //         self::HTTP_BAD_REQUEST
  //       );
  //     }
  // }

  public function acceptRequest($data){
    $request =  new static();
    $request->db->insert('',$data);
  }

  
  public function tokens_post(){
    $password = $this->input->post('pin');
    $access_token = $this->input->post('access_token');
    // add colm type to access_token :: oauth/auth
    //1, validate the token is not empty
    //2, $access_token = query access_toke table where $token = token and type = oauth
    //3, $wip = query wip_process table where $access_token.id = wip_process.access_token_id
    //4, WipProcessor::complite($wip)

    //$response = WIPProcessor::complite($wip);
        $response = true;
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
  }

  public function token_get($token = null){
$this->load->library("WIPProcessor");
    $data['title'] = 'UICI Open Url Auth';
    $data['subview'] = 'login/authorize_api';
    $data['token'] = $token;
    
		$this->load->view('components/layout', $data);
  }






}
