<?php

class ApiKey extends MY_Model 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("Untils");
      
    }   
    public static function getTableName()
    {
        return 'api_keys';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public static function findByPublicKey($publicKey)
    {
        return self::findOne([ 'public_key' => $publicKey ]);
    }

    public function updateLastAccess()
    {
        $this->last_access = date('Y-m-d H:i:s');
        $this->save();
    }

    public static function findAppId($app_id){
        return SELF::findOne(['app_id'=>$app_id]);
     }

    public static function generatePublicKey(){
        $keygen = new static();
        return 'UICIPUBK-'.$keygen->untils->autoGeneratorPwd(50).'-F';
    }
    public static function generatePrivateKey(){
        $keygen = new static();
        $keygen->load->library("Untils");
        return 'UICIPRIK-'.$keygen->untils->autoGeneratorPwd(50).'-F';
    }


}