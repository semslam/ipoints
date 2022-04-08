<?php

class AnnualFee extends MY_Model 
{   
    const STATUS_PAYMENT_PROCESSED = 1,
        STATUS_PAYMENT_UNPROCESSED = 0;

    public static function getTableName()
    {
        return 'user_subscriptions';
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
        return SELF::findOne(['txn_reference'=>$reference]);
    }

    public static function findById($id){
        return SELF::findOne(['id'=>$id]);
    }


    public static function getAnnualFee(PDO $db, $data, $isExport=false){ 
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  us.start_date  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['txn_reference'])){
            $where[] =" us.txn_reference = '".$data['txn_reference']."'";
        }if(!empty($data['user_id'])){
            $where[] =' `us`.`user_id` = '.(int)$data['user_id'];
        }if(!empty($data['total_paid'])){
            $where[] =' `us`.`total_paid` = '.(float)$data['total_paid'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        $countWhere = $where;

        $where = $where .= (!$isExport)?" ORDER BY created_at DESC LIMIT ".$data['limit']." OFFSET ".$data['offset']:" ";
        
        $fromAndJoin = " FROM `user_subscriptions` `us`
        LEFT JOIN `users` as `u` ON `u`.`id` = `us`.`user_id`
             ";
        
        try{
            
            $query = " SELECT us.id, CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `user_name`, 
            COALESCE(`u`.`mobile_number`,`u`. `email`,'') contact,
            us.txn_reference, us.cost, 
            us.total_paid, us.is_complete , 
            us.is_latest, us.is_active, 
            us.start_date, us.end_date 
            {$fromAndJoin} {$where} ";

            if ($isExport) {
                return $query;
            }

            $countQuery = " SELECT COUNT(*) AS allCount {$fromAndJoin} {$countWhere} ORDER BY created_at";
            if(true){
                    
                $stmt= $db->query($countQuery);
                SELF::$result_count = $stmt->fetch(PDO::FETCH_ASSOC);
            }
           
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $ex){
            throw $ex;
        }
    }

// --1 select user balances where is grater than 70 or equal to 70 and wallet_id is 4(iSavings) or 5(iInsurance)
// --2 Check if the user have not paid from user_transcation table
// --3 Debit 60 ipoints out of any wallet of the user in user_balance
// --4 Credit the 60 ipoints in a Subscription wallet id 1 as the system wallet
// --5 Log the payment history in user_subscriptions 
// --6 Log the ipoints transfer in transactions table





}