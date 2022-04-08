<?php

class Transaction_m extends CI_Model {   

    function __construct()
    {
        parent::__construct();
        $this->load->library('datagrid');
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
        $table  = 'transactions as a';
        $select = 'a.*';

        $replace_field  = [
            ['old_name' => 'product_name', 'new_name' => 'a.product_name']
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