<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServicesLog extends Base_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('PaymentPurchase');
        $this->load->library("Untils");
        $this->load->library("pdolib");
    }

	/**
     * List of Users
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	// public function index(){
	// 	$this->data['title'] = 'Services Log';
	// 	$this->data['subview'] = 'services_log/main';
	// 	$this->load->view('components/main', $this->data);
	// }

	

	
	/**
     * Datagrid Data
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

	// public function data()
	// {
    //     header('Content-Type: application/json');
    //     $this->load->model('serviceslog_m');
	// 	echo json_encode($this->serviceslog_m->getJson($this->input->post()));
    // }

    


}
