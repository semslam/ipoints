<?php

class Migration_create_api_keys_table extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'provider_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'public_key' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ),
            'private_key' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ),
            'access_control' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ),
            'ips' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null
            ),
            'last_access' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => null
            ),
            'created_at' => array(
                'type' => 'DATETIME'
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => null
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_keys');

        if (defined('ENVIRONMENT') && (ENVIRONMENT == 'development' || ENVIRONMENT == 'staging')) {
            $this->db->insert('api_keys', [
                'public_key' => 'EDBDD5239D7AABD99EA0077794E50F60DCEE578F906B6A3CE978FB2EEDB4B29D',
                'private_key' => 'A138EDA6F4996D2DEBA06C1AF5B0FFCCCD57CCAE277680AC0255D5A09E9D0594',
                'access_control' => 'all',
                'created_at' => date('Y-m-d H:i:s') 
            ]);
        }
    }

    public function down() {
        $this->dbforge->drop_table('api_keys');
    }

}