<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Reorder_columns_products extends CI_Migration
{
	public function up()
	{
		$this->dbforge->modify_column('products',[
			'allowable_tenure'=>[
				'type'=>'INT',
				'default'=>0,
				'after'=>'base_product_yn'
			],
			'provider_id'=>[
				'type'=>'INT',
				'null'=>true,
				'after'=>'allowable_tenure'
			],
			'is_group_prod'=>[
				'type'=>'BOOLEAN',
				'default'=>0,
				'after'=>'provider_id'
			],
			'group_id'=>[
				'type'=>'INT',
				'null'=>true,
				'after'=>'is_group_prod'
			]
		]);
	}
	public function down()
	{

	}
}
?>