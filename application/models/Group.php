<?php

class Group extends MY_Model {

    const TYPE_MERCHANT = 'Merchant';
    const TYPE_SUBSCRIBER = 'Subscriber';
    const TYPE_UNDERWRITER = 'Underwriter';
    const TYPE_ADMINISTRATOR = 'Administrator';

    
    public static function getTableName()
    {
        return 'groups';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public static function findByName($name){
        return SELF::findOne(['name'=>$name]);
    }

}