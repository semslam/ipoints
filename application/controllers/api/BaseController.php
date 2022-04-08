<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'libraries/Format.php';

class BaseController extends REST_Controller 
{
  const API_VERSION = "1.0.0";
  protected $payload, $accessObj;

  public function __construct()
  {
    parent::__construct();
    // Load important libraries and models
    $this->load->library("utilities/Applib");
    $this->load->library('utilities/Apilib');
    $this->rest_format = Format::JSON_FORMAT;
  }

  public function _remap($method, $params = [])
  {
    try {
      $this->init();
      if(!$this->_checkEndpointExistence($method)){
        $this->_do404();
      }
      else {
        parent::_remap($method, $params);
      } 
    } catch (Exception $ex) {
      $this->response(
        Apilib::getErrorResponse($ex->getMessage()), 
        self::HTTP_UNAUTHORIZED
      );
    }
  }

  protected function init()
  {
    Apilib::_confirmHTTPs();
    $jsonPayload = file_get_contents("php://input");
    if (! empty($jsonPayload)) {
      $this->payload = Apilib::_decodePayload($jsonPayload);
      log_message('info','Payload received is....'.print_r($this->payload,true));
    }
    
    // Extract authorization
    $authorization = Apilib::_extractHTTPAuthorization();
    if (empty($authorization)) {
      throw new Exception("Authorization header is not set or invalid.");
    }
    $publicKey = $authorization[0];
    $signature = $authorization[1];

    // Initialize accessObj and authorize client has access to the endpoint.
    $accessObj = Apilib::_authorizeEndpointAccess($publicKey);
    if (empty($accessObj)) {
      throw new Exception('You are not authorized to access this endpoint.');
    }
    $this->accessObj = $accessObj;
    
    // Validate signature
    if (!Apilib::_validateSignature($this->accessObj->private_key, $authorization[1], $jsonPayload)) {
      throw new Exception("Incorrect signature.");
    }
  }

  protected function _checkEndpointExistence($endpoint)
  {
    $method = $this->input->method();
    if (!method_exists($this,"{$endpoint}_{$method}")) {
      return false;
    }
    return true;
  }

  /**
   * Validates session
   * @throws Exception
   */
  protected function _checkUserAccess() 
  {
    try {
      $validated = false;
      $userEmail = $this->payload['userEmail']??false;
      $userPin = $this->payload['userPin']??false;
      $userId = $this->payload['userId']??false;
      $accessToken = $this->payload['accessToken']??false;
      log_message('debug', print_r([$userId, $accessToken, $this->accessObj], true));
      if ($userId && $accessToken) {
        if (($validated = Apilib::_authorizeClient($accessToken, $userId, $this->accessObj->app_id))) {
          return true;
        }
      }
      // Attempt one-time-login
      if ($userEmail && $userPin) {
        $mdata = ['data' => [
          'userEmail' => $userEmail,
          'userPin' => $userPin
        ]];
        return !empty($this->authenticateSession($mdata));
      }
      if((!$accessToken && !$userId) || (!$userEmail && $userPin)) {
        throw new Exception("Required fields userId and accessToken (OR Phone number and pin for One-Time-Request) are missing or invalid.");
      }
      
      return $validated;
    } catch (Exception $e) {
      throw $e;
    }
  }

  protected function _checkDataExistence() 
  {
    $keys = func_get_args();
    $empty = [];
    foreach($keys as $key){
      if(empty($this->payload['data'][$key])){
        $empty[] = $key;
      }
    }
    if(!empty($empty)){
      throw new Exception(implode(' ,',$empty).' not provided');
    }
  }

  protected function _do404()
  {
    exit(
      $this->response(
        Apilib::getErrorResponse('Endpoint does not exist'), 
        self::HTTP_NOT_FOUND
      )
    );
  }

  public function getAPIVersion_get()
  {
    exit(
      $this->response(
        Apilib::getSuccessResponse(null, ["currentAPIVersion" => self::API_VERSION]), 
        self::HTTP_OK
      )
    );
  }

  /**
   * 
   */
  public function login_post()
  {
    try {
      $sessionData = $this->authenticateSession();
      // Create key for user
      $mappKey = $this->setUserAccessToken($sessionData['id']);
      $result = Apilib::getSuccessResponse('User authentication successful', [ 
        "accessToken" => $mappKey, 
        "userId" => $sessionData['id'] 
      ]);
      $this->response(
        $result, 
        self::HTTP_OK
      );
    } catch (Exception $ex) {
      $this->response(
        Apilib::getErrorResponse($ex->getMessage()),
        self::HTTP_FORBIDDEN
      );
    }
  }

  public function openUrlAuth(){
    try {
      $validated = false;
      $username = $this->payload['username']??false;
      $password = $this->payload['password']??false;
      $this->response(
        Apilib::getSuccessResponse('Success', ['url' =>base_url('openUrlAuth?token=30809dkj498dkjd'), 'expired' =>'23789738']),
        self::HTTP_OK
      );
      
    } catch (Exception $e) {
      $this->response(
        Apilib::getErrorResponse($e->getMessage()),
        self::HTTP_BAD_REQUEST
      );
    }
  }

  public function setUserAccessToken($userId){
    $mappKey = Applib::generateToken();
    $data = [
      'app_id' => $this->accessObj->app_id,
      'user_id' => $userId,
      'token' => $mappKey
    ];
    return AccessToken::createSessionToken($data) ? $mappKey : false;
  }

  /**
   * Session Manager Helper
   * @throws Exception
   */
  private function authenticateSession(array $payload=[])
  {
    $mPayload = $payload ?: $this->payload;
    $userEmail = $mPayload['data']['userEmail']??false;
    $userPin = $mPayload['data']['userPin']??false;
    if (! $userEmail || ! $userPin) {
      throw new Exception("Email Address and pin are required.");
    }
    //Valdiate phone number
    if (!Applib::validateEmail($userEmail)) {
      throw new Exception('Email Address is invalid');
    }
    //$phoneNo = Applib::cleanPhoneNumber($userEmail);
    $this->load->model("User");
    $data = User::authenticate($userEmail, $userPin);
    if (empty($data)) {
      throw new Exception('Authentication failed! That did not work, try again');
    }
    // Check that user account is active and not blocked.
    if ($data['status'] != USER_STATUS_ACTIVE) {
      throw new Exception("Your account is currently inactive. It might have been blocked");
    }
    return $data;
  }
}