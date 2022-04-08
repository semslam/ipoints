<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Modify_merchant_phone_to_userid extends CI_Migration
{
	public function up(){

		
		// $this->dbforge->modify_column('offline_payments',[
		// 	'merchant_phone'=>[
		// 		'name' => 'user_id',
		// 		'type'=>'INT',
		// 		'constraint' => 11,
		// 		'after'=>'reference'
		// 	],
			
		// ]);

		// $this->dbforge->add_column('offline_payments',[
		// 	'status'=>[
		// 		'type'=>'ENUM("pending","approved","canceled","processed")',
		// 		'after'=>'user_id'
		// 	],
		// 	'updated_at'=>[
		// 		'type'=>'CURRENT_TIMESTAMP',
		// 		'after'=>'created_at'
		// 	]
		// ]);

		
		//$this->db->simple_query('ALTER TABLE offline_payments ADD CONSTRAINT fk_of_user_id FOREIGN KEY (`user_id`) REFERENCES users(id)');

	}
	public function down()
	{

	}
}
?>