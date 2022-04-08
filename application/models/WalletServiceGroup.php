<?php

class WalletServiceGroup extends MY_Model { 
    

    public static function getTableName(){
        return 'wallet_service_group';
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


    public static function getServiceGroup(PDO $db, $data, $isExport=false){ 
        $where = [];

        if(!empty($data['id'])){
            $where[] =' p.id = '.(int)$data['id'];
        }if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  `w`.`created_at`  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['description'])){
            $where[] =' p.description = '.(int)$data['description'];
        }
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        try{
            $query = " SELECT wsg.id,wsg.name , 
            wsg.description, wsg.created_at, 
            wsg.updated_at
            FROM `wallet_service_group` `wsg`
 
            ".$where;
            if($isExport) {
                return $query;
            }
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $ex){
            throw $ex;
        }
    }

}    