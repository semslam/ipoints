<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends Base_Controller {

	/**
     * List of Products
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function index()
	{	
		$this->data['title'] = 'Product';
		$this->data['subview'] = 'product/main';
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
		$this->load->view('product/form', $data);
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
        $this->load->model('product_m');
		echo json_encode($this->product_m->getJson($this->input->post()));
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
				'field' => 'product_name',
				'label' => 'Product Name',
				'rules' => 'required'
			],
			[
				'field' => 'price',
				'label' => 'Price',
				'rules' => 'required'
			],
			[
				'field' => 'stock',
				'label' => 'Stock',
				'rules' => 'required'
			],
			[
				'field' => 'images',
				'label' => 'Images',
				'rules' => 'required'
			],
			[
				'field' => 'description',
				'label' => 'Description',
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
		$data['product_name'] 	= $this->input->post('product_name');
		$data['price']   		= $this->input->post('price');
		$data['stock']   		= $this->input->post('stock');
		$data['images']   		= $this->input->post('images');
		$data['description']   	= $this->input->post('description');
		$this->db->insert('products', $data); 

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
		$data['product_name'] 	= $this->input->post('product_name');
		$data['price']   		= $this->input->post('price');
		$data['stock']   		= $this->input->post('stock');
		$data['images']   		= $this->input->post('images');
		$data['description']   	= $this->input->post('description');
		$this->db->where('id', $this->input->post('id'));
		$this->db->update('products', $data); 

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
		$this->db->where('id', $this->input->post('id'));
		$this->db->delete('products');
	}

}
