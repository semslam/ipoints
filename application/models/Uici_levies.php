<?php
class Uici_levies extends MY_Model {

    public function __construct()
	{
	  parent::__construct();
     
	}

    public static function getTableName(){
        return 'uici_levies';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    } 

    public function beforeSave(){
        $this->created_at = date('Y-m-d H:i:s');
    }


    public function getLevies() {

        $query = $this->db->query("SELECT `name`, `value` FROM `uici_levies`;");
        $output = array();

        foreach ($query->result_array() as $levy) {
            /** $output keys:
             *  charge_per_ipoint_sold,
             *  user_annual_subscription,
             *  sms_charge
            **/
            
            $output[$levy['name']] = $levy['value'];

        }

        return $output;
        
    }


    public static function getUiciLevieValue($name){
        $levie = new static();
        $query = $levie->db->from('uici_levies ul')
        ->select('ul.name, ul.value')
        ->where('ul.name', $name)
        ->get();
        return $query->row();
    }


    public static function getUiciLevies(PDO $db){
        try{
            $query = "SELECT `ul`.`id`, 
            `ul`.`name` , `ul`.`value`,`ul`.`description`, `ul`.`created_at`    
            FROM uici_levies ul";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }  
    }

    public static function getUsersUiciLevies($user_id){
        
        $levies = new static();
        $query = $levies->db->from('uici_levies ul');
        $levies->db->select("u.id as userId,uc.id,ul.levies_name,ul.id as uici_levies_id , ul.name,ul.value,ul.type,ul.value_type,IF(ISNULL(u.name) OR ISNULL(u.business_name), 'NULL', uc.status) `status`,ul.description");
        $levies->db->join('user_charges as uc', 'ul.id = uc.uici_levies', 'left');
        $levies->db->join('users as u', "(COALESCE(uc.user_id, u.id) = u.id) and u.id = {$user_id}", 'left');
        $levies->db->where("ul.type != 'system'");
        return $levies->db->get()->result();
    }

    // SELECT lu.*, IF(ISNULL(u.name) OR ISNULL(u.business_name), null, uc.status) `status`, u.name 
    // FROM `uici_levies` as lu
    // left join `user_charges` as uc on lu.id = uc.uici_levies
    // left join users u on ((COALESCE(uc.user_id, u.id), uc.uici_levies) = (u.id, uc.uici_levies)) and u.id = 2
    // where lu.type != 'system'

}