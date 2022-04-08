<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Levies extends Base_Controller{


    public function __construct()
	{
	  parent::__construct();
	  $this->load->library("pdolib");
      $this->load->model('Uici_levies');
	 
	}


    public function index(){

        $this->data['headStyles'] = [
			BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
		  ];
		$this->data['footerScripts'] = [
			BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
			
		  ];

        $this->data['title'] = ' Levies';
		$this->data['subview'] = 'backend/levies';
		$this->load->view('components/main', $this->data);

    }

    public function loadLevies(){
		$db = $this->pdolib->getPDO();
		header('Content-Type: application/json');
        $levies = Uici_levies::getUiciLevies($db);
		echo json_encode(['value'=>'success','levies'=> $levies]);	
	}
	public function processLevies(){
		
		$data['id'] = $this->input->post('id');
		$data['name'] = $this->input->post('name');
		$data['value'] = $this->input->post('value');
		$data['description'] = $this->input->post('description');
		$data['created_at'] = date('Y-m-d H:i:s');
        
     
			$rules = [
                [
                    'field' => 'name',
                    'label' => 'Name',
                    'rules' => 'trim|required'
                ],[
                    'field' => 'value',
                    'label' => 'Value',
                    'rules' => 'trim|required'
                ],[
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required'
                ]
            ];
    
		
        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $leviesResult =true;
			if(!empty($data['id'])){
                $leviesResult = Uici_levies::updateByPk($data['id'],$data);
                if (!$leviesResult) {
                    echo json_encode(['name'=>'Levies was not updated successful, Please Try Again']);
                }
            }else{
                $levies = new Uici_levies();
                $levies->name = $data['name'];
                $levies->value = $data['value'];
                $levies->description = $data['description'];
                $levies->created_at = $data['created_at'];
                $leviesResult = $levies->save();
            }
            $result = ($leviesResult)?['value'=>'success']:['name'=>'Levies was not created successful, Please Try Again'];
		    echo json_encode($result);	
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }
}