<?php

class Setting_m extends MY_Model { 
  
  


  public static function getTableName()
  {
      return 'settings';
  }

  public static function getPrimaryKey()
  {
      return SELF::DEFAULT_PRIMARY_KEY;
  }


  public function beforeSave(){
      if($this->isNew){
          
      }
  }

  public static function findByReference($meta_key){
      return SELF::findOne(['meta_key'=>$meta_key]);
  }

  public static function findById($id){
      return SELF::findOne(['id'=>$id]);
  }

  public static function  getAllSettings(PDO $db){
      try{
          $query = "SELECT s.*, CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `author_name`
          FROM settings s
          LEFT JOIN `users` as `u` ON `u`.`id` = `s`.`author_by`  ";
          $stmt = $db->query($query);
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } catch (Exception $ex)
      {
          throw $ex;
      }
  }

  public static function  getSettingsById(PDO $db, $id){
      try{
          $query = "SELECT *
          FROM settings WHERE id = $id";
          $stmt = $db->query($query);
          return $stmt->fetch(PDO::FETCH_ASSOC);
      } catch (Exception $ex)
      {
          throw $ex;
      }
  }

    /**
     * Get List of Settings
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

    public function all()
    {
    	$settings = $this->db->get('settings')->result();
		  return $settings;
    }


    public function findSystemConstantByKey($key){
        
		$query = $this->db->from('settings s')
						->select('s.meta_key, s.meta_value')
						->where('s.meta_key', $key)
						->get();
			return $query->row();
    }
    

    public static function getAlliPointCharges(PDO $db){
       
        try{
            $query = " SELECT s.id, s.meta_key, s.meta_value
            FROM `settings` `s` WHERE `s`.`meta_key` = '".IINSURANCE_CHARGES_KEY."' OR `s`.`meta_key`= '".IPENSION_CHARGES_KEY."' OR `s`.`meta_key`= '".ISAVINGS_CHARGES_KEY."'";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $ex){
            throw $ex;
        }
    }

}