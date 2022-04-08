<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Modify_all_colums_ipoint_value_to_double extends CI_Migration
{
	public function up()
	{
		$this->dbforge->modify_column('wip_transaction', [
            'qty' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
            ]
		]);
		$this->dbforge->modify_column('wip_bulk_transfer_requests', [
            'total_transaction_value' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
            ]
		]);
		
		$this->dbforge->modify_column('user_subscriptions', [
            'cost' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			],
			'total_paid' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
            ]
		]);
		
		$this->dbforge->modify_column('user_balance', [
            'balance' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			],
			'overdraft_limit' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
            ]
		]);
		
		$this->dbforge->modify_column('transactions', [
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			]
		]);
		
		$this->dbforge->modify_column('services_log', [
            'value' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			]
		]);
		
		$this->dbforge->modify_column('products', [
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			]
		]);
		
		$this->dbforge->modify_column('payment_purchases', [
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			],
			'quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			]
        ]);$this->dbforge->modify_column('payment_purchases', [
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			],
			'quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			]
        ]);

	}
	public function down()
	{

	}
}
?>