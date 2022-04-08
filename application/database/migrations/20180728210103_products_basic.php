<?php

class Migration_products_basic extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'weighting' => array(
                'type' => 'int',
                'constraint' => 11
            ),
            'parent_product' => array(
                'type' => 'int',
                'constraint' => 11
            ),
            'created_by' => array(
                'type' => 'int',
                'constraint' => 11
            ),
            'last_updated_by' => array(
                'type' => 'int',
                'constraint' => 11
            ),
            'created' => array(
                'type' => 'DATETIME'
            ),
            'updated' => array(
                'type' => 'DATETIME'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('products_basic', true);
    }

    public function down() {
        $this->dbforge->drop_table('products_basic');
    }

}
