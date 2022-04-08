<?php

class User_m extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->library('datagrid');
	}

	/**
     * Check User Credentials
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */
	
	public function attempt($key,$input)
	{
		$query = $this->db->from('users u')
						 ->select('u.*, g.group_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name, s.state_name, l.name as local_govts_name, i.name as industry_name')
						//->select('u.*, g.group_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name')
						->join('groups as g', 'g.id = u.group_id', 'left')
						->join('state_tbl as s', 's.state_id = u.states', 'left')
						->join('local_govts as l', 'l.id = u.lga', 'left')
						->join('industry as i', 'i.id = u.industry', 'left')
						->where($key, $input)
						->where('u.status', 1)
						->get();
		
		return $query->row();
	}

	public function userexist($key,$input)
	{
		$query = $this->db->from('users u')
						->select('u.*')
						->where($key, $input)
						->get();

		return $query->row();
	}
	


	public function userinfo($key,$input){
		$query = $this->db->from('users u')
						->select('u.*, g.group_name,if( length(u.name), u.name, u.business_name) name')
						->where($key, $input)
						->where('status', 0)
						->join('groups as g', 'g.id = u.group_id', 'left')
						->get();

		return $query->row();
	}

	public function auto_create_user($data){
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function insert($data){
		$this->db->insert('users', $data);
		return true;
	}

	public function update($id,$data){
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		return true;
	}


	public function insert_access_tokens($data){
		$this->db->insert('access_tokens', $data);
		return true;
	}

	public function update_access_tokens($id,$data){
		$this->db->where('user_id', $id);
		$this->db->update('access_tokens', $data);
		return true;
	}

	public function get_access_tokens($key,$input){
		$query = $this->db->from('access_tokens a')
						->select('a.*')
						->where($key, $input)
						->get();
		return $query->row();
	}


	/**
     * Get User by ID
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function get_user($id)
	{
						$query = $this->db->from('users u')
						->select('u.*, g.group_name,if( length(u.name), u.name, if(length(u.business_name), u.business_name, if(length(u.mobile_number), u.mobile_number, u.email))) name, s.state_name, l.name as local_govts_name, i.name as industry_name')
						
						->join('groups as g', 'g.id = u.group_id', 'left')
						->join('state_tbl as s', 's.state_id = u.states', 'left')
						->join('local_govts as l', 'l.id = u.lga', 'left')
						->join('industry as i', 'i.id = u.industry', 'left')
						->where('u.id', $id)
						->get();

		return $query->row();
	}

	/**
     * Datagrid Data
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function getJson($input)
	{
		$table  = 'users as a';
		$select = 'a.*, g.group_name';

		$replace_field  = [
			['old_name' => 'name', 'new_name' => 'a.name'],
			['old_name' => 'group_name', 'new_name' => 'g.group_name']
		];

		$param = [
			'input'     => $input,
			'select'    => $select,
			'table'     => $table,
			'replace_field' => $replace_field
		];

		$data = $this->datagrid->query($param, function($data) use ($input) {
			return $data->join('groups as g', 'g.id = a.group_id', 'left')
						->where('a.id !=', $this->session->userdata('active_user')->id);
		});

		return $data;
	}

	public static function listMerchantsAutocomplete(PDO $pdo, $term='', $phone='',$email=''){
		try{
		$stmt = $pdo->prepare(" SELECT `id`, CONCAT(COALESCE(`name`, `business_name`,'') , ' (',COALESCE(`mobile_number`, `email`,''), ')') `name`, `mobile_number`, `email` 
			FROM users 
			Where (`business_name` Like :bname
				OR `mobile_number` Like :phone
				OR `email` Like :email ) LIMIT 50");

		$stmt->bindValue(':bname', "%$term%");
		$stmt->bindValue(':phone', "%$phone%");
		$stmt->bindValue(':email', "%$email%");
		
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);

		} catch (Exception $ex){
		throw $ex;
		}
  }

}