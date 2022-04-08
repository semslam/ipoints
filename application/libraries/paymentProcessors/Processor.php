<?php

abstract class Processor 
{ 
    public static $name;
    public $ref;
    private static $ci;
    abstract public function verify($ref);
    public function constructor($ref = '')
    {
        if(!empty($ref)){
            $this->ref = $ref;
        }  
    }

    public static function getCi(){
        if(!SELF::$ci){
            SELF::$ci = &get_instance();
        }
        return SELF::$ci;
    }

    public function process(){
        $payment = $this->verify();
        if($payment->status){
            return $this->logPurchase($payment);
        }
    }

    private function getUserId($phone,$email = NULL){
    
    }

    private function logPurchase($payment){
        try {
            $payment = new PaymentPurchase();
            $payment->payment_ref = $payment->ref;
            $payment->user_id = $payment->user_id;
            $payment->product_id = $payment->product_id;
            $payment->amount = $payment->amount;
            $payment->quantity = $payment->quantity;
            $payment->wallet_id = $payment->wallet_id;
            $payment->payment_processor = $this->name;
            $payment->payment_processor_fees = $payment->fees;
            $payment->processing_status = PaymentPurchase::STATUS_PAYMENT_UNPROCESSED;
            $payment->description = $payment->description;
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

    public function post($url,$data)
    {
        try{
            $ch = curl_init($url);
            $headers = [];
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION,
                    function($curl, $header) use (&$headers)
                    {
                        $len = strlen($header);
                        $header = explode(':', $header, 2);
                        if (count($header) < 2) // ignore invalid headers
                        return $len;

                        $name = strtolower(trim($header[0]));
                        if (!array_key_exists($name, $headers))
                        $headers[$name] = [trim($header[1])];
                        else
                        $headers[$name][] = trim($header[1]);

                        return $len;
                    }
            );
            $ch = $this->beforePost($ch,$url,$data);
            $data = curl_exec($ch);
            if($data === false){
                throw new Exception(curl_error($ch));
            }
            $headers['status_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $response = [
                'headers'=>$headers,
                'body'=>$data
            ];
            curl_close ($ch);
            return $response;   
        } catch(Exception $ex){
            throw $ex;
        }
    }

    public function get($url)
    {
        $ch = curl_init($url);
        try{
            $headers = [];
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION,
                    function($curl, $header) use (&$headers)
                    {
                        $len = strlen($header);
                        $header = explode(':', $header, 2);
                        if (count($header) < 2) // ignore invalid headers
                        return $len;

                        $name = strtolower(trim($header[0]));
                        if (!array_key_exists($name, $headers))
                        $headers[$name] = [trim($header[1])];
                        else
                        $headers[$name][] = trim($header[1]);

                        return $len;
                    }
            );
            $ch = $this->beforeGet($ch,$url);
            $data = curl_exec($ch);
            if($data === false){
                throw new Exception(curl_error($ch));
            }
            $headers['status_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $response = [
                'headers'=>$headers,
                'body'=>$data
            ];
            curl_close ($ch);
            log_message('debug', $data);
            return $response;   
        } catch(Exception $ex){
            curl_close ($ch);
            throw $ex;
        }
    }
    
    public function beforePost($ch,$url,$data)
    {
        return $ch;
    }

    public function beforeGet($ch,$url)
    {
        return $ch;
    }
}