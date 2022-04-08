<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Change_Password extends Base_Controller {
	
	/**
     * Change Password Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */

	public function index()
	{
		$this->data['title'] = 'Change Password';
		$this->data['subview'] = 'change_password/main';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Validate Input
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	public function save()
	{
		$rules = [
			[
				'field' => 'old_password',
				'label' => 'Old Password',
				'rules' => 'required'
			],
			[
				'field' => 'new_password',
				'label' => 'New Password',
				'rules' => 'trim|required|min_length[8]'
			],
			[
				'field' => 'conf_password',
				'label' => 'Confirm Password',
				'rules' => 'trim|required|matches[new_password]'
			]
		];

		$this->load->model('user_m');
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$id = $this->session->userdata('active_user')->id;
			$attempt = $this->user_m->get_user($id);
			if (!password_verify($this->input->post('old_password'),$attempt->password)) {
				header("Content-type:application/json");
				echo json_encode(['old_password' => ['Wrong old password']]);
			} else {
				$data['password'] =$this->input->post('new_password');
				$data['password'] = password_hash($data['password'],PASSWORD_BCRYPT,['cost'=> 12]);
				$data['updated_at']   = date('Y-m-d H:i:s');
				if($this->user_m->update($id,$data)){
					header("Content-type:application/json");
					echo json_encode(['status' => 'success']);
		
				}else{
					header('Content-Type: application/json');
					echo json_encode(['conf_password' => 'Password can not be change, Please try again later']);
				}
			}
		} else {
			header("Content-type:application/json");
			echo json_encode($this->form_validation->get_all_errors());
		}
	}

	/**
     * Save a New Password
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	// public function save()
	// {
	// 	$data['password'] = $this->input->post('new_password');
	// 	$this->db->where('id', $this->input->post('id'));
	// 	$this->db->update('users', $data);

	// 	header("Content-type:application/json");
	// 	echo json_encode('success');
	// }

}
