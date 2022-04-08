<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	/**
     * Dashboard
     *
     * @access  public
     * @param   
     * @return  view
     */
	
	public function view()
    {  
		if($this->config->item('use_front_template') == 1)
		{
			$this->data['title'] = 'Welcome::iPoints';
			$this->data['subview'] = 'components/home';
			$this->load->view('components/front_layout', $this->data);
		}
		elseif($this->config->item('use_front_template') == 2){
			$this->data['title'] = 'Welcome :: iPoints';
			$this->data['subview'] = 'components_template_2/home';
			$this->load->view('components_template_2/front_layout', $this->data);			
		}
    }
}
