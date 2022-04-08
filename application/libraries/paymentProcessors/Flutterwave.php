<?php
require_once __DIR__.'/Processor.php';

define("FLW_METHOD", "both");
define("FLW_COUNTRY", "NG");
define("FLW_CURRENCY", "NGN");
define("FLW_BUY_DESC", "iPoint Payment");;
define("FLW_GIFT_DESC", "iPoint Payment");
define("FLW_TITLE", "UICI Innovations Ltd");
define("REF_SEPARATOR", "_");
// define("FLW_LOGOURL", BASE_URL . '/assets/images/logo.png');

class Flutterwave extends Processor
{
  const PROCESSOR_ID = 1;
  private static $ci;

  public function __construct()
  {
    self::$ci =& get_instance();
    self::$ci->load->model('PaymentPurchase');
  }

  public function generatePaymentFingerprint($formData) 
  {
    $metaData = $this->validateData($formData);
    if (! $metaData) {
      throw new Exception('Required fields are invalid or missing');
    }
    $userId = $formData['user_id'];
    $identity = $formData['user_name']??'';
    $desc = ($formData['description']??'') ?: FLW_BUY_DESC;
    $desc .= '<br>User: <strong>'.$identity.'</strong>';

    $payload = [
      "PBFPubKey" => FLW_PUBLIC_KEY,
      "amount" => $this->amount,
      "customer_email" => $this->email,
      "payment_method" => FLW_METHOD,
      "country" => FLW_COUNTRY,
      "currency" => FLW_CURRENCY,
      "txref" => $this->generatePaymentRef($userId),
      "custom_description" => $desc,
      "custom_title" => FLW_TITLE,
      // "custom_logo" => FLW_LOGOURL,
    ];

    $payload['integrity_hash'] = $this->generateIntegrityHash($payload);
    $payload['meta'] = $this->generateMetadata($metaData);
    
    return $payload;
  }

  private function generatePaymentRef($userId)
  {
    if (! $userId) {
      throw new Exception('Failed to generate payment reference');
    }
    $now = time();
    return "FLW-$userId-$now";
  }

  // required metadata variables
  private function validateData($formData)
  {
    $metaDataProto = [
      'product_id' => null,
      'user_id' => null,
      'wallet_id' => null,
      'quantity' => null,
      'tenure' => null,
      'description' => null,
    ];
    $this->amount = round(($formData['amount']??0), 2);
    $this->email = $formData['email']??false;
    $isValid = $this->amount && $this->email;
    $data = array_intersect_key($formData, $metaDataProto);
    if (! $isValid || count($data) < count($metaDataProto)) {
      return false;
    }
    return $data;
  }

  private function generateIntegrityHash(array $data)
  {
    ksort($data);
    $valStr = implode('', $data);
    return hash('sha256', $valStr.FLW_PRIVATE_KEY);
  }

  private function generateMetadata(array $data)
  {
    foreach($data as $key => $val) {
      $metadata[] = [
        "metaname" => $key,
        "metavalue" => $val
      ];
    }
    return $metadata;
  }

  private function getMetadata(array $metadata)
  {
    $otherDetails = [];
    array_walk($metadata, function($meta) use (&$otherDetails) {
      $name = $meta['metaname'];
      $val = $meta['metavalue'];
      $otherDetails[$name] = $val;
    });
    return $otherDetails;
  }
  
