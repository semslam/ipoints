<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_annual_charged_user_balance extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('products', [
			'commission_wallet_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'null' => true,
				'after'=>'charge_commission_id',
			]
		]);

		$this->dbforge->add_column('services_log', [
			'batch_id' => [
				'type' => 'VARCHAR',
				'constraint' => 150,
				'null' => true,
				'after'=>'id',
			]
		]);

		$this->db->simple_query('ALTER TABLE products ADD CONSTRAINT fk_of_commission_wallet_id FOREIGN KEY (`commission_wallet_id`) REFERENCES wallets(id)');

		$this->dbforge->modify_column('users', [
            'birth_date' => [
                'type' => 'DATE',
                'null' => true,
			]
		]);
		

		$this->dbforge->modify_column('wallets', [
			'type' => [
				'type' => 'ENUM("product","system","savings","commission")',
				'null'=>false,
				'default'=>'system'
			]
		]);

		$this->dbforge->modify_column('wip_transaction', [
			'status' => [
				'type' => 'ENUM("pending","processing","completed","invalid","cancel")',
				'null'=>false,
				'default'=>'pending'
			]
		]);


	}

	public function down() { }
}
