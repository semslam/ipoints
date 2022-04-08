<?php

class Migration_modify_payment_purchases extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('payment_purchases', [
            'description' => [
                'type' => 'varchar',
                'constraint' => 255,
                'null' => true,
                'default' => null,
                'after' => 'wallet_cover_id'
            ]
        ]);
    }

}