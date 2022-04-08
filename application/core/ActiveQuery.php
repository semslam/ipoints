<?php

require_once(__DIR__ . '/DB_Helper.php');
class ActiveQuery
{
    const OPTION_RETURN_SINGLE = 'return_single';
    const OPTION_CREATE_INSTANCE = 'create_instance';
    const OPTION_THROW_IF_NOT_EXISTS = 'throw_exeption';
    const OPTION_EXCEPTION_CLASS = 'exception_class';
    const OPTION_EXCEPTION_MESSAGE = 'exception_message';

    private $_steps = [];
    private $_db;
    private $_class;
    private $_options;

    private static $_allowed_options = [
        SELF::OPTION_CREATE_INSTANCE,
        SELF::OPTION_RETURN_SINGLE,
        SELF::OPTION_THROW_IF_NOT_EXISTS,
        SELF::OPTION_EXCEPTION_CLASS,
        SELF::OPTION_EXCEPTION_MESSAGE
    ];

    public function __construct($class = NULL, $options = [])
    {
        if(!is_null($class)){
            $this->setClass($class);
        }
        if(is_array($options) && !empty($options)){
            $this->setOptions($options);
        }
        $this->_db = DB_Helper::getDb();
    }

    public function setClass($class){
        $this->_class = $class;
        return $this;
    }

    public function getClass(){
        return $this->_class;
    }

    public static function getAllowedOptions(){
        return SELF::$_allowed_options;
    }

    public function setOptions($options){
        if(!is_array($options)){
            throw new InvalidArgumentException('supplied argument must be an array of key value options');
        }
        foreach($options as $option=>$value){
            $this->addOption($option,$value);
        }
        return $this;
    }

    public function addOption($option,$value){
        if(!in_array($option,STATIC::getAllowedOptions())){
            throw new InvalidArgumentException("Invalid option '{$option}' passed");
        }
        $this->_options[$option] = $value;
        return $this;
    }

    public function getOption($option, $default = NULL){
        if(!in_array($option,STATIC::getAllowedOptions())){
            throw new InvalidArgumentException("Option '{$option}' does not exist");
        }
        if(isset($this->_options[$option])){
            return $this->_options[$option];
        }
        if(!is_null($default)){
            return $default;
        }
        throw new InvalidArgumentException("Option '{$option}' does not exist");
    }

    public function throwIfNotExists($exception_config = []){
        $this->addOption(SELF::OPTION_THROW_IF_NOT_EXISTS,true);
        if(!empty($exception_config['class'])){
            $this->addOption(SELF::OPTION_EXCEPTION_CLASS,$exception_config['class']);
        }
        if(!empty($exception_config['message'])){
            $this->addOption(SELF::OPTION_EXCEPTION_MESSAGE,$exception_config['message']);
        }
        return $this;
    }

    public function asArray(){
        return $this->addOption(SELF::OPTION_CREATE_INSTANCE,false);
    }

    public function asInstance(){
        return $this->addOption(SELF::OPTION_CREATE_INSTANCE,true);
    }

    private function setStep($step,$args){
        is_array($args) || $args = array($args);
        $this->_steps[] = [
            'method'=>$step,
            'args'=>$args
        ];
    }

    public function __call($method,$args){
        if(method_exists($this,$method)){
            call_user_func_array([$this,$method],$args);
            return $this;
        }
        if(method_exists($this->_db,$method)){
            $this->setStep($method,$args);
            return $this;
        }
        throw new Exception("Method '{$method}' does not exist on ActiveQuery or CodeIgniter's DB Object");
    }

    protected function assertOption($option,$value){
        return isset($this->_options[$option]) && ($this->_options[$option] === $value);
    }

    private function _prepareQuery(){
        foreach($this->_steps as $step){
            $value = call_user_func_array([$this->_db,$step['method']],$step['args']);
        }
        $this->_db->from($this->_class::getTableName());
        return $value;
    }

    public function all()
    {
        return $this->_run();
    }

    public function one(){
        $this->addOption(SELF::OPTION_RETURN_SINGLE,true);
        return $this->limit(1)->_run();
    }

    public function count(){
        $this->_prepareQuery();
        return $this->_db->count_all_results();
    }

    private function _run(){
        $this->_prepareQuery();
        return $this->prepareResults($this->_db->get()->result_array());
    }

    public function raw(){
        return $this->_prepareQuery();
    }

    private function prepareResults($results){
        $single = $this->assertOption(SELF::OPTION_RETURN_SINGLE,true);
        $create_instance = $this->assertOption(SELF::OPTION_CREATE_INSTANCE,true); 
        $throw_exception = $this->assertOption(SELF::OPTION_THROW_IF_NOT_EXISTS,true);
        if(!empty($results)){
            if(!$create_instance){
                return $single?$results[0]:$results;
            }
            $outputs = [];
            $class = $this->getClass();
            $instance = new $class;
            $instance->isNew = false;
            if($single){
                $instance->setAttributes($results[0]);
                return $instance;
            }
            foreach($results as $result){
                $new = clone $instance;
                $new->setAttributes($result);
                $outputs[] = $new;
            }
            unset($results);
            return $outputs;
        }
        if($throw_exception){
            $class = $this->getOption(SELF::OPTION_EXCEPTION_CLASS,'Exception');
            $message = $this->getOption(SELF::OPTION_EXCEPTION_MESSAGE,'Model not found');
            throw new $class($message);
        }
        return $single?NULL:[];
    }
}

class ModelNotFoundException extends Exception
{

}