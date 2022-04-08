<?php

class User extends MY_Model
{
    private static $_system;
    public static function getTableName(){
        return 'users';
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


    public static function getAPIUsers(PDO $db){
       

        try{
            $query = "SELECT `u`.`id`, 
            CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `user_name`,`g`.`group_name`
            FROM users u
        LEFT JOIN `groups` as `g` ON `g`.`id` = `u`.`group_id`  WHERE  `g`.`group_name` = '".MERCHANT."' || `g`.`group_name` = '".VENDOR."' || `g`.`group_name` = '".UNDERWRITER."'";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }  
    }

    public static function getSystemUser(){
        if(!empty(SELF::$_system)){
            return SELF::$_system;
        }
        $system = SELF::findOne(['is_system'=>1]);
        if(is_null($system)){
            throw new InvalidConfigException('No system user has been configured');
        }
        SELF::$_system = $system;
        return $system;
    }

    public function getIdentity(){
        if(!empty($this->business_name)){
            return $this->business_name;
        }
        return $this->name;
    }

    public static function initWallet($user_id, $wallet_id){
        $user = SELF::findByPk($user_id);
        if(is_null($user)){
            return false;
        }
        return $user->createWallet($wallet_id);
    }
    
    public function createWallet($wallet_id){
        $where = ['user_id'=>$this->id,'wallet_id'=>$wallet_id];
        $user_balance = UserBalance::findOne($where);
        if(!is_null($user_balance)){
            return true;
        }
        $wallet = Wallet::findByPk($wallet_id);
        if(is_null($wallet)){
            return false;
        }
        $user_balance = new UserBalance;
        $user_balance->setAttributes($where);
        $user_balance->balance = 0;
        $user_balance->overdraft_limit = 0;
        return $user_balance->save();
    }

    public static function validateCrossUserTransfer($from, $to){
        if($from == $to){
            throw new UserException('You cannot transfer to yourself');
        }
        $from_group = SELF::fetchUserGroup($from);
        $to_group = SELF::fetchUserGroup($to);
        if(is_null($from_group) || is_null($to_group)){
            throw new UserException('Source or destination user not found');
        }
        if($from_group['group_name'] == Group::TYPE_MERCHANT || $from_group['group_name'] == Group::TYPE_ADMINISTRATOR){
            return true;
        }
        if($from_group['group_name'] == Group::TYPE_SUBSCRIBER && $to_group['group_name'] == Group::TYPE_MERCHANT){
            throw new UserException('Destination user is unable to receive this transfer');
        }
        return true;
    }

    public static function fetchUserGroup($user_id){
        return SELF::find(['users.id'=>$user_id])
                    ->select('groups.*')
                    ->join('groups','users.group_id = groups.id')
                    ->asArray()
                    ->one();
    }

    public static function fetchByGroupId($group_id){
        $vendor = new static();
		$query = $vendor->db->from('groups g')
						->select('g.id, g.group_name')
						->where('g.id', $group_id)
						->get();
		    return $query->row();
    }


    public function getGroups(){
        $query = $this->db->from('groups g');
        $this->db->select('g.id, g.group_name');
        return $this->db->get()->result();
    }


    public function getActiveSubscription(){
        $this->load->model('UserSubscription');
        return UserSubscription::findActive($this->id);
    }

    public static function authenticate($email, $userPin)
    {
        $user = self::findOne(['email' => $email]);
        if (empty($user)) {
            return false;
        }
        (get_instance())->load->library('utilities/Applib');
        
        if (!Applib::isPasswordCorrect($userPin, $user->password)) {
            return false;
        }
        return $user->getSafeAttributes();
    }
    
    /**
     * 
     */
    public static function getUsersWithSubscribeableBalances(QueryModifier $qmf, $start, $end, $productId, $hasValidKycs=false, $export=false)
    {
        $ci =& get_instance();
        $ci->load->library('utilities/Subscription');
        if ($export) {
            return Subscription::massProductSubscribeAndExportList($productId, $start, $end);
        }
        $sql = Subscription::getSubscribeableUsersSQL($productId, $start, $end, $hasValidKycs);
        if ($sql === false) {
            throw new Exception('ProductId, start and end date are required');
        }
        $qmf->compileQuery($sql)->getQueryString();
        return $qmf->getResult();
    }
    
    public static function findById($id){
        return self::findOne(['id' => $id]);
    }

    public static function findByPhoneNumber($mobile_number){
        return self::findOne(['mobile_number' => $mobile_number]);
    }

    public static function findByPhoneNumberOrEmail($contact){
        $user = new static();
       // $ci =& get_instance();
        $user->load->library('Untils');
        $user->load->library('Sms');
        if($user->untils->isMobileNumber($contact)){
            $username = $user->sms->cleanPhoneNumber($contact);
            return self::findOne(['mobile_number' => $username]);	
        }else{
            return self::findOne(['email' => $contact]);
        }
    }

    public static function findByEmail($email){
        return self::findOne(['email' => $email]);
    }

    public static function systemAccount(){
        return self::findOne(['is_system' => 1]);
    }

    public static function getVendor($user_id)
	{
        $vendor = new static();
		$query = $vendor->db->from('users u')
						->select('u.id, u.email')
						->where('u.id', $user_id)
						->get();
		    return $query->row();
    }
    
    public static function findBackEndUsers(PDO $db){ 
        try{
            $query = " SELECT `u`.`id`, 
           CONCAT(COALESCE(IF(`u`.`name` = '', NULL, `u`.`name`), `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `user_name`, 
            `u`.`email`, `u`.`status`,`u`.`is_system`, `g`.`group_name`, `u`.`created_at`,`u`.`updated_at`
            FROM `users` `u` 
        LEFT JOIN `groups` `g`  On `g`.`id` = `u`.`group_id`  WHERE `g`.`group_name` NOT IN ('".MERCHANT."', '".SUBSCRIBER."', '".UNDERWRITER."', '".PARTNER."')";
             //var_dump($query);exit;
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

    public static function findUserClient(PDO $db){ 
        try{
            $query = " SELECT `u`.`id`, 
            CONCAT(COALESCE(IF(`u`.`name` = '', NULL, `u`.`name`), `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `user_name`, 
            `u`.`email`, `u`.`status`, `g`.`group_name`, `u`.`created_at`,`u`.`updated_at`
            FROM `users` `u` 
        LEFT JOIN `groups` `g`  On `g`.`id` = `u`.`group_id`  WHERE `g`.`group_name` IN ('".MERCHANT."', '".VENDOR."', '".UNDERWRITER."', '".PARTNER."')";
             //var_dump($query);exit;
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }
}