<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends Base_Controller {

	/**
     * Fixed Menu
     *
     * @access  public
     * @param   
     * @return  view
     */
	
	public function fixed_menu()
	{	
		$this->data['title'] = 'Fixed Menu';
		$this->data['subview'] = 'dashboard/main';
		$this->data['menu_style'] = 'fixed-nav';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Compact Menu
     *
     * @access  public
     * @param   
     * @return  view
     */
	
	public function compact_menu()
	{	
		$this->data['title'] = 'Compact Menu';
		$this->data['subview'] = 'dashboard/main';
		$this->data['menu_style'] = 'compact-nav';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Form Element
     *
     * @access  public
     * @param   
     * @return  view
     */
	
	public function form_element()
	{	
		$this->data['title'] = 'Form Elements';
		$this->data['subview'] = 'template/form_element';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Form Validation
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function form_validation()
	{	
		$this->data['title'] = 'Form Validations';
		$this->data['subview'] = 'template/form_validation';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Form Button
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function form_button()
	{	
		$this->data['title'] = 'Form Buttons';
		$this->data['subview'] = 'template/form_button';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Form Wizard
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function form_wizard()
	{	
		$this->data['title'] = 'Form Wizards';
		$this->data['subview'] = 'template/form_wizard';
		$this->load->view('components/main', $this->data);
	}

	/**
     * File Upload
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function file_upload()
	{	
		$this->data['title'] = 'File Upload';
		$this->data['subview'] = 'template/file_upload';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Wysiwyg Editor
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function wysiwyg_editor()
	{	
		$this->data['title'] = 'Wysiwyg Editor';
		$this->data['subview'] = 'template/wysiwyg_editor';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Basic Table
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function basic_table()
	{	
		$this->data['title'] = 'Basic Table';
		$this->data['subview'] = 'template/basic_table';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Datatable
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function datatable()
	{	
		$this->data['title'] = 'Datatable';
		$this->data['subview'] = 'template/datatable';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Chart
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function chart()
	{	
		$this->data['title'] = 'Charts';
		$this->data['subview'] = 'template/chart';
		$this->load->view('components/main', $this->data);
	}

    /**
     * Landing Page
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function landing_page()
    {   
        $this->data['title'] = 'Landing Page';
        $this->data['subview'] = 'template/landing_page';
        $this->load->view('components/front_layout', $this->data);
    }

	/**
     * Invoice
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function invoice()
	{	
		$this->data['title'] = 'Invoices';
		$this->data['subview'] = 'template/invoice';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Login
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function login_form()
	{	
		$this->data['title'] = 'Login';
		$this->data['subview'] = 'template/login';
		$this->load->view('components/layout', $this->data);
	}

	/**
     * Register
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function register_form()
	{	
		$this->data['title'] = 'Register';
		$this->data['subview'] = 'template/register';
		$this->load->view('components/layout', $this->data);
	}

	/**
     * Lock Screen
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function lock_screen()
	{	
		$this->data['title'] = 'Lock Screen';
		$this->data['subview'] = 'template/lock_screen';
		$this->load->view('components/layout', $this->data);
	}

	/**
     * Media
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function media()
	{	
		$this->data['title'] = 'Media';
		$this->data['subview'] = 'template/media';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Chat
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function chat()
	{	
		$this->data['title'] = 'Chat';
		$this->data['subview'] = 'template/chat';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Error 404
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function error_404()
	{	
		$this->data['title'] = 'Error 404';
		$this->data['subview'] = 'template/error_404';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Error 500
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function error_500()
	{	
		$this->data['title'] = 'Error 500';
		$this->data['subview'] = 'template/error_500';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Blank Layout
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function blank_layout()
	{	
		$this->data['title'] = 'Blank Layout';
		$this->data['subview'] = 'template/blank_layout';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Calendar
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function calendar()
	{	
		$this->data['title'] = 'Calendar';
		$this->data['subview'] = 'template/calendar';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Font Awesome
     *
     * @access  public
     * @param   
     * @return  view
     */

	public function font_awesome()
	{	
		$this->data['title'] = 'Font Awesome';
		$this->data['subview'] = 'template/font_awesome';
		$this->load->view('components/main', $this->data);
	}

	/**
     * Batch
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function batch()
    {
        $this->data['title'] = 'Batch';
        $this->data['subview'] = 'template/batch';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Dashicon
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function dashicon()
    {
        $this->data['title'] = 'Dashicon';
        $this->data['subview'] = 'template/dashicon';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Dripicon
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function dripicon()
    {
        $this->data['title'] = 'Dripicon';
        $this->data['subview'] = 'template/dripicon';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Eightyshades
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function eightyshades()
    {
        $this->data['title'] = 'Eightyshades';
        $this->data['subview'] = 'template/eightyshades';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Foundation
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function foundation()
    {
        $this->data['title'] = 'Foundation';
        $this->data['subview'] = 'template/foundation';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Metrize
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function metrize()
    {
        $this->data['title'] = 'Metrize';
        $this->data['subview'] = 'template/metrize';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Simple Line
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function simple_line()
    {
        $this->data['title'] = 'Simple Line';
        $this->data['subview'] = 'template/simple_line';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Themify
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function themify()
    {
        $this->data['title'] = 'Themify';
        $this->data['subview'] = 'template/themify';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Typeicon
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function typeicon()
    {
        $this->data['title'] = 'Typeicon';
        $this->data['subview'] = 'template/typeicon';
        $this->load->view('components/main', $this->data);
    }

    /**
     * Weather Icon
     *
     * @access  public
     * @param   
     * @return  view
     */

    public function weather_icon()
    {
        $this->data['title'] = 'Weather Icon';
        $this->data['subview'] = 'template/weather_icon';
        $this->load->view('components/main', $this->data);
    }

}
