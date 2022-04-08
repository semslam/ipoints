<?php

class Migration_modify_products extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('products', [
            'is_insurance_prod' => [
                'type' => 'tinyint',
                'constraint' => 1,
                'null' => false,
                'default' => 0,
                'after' => 'price'
            ]
        ]);

        $this->dbforge->modify_column('products', [
            'price' => array(
                'type' => 'double',
                'constraint' => '11,2',
                'null' => false
            )
        ]);
    }

    public function down() {
        $this->dbforge->drop_table('payment_processors');
    }

}