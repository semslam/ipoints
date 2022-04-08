<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_offline_payment_table extends CI_Migration
{
	public function up()
	{
		// $this->dbforge->add_field('id');
		// $otherFields = [
		// 	'reference'=>[
		// 		'type'=>'VARCHAR',
		// 		'constraint'=>65,
		// 		'unique'=>true
		// 	],
		// 	'merchant_phone'=>[
		// 		'type'=>'VARCHAR',
		// 		'constraint'=>13
		// 	],
		// 	'is_approved'=>[
		// 		'type'=>'BOOLEAN',
		// 		'default'=>0
		// 	],
		// 	'approved_by'=>[
		// 		'type'=>'INT',
		// 		'null'=>true
		// 	],
		// 	'approved_date'=>[
		// 		'type'=>'DATETIME',
		// 		'null'=>true
		// 	],
		// 	'is_settled'=>[
		// 		'type'=>'BOOLEAN',
		// 		'default'=>0
		// 	],
		// 	'settled_date'=>[
		// 		'type'=>'DATETIME',
		// 		'null'=>true
		// 	],
		// 	'value_given'=>[
		// 		'type'=>'BOOLEAN',
		// 		'default'=>0
		// 	],
		// 	'value_given_date'=>[
		// 		'type'=>'DATETIME',
		// 		'null'=>true
		// 	]
		// ];
		// $this->dbforge->add_field($otherFields);
		// $this->dbforge->add_field("`amount` DECIMAL(15,2) NOT NULL");
		// $this->dbforge->add_field("`payment_purpose` TINYINT NOT NULL");
		// $this->dbforge->add_field("`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
		// $this->dbforge->create_table('offline_payments', TRUE);

	}
	public function down()
	{

	}
}
?>