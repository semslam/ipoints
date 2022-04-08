<?php

class Migration_create_access_tokens_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'app_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'token' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ),
            'expiry' => array(
                'type' => 'DOUBLE',
                'null' => false,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('access_tokens');
    }

    public function down() {
        $this->dbforge->drop_table('access_tokens');
    }

}