<?php

class Migration_payment_purchases extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'payment_ref' => array(
                'type' => 'varchar',
                'constraint' => 255,
                'null' => false
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'product_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
            ),
            'amount' => array(
                'type' => 'DOUBLE',
                'null' => false
            ),
            'quantity' => array(
                'type' => 'INT',
                'null' => false
            ),
            'payment_processor' => array(
                'type' => 'int',
                'constraint' => 5,
                'null' => false
            ),
            'payment_processor_fees' => array(
                'type' => 'double',
                'default' => 0
            ),
            'processing_status' => array(
                'type' => 'tinyint',
                'constraint' => 1
            ),
            'wallet_cover_id' => array(
                'type' => 'int',
                'constraint' => 11
            )
        ));
        $this->dbforge->add_field("`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`updated_at` TIMESTAMP NULL");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payment_purchases', true);
    }

    public function down()
    {
        $this->dbforge->drop_table('payment_purchases');
    }

}
