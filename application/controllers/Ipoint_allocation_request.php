<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipoint_allocation_request extends Base_Controller {

	/**
     * List of Ipoints Allocation Requests
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function index()
	{	
		$this->data['title'] = 'Ipoints Allocation Request';
		$this->data['subview'] = 'ipoint_allocation_request/main';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Ipoints Allocation Request Form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */

	public function form()
	{
		$data['index'] = $this->input->post('index');
		$this->load->view('ipoint_allocation_request/form', $data);
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
        $this->load->model('ipoint_allocation_request_m');
		echo json_encode($this->ipoint_allocation_request_m->getJson($this->input->post()));
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
				'field' => 'qty',
				'label' => 'Number of iPoints',
				'rules' => 'required'
			],
			[
				'field' => 'recipient_phone',
				'label' => 'Recipient Phone Number',
				'rules' => 'required'
			],
			[
				'field' => 'notes',
				'label' => 'notes',
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
     * Create a New Ipoints Allocation Request
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function create()
	{
		try {
			$string = random_bytes(16);
		} catch (TypeError $e) {
			// Well, it's an integer, so this IS unexpected.
			die("An unexpected error has occurred"); 
		} catch (Error $e) {
			// This is also unexpected because 32 is a reasonable integer.
			die("An unexpected error has occurred");
		} catch (Exception $e) {
			// If you get this message, the CSPRNG failed hard.
			die("Could not generate a random string. Is our OS secure?");
		}
		$data['request_id']   		= $this->input->post($string);
		$data['qty']   		        = $this->input->post('qty');
		$data['recipient_phone']   	= $this->input->post('recipient_phone');
		$data['status']   		    = $this->input->post('status');
		$data['client_id']   	    = $this->input->post('client_id');
		$data['notes']   	        = $this->input->post('notes');
		$this->db->insert('ipoint_allocation_request_approval_log', $data); 

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

	/**
     * Update Existing Ipoints Allocation Request
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function update()
	{
		$data['request_id']   		= $this->input->post($string);
		$data['qty']   		= $this->input->post('qty');
		$data['recipient_phone']   		= $this->input->post('recipient_phone');
		$data['status']   		= $this->input->post('status');
		$data['notes']   	= $this->input->post('notes');
		$this->db->where('request_id', $this->input->post('request_id'));
		$this->db->update('ipoint_allocation_request_approval_log', $data); 
		
		/*//////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
			At this point transfer this entry to the wip_transaction table 
		\\\\\\\\\\\\\\\\\\\\\\\//////////////////////////////////////////////*/

		header('Content-Type: application/json');
    	echo json_encode('success');
	}

}
