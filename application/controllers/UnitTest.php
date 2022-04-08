<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnitTest extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->library('unit_test');
		$this->load->model('Transaction');	
    }
    
    public function index(){
        $this->unit->run(Transaction::credit(128,0.375,4,'OnUnitTesting123','Credit On Testing',5,true),7,'This is main credit function');
        $this->load->view('unit_test\test');
    }
}