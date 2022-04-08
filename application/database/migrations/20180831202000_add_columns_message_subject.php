<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_columns_message_subject extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('message_template',[
			'message_subject'=>[
				'type'=>'VARCHAR',
				'constraint'=>65,
				'after'=>'id'
			],
		]);
	}
	public function down()
	{

	}
}
?>