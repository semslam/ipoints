<?php

class IPaymentData
{
    public $processor = '';
    public $txRef = '';
    public $status = false;
    public $message = '';
    public $userId = NULL;
    public $customerPhone;
    public $customerEmail;
    public $amount;
    public $fees = 0;
    public $currency = 'NGN';
    public $raw;
    public $desc = NULL;
    public $custom = [];

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function compare($var,$value)
    {
        if(property_exists($this,$var)){
            return $this->$var == $value;
        }
        return false;
    }
}