<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_reference_to_subscription_table extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('user_subscriptions',[
			'txn_reference'=>[
				'type'=>'VARCHAR',
				'constraint'=>65,
				'unique'=>true,
				'after'=>'user_id'
			]
		]);
		$this->dbforge->add_column('services_log',[
			'txn_reference'=>[
				'type'=>'VARCHAR',
				'constraint'=>65,
				'unique'=>true,
				'after'=>'user_id'
			]
		]);
	}
	public function down()
	{

	}
}
?>