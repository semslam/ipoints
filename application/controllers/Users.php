<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Base_Controller {


	public function __construct(){
		parent::__construct();
		$this->load->library('sms');
		$this->load->library('emailer');
		$this->load->library('Untils');
		$this->load->model('MessageTemplate_m', 'messagetemplate_m');
		$this->load->model('user_m');
		$this->load->model('product_m');
		$this->load->model('Wallet');
		$this->load->model('User');
		$this->load->library("pdolib");
	}
	/**
     * List of Users
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function index()
	{
		$this->data['headStyles'] = [
			BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
		  ];
		$this->data['footerScripts'] = [
			BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
			
		  ];
		$this->data['title'] = 'Create Backend User';
		$this->data['subview'] = 'user/backend_users';
		$this->load->view('components/main', $this->data);
	}


	public function client()
	{
		$this->data['headStyles'] = [
			BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
		  ];
		$this->data['footerScripts'] = [
			BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
			
		  ];
		$this->data['title'] = 'Clent User Manager';
		$this->data['subview'] = 'user/client_users';
		$this->load->view('components/main', $this->data);
	}

	/**
     * User Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */

	public function form()
	{
		$this->load->model('group_m');
		$data['groups'] = $this->group_m->all();
		$data['index'] = $this->input->post('index');

		$this->load->view('user/form', $data);
	}

	/**
     * Datagrid Data
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function data()
	{
        header('Content-Type: application/json');
        $this->load->model('user_m');
		echo json_encode($this->user_m->getJson($this->input->post()));
	}

	/**
     * Validate Input
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

    public function validate()
	{
		$rules = [
			[
				'field' => 'name',
				'label' => 'Name',
				'rules' => 'required'
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'required|valid_email'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required'
			],
			[
				'field' => 'group_id',
				'label' => 'Group Id',
				'rules' => 'required'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			header("Content-type:application/json");
			echo json_encode('success');
		} else {
			header("Content-type:application/json");
			echo json_encode($this->form_validation->get_all_errors());
		}
	}

	
	/**
     * Create Update Action
     *
     * @access 	public
     * @param 	
     * @return 	method
     */

	public function action(){
		(!$this->input->post('id')) ? $this->create() :$this->update();
	}
	public function search(){
		$query = trim($this->input->get('name',TRUE));
		$this->db->select('id, name'); 
		$this->db->like('name', $query);
		$data = $this->db->get("users")->result();
		$users = [];
		if(!empty($data)){
            foreach($data as $dt){
                $users[$dt->id] = $dt->name;
            }
        }
		header('Content-Type: application/json');
        echo json_encode( $users);
	}
	

	/**
     * Create a New User
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function create()
	{
		$data['name'] 		= $this->input->post('name');
		$data['email']   	= $this->input->post('email');
		$data['password']   = $this->input->post('password');
		$data['group_id']   = $this->input->post('group_id');
		$this->db->insert('users', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Update Existing User
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function update()
	{
		$data['name'] 		= $this->input->post('name');
		$data['email']   	= $this->input->post('email');
		$data['password']   = $this->input->post('password');
		$data['group_id']   = $this->input->post('group_id');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('users', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Delete a User
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function delete()
	{
		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('users');
	}



	public function autocompleteusers(){
		header('Content-Type: application/json');
		
		$pdo = $this->pdolib->getPDO();
		$query = $this->input->get('customerName', true);
		$phone = $this->sms->cleanPhoneNumber($query);
		$email = $query;
		$users = user_m::listMerchantsAutocomplete($pdo, $query, $phone, $email);
    	echo json_encode($users);
	}


	public function processor(){
		$group_id		= $this->input->post('acct_type');
		$data['name'] 		= $this->input->post('name');
		$data['email']   	= $this->input->post('email');
		$data['password']   = $this->input->post('password');
		$data['gender']   	= $this->input->post('gender');
		$data['group_id']   	= $this->input->post('group_id');
		$data['created_at']   = date('Y-m-d H:i:s');
		$data['updated_at']   = date('Y-m-d H:i:s');
		header('Content-Type: application/json');
			$rules = [
				[
					'field' => 'name',
					'label' => 'Full Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'gender',
					'label' => 'Gender',
					'rules' => 'trim|required|in_list[male,female]'
				],
				[
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'trim|required|min_length[8]'
				],
				[
					'field' => 'email',
					'label' => 'Email',
					'rules' => "trim|valid_email"
				]
			];
		
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
			$exist;
			$exist = (!empty($this->user_m->userexist('email',$data['email'])))? true: false;
			if(!empty($exist)){
				exit(json_encode(['name' => 'User already exist on the platform']));
			}
			$group = User::fetchByGroupId($data['group_id']);
			if($group->group_name == MERCHANT || $group->group_name == SUBSCRIBER || $group->group_name == UNDERWRITER || $group->group_name == PARTNER){
				exit(json_encode(['name' => 'This user can\'t be create on backend']));
			}
			$this->db->trans_start();
			//$genPassword = $this->untils->autoGeneratorPwd();
			$data['password'] = password_hash($data['password'],PASSWORD_BCRYPT,['cost'=> 12]);
			//$data['otp'] = $this->untils->otpGenerator(7);
			$user  = new User();
			$user->name = $data['name']; 
			$user->group_id = $data['group_id'];
			$user->gender = $data['gender'];
			$user->email = $data['email'];
			$user->status = 1;
			$user->password = $data['password'];
			$user->created_at = $data['created_at'];
			$user->updated_at = $data['updated_at'];
			$result = $user->save();
			
			if($result){
				// $contact = $data['email'];
				// $type = MESSAGE_EMAIL;
				// $variable = array($data['otp'],$genPassword);
				// MessageQueue::messageCommit($contact, $type, REGISTER, $variable);
				$this->db->trans_commit();
				exit(json_encode(['value' =>'success']));
			}else{ 
				$this->db->trans_rollback();
				exit(json_encode(['name' =>'User account can not be create, Please try again later']));
			}
			
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
	}

		public function loadBackEndUsers(){
			$data['id'] = $this->input->post('id');
			$data['name'] = $this->input->post('name');
			header('Content-Type: application/json');
			$db = $this->pdolib->getPDO();
			$users = User::findBackEndUsers($db);
			echo json_encode(['value'=>'success', 'users'=> $users]);
	  	}


	  public function loadClientUsers(){
		$data['id'] = $this->input->post('id');
		$data['name'] = $this->input->post('name');
		header('Content-Type: application/json');
		$db = $this->pdolib->getPDO();
		$users = User::findUserClient($db);
		echo json_encode(['value'=>'success', 'users'=> $users]);
  }


	public function blockAndUnblockUser(){
        $id = $this->input->post('id');
        $data['status'] = $this->input->post('status');
        $data['updated_at'] = date('Y-m-d H:i:s');
		header('Content-Type: application/json');
		$user =User::findById($id);
		if($user->is_system == 1){
			exit(json_encode(['value'=>'Supper administrator can not be block']));
		}
        $result = User::updateByPk($id, $data);
        if (!$result) {
            exit(json_encode(['value'=>'User was not updated successful, Please Try Again']));
        }
        echo json_encode(['value'=>'success']);
	  }


	  public function changeUserRole(){
        $id = $this->input->post('id');
        $data['group_id'] = $this->input->post('group_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
		header('Content-Type: application/json');
		$user =User::findById($id);
		if($user->is_system == 1){
			exit(json_encode(['value'=>'Supper administrator role can not be change']));
		}
        $result = User::updateByPk($id, $data);
        if (!$result) {
            exit(json_encode(['value'=>'User role was not successful update, Please Try Again']));
        }
        echo json_encode(['value'=>'success']);
	  }
	  
	  public function groups(){
        header('Content-Type: application/json');
        $groups = $this->User->getGroups();
        echo json_encode(['value'=>'success','groups'=>$groups]);
    }
	

}
