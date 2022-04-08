<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends Base_Controller {

	public function __construct(){
		parent::__construct();
		//$this->load->library('sms');
		//$this->load->library('emailer');
		$this->load->library('Untils');
		$this->load->model('MessageTemplate_m', 'messagetemplate_m');
		$this->load->model('user_m');
		$this->load->model('product_m');
	}
	/**
     * Transaction List
     *
     * @access  public
     * @param   
     * @return  view
     */
	
	public function index()
	{
		$this->data['title'] = 'Transaction';
		$this->data['subview'] = 'transaction/main';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Invoice Details
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function invoice($invoice_number)
	{
		
		$this->data['title'] = 'Invoice';
		$this->data['subview'] = 'transaction/invoice';
		$this->data['company_info'] = $this->db->from('settings')
										 ->where_in('meta_key', [
        									'company_name', 
        									'company_address', 
        									'company_phone_number',
        									'company_email'
        								 ])->get()->result();
		$this->data['transaction'] = $this->db->from('transactions t')
										->where('t.invoice_number', $invoice_number)
										->get()->row();
		$this->data['transaction_details'] = $this->db->from('transaction_details t')
												->where('t.transaction_id', $this->data['transaction']->id)
												->join('products as p', 'p.id = t.product_id', 'left')
												->get()->result();

		$this->load->view('components/main', $this->data);
	}

	/**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

	public function data()
	{
        header('Content-Type: application/json');
        $this->load->model('transaction_m');
		echo json_encode($this->transaction_m->getJson($this->input->post()));
	}

	/**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

	public function detail()
	{
        header('Content-Type: application/json');
        $this->load->model('transaction_detail_m');
		echo json_encode($this->transaction_detail_m->getJson($this->input->post()));
	}
}
