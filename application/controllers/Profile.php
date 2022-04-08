<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Base_Controller {

	/**
     * Update Profile Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function index()
	{	
		$this->load->model('group_m');

		$this->data['title'] = 'Profile';
		$this->data['subview'] = 'profile/main';
		$this->data['groups'] = $this->group_m->all();

		$this->load->view('components/main', $this->data);
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
		$rules = [];
		$group_id		= $this->input->post('acct_type');
		$userId = $this->session->userdata('active_user')->id;
		$user_group = User::fetchUserGroup($userId);
		
		if($user_group['group_name'] == SUBSCRIBER){
			//Subscriber
			//var_dump($data);exit;
			$data['group_id'] = $group_id;
			$rules = [
				[
					'field' => 'name',
					'label' => 'Full Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'email',
					'label' => 'Email',
					'rules' => "trim|valid_email"
				],
				[
					'field' => 'gender',
					'label' => 'Gender',
					'rules' => "trim|required|in_list[male,female]"
				],
				[
					'field' => 'states',
					'label' => 'State',
					'rules' => 'required|numeric|is_natural_no_zero|max_length[737]'
				],
				[
					'field' => 'lga',
					'label' => 'Local Government of Residence',
					'rules' => 'required|numeric|is_natural_no_zero|max_length[737]'
				],
				[
					'field' => 'nok_name',
					'label' => 'Next of Kin Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'nok_phone',
					'label' => 'Next of Kin Mobile Number',
					'rules' => 'required|regex_match[/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/]'
				],
				[
					'field' => 'dob',
					'label' => 'Date Of Birth',
					'rules' => 'trim|required|regex_match[/\d{4}\-\d{2}-\d{2}/]'
				]
			];
		}else if($user_group['group_name'] == MERCHANT){
			//Merchant
			$data['group_id'] = $group_id;
			$rules = [
				[
					'field' => 'biz_name',
					'label' => 'Business Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'biz_address',
					'label' => 'Business Address',
					'rules' => 'trim|required|min_length[2]|max_length[40]'
				],
				[
					'field' => 'rc_number',
					'label' => 'RC Number',
					'rules' => 'required|max_length[25]'
				],
				[
					'field' => 'referrer',
					'label' => 'Referrer',
					'rules' => 'required|max_length[30]'
				],
				[
					'field' => 'industries',
					'label' => 'Industry',
					'rules' => 'required|numeric|is_natural_no_zero|max_length[181]'
				]
			];

		} else if($user_group['group_name'] == UNDERWRITER){
			//Under Writer
			$data['group_id'] = $group_id;
			$rules = [
				[
					'field' => 'biz_name',
					'label' => 'Business Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'biz_address',
					'label' => 'Business Address',
					'rules' => 'trim|required|min_length[2]|max_length[40]'
				]
			];

		}else if($user_group['group_name'] == PARTNER){
			//Partner
			$data['group_id'] = $group_id;
			$rules = [
				[
					'field' => 'biz_name',
					'label' => 'Business Name',
					'rules' => 'trim|required|min_length[3]|max_length[30]|regex_match[/([a-zA-Z]|\s)+/]'
				],
				[
					'field' => 'biz_address',
					'label' => 'Business Address',
					'rules' => 'trim|required|min_length[2]|max_length[40]'
				]
			];

		}

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
     * Save Profile Changes
     *
     * @access 	public
     * @param 	
     * @return 	json('string')
     */

	public function save()
	{
		$group_id		= $this->input->post('acct_type');
		$data['birth_date']   = $this->input->post('dob');
		$data['rc_number']   = $this->input->post('rc_number');
		$data['referrer']   = $this->input->post('referrer');
		$data['address']   = $this->input->post('biz_address');
		$data['business_name']  = $this->input->post('biz_name');
		$data['industry']   = $this->input->post('industries');
		$data['next_of_kin']   = $this->input->post('nok_name');
		$data['next_of_kin_phone']   = $this->input->post('nok_phone');
		$userId = $this->session->userdata('active_user')->id;
		$user_group = User::fetchUserGroup($userId);
		if($user_group['group_name'] == SUBSCRIBER){
			//Subscriber
			$data['name'] 		= $this->input->post('name');
			$data['email']   	= $this->input->post('email');
			$data['gender']   = $this->input->post('gender');
			$data['states']   = $this->input->post('states');
			$data['lga']   = $this->input->post('lga');
		}
		// else if($group_id == '3'){
		// 	//Merchant
			

		// } else if($group_id == '5' ||$group_id == '6' ){
		// 	//Under Writer OR Partner
		// 	$data['address']   = $this->input->post('biz_address');
		// 	$data['business_name']   	= $this->input->post('biz_name');

		// }
		
		$data['updated_at']   = date('Y-m-d H:i:s');

		$this->db->where('id', $userId);
		$this->db->update('users', $data);

		$this->load->model('user_m');
		$user = $this->user_m->get_user($userId);
		$this->session->set_userdata('active_user', $user);

		header("Content-type:application/json");
		echo json_encode('success');
	}

}
