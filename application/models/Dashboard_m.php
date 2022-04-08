<?php

class Dashboard_m extends CI_Model {   

    function __construct()
    {
        parent::__construct();
        $this->load->library('datagrid');
    }

    public $query_array = [];
    public $is_count = true;
    public static $result_count = null;

    /**
     * Get Group by ID
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

    public function numberofusersbygroup(){
        $this->db->from('users u'); 
        $this->db->select('u.id, g.group_name');
        $this->db->where('u.status', 1);
        $this->db->join('groups as g', 'g.id = u.group_id', 'left');
		return $this->db->get()->result();
    }
    
    public function get_count_of_each_user(){
        $this->db->from('users u'); 
        $this->db->select(' g.group_name, COUNT(*) as number');
        $this->db->where('u.status', 1);
        $this->db->join('groups as g', 'g.id = u.group_id', 'inner');
        $this->db->group_by('g.group_name'); 
		return $this->db->get()->result();
    }

    public function getGroupUsersByWallet(){
        $this->db->from('user_balance ub'); 
        $this->db->select('w.name,COUNT(*) users, SUM(ub.balance) balance');
        $this->db->where('w.can_user_inherit', 1);
        $this->db->join('wallets as w', 'w.id = ub.wallet_id', 'inner');
        $this->db->group_by('w.id'); 
		return $this->db->get()->result();
    }

    public function getUsersBySubscribeServices(){
        $this->db->from('services_log sl'); 
        $this->db->select('p.product_name,COUNT(*) users, u.business_name as provider_name');
        // $this->db->where('w.can_user_inherit', 1);
        $this->db->join('products as p', 'p.id = sl.product_id', 'inner');
        $this->db->join('users as u', 'u.id = p.provider_id', 'left');
        $this->db->group_by('p.id'); 
		return $this->db->get()->result();
    }

    public function getNewUser(){
        $data['end_date'] = date('Y-m-d'); 
        $data['start_date'] = date('Y-m-d', strtotime('today - 14 days'));
        $query = $this->db->from('users u')
        ->select('COUNT(*) as count')
        ->where('u.status', 1)
        ->where('u.group_id', 4)
        ->where("u.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'",null,false)->get();
		return $query->row();
    }

    public static function getUsers(PDO $db, $data, $isExport=false){
            
            $status = (isset($data['status']))?$data['status'] :''; 
            $where = [];
            if(!(empty($data['start_date']) && empty($data['end_date']))){
                $where[] ="  u.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
            }if(!empty($data['kyc_status'])){
                if($data['kyc_status']=='complete'){
                    $where[] =" IF(u.name IS NULL OR u.name = '', 'empty', u.name) AND IF(u.birth_date IS NULL OR u.birth_date = '', 'empty', u.birth_date) AND IF(u.lga IS NULL OR u.lga = '', 'empty', u.lga) AND IF(u.email IS NULL OR u.email = '', 'empty',u.email) AND IF(u.next_of_kin IS NULL OR u.next_of_kin = '', 'empty', u.next_of_kin) AND IF(u.next_of_kin_phone IS NULL OR u.next_of_kin_phone = '', 'empty', u.next_of_kin_phone)";
                }elseif($data['kyc_status']=='none-complete'){
                    $where[] =' isNull(u.name) AND isNull(u.birth_date) AND isNull(u.lga) AND isNull(u.email) AND isNull(u.next_of_kin) AND isNull(u.next_of_kin_phone)';
                }
            }if(!empty($data['states'])){
                $where[] ='u.states ='.(int)$data['states'];
            }if( $status != null ||  $status != ''){
                    $where[] ='u.status ='.(int)$status;
            }if(!empty($data['id'])){
                $where[] ='u.id = '.(int)$data['id'];
            }

            $where = $where ? ' AND '.implode(' AND ', $where) : '';
            $countWhere = $where;

            $where = $where .= (!$isExport)?" ORDER BY u.created_at DESC LIMIT ".$data['limit']." OFFSET ".$data['offset']:"";
            
            $fromAndJoin = " FROM `users` `u`
            LEFT JOIN `groups` as `g` ON `g`.`id` = `u`.`group_id`
            LEFT JOIN `local_govts` as `l` ON `l`.`id` = `u`.`lga` 
            LEFT JOIN `state_tbl` as `s` ON `s`.`state_id` = `u`.`states` WHERE `u`.`group_id` = 4
             ";
             
            try{
                $query = " SELECT `u`.`name`, `u`.`gender`, `u`.`email`, `u`.`mobile_number`, `u`.`created_at`, `u`.`birth_date`, `u`.`address`, `s`.`state_name` as `state`, `l`.`name` as `lga`, `u`.`next_of_kin`, `u`.`next_of_kin_phone`, `g`.`group_name`
                {$fromAndJoin} {$where} ";
                if ($isExport) {
                    return $query;
                }

                $countQuery = " SELECT COUNT(*) AS allCount {$fromAndJoin} {$countWhere}";
                if(true){
                        //var_dump($countQuery);
                    $stmt= $db->query($countQuery);
                    SELF::$result_count = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                $stmt = $db->query($query);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $ex)
            {
                throw $ex;
            }
    }

    public static function getServicesProduct(PDO $db, $data, $isExport=false){        
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  sl.purchase_date  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['product'])){
            $where[] ='p.id = '.(int)$data['product'];
        }if(!(empty($data['fitter']) && empty($data['value']))){
            $where [] =' sl.value '. $data['fitter'].' '.(int)$data['value'];
        }if(!empty($data['period'])){
            $where[] ='sl.cover_period = '.(int)$data['period'];
        }if(!empty($data['id'])){
            $where[] ='u.id = '.(int)$data['id'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $countWhere = $where;

        $where = $where .= (!$isExport)?" ORDER BY p.created_at DESC LIMIT ".$data['limit']." OFFSET ".$data['offset']:""; 
        
        $fromAndJoin = " FROM `services_log` `sl`
        INNER JOIN `users` as `u` ON `u`.`id` = `sl`.`user_id`
        INNER JOIN `products` as `p` ON `p`.`id` = `sl`.`product_id`
             ";
        try{
            $query = " SELECT sl.*,`u`.`name`, `u`.`mobile_number`, p.product_name {$fromAndJoin} {$where} ";
            if ($isExport) {
                return $query;
            }

            $countQuery = " SELECT COUNT(*) AS allCount {$fromAndJoin} {$countWhere}";
            if(true){
                    //var_dump($countQuery);
                $stmt= $db->query($countQuery);
                SELF::$result_count = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            //var_dump('<pre>',$query);exit;
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
}

    public function getNotCompleteKYC(){
        $query = $this->db->from('users u')
        ->select('COUNT(*) count')
        ->where('u.status', 1)
        ->where('u.group_id', 4)
        ->where('isNull(u.name) AND isNull(u.birth_date) AND isNull(u.lga) AND isNull(u.email) AND isNull(u.next_of_kin) AND isNull(u.next_of_kin_phone)',null,false)
        ->get();
        return $query->row();
    }
    
    public function get_products(){
        $this->db->from('products p'); 
        $this->db->select('p.*, u.business_name as provider_name');
        $this->db->join('users as u', 'u.id = p.provider_id', 'left');
        return $this->db->get()->result();
    }

    public function get_ipoints(){
        $query = $this->db->from('payment_purchases pp')
						->select('SUM(pp.quantity) as ipoints, SUM(pp.amount) as amount')
						->get();
		return $query->row();
    }

    public function get_wallet($id){
        $query = $this->db->from('services_log sl')
        ->select('sl.amount,pw.product_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name, sl.cover_period, sl.next_due_date')
        ->where('u.status', 1)
        ->where('sl.user_id', $id)
        ->join('products_wallets as pw', 'pw.id = sl.product_wallet_id', 'left')
        ->join('users as u', 'u.id = sl.user_id', 'left')
		->get();
		return $query->row();
    }

    public function get_referrer($referrer){
        $query = $this->db->from('services_log sl');
        $this->db->select('sl.value,w.name AS product_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name, u.created_at');
        $this->db->where('u.status', 1);
        $this->db->where('w.can_user_inherit',1);
        $this->db->where('u.referrer', $referrer);
        $this->db->join('wallets as w', 'w.id = sl.product_wallet_id', 'left');
        $this->db->join('users as u', 'u.id = sl.user_id', 'left');
        return $this->db->get()->result();
    }

    public function getUserService($id){
        $query = $this->db->from('services_log sl');
        $this->db->select('sl.*,p.product_name, up.business_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name, p.description');
        $this->db->where('u.status', 1);
        $this->db->where('sl.user_id', $id);
        // $this->db->like('purchase_date', '%' . date('Y') . '%');
        $this->db->join('products as p', 'p.id = sl.product_id', 'left');
        $this->db->join('users as up', 'up.id = p.provider_id', 'left');
        $this->db->join('users as u', 'u.id = sl.user_id', 'left');
        return $this->db->get()->result();
    }

    public function getAllUserBalance($id){
        $query = $this->db->from('user_balance ub')
        ->select('SUM(ub.balance) as total_balance')
        ->where('u.status', 1)
        ->where('ub.user_id', $id)
        ->join('users as u', 'u.id = ub.user_id', 'left')
        ->get();
		return $query->row();
    }

    // limit by last 50 row
    public function getWalletTransferInfo($id){
        $query = $this->db->from('transactions t');
        $this->db->select('t.*,w.name AS product_name,
        if(length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name,
        if(length(ur.name), ur.name, if(length(ur.business_name), ur.business_name, if(length(ur.mobile_number), ur.mobile_number, ur.email))) name_receiver,
        if(length(us.name), us.name, if(length(us.business_name), us.business_name, if(length(us.mobile_number), us.mobile_number, us.email))) name_sender');
        $this->db->where('u.status', 1);
        $this->db->where('t.user_id', $id);
        $this->db->where('w.can_user_inherit',1);
        $this->db->order_by("t.created_at", "asc");
        $this->db->limit(50);
        $this->db->join('wallets as w', 'w.id = t.wallet_id', 'left');
        $this->db->join('users as u', 'u.id = t.user_id', 'left');
        $this->db->join('users as ur', 'ur.id = t.receiver_id', 'left');
        $this->db->join('users as us', 'us.id = t.sender_id', 'left');
        return $this->db->get()->result();
    }
	

    private function usernameVerification($value){
		// phone return true while email return false
		if(preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/', $value)) {
			// $phone is valid
			return 'PHONE';
		}else if(filter_var($value, FILTER_VALIDATE_EMAIL)){
			// $email is valid
			return 'EMAIL';
		}else{
			return 'ERROR';
		}
    }

    public function userWalletsBalance($id){
        $query = $this->db->from('user_balance wb');
        $this->db->select('wb.*,w.name as product_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name');
        $this->db->where('u.status', 1);
        $this->db->where('wb.user_id', $id);
        $this->db->join('wallets as w', 'w.id = wb.wallet_id', 'left');
        $this->db->join('users as u', 'u.id = wb.user_id', 'left');
        return $this->db->get()->result();
    }

    // public function getActorsGroup(){
    //     $query = $this->db->from('groups g');
    //     $this->db->select('g.id,g.group_name');
    //     $this->db->where('id NOT IN (1,2)',null, false);
    //     return $this->db->get()->result();
    // }

    public function getProducts(){
        $query = $this->db->from('products p');
        $this->db->select('p.id,p.product_name as name');
        // $this->db->where('w.can_user_inherit',1);
        return $this->db->get()->result();
    }

    public function getPeriod(){
        $query = $this->db->from('services_log sl');
        $this->db->distinct('sl.cover_period');
        $this->db->select('sl.cover_period');
        return $this->db->get()->result();
    }
    
    public function insert_log($data){
        $this->db->insert('activities_log', $data);
		return true;
    }

}