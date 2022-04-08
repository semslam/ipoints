<?php

class Migration_payment_processors extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'varchar',
                'constraint' => 150,
                'null' => false
            ),
            'charges' => array(
                'type' => 'double',
                'constraint' => '11,4',
                'default' => 0,
                'null' => false,
            ),
            'is_flat_charges' => array(
                'type' => 'tinyint',
                'constraint' => 1,
                'null' => false,
                'default' => 0
            ),
            'is_web_enabled' => array(
                'type' => 'tinyint',
                'constraint' => 1,
                'null' => false,
                'default' => 0
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payment_processors', true);
        $this->db->insert('payment_processors', ['name' => 'Flutterwave']);
    }

    public function down() {
        $this->dbforge->drop_table('payment_processors');
    }

}
