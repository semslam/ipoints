<?php

class Levy extends MY_Model {
    
    const USER_ANNUAL_SUBSCRIPTION = 'user_annual_subscription';

    public static function getTableName()
    {
        return 'uici_levies';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public static function findByName($name){
        return SELF::findOne(['name'=>$name]);
    }

    public static function getUserSubscriptionLevy(){
        $levy = SELF::findByName(SELF::USER_ANNUAL_SUBSCRIPTION);
        if(is_null($levy)){
            throw new InvalidConfigException(SELF::USER_ANNUAL_SUBSCRIPTION.' levy cannot be found!');
        }
        return $levy;
    }

}