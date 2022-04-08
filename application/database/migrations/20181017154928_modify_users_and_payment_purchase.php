<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Modify_users_and_payment_purchase extends CI_Migration
{
	public function up()
	{

		$this->dbforge->add_column('payment_purchases',[
			'requested_by'=>[
				'type'=>'INT',
				'constraint'=>11,
				'after'=>'processing_status'
			],
			'approved_by'=>[
				'type'=>'INT',
				'constraint'=>11,
				'after'=>'requested_by'
			]
		]);

		$this->dbforge->add_column('users',[
			'gender'=>[
				'type'=>'ENUM("male","female")',
				'after'=>'name'
			],
			'states'=>[
				'type'=>'INT',
				'constraint'=>11,
				'after'=>'birth_date'
			]
		]);


		$this->db->simple_query('ALTER TABLE products ADD CONSTRAINT fk_of_provider_id FOREIGN KEY (`provider_id`) REFERENCES service_provider(id)');
		$this->db->simple_query('ALTER TABLE payment_purchases ADD CONSTRAINT fk_of_payment_processor_id FOREIGN KEY (`payment_processor`) REFERENCES payment_processors(id)');

	}
	public function down()
	{

	}
}
?>