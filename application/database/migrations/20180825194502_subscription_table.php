<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Subscription_table extends CI_Migration
{
	public function up()
	{
		$this->dbforge->drop_table('user_subscriptions', true);
		$this->dbforge->add_field('id');
		$other_fields = [
			'user_id'=>[
				'type'=>'INT'
			],
			'cost'=>[
				'type'=>'INT'
			],
			'total_paid'=>[
				'type'=>'INT'
			],
			'is_complete'=>[
				'type'=>'BOOLEAN',
				'default'=>0
			],
			'is_latest'=>[
				'type'=>'BOOLEAN',
				'default'=>0,
			],
			'is_active'=>[
				'type'=>'BOOLEAN',
				'default'=>1
			]
		];
		$this->dbforge->add_field($other_fields);
		$this->dbforge->add_field("`start_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_field("`end_date` DATETIME NOT NULL ");
		$this->dbforge->create_table('user_subscriptions', true);
	}
	public function down()
	{

	}
}
?>