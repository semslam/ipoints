<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
  public function index()
  {
   $this->load->view('ipoints_site_config');
  }
  public function config()
  {
   $this->load->view('config');
  }
  public function update()
  {
   $update_data=array(
      'sitename'=>$this->input->post('sitename'),
      'sitedescription'=>$this->input->post('sitedescription')
      );
   $success = $this->Siteconfig->update_config($update_data);
   if($success) redirect("/ipoints_site_config/index");
  }
}