<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Modify_group_to_author_and_add_author_product_and_benefit extends CI_Migration
{
	public function up()
	{
		$this->dbforge->modify_column('products',[
			'group_id'=>[
				'name' => 'author_by',
				'type'=>'INT',
				'constraint'=>11,
				'after'=>'description'
			]
			
		]);

		$this->dbforge->add_column('product_benefit',[
			'author_by'=>[
				'type'=>'INT',
				'constraint'=>11,
				'after'=>'note'
			]
		]);
	}
	public function down()
	{

	}
}
?>