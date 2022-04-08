<?php

class ReportSubscription extends MY_Model 
{   
   

    public static function getTableName()
    {
        return 'report_subscription';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }


    public function beforeSave(){
        if($this->isNew){
            
        }
    }

    public static function findByReference($status){
        return SELF::findOne(['status'=>$status]);
    }

    public static function findById($id){
        return SELF::findOne(['id'=>$id]);
    }


    public static function findByPreference(PDO $db, $data, $isExport=false){ 
       
        $status = isset($data['status']) ?''.$data['status'] :'';  
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  rs.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['dispatcher_type'])){
            $where[] =" rs.dispatcher_type = '".$data['dispatcher_type']."'";
        }if(!empty($data['report_type'])){
            $where[] =" rs.report_type = '".$data['report_type']."'";
        }if(!empty($data['frequency'])){
            $where[] =" rs.frequency = '".$data['frequency']."'";
        }if($status != null ||  $status != ''){
            $where[] =' rs.status = '.$data['status'];
        }if(!empty($data['user_id'])){
            $where[] =' `rs`.`user_id` = '.(int)$data['user_id'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        try{
            $query = " SELECT rs.id, 
            CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `user_name`, 
            if( length(rs.send_to_all), rs.send_to_all, u.email) email, 
            rs.frequency, rs.report_type,  rs.dispatcher_type, 
            rs.status, rs.created_at,rs.updated_at,
            CONCAT(COALESCE(`au`.`name`, `au`.`business_name`,'') , ' (',COALESCE(`au`.`mobile_number`,`au`. `email`,''), ')') `author_name`
            FROM report_subscription rs 
            Inner Join users u  On u.id = rs.user_id
            Inner Join users au  On au.id = rs.author_by ".$where;
            if ($isExport) {
                return $query;
            }
           
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

  public static function  getReportSubscription(PDO $db,$data){

        if(!empty($data['dispatcher_type'])){
            $where[] =" rs.dispatcher_type = '".$data['dispatcher_type']."'";
        }if(!empty($data['report_type'])){
            $where[] =" rs.report_type = '".$data['report_type']."'";
        }if(!empty($data['frequency'])){
            $where[] =" rs.frequency = '".$data['frequency']."'";
        }

        $where = $where ? ' WHERE rs.status = 1 AND u.status = 1 AND '.implode(' AND ', $where) : ''; 
        try{
            $query = " SELECT  if( length(rs.send_to_all), rs.send_to_all, u.email) email
            FROM `report_subscription` rs 
            Inner Join users u  On u.id = rs.user_id ".$where;
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }


    public static function  getProducts(PDO $db){
        try{
            $query = " SELECT `id`, `product_name`, price
            FROM `products` WHERE is_insurance_prod = 1";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }


    public static function  getPaymentProcessor(PDO $db){
        try{
            $query = " SELECT `id`, `name`
            FROM `payment_processors`";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

}