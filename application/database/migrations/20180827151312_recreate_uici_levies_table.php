<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Recreate_uici_levies_table extends CI_Migration
{
	public function up()
	{
		$this->dbforge->drop_table('uici_levies', true);
		$this->dbforge->add_field('id');
		$other_fields = [
			'name'=>[
				'type'=>'VARCHAR',
				'constraint'=>45,
				'unique'=>true
			],
			'value'=>[
				'type'=>'DECIMAL(17,2)'
			]
		];
		$this->dbforge->add_field($other_fields);
		$this->dbforge->add_field("`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->create_table('uici_levies', true);
		$this->db->insert_batch('uici_levies',[
			[
				'name'=>'charge_per_ipoint_sold',
				'value'=>0.20
			],
			[
				'name'=>'user_annual_subscription',
				'value'=>60.00
			],
			[
				'name'=>'sms_charge',
				'value'=>60.00
			]
		]);
	}
	public function down()
	{

	}
}
?>