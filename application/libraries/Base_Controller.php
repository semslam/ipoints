<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

	protected $data;
	public static $rowperpage = 10;
	
	public function __construct() {
		parent::__construct();
		// Redirect If Not Authenticated
		$this->session->userdata('active_user') == null ? redirect('auth/login') : '';

		$this->load->model('menu_m');
		$this->load->model('group_m');
		$this->load->library('MenuHandler');
		$this->load->library('pagination');

		// Get Authenticated User
		$this->data['active_user'] = $this->session->userdata('active_user');
		$this->data['active_user_group'] = $this->group_m->get_group($this->data['active_user']->group_id);
		$this->data['list_menu'] = $this->menu_m->get_menu($this->data['active_user']->group_id);
		$this->data['active_menu'] = MenuHandler::active_menu($this->data['list_menu']);
		$this->data['menu_style'] = 'default';
		header('Access-Control-Allow-Origin: *');
	}


	public function rowperpage_and_rowno($rowperpage,$rowno){
        if($rowno != 0){
            return $rowno = ($rowno-1) * $rowperpage;
        }else{
            return $rowno;
        }
    }

    public function pagination($pagin_path,$allcount,$rowperpage,$rowno =0){
       
        $config['base_url'] = base_url().$pagin_path;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;
 
        $config['full_tag_open']    = '<div class="pagging text-right"><nav><ul sytle="border: 1px solid #ddd;" class="pagination">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close']  = '</span></li>';
        $this->pagination->initialize($config);
 
		//return $this->pagination->create_links();
		
		$mult = 0; $int =0; $count = $rowno;
		if($rowno != 0){ $rowno = ($rowno-1) * $rowperpage; $int += $rowno; $mult = $rowperpage *=$count;
		 }else{ $int =1; $mult = $rowperpage;}

	return '<div class="col-sm-5">'.
			 '<div class="dataTables_info" id="example_info" role="status" aria-live="polite">Showing '.$int.' to '.$mult.' of '.$allcount.' entries</div>'
			.'</div>'
			.'<div class="col-sm-7">'
				.$this->pagination->create_links().
			'</div>';
    }

}