  /**
   * @param $flwRef
   * @param $payAmount
   * @param $currency
   * @return PaymentPurchase
   */
  public function verifyPayment($flwRef, $payAmount, $currency): PaymentPurchase
  {
    try {
      $query = [
        "SECKEY" => FLW_PRIVATE_KEY,
        "flw_ref" => $flwRef,
        "normalize" => "1"
      ];

      $dataStr = json_encode($query);

      $ch = curl_init();
      $curlOpts = [
        CURLOPT_URL => FLW_VERIFY_URL,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $dataStr,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
          'Content-Type: application/json'
        ]
      ];

      curl_setopt_array($ch, $curlOpts);

      $raw = curl_exec($ch);

      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $header = substr($raw, 0, $header_size);
      $body = substr($raw, $header_size);

      curl_close($ch);

      $resp = json_decode($raw, true);
      log_message('info','verification data ==> '.print_r($resp,true));
      if (empty($resp) || empty($resp['data']) || strcasecmp($resp['status'], 'success')!==0) {
        throw new Exception('Invalid verification data');
      }
      $respData = $resp['data'];
      $isValidated = $this->validateResponse($respData, $payAmount, $currency);
      if ($isValidated) {
        $otherDetails = $this->getMetadata(($respData['meta']??[]));
        $userId = $otherDetails['user_id'];
        $paymentRef = $respData['tx_ref'];
        $processorFees = $respData['appfee'] ?? 0;
        $refId = $paymentRef.REF_SEPARATOR.$flwRef;
        $mdata = [
          'payment_ref' => $refId,
          'user_id' => $userId,
          'amount' => $payAmount,
          'payment_processor' => self::PROCESSOR_ID,
          'payment_processor_fees' => $processorFees
        ];
        $mdata += $otherDetails;
        $payment = PaymentPurchase::findOne(['payment_ref' => $refId]);
        if (! $payment) {
          $paymentPurchase = $this->logPurchase($mdata);
          if (! $paymentPurchase) {
            throw new Exception('Failed to process payment.');
          }
          return $paymentPurchase;
        }
        else {
          throw new Exception('Payment already verified');
        }
      }
      else {
        throw new Exception('Failed to validate payment verification data');
      }
    } catch (Exception $ex) {
      throw $ex;
    }
  }

  protected function validateResponse(array $resp, $payAmount, $currency)
  {
    $chargeResponse = $resp['flwMeta']['chargeResponse']??false;
    $chargeAmount = $resp['amount']??false;
    $chargeCurrency = $resp['transaction_currency']??false;

    // log details
    log_message('info','charge response is ==> '.$resp['flwMeta']['chargeResponse']);
    log_message('info','charge amount is ==> '.$resp['amount']);
    log_message('info','charge currencyy is ==> '.$resp['transaction_currency']);

    return ($chargeResponse == "00" || $chargeResponse == "0") && ($chargeAmount >= $payAmount)
            && ($chargeCurrency == $currency);
  }

  public function verify($ref){
    $payment = new IPaymentData;
    $payment->processor = 'Flutterwave';
    try {
        $data = [
            "SECKEY" => FLW_PRIVATE_KEY,
            "flw_ref" => $ref,
            "normalize" => "1"
        ];
        $request = $this->post(FLW_VERIFY_URL,$data);
        $headers = $request['headers'];
        $response = json_decode($request['body'],true);
        $payment->raw = $response;
        if($response['status'] == 'success'){
            if($response['data']['flwMeta']['chargeResponse'] == '00' || $response['data']['flwMeta']['chargeResponse'] == '0'){
                $payment->status = true;
                $payment->amount = $response['data']['amount'];
                $payment->fees = $response['data']['appfee'];
                $payment->currency = $response['data']['transaction_currency'];
                $payment->customerPhone = $response['data']['customer']['phone'];
                $payment->customerEmail = $response['data']['customer']['email'];
                foreach($response['data']['meta'] as $key){
                    $payment->custom[$key['metaname']] = $key['metavalue'];
                }
                if(empty($payment->customerPhone) && !empty($payment->custom['merchantPhoneNo'])){
                    $payment->customerPhone = $payment->custom['merchantPhoneNo'];
                }
            }
        } else {
            if(isset($response['message'])){
                $payment->message = $headers['status_code'].'  '.$response['message'];
            } else {
                $payment->message = $headers['status_code'].'  '.'Payment does not seem to be a successful one';
            }
        }
    } catch(Exception $ex){
        $payment->message = 'An error occured while verifying payment';
    }
    //return $response;
    return $payment;
}

public function beforePost($ch,$url,$data){
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
    return $ch;
}

  private function logPurchase(array $data)
  {
    try {
      $payment = new PaymentPurchase();
      $payment->payment_ref = $data['payment_ref'];
      $payment->user_id = $data['user_id'];
      $payment->product_id = $data['product_id']?:null;
      $payment->amount = $data['amount'];
      $payment->quantity = $data['quantity'];
      $payment->wallet_id = $data['wallet_id'];
      $payment->payment_processor = $data['payment_processor'];
      $payment->payment_processor_fees = $data['payment_processor_fees'];
      $payment->processing_status = PaymentPurchase::STATUS_PAYMENT_UNPROCESSED;
      $payment->description = $data['description'];
      $payment->created_at = date('Y-m-d H:i:s');
      
      if (! $payment->save()) {
        log_message('error', 'Failed save paymentpurchase ==> '.print_r($payment, true));
        throw new Exception('Failed to create payment purchase record');
      }
      return $payment;
    } catch (Exception $e) {
      log_message('error', $e->getMessage());
      throw $e;
    }
  }

}
