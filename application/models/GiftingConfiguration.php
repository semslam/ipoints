
<?php
//MY_Model
class GiftingConfiguration extends MY_Model { 

    public static function getTableName(){
            return 'gifting_config';
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


        public static function createAndUpdate($id,$data){
    
            if(empty($id)){
                $GiftingConfig = new GiftingConfiguration();
                $GiftingConfig->user_id = $data['user_id'];
                $GiftingConfig->wallet_id = $data['wallet_id'];
                $GiftingConfig->message_temp = $data['message_temp'];
                $GiftingConfig->process_type = $data['process_type'];
                $GiftingConfig->message_designate = $data['message_designate'];
                $GiftingConfig->send_message = $data['send_message'];
                $GiftingConfig->config_type = $data['config_type'];
                $GiftingConfig->modified_by = $data['modified_by'];
                $GiftingConfig->created_at = date('Y-m-d H:i:s');
                return  $GiftingConfig->save();
            }else
            $data['updated_at'] = date('Y-m-d H:i:s');
             return SELF::updateByPk($id,$data);    
        }

        public static function getGiftingConfigs($user_id){
            $config = new static();
            $config->db->from('gifting_config gc');
            $config->db->select('gc.*, w.name'); 
            $config->db->where('gc.user_id', $user_id);
            $config->db->join('wallets as w', 'w.id = gc.wallet_id', 'left');
            return $config->db->get()->result();
        }
}        