<?php

class Transaction_detail_m extends CI_Model {   

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
        $table  = 'transaction_details as a';
        $select = 'a.*, p.product_name';

        $replace_field  = [
            ['old_name' => 'product_name', 'new_name' => 'p.product_name']
        ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input) {
            return $data->join('products as p', 'p.id = a.product_id', 'left')
                        ->where('a.transaction_id', $input['transaction_id']);
        });

        return $data;
    }

}