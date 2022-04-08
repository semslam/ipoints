<?php

class ProductBenefit extends MY_Model { 
    
    private static $_wallet_cache = [];
    public static function getTableName(){
        return 'product_benefit';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    } 

    public function beforeSave(){
        $this->updated_at = date('Y-m-d H:i:s');
        if($this->isNew){
            $this->created_at = date('Y-m-d H:i:s');
            $this->updated_at = $this->created_at;
        }
    }


    public static function getProductBenefit(PDO $db, $data, $isExport=false){ 
        $status = isset($data['status']) ?''.$data['status'] :'';  
        $where = [];
        if(!empty($data['amount'])){
            $where[] =" `pb`.`amount` = '".$data['amount']."'";
        }if($status != null ||  $status != ''){
            $where[] =' pb.status = '.$data['status'];
        }if(!empty($data['product'])){
            $where[] =' `pb`.`product_id` = '.(int)$data['product'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        try{
            $query = " SELECT `pb`.`id`, `pb`.`amount`,
           `pb`.`name`, `pb`.`product_id`,
            `pb`.`status`, 
            `pb`.`note`, `p`.`product_name`,
            `pb`.`updated_at`, `pb`.`created_at`
            FROM `product_benefit` `pb`
            LEFT JOIN `products` as `p` ON `p`.`id` = `pb`.`product_id` ".$where;
            if ($isExport) {
                return $query;
            }
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $ex){
            throw $ex;
        }
    }

}