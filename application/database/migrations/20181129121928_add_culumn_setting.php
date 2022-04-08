<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_culumn_setting extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('settings',[
			'meta_type'=>[
				'type'=>'ENUM("text","number","textarea")',
				'after'=>'meta_value'
			],
			'meta_label'=>[
				'type'=>'VARCHAR',
				'constraint'=>20,
				'after'=>'meta_type'
			],
			'meta_description'=>[
				'type'=>'TEXT',
				'after'=>'meta_label'
			],
			'author_by'=>[
				'type'=>'INT',
				'constraint'=>11,
				'after'=>'meta_description'
			]
		]);
	}
	public function down()
	{

	}
}
?>