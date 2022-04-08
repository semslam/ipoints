<?php

class WithdrawRequest extends MY_Model
{
    private static $_system;

    public static function getTableName(){
        return 'withdraw_request';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public function beforeSave(){
        $this->updated_at = date('Y-m-d H:i:s');
        if($this->isNew){
            $this->created_at = $this->updated_at;
        } 
    }
    
    public static function findById($id){
        return self::findOne(['id' => $id]);
    }


    public static function isSettleAutomatically(PDO $db,$wallet_id){ 
        try{
            $query = " SELECT vc.vendor_id,vc.settle_automatically ,ub.balance,ub.wallet_id  FROM `vendor_configuration` vc 
            LEFT JOIN user_balance ub ON ub.user_id = vc.vendor_id 
            WHERE ub.wallet_id = {$wallet_id} AND vc.settle_automatically = 1";

            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

    public static function getWithdrawerStatus($status){
        $withdrawstatus = new static();
        $query = $withdrawstatus->db->from('withdraw_request wr')
        ->select('SUM(wr.amount) as amount, COUNT(*) users')
        ->where('wr.status',$status)
        ->get();
        return $query->row();
    }

    public static function getWithdrawerBalance(){
        $withdrawstatus = new static();
        $query = $withdrawstatus->db->from('user_balance ub')
        ->select('ub.balance as amount')
        ->get();
        return $query->row();
    }

    public static function getWithdrawers(PDO $db, $data, $isExport=false){ 
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  `wr`.`created_at`  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['status'])){
            $where[] =" `wr`.`status` = '".$data['status']."'";
        }if(!empty($data['user_id'])){
            $where[] =' `ub`.`user_id` = '.(int)$data['user_id'];
        }if(!empty($data['amount'])){
            $where[] =' `wr`.`amount` = '.$data['amount'];
        }if(!empty($data['reference'])){
            $where[] =" `wr`.`transaction_reference` = '".$data['reference']."'";
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        
        $where = $where .= (!$isExport)?' ORDER BY created_at DESC LIMIT 150':'';
        try{
            $query = " SELECT wr.id,`u`.`id` as `user_id`, CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `user_name`, 
            COALESCE(`u`.`mobile_number`,`u`. `email`,'') as contact ,`wr`.`status`,
            `w`.`name` as wallet_name,`wr`.`transaction_reference`, `wr`.`bank_name`, `wr`.`account_number`,`wr`.`amount`, 
            `wr`.`created_at`, `wr`.`updated_at`, 
            CONCAT(COALESCE(`us`.`name`, `us`.`business_name`,'') , ' (',COALESCE(`us`.`mobile_number`,`us`. `email`,''), ')') `author_by` 
            FROM `withdraw_request` `wr`
            LEFT JOIN `user_balance` as `ub` ON `ub`.`id` = `wr`.`user_balance_id`
            LEFT JOIN `users` as `u` ON `u`.`id` = `ub`.`user_id`
            LEFT JOIN `wallets` as `w` ON `w`.`id` = `ub`.`wallet_id`
            LEFT JOIN `users` as `us` ON `us`.`id` = `wr`.`author_by`
             ".$where;
            
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