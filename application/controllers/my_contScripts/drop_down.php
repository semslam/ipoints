
	
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Welcome extends CI_Controller {
    public function index()
    {
        $this->load->helper(array('dropdown','form'));
        $this->load->database();
        $dropdownItems = listData('country_tbl','country_id', 'country_name');
        $countryDropdown = form_dropdown('country',$dropdownItems);
        $stateDropdown =   form_dropdown('state',[]);
        $this->load->view('welcome_message', ['countryDropdown' => $countryDropdown, 'stateDropdown' => $stateDropdown]);
    }
 
    public function getState() {
        $this->load->helper(array('dropdown','form'));
        $this->load->database();
     $country_id = $this->input->get('country_id');
        $dropdownItems = listData('state_tbl','sate_id', 'sate_name',['where' => ['country_id' => $country_id]]);
        echo $stateDropdown =   form_dropdown('state',$dropdownItems);
        
    }
	
	 public function index()
    {
        $this->load->helper(array('dropdown','form'));
        $this->load->database();
        $dropdownItems = listData('states_in_nigeria','state_id', 'name');
        $stateDropdown = form_dropdown('State',$dropdownItems);
        $lgaDropdown =   form_dropdown('LGA',[]);
        $this->load->view('template/drop_down', ['stateDropdown' => $stateDropdown, 'lgaDropdown' => $lgaDropdown]);
    }
 
    public function getState() {
        $this->load->helper(array('dropdown','form'));
        $this->load->database();
        $state_id = $this->input->get('state_id');
        $dropdownItems = listData('local_govts','id', 'name',['where' => ['state_id' => $state_id]]);
        echo $lgaDropdown =   form_dropdown('LGA',$dropdownItems);
        
    }
}