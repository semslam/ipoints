<?php

/*
  # Title: Extended ActiveRecord base class for CRUD operations in CodeIgniter 3 (For lazy programmers like myself)
  # Author: Peter-Smart
  # Version: 1.0.0
  # Since: 2017-08-10
  # Client: FisshBONE & LESTR
  # Contributors: ['Ibrahim Olanrewaju']
  */


require_once(__DIR__ . '/DB_Helper.php');
class MY_Model extends CI_Model{

    const HAS_ONE = 1;
    const HAS_MANY = 3;
    const BELONGS_TO = 2;
    const DEFAULT_PRIMARY_KEY = 'id';
    protected $pdo;
    public $isNew = true;
    private $_errors = [];
    private $_primary_key;
    public $attributes = [];
    private $_relations = [];

    public $query_array = [];
    public $is_count = true;
    public static $result_count = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->setRelations();
        $this->_initTableSchema();
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function getDb(){
        return DB_Helper::getDb();
    }

    public static function startDbTransaction(){
        DB_Helper::getDb()->trans_start();
    }

    public static function endDbTransaction($commit = true){
        if($commit){
            DB_Helper::getDb()->trans_commit();
        } else {
            DB_Helper::getDb()->trans_rollback();
        }
    }

    public function prepareAttributes()
    {
        $schema = $this->getTableSchema();
        foreach($schema['Fields'] as $column){
            if(isset($this->$column)){
                $this->attributes[$column] = $this->$column;
            } else {
                $this->attributes[$column] = $schema['Default'][$column];
            }
        }
        if($this->isNew){
            unset($this->attributes[$this->_primary_key]);
        }
    }

    public function unsafeAttributes()
    {
        return [];
    }

    public static function getPrimarykey()
    {
      throw new Exception('You must specify the primary key for this Active record class. Please create a static method in your class which returns the primary column');
    }

    public static function getTableName()
    {
      throw new Exception('Database table for this Active record class is not set correctly. Please create a static method in your class which returns the table name');
    }

