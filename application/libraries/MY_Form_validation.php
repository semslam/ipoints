<?php

class MY_Form_validation extends CI_Form_validation {

    public function __construct() {
        parent::__construct();
    }

    /**
    * Return all validation errors
    *
    * @access  public
    * @return  array
    */

    function get_all_errors() {
        $error_array = array();

        if (count($this->_error_array) > 0) {
            foreach ($this->_error_array as $k => $v) {
                $error_array[$k][] = $v;
            }
            return $error_array;
        }

        return false;

    }
	/*
	|	CodeIgniter has a nice Form_validation class. It comes with several validation rules:
	|	These are useful, but there is an important one missing from this list: to check for unique values. 
	|	For example, most user registration forms need to check that the username is not already taken, or the e-mail address is not already in the system.

	|	With this hack, you will be able add this validation rule to your form submission handler very easily:
			
	|	$this->form_validation->set_rules('username', 'Username',
	|			'required|alpha_numeric|min_length[6]|unique[User.username]');

	|	Note the last part that says "unique[User.username]." This new validation rule is just called "unique," 
	|	and takes a parameter inside the square brackets, which is "tablename.fieldname". 
	|	So it will check the "username" column of the "User" table to make sure the submitted value does not already exist.

	|	Similarly, you can check for duplicate e-mails:
			
	|	$this->form_validation->set_rules('email', 'E-mail',
    |   'required|valid_email|unique[User.email]');
	|   ref:https://code.tutsplus.com/tutorials/6-codeigniter-hacks-for-the-masters--net-8308
	*/
	function unique($value, $params) {
 
        $CI =& get_instance();
        $CI->load->database();
 
        $CI->form_validation->set_message('unique',
            'The %s is already being used.');
 
        list($table, $field) = explode(".", $params, 2);
 
        $query = $CI->db->select($field)->from($table)
            ->where($field, $value)->limit(1)->get();
 
        if ($query->row()) {
            return false;
        } else {
            return true;
        }
 
    }
}