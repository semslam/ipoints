<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Base_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->library("pdolib");
    }

	/**
     * Settings form
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function index()
	{
		$this->load->model('setting_m');
		
		$this->data['title'] = 'Settings';
		$this->data['subview'] = 'settings/main';
		$this->data['settings'] = $this->setting_m->all();

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
		$rules = [
			[
				'field' => 'company_name',
				'label' => 'Company Name',
				'rules' => 'required'
			],
			[
				'field' => 'company_address',
				'label' => 'Company Address',
				'rules' => 'required'
			],
			[
				'field' => 'company_phone_number',
				'label' => 'Company Phone Number',
				'rules' => 'required'
			],
			[
				'field' => 'company_email',
				'label' => 'Company Email',
				'rules' => 'required|valid_email'
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
     * Save Settings Changes
     *
     * @access 	public
     * @param 	
     * @return 	json(string)
     */

	public function save()
	{
		$data['meta_value'] = $this->input->post('company_name');
		$this->db->where('meta_key', 'company_name');
		$this->db->update('settings', $data);

		$data['meta_value'] = $this->input->post('company_address');
		$this->db->where('meta_key', 'company_address');
		$this->db->update('settings', $data);

		$data['meta_value'] = $this->input->post('company_phone_number');
		$this->db->where('meta_key', 'company_phone_number');
		$this->db->update('settings', $data);

		$data['meta_value'] = $this->input->post('company_email');
		$this->db->where('meta_key', 'company_email');
		$this->db->update('settings', $data);

		header("Content-type:application/json");
		echo json_encode('success');
	}

	public function createSetting()
	{
		$this->load->model('setting_m');
		$this->data['title'] = 'Create System Configuration Settings';
		
		$this->data['headStyles'] = [
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css',
			BACKOFFICE_HTML_PATH . '/css/summernote.css',
			BACKOFFICE_HTML_PATH . '/css/toastr.min.css'
		];
		$this->data['footerScripts'] = [
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js',
			BACKOFFICE_HTML_PATH . '/js/summernote.js',
			BACKOFFICE_HTML_PATH . '/js/toastr.min.js'
		];
		$this->data['subview'] = 'settings/create';
		$this->load->view('components/main', $this->data);
	}


	public function update()
	{
		$this->load->model('setting_m');
		
		$this->data['title'] = 'Upload System Configuration Settings';
		$this->data['headStyles'] = [
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css',
			BACKOFFICE_HTML_PATH .'/css/summernote.css',
			BACKOFFICE_HTML_PATH . '/css/toastr.min.css'
		];
			
		$this->data['footerScripts'] = [
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js',
			BACKOFFICE_HTML_PATH .'/js/summernote.js',
			BACKOFFICE_HTML_PATH . '/js/toastr.min.js'
		];
		$this->data['subview'] = 'settings/update';
		$this->load->view('components/main', $this->data);
	}

	public function create(){

		$id = $this->input->post('id');
		$data['meta_key'] = $this->input->post('meta_key');
		$data['meta_label'] = $this->input->post('meta_label');
		$meta_type = $this->input->post('meta_type');
		$data['meta_description'] = $this->input->post('meta_description');
		$meta_value_text = $this->input->post('meta_value_text');
		$meta_value_area = $this->input->post('meta_value_area');
		$meta_value_numb = $this->input->post('meta_value_numb');
		$data['author_by'] = $this->session->userdata('active_user')->id;
		
		$rules = [];

		if(empty($id)){
			$rules = [
				[
					'field' => 'meta_type',
					'label' => 'Meta Type',
					'rules' => 'trim|required|in_list[text,number,textarea]'
				] 
			];
		}

		if(!empty($meta_value_text)){
			$rules = [
				[
				  'field' => 'meta_value_text',
				  'label' => 'Meta Value',
				  'rules' => 'trim|required'
				  ] 
			];
			$data['meta_value'] = $meta_value_text;
		}else if(!empty($meta_value_area)){
			$rules = [
				[
				  'field' => 'meta_value_area',
				  'label' => 'Meta Value',
				  'rules' => 'trim|required'
				  ] 
			];
			$data['meta_value'] = $meta_value_area;
		}else if(!empty($meta_value_numb)){
			$rules = [
				[
				  'field' => 'meta_value_numb',
				  'label' => 'Meta Value',
				  'rules' => 'trim|required'
				  ] 
			];
			$data['meta_value'] = $meta_value_numb;
		}

		$rules = [
			[
				'field' => 'meta_key',
				'label' => 'Meta Key',
				'rules' => 'trim|required'
			],[
				'field' => 'meta_label',
				'label' => 'Meta Label',
				'rules' => 'trim|required'
			],[
				'field' => 'meta_description',
				'label' => 'Meta Description',
				'rules' => 'trim|required'
			]
		];
	
		header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			if (!empty($id)) {
				$data['updated_at'] = date('Y-m-d H:i:s');
				$result = Setting_m::updateByPk($id, $data);
				if (!$result) {
					echo json_encode(['value'=>'Settings was not updated successful, Please Try Again']);
				}
				echo json_encode(['value'=>'success']);
			} else {
				$data['created_at'] = date('Y-m-d H:i:s'); 
				$setting_m = new Setting_m();
				$setting_m->meta_key = $data['meta_key'];
				$setting_m->meta_value = $data['meta_value'];
				$setting_m->meta_label = $data['meta_label'];
				$setting_m->meta_type = $meta_type;
				$setting_m->meta_description = $data['meta_description'];
				$setting_m->author_by = $data['author_by'];
				$setting_m->updated_at = $data['created_at'];
				$setting_m->created_at = $data['created_at'];
				$setting_m->save();
				echo json_encode(['value'=>'success']);
			}
		} else {
			echo json_encode($this->form_validation->get_all_errors());
		}
	}


	public function manager()
	{
		$this->load->model('setting_m');
		$this->data['title'] = 'System Configuration Settings Manager';
		
		$this->data['subview'] = 'settings/list';
		$this->load->view('components/main', $this->data);
	}

	public function listSettings(){
		header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $settings = Setting_m::getAllSettings($db);
        echo json_encode(['value'=>'success', 'settings'=> $settings]);
	}

	public function editSettings(){
		header('Content-Type: application/json');
		$id = $this->input->post('id');
        $db = $this->pdolib->getPDO();
        $setting = Setting_m::getSettingsById($db,$id);
        echo json_encode(['value'=>'success', 'setting'=> $setting]);
	}




}
