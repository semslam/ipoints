<?php

class Product extends MY_Model 
{   

    public static function getTableName()
    {
        return 'products';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public function relations()
    {
        return [
            'wallet' => ['Wallet', 'id', SELF::HAS_ONE, 'product_id']
        ];
    }

    public static function getIpoint()
    {
        return self::findOne(['base_product_yn' => true]);
    }

    public function getWallet($createIfNotExists = true){
        if(isset($this->wallet) && !empty($this->wallet)){
            return $this->wallet;
        }
        $wallet = $this->loadRelation('wallet');
        if(!is_null($wallet)){
            $this->wallet = $wallet;
            return $wallet;
        }
        if($createIfNotExists){
            $this->load->model('Wallet');
            $wallet = new Wallet;
            $wallet->name = $this->product_name;
            $wallet->type = Wallet::TYPE_PRODUCT;
            $wallet->product_id = $this->id;
            $wallet->can_user_inherit = 1;
            if($wallet->save()){
                $this->wallet = $wallet;
                return $wallet;
            }
            return NULL;
        }
        return NULL;
    }

    public static function getProducts(PDO $db, $data, $isExport=false){ 
        $where = [];

        if(!empty($data['id'])){
            $where[] =' p.id = '.(int)$data['id'];
        }if(!empty($data['is_insurance_prod'])){
            $where[] =' p.is_insurance_prod = '.(int)$data['is_insurance_prod'];
        }if(!empty($data['allowable_tenure'])){
            $where[] =' p.allowable_tenure = '.(int)$data['allowable_tenure'];
        }if(!empty($data['provider'])){
            $where[] =' `p`.`provider_id` = '.(int)$data['provider'];
        }
        $where = $where ? ' WHERE '.implode(' AND ', $where) : ''; 
        try{
            $query = " SELECT p.id, CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `provider_name`,
            p.product_name, p.price, 
            p.is_insurance_prod, p.base_product_yn, 
            p.allowable_tenure, p.is_group_prod,
            p.images, p.description, p.provider_id,
            p.created_at, p.updated_at, ul.id as levies_id, ul.value, w.id as coms_wallet_id, w.name as commission_wallet
            FROM `products` `p`
            LEFT JOIN `users` as `u` ON `u`.`id` = `p`.`provider_id` 
            LEFT JOIN `uici_levies` as `ul` ON `ul`.`id` = `p`.`charge_commission_id` 
            LEFT JOIN `wallets` as `w` ON `w`.`id` = `p`.`commission_wallet_id` 
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

    public static function getInsuranceProducts(){
    
        $insurance = new static();
		return $insurance->db->select('p.id, p.product_name, p.price, p.allowable_tenure')
			->from('products p')
			->join('wallets w', "p.id = w.product_id AND p.provider_id IS NOT NULL AND w.type = 'product'")
			->get()->result_object('Product');
    }

    public static function fetchProductDetails($product_id){
        //w.name AS commission_wallet,
        $product = new static();
        $query = $product->db->from('products p')
        ->select('p.id, p.product_name,
         p.price, p.allowable_tenure, 
         p.charge_commission_id, ul.value as commission_value,
         p.commission_wallet_id,
         p.provider_id ')
        ->join('uici_levies ul', 'ul.id = p.charge_commission_id', 'left')
        // ->join('wallets as w', 'w.id = p.commisson_wallet_id', 'left')
        ->where('p.id', $product_id)
        ->get();
       return $query->row();
    }

}