<?php

class Product_m extends CI_Model {   

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
        $table  = 'products as a';
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


    public function get_wallet($id){
        $this->db->from('user_balance ub'); 
        $this->db->select(' ub.*, if( length(u.name), u.name, u.business_name) name, pw.product_name');
        $this->db->where('u.status', 1);
        $this->db->join('products_wallet as pw', 'pw.id = ub.wallet_id', 'inner');
        $this->db->join('users as u', 'u.id = ub.user_id', 'inner');
		return $this->db->get()->result();
    }

}