<?php

class Migration_menu extends CI_Migration {

    public function up() {
        $this->db->where('id', 14);
        $this->db->update('menus', [
            'title' => 'Buy A Product',
            'link' => 'purchase/product'
        ]);
    }

    public function down() {
        
    }

}