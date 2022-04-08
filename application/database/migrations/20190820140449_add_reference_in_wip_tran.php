<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_reference_in_wip_tran extends CI_Migration
{
	public function up()
	{

		$this->dbforge->add_column('wip_transaction', [
			'unq_reference' => [
				'type' => 'VARCHAR',
				'constraint' => 250,
				'null' => true,
				'after'=>'message',
			]
		]);

	}
	public function down()
	{

	}
}
?>