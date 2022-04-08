<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keys extends Base_Controller {

	/**
     * List of Products
     *
     * @access 	public
     * @param 	
     * @return 	view
     */

	
	public function index()
	{	

		$this->data['title'] = 'Keys';
		$this->data['subview'] = 'key/main';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Product Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */

	public function form()
	{
		$data['index'] = $this->input->post('index');
		$this->load->view('key/form', $data);
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
        $this->load->model('key_m');
		echo json_encode($this->key_m->getJson($this->input->post()));
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
                'field' => 'user_id',
                'label' => 'Agent Name',
                'rules' => 'required'
            ],
            [
                'field' => 'level',
                'label' => 'Level',
                'rules' => 'trim|required|less_than_equal_to[10]'
            ],
            [
                'field' => 'ip_address',
                'label' => 'Ip Address',
                'rules' => 'required|valid_ip'
            ],
            [
                'field' => 'ignore_limits',
                'label' => 'Ignore Limit',
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

	public function action()
	{
		if (!$this->input->post('id')) {
			$this->create();
		} else {
			$this->update();
		}
	}

	/**
     * Create a New Product
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function create()
	{
		$this->load->model('key_m');
		$key = $this->key_m->_generate_key();
		$data['user_id'] 	= $this->input->post('user_id');
		$data['level']   		= $this->input->post('level') ? $this->input->post('level') : 1;
		$data['ip_addresses']   = $this->input->post('ip_address');
		$data['ignore_limits']  = ctype_digit($this->input->post('ignore_limits')) ? (int) $this->input->post('ignore_limits') : 1;
		$data['	api_key']   	= $key;
		$data['	date_created']  = date('Y-m-d H:i:s');
		$this->db->insert('api_keys', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Update Existing Product
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function update()
	{
		$data['user_id']= $this->input->post('user_id');
		$data['level'] 	= $this->input->post('level') ? $this->input->post('level') : 1;
		$data['ip_addresses']	= $this->input->post('ip_address');
        $data['ignore_limits']	= ctype_digit($this->input->post('ignore_limits')) ? (int) $this->input->post('ignore_limits') : 1;
        $data['	date_created']	= date('Y-m-d H:i:s');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('api_keys', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Delete a Product
     *
     * @access 	public
     * @param 	
     * @return 	redirect
     */

	public function delete()
	{	
		$level = (int) $this->input->post('level');
		($level > 0) ? $level = 0 : $level = 10;
		$data['level'] =$level;
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('api_keys', $data); 
	}

}
