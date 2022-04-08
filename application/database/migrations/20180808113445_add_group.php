<?php

class Migration_add_group extends CI_Migration {

    public function up() {
        $data = [
            'group_name' => 'Subscriber',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('groups', $data);
    }

}