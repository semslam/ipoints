<?php

final class Apilib
{
  const SESSION_LIFESPAN = 60 * 5; // 5 mins
  private static $ci;

  public function __construct()
  {
    self::$ci =& get_instance();
    self::$ci->load->model("ApiKey");
  }

  public static function _confirmHTTPs()
  {
    // Check that communication is over secure channel
    if (! is_https()) {
      // throw new Exception("These APIs must be called over secure channel.");
    }
  }

  public static function _extractHTTPAuthorization()
  {
    $headers = self::$ci->input->request_headers(true); //apache_request_headers();

    $authorization = '';
    foreach ($headers as $key => $value) {
      if (strcasecmp(strtolower($key), 'authorization') == 0) {
        $authorization = $headers[$key];
      }
    }
    if (empty($authorization)) {
      return null;
    }
    //log_message("info", "Authorization: " . $authorization);
    $pieces = explode(" ", $authorization);
    if (strcasecmp($pieces[0], 'Bearer') != 0) {
      return null;
    }
    return [$pieces[1]??'', $pieces[2]??''];
  }
  
  public static function _getEndpoint () 
  {
    return self::$ci->router->method;
  }

  public static function _authorizeEndpointAccess($publicKey)
  {
    // Does client have privilege to access this endpoint?
    $accessObj = ApiKey::findByPublicKey($publicKey);
    if (empty($accessObj)) {
      log_message('debug', 'Authorization record not found for PBK ==> '. $publicKey);
      return null;
    }
    $endpoint = self::_getEndpoint();
    $authorizedEndpoints = explode(',', $accessObj->access_control);
    //log_message('debug', '__authorizeEndpointAccess: endpoint: '.$endpoint.'. accessObj ==> '.print_r([$authorizedEndpoints, $accessObj], true));
    if (in_array($endpoint, $authorizedEndpoints) || in_array("all", $authorizedEndpoints)) {
      return $accessObj;
    }
    return null;
  }

  public static function _decodePayload($jsonPayload)
  {
    $httpMethod = self::$ci->input->method();
    $payload = json_decode($jsonPayload, true);
    if (empty($payload) && strcasecmp($httpMethod, 'get') !== 0) {
      return null;
    }
    return $payload;
  }

  /*
  # This private method is used to confirm the integrity of the payload.
  # using SHA512.
  #
  # This method validates the signature to ensure the integrity of the payload.
  */
  public static function _validateSignature($privateKey, $signature='', $jsonPayload='')
  {
    $inputSignature = hash('sha512', $jsonPayload . $privateKey);
    log_message("info", 'Input Signature: ' . $inputSignature);
    log_message("info", 'Sent Signature: ' . $signature);
    return (strcasecmp($signature, $inputSignature) == 0);
  }

  public static function getSuccessResponse($msg=null, array $data=[])
  {
    return [
      'responseStatus' => 'success',
      'responseMessage' => $msg ?: 'Successful',
      'responseCode' => "00"
    ] + $data;
  }

  public static function getErrorResponse($msg='', $code="01")
  {
    return [
      'responseStatus' => 'error',
      'responseMessage' => $msg ?: 'Failed',
      'responseCode' => ($code == "00"||!is_numeric($code)) ? "01" : strval($code)
    ];
  }

  public static function getResponse(bool $responseType, $msg, $code=null)
  {
    if ($responseType) {
      return self::getSuccessResponse($msg);
    }
    return self::getErrorResponse($msg, $code);
  }

  /**
   * Attempt to retrieve customerPhone & customerPin
   */
  public static function _authorizeClient($accessToken, $userId, $appId)
  {
    try {
      if (empty($accessToken) || empty($userId)) {
        return false;
      }
      self::$ci->load->model('AccessToken');
      $token = AccessToken::validateToken($accessToken, $userId, $appId);
      log_message('info', '__authorizeClient ==>> accessToken = ' . $token['token']);
      if ($token === true) {
        return true;
      }
      elseif (is_null($token)) {
        throw new Exception('Access denied. Access Tokens could not be retrieved from the Database.');
      }
      // Ensure that accessToken has not expired.
      else {
        throw new Exception('Access denied. Invalid Access Token.');
      }
    } catch (Exception $ex) {
      throw $ex;
    }
  }

}
