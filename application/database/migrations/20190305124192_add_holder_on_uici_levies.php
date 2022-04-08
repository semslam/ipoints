<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_holder_on_uici_levies extends CI_Migration
{
	public function up()
	{

		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
			),
			'batch_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 65,
				'null' => false
            ),
            'merchant_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'ipin_value' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
			),
			'ipin_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 16,
				'null' => false,
				'unique'=>true
			),
			'wallet_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'status' => array(
                'type'=>'ENUM("new","active","cancel","used")',
				'null'=>false,
				'default'=>'new',
			),
			'used_by' => array(
				'type' => 'INT',
				'constraint' => 11,
				'null' => true,
			),
			'created_at' => array(
			  'type' => 'DATETIME',
			),
			'updated_at' => array(
			  'type' => 'DATETIME',
			)
		));
		
		
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('ipin_generate', true);
		$this->db->simple_query('ALTER TABLE ipin_generate ADD CONSTRAINT fk_of_mercaht_id FOREIGN KEY (`merchant_id`) REFERENCES users(id)');
		$this->db->simple_query('ALTER TABLE ipin_generate ADD CONSTRAINT fk_of_wallet_id FOREIGN KEY (`wallet_id`) REFERENCES wallets(id)');
		
		$this->dbforge->add_column('withdraw_request', [
			'bank_name'=>[
				'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => false,
				'after'=>'user_balance_id'
			],'account_number'=>[
				'type' => 'INT',
                'constraint' => 11,
                'null' => false,
				'after'=>'bank_name'
			]
		]);
	}
	public function down()
	{
		$this->dbforge->drop_table('ipin_generate');
		$this->dbforge->drop_table('withdraw_request');
	}
}
?>