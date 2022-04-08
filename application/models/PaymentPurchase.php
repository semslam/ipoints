<?php

class PaymentPurchase extends MY_Model 
{   
    const STATUS_PAYMENT_PROCESSED = 1,STATUS_PAYMENT_VOID = 2,
        STATUS_PAYMENT_UNPROCESSED = 0;

    public static function getTableName()
    {
        return 'payment_purchases';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }


    public function beforeSave(){
        if($this->isNew){
            
        }
    }

    public static function findByReference($reference){
        return SELF::findOne(['reference'=>$reference]);
    }

    public static function findById($id){
        return SELF::findOne(['id'=>$id]);
    }

    public static function findByPreference(PDO $db, $data, $isExport=false){ 
        $status = isset($data['status']) ?''.$data['status'] :'';  
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  pp.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['reference'])){
            $where[] =" pp.payment_ref = '".$data['reference']."'";
        }if(!empty($data['wallet'])){
            $where[] =' pp.wallet_id = '.(int)$data['wallet'];
        }if(!empty($data['amount'])){
            $where[] =' pp.amount = '.(float)$data['amount'];
        }if(!empty($data['product'])){
            $where[] =' pp.product_id = '.(int)$data['product'];
        }
        if(!empty($data['payment_processor'])){
            $where[] =' ps.id = '.(int)$data['payment_processor'];
        }if($status != null ||  $status != ''){
            $where[] =' pp.processing_status = '.$data['status'];
        }if(!empty($data['id'])){
            $where[] =' `pp`.`user_id` = '.(int)$data['id'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        try{
            $query = " SELECT pp.id, CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `user_name`, 
            COALESCE(`u`.`mobile_number`,`u`. `email`,'') contact, 
            pp.amount,
            pp.quantity, pp.payment_ref, 
            w.name as wallet_name, p.product_name, 
            ps.name as payment_processor, 
            pp.payment_processor_fees, 
            pp.processing_status, 
            CONCAT(COALESCE(`ur`.`name`,`ur`.`business_name`,'') , ' (',COALESCE(`ur`.`mobile_number`, `ur`.`email`,''), ')') `requester_name`, 
            CONCAT(COALESCE(`au`.`name`,`au`.`business_name`,'') , ' (',COALESCE(`au`.`mobile_number`, `au`.`email`,''), ')') approver_name, 
            pp.description,
            pp.updated_at,
            pp.created_at
            FROM `payment_purchases` `pp`
            LEFT JOIN `wallets` as `w` ON `w`.`id` = `pp`.`wallet_id`
            LEFT JOIN `payment_processors` as `ps` ON `ps`.`id` = `pp`.`payment_processor`
            LEFT JOIN `products` as `p` ON `p`.`id` = `pp`.`product_id`
            LEFT JOIN `users` as `u` ON `u`.`id` = `pp`.`user_id`
            LEFT JOIN `users` as `ur` ON `ur`.`id` = `pp`.`requested_by`
            LEFT JOIN `users` as `au` ON `au`.`id` = `pp`.`approved_by` ".$where;
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

  public static function  getWallets(PDO $db){
        try{
            $query = " SELECT `id`, `name`
            FROM `wallets` WHERE can_user_inherit = 1  AND can_topup = 1 ";
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

    public static function  getService_group(PDO $db){
        try{
            $query = " SELECT `id`, `name`
            FROM `wallet_service_group`";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

    public static function  getCharge_commission(PDO $db){
        try{
            $query = " SELECT `id`, `name`, `value`
            FROM `uici_levies`";
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