<?php

class DB_Helper {
    
    protected static $_table_columns = [];
    protected static $_primary_keys = [];
    protected $ci;
    private static $_db;
    private static $_db_loaded = false;
    
    public function __construct(){
        $this->ci =& get_instance();  
    }

    public static function getDb($new=false){
        if(!$new && SELF::$_db_loaded){
            return SELF::$_db;
        }
        $instance = new static;
        $instance->ci->load->database();
        $db =& $instance->ci->db;
        if(!$new){
            SELF::$_db =& $db;
            SELF::$_db_loaded = true;
        }
        return $db;
    }

    public static function getTableSchema($table_name, $force_new = false){
        if($force_new || empty(SELF::$_table_columns[$table_name])){
            SELF::_loadTableSchema($table_name);
        }
        return SELF::$_table_columns[$table_name];
    }

    public static function initTableSchema($table_name){
        $attributes = SELF::_loadTableSchema($table_name);
        foreach($attributes as $attribute){
            SELF::$_table_columns[$table_name]['Fields'][] = $attribute->Field;
            SELF::$_table_columns[$table_name]['Default'][$attribute->Field] = $attribute->Default;
        }
    } 

    private static function _loadTableSchema($table_name){
        $sql = 'SHOW COLUMNS FROM '.$table_name;
        return SELF::getDb()->query($sql)->result_object();
    }

    public static function setPrimaryKey(string $table_name,string $value){
        SELF::$_primary_keys[$table_name] = $value;
    } 

    public static function getPrimaryKey(string $table_name){
        if(isset(SELF::$_primary_keys[$table_name])){
            return SELF::$_primary_keys[$table_name];
        }
        throw new Exception('Primary key for this table has not been set by a call to getTableSchema');
    }
}
