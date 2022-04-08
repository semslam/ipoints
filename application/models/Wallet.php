<?php

class Wallet extends MY_Model { 
    
    const TYPE_PRODUCT = 'product';
    const TYPE_SYSTEM = 'system';
    const GENERIC_SUB_WALLET = 'Subscription';
    const GENERIC_INCOME_WALLET = 'iIncome';
    const GENERIC_IPOINTS_WALLET = 'iPoints';
    const GENERIC_ISAVINGS_WALLET = 'iSavings';
    private static $_wallet_cache = [];
    public static function getTableName(){
        return 'wallets';
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

    public static function findByName($name){
        if(!empty(SELF::$_wallet_cache[$name])){
            return SELF::$_wallet_cache[$name];
        }
        $wallet = SELF::findAndThrow(['name'=>$name])->one();
        if(!is_null($wallet)){
            SELF::$_wallet_cache[$name] = $wallet;
        }
        return $wallet;
    }

    public static function get_iSavings_wallet(){
        $wallet = new static();
        $query = $wallet->db->from('wallets w')
        ->select('w.id,w.name')
        ->where('w.name',I_SAVINGS)
        ->get();
		return $query->row();
    }

    public static function getMain(){
        return SELF::findByName(SELF::GENERIC_IPOINTS_WALLET);
    }

    public static function getForSubscription(){
        return SELF::findByName(SELF::GENERIC_SUB_WALLET);
    }

    public static function getForIncome(){
        return SELF::findByName(SELF::GENERIC_INCOME_WALLET);
    }

    public static function findByProductId($id){
        return SELF::findOne("product_id = {$id}");
    }

    public static function walletByName($wallet_name){
                    $wallet  = new static();
                    $query = $wallet->db->from('wallets w')
                    ->select('w.id, w.name')
                    ->where('w.name',$wallet_name)
                    ->get();
                    return $query->row();            
    }

    public static function walletById($wallet_id){
        $wallet  = new static();
        $query = $wallet->db->from('wallets w')
        ->select('w.id, w.name')
        ->where('w.id',$wallet_id)
        ->get();
        return $query->row();            
}

    //@to do
    public static function validateCrossWalletSpending($from,$to){
        if($from === $to){
            return true;
        }
        $from_wallet = SELF::findAndThrow(['id'=>$from],[
            'exception_class'=>'InvalidConfigException'
        ])->one();
        if($from_wallet->name == SELF::GENERIC_IPOINTS_WALLET){
            return true;
        }
        $to_wallet = SELF::findAndThrow(['id'=>$to],[
            'exception_class'=>'InvalidConfigException',
        ])->one();
        if($to_wallet->type == SELF::TYPE_SYSTEM){
            return true;
        }
        if($from_wallet->type == SELF::TYPE_SYSTEM){
            return true;
        }
        if($from_wallet->name == SELF::GENERIC_ISAVINGS_WALLET && $to_wallet->name !== SELF::GENERIC_IPOINTS_WALLET){
            return true;
        }
        //var_dump('Wallet Validate: ',$from,$to);
        throw new UserException('Invalid wallet action!');
    }

    public static function getAllWallets(PDO $db){
        
        try{
            $query = " SELECT `id`, `name`
            FROM `wallets` `w` WHERE `w`.`can_user_inherit` = 1";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $ex){
            throw $ex;
        }
    }


    public static function getWallets(PDO $db, $data, $isExport=false){ 

        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  `w`.`created_at`  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['name'])){
            $where[] =" `w`.`name` = '".$data['name']."'";
        }if(!empty($data['type'])){
            $where[] =" `w`.`type` = '".$data['type']."'";
        }if(!empty($data['product'])){
            $where[] =' `w`.`product_id` = '.(int)$data['product'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $where = $where .= (!$isExport)?' ORDER BY w.created_at DESC LIMIT 150':''; 
        try{
            $query = " SELECT `w`.`id`, `w`.`product_id`,
           `p`.`product_name`, `w`.`name`,
            `w`.`type`, `w`.`can_user_inherit`, 
            `w`.`can_topup`,`wg`.`name` as `service_group_name`, 
            `w`.`updated_at`, `w`.`created_at`
            FROM `wallets` `w`
            LEFT JOIN `products` as `p` ON `p`.`id` = `w`.`product_id`
            LEFT JOIN `wallet_service_group` as `wg` ON `wg`.`id` = `w`.`wsg_id`
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