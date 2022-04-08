<?php
//MY_Model
class MessageTemplate_m extends MY_Model {   

    // function __construct()
    // {
    //     parent::__construct();
    //     $this->load->library('datagrid');
    // }


    public static function getTableName(){
        return 'message_template';
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

    /**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */


    public function attempt($key,$input)
	{
		$query = $this->db->from('message_template mt')
						->select('mt.*, g.group_name')
						->where($key, $input)
						->join('groups as g', 'g.id = mt.target_user', 'left')
						->get();

		return $query->row();
    }

    public function get_template($input){
		$query = $this->db->from('message_template mt')
						->select('mt.*')
						->where('message_channel', $input['message_channel'])
						->where('action', $input['action'])
						->get();

		return $query->row();
    }

    public function getTemplateByIds($ids){
        $this->db->from('message_template mt');
        $this->db->select('mt.*'); 
        $this->db->where('mt.id ='.$ids['idOne'].' OR mt.id ='.$ids['idTwo']);
		return $this->db->get()->result();
	}

    public function getTemplateAction(){
        $this->db->select('action'); 
		return $this->db->get("message_template")->result();
	}
    
    public function groups(){
        $this->db->select('id, group_name'); 
		return $this->db->get("groups")->result();
	}

    
public static function createAndUpdate($id,$data){
    
    if(empty($id)){
        $messageTemplate = new MessageTemplate_m();
        $messageTemplate->message_subject = !empty($data['message_subject'])?$data['message_subject']:'';
        $messageTemplate->message_template = $data['message_template'];
        $messageTemplate->action = $data['action'];
        $messageTemplate->message_channel = $data['message_channel'];
        $messageTemplate->charge = $data['charge'];
        $messageTemplate->attempt_no = $data['attempt_no'];
        $messageTemplate->priority = $data['priority'];
        $messageTemplate->last_updated_by = $data['last_updated_by'];
        $messageTemplate->created = date('Y-m-d H:i:s');
        return  $messageTemplate->save();
    }else
    $data['updated'] = date('Y-m-d H:i:s');
     return SELF::updateByPk($id,$data);    
}

    public static function fitterTemplateMessage(PDO $db,$data,$isExport = false){
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  mt.created  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['action'])){
            $where[] =" mt.action = '".$data['action']."'";
        }if(!empty($data['action'])){
            $where[] =" mt.message_channel = '".$data['message_channel']."'";
        }if(!empty($data['priority'])){
            $where[] =" mt.priority = '".$data['priority']."'";
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $where = $where .= (!$isExport)?' GROUP BY mt.id  LIMIT 150':' GROUP BY mt.id';

        try{
            $query = " SELECT * 
            FROM message_template mt ".$where;
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

}