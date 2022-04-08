<?php

class Key_m extends CI_Model {   

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
        $table  = 'api_keys as ak';
        $select = 'ak.*, u.name';

        $replace_field  = [
            ['old_name' => 'name', 'new_name' => 'u.name']
        ];

        $param = [
            'input'     => $input,
            'select'    => $select,
            'table'     => $table,
            'replace_field' => $replace_field
        ];

        $data = $this->datagrid->query($param, function($data) use ($input) {
            return $data->join('users as u', 'u.id = ak.user_id', 'left');
        });


        return $data;
    }


    public function _generate_key()
    {
        do
        {
            // Generate a random salt
            $salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);

            // If an error occurred, then fall back to the previous method
            if ($salt === FALSE)
            {
                $salt = hash('sha256', time() . mt_rand());
            }
           
            $new_key = substr($salt, 0, 65);
        }
        while ($this->_key_exists($new_key));
       
        return $new_key;
    }

    private function _key_exists($key)
    {
        return $this->datagrid->db
            ->where('api_key', $key)
            ->count_all_results('api_keys') > 0;
    }


    public function insertKey(){

    }

    public function updateKey(){}

    public function keyLevel(){}

}