<?php

require_once(__DIR__ . '/Processor.php');
require_once(__DIR__ . '/IPaymentData.php');

class Offline extends Processor 
{

    public function init()
    {
        $this->ci =& get_instance();
        $this->ci->load->model('OfflinePayment');
    }

    public function verify($ref)
    {
        $offlinePayment = OfflinePayment::findByReference($ref);
        $payment = new IPaymentData;
        $payment->processor = 'Offline';
        try {
            if(is_null($offlinePayment)){
                $payment->message = 'Transaction Reference for this payment method is invalid';
            } elseif(!$offlinePayment->is_approved){
                $payment->message = 'Transaction has not been approved';
            } else {
                $payment->status = true;
                $payment->customerPhone = $offlinePayment->merchant_phone;// issue have change merchant_phone to user_id
                $payment->amount = $offlinePayment->amount;
                $payment->raw = $offlinePayment->getSafeAttributes(); 
            }    
        } catch(Exception $ex){
            $payment->message = 'An error occured while verifying payment';
        }
        //return $response;
        return $payment;
    }

    public function markValue($ref){
        return true;
    }
}