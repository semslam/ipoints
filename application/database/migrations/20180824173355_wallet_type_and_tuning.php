<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Wallet_type_and_tuning extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field('id');
		$other_fields = [
			'name'=>[
				'type'=>'VARCHAR',
				'constraint'=>100,
				'unique'=>true,
			],
			'type'=>[
				'type'=>'ENUM("product","system")',
			],
			'product_id'=>[
				'type'=>'INT',
				'unique'=>true,
				'null'=>true
			],
			'can_user_inherit'=>[
				'type'=>'BOOLEAN'
			]
		];
		$this->dbforge->add_field($other_fields);
		$this->dbforge->add_field("`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->create_table('wallets', true);
		$this->db->insert('wallets',[
			'name' => 'Subscription',
			'type' => 'system',
			'can_user_inherit' => 0
		]);
		$this->dbforge->add_column('products',[
			'allowable_tenure'=>[
				'type'=>'INT',
				'default'=>0
			],
			'is_group_prod'=>[
				'type'=>'BOOLEAN',
				'default'=>0
			],
			'group_id'=>[
				'type'=>'INT',
				'null'=>true
			],
			'provider_id'=>[
				'type'=>'INT',
				'null'=>true
			]
		]);
	}
	public function down()
	{

	}
}
?>