    public function setAttributes(Array $attributes)
    {
        $schema = $this->getTableSchema();
        foreach($schema['Fields'] as $column){
            if(isset($attributes[$column])){
                $this->attributes[$column] = $attributes[$column];
                $this->$column = $attributes[$column];
            }  else {
                $this->attributes[$column] = $schema['Default'][$column];
                if(!isset($this->$column)){
                    $this->$column = $schema['Default'][$column];
                }
            }
        }
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getSafeAttributes()
    {
        $attributes = $this->attributes;
        $unsafe_attributes = $this->unsafeAttributes();
        foreach($unsafe_attributes as $unsafe){
           unset($attributes[$unsafe]);
        }
        return $attributes;
    }

    private function _initTableSchema(){
        DB_Helper::initTableSchema(STATIC::getTableName());
        $this->_primary_key = STATIC::getPrimarykey();
    }

    private function getTableSchema()
    {
        return DB_Helper::getTableSchema(STATIC::getTableName());
    }

    private function setRelations()
    {
        $relations = $this->relations();
        if(!empty($relations)){
            $this->_relations = array_keys($relations);
        }
    }

    public function relations()
    {
        return [];
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function beforeSave()
    {
        return true;
    }
    
    public function afterSave()
    {
        return true;
    }

    public function beforeDelete()
    {
        return true;
    }

    public function afterDelete()
    {
        return true;
    }

    public function save()
    {
        $this->beforeSave();
        $this->prepareAttributes();
        try{
            if($this->isNew){
                $this->setAttributes($this->attributes);
                if($this->db->insert(STATIC::getTableName(), $this->attributes)){
                    $pk = $this->_primary_key;
                    $this->$pk = $this->db->insert_id();
                    $this->attributes[$this->_primary_key] = $this->db->insert_id();
                     return $this->afterSave();
                }
                $this->_errors[] = $this->db->error();
                //$this->_errors[] = $this->db->errors();
                return false;
            } else {
                if($this->db->update(STATIC::getTableName(), $this->attributes, array($this->_primary_key=>$this->attributes[$this->_primary_key]))){
                    return $this->afterSave();
                }
                return false;
            }
        }
        catch(Throwable $e){
            log_message('error','My_Model Class Save function error===>'.$e->getMessage());
            return false;
        }
    }

    public function delete()
    {
        $this->beforeDelete();
        $this->db->where([$this->_primary_key=>$this->attributes[$this->_primary_key]]);
        if($this->db->delete(STATIC::getTableName())){
            $this->afterDelete();
            return true;
        }
        return false;
    }

    public function refresh()
    {
        if(isset($this->_primary_key) && !empty($this->_primary_key) && !$this->isNew){
            $where[$this->_primary_key] = $this->_primary_key;
            $this->db->where($where);
            $result = $this->db->get(STATIC::getTableName())->result_array();
            if(!empty($result)){
                $this->isNew = false;
                $this->setAttributes($result[0]);
            }
        }
    }

    public static function getActiveQuery(){
        require_once(__DIR__ . '/ActiveQuery.php');
        return new ActiveQuery(STATIC::class);
    }

    public static function findByPK(int $id)
    {
        $where[STATIC::getPrimarykey()] = $id;
        return STATIC::find($where)->one();
    }

    public static function findOne($where = [], array $options = [])
    {
        return STATIC::find($where,$options)->one();
    }


    public static function isExist(array $where = [])
    {
        $instance = new static();
        $instance->db->where($where);
        $instance->db->limit(1);
        $result = $instance->db->get($instance->getTableName())->result_array();
        if(!empty($result)){
            return true;
        }
        return false;
    }

    public static function isExistwithValue(array $where = [])
    {
        $instance = new static();
        $instance->db->where($where);
        $instance->db->limit(1);
        $result = $instance->db->get($instance->getTableName())->row();
        if(!empty($result)){
            return $result;
        }
        return $result;
    }

    public static function findAll($where = [], array $options = []){
        return STATIC::find($where,$options)->all();
    }

    public static function findAndThrow($where = [],$options = []){
        return STATIC::find($where,$options)->throwIfNotExists()->setOptions($options);
    }

    public static function find($where = [],array $options = [])
    {
        return STATIC::getActiveQuery()
                ->where($where)
                ->asInstance()
                ->setOptions($options);
    }

    public static function insert(array $data, $batch = false){
        $verb = $batch?'insert_batch':'insert';
        return DB_Helper::getDb()->$verb(STATIC::getTableName(),$data);
    }

    public static function updateByPk(int $id, array $data){
        $where[STATIC::getPrimarykey()] = $id;
        return STATIC::update($where,$data);
    }

    public static function update(array $where, array $data){
        return DB_Helper::getDb()->update(STATIC::getTableName(),$data,$where);
    }

    public static function count(array $where = [])
    {
        return DB_Helper::getDb()->where($where)->count_all_results(STATIC::getTableName());
    }

    public function with($relation)
    {
        $this->$relation = $this->loadRelation($relation);
        return $this;
    }

    protected function loadRelation($relation,$options = []){
        try{
            if(in_array($relation,$this->_relations)){
                $link = $this->relations()[$relation];
                $this->load->model($link[0]);
                switch($link[2]){
                    case 0:
                        $primary = $link[1];
                        $where[$link[3]] = $this->$primary;
                        return $link[0]::findOne($where);
                    break;
                    case 1;
                        $primary = $link[1];
                        $where[$link[3]] = $this->$primary;
                        return $link[0]::findOne($where);
                    break;
                    case 2;
                    $primary = $link[3];
                    $where[$link[1]] = $this->$primary;
                    return $link[0]::findOne($where);
                    break;
                    case 3:
                        $primary = $link[1];
                        $where[$link[3]] = $this->$primary;
                        return $link[0]::find($where)->all();
                    break;
                    default:
                        throw new Exception('Unknown relationship type');
                }
            } else {
                throw new Exception('Relation '.$relation.' is not configured');
            }
        } 
        catch(Exception $e) {
            throw $e;
        }
    }

    protected function transformToPubnubData(array $data)
    {
        if (empty($data)) {
            return $data;
        }
        
        $mdata = [];

        $cols = array_keys($data[0]['data']);
        $titles = array_column($data, 'title');
        array_unshift($titles, 'x1');
        $mdata[] = $titles;
        
        foreach ($cols as $col) {
            $marr_cols = array_column($data, 'data');
            $rd = array_column($marr_cols, $col);
            array_unshift($rd, $col);
            $mdata[] = $rd;
        }

        return $mdata;
    }

    public function pivotData(array $data, $x='period', $y='platform', $dataCol='total_cost')
    {
        $mPivot = [];
        $colsIndx = [];
        
        $cols = array_column($data, $y);
        $arr = array_unique(array_column($data, $y));
        $columnsDef = array_fill_keys(array_values($arr), 0);

        array_map(function($val) use (&$mPivot, &$colsIndx, $x, $y, $dataCol, $columnsDef) {
            if (empty(array_keys(array_column($mPivot, 'title', 'title'), $val[$x]))) {
                $cnt = count($mPivot);
                $mPivot[] = ['title' => $val[$x], 'data' => $columnsDef];
                $colsIndx[$x] = $cnt;
            }
            
            $mPivot[$colsIndx[$x]]['data'][$val[$y]] = $val[$dataCol] ?? null;
        }, $data);
        
        return $this->transformToPubnubData($mPivot);
    }

}
