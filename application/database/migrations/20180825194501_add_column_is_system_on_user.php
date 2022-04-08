<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_column_is_system_on_user extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('users',[
			'is_system'=>[
				'type'=>'ENUM("1")',
				'unique'=>true,
				'null'=>true,
				'default'=>NULL,
				'after'=>'group_id'
			]
		]);
		$this->dbforge->add_column('user_balance',[
			'can_overdraft'=>[
				'type'=>'BOOLEAN',
				'default'=>0,
				'after'=>'balance'
			],
			'overdraft_limit'=>[
				'type'=>'INT',
				'null'=>true,
				'default'=>NULL,
				'after'=>'can_overdraft'
			]
		]);
	}
	public function down()
	{

	}
}
?>