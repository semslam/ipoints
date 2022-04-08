<?php

class Config_m extends CI_Model {   

    function __construct()
    {
        parent::__construct();
        $this->load->library('datagrid');
    }

    /**
     * Get List of Modes
     *
     * @access 	public
     * @param 	
     * @return 	json(array)
     */

    public function all()
    {
    	$modes = $this->db->get('modes')->result();
		return $modes;
    }

    /**
     * Get Group by ID
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

    public function get_mode($id)
    {
        $query = $this->db->from('modes g')
                        ->select('g.*')
                        ->where('g.id', $id)
                        ->get();

        return $query->row();
    }

    /**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

    public function getJson($input)
    {
        $table  = 'modes as a';
        $select = 'a.*';

        $replace_field  = [
            ['old_name' => 'mode_name', 'new_name' => 'a.mode_name']
        ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input) {
            return $data;
        });

        return $data;
    }

}