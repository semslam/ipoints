<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_batch_id_to_services_log extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('services_log',[
			'batch_id'=>[
				'type'=>'VARCHAR',
				'constraint'=>65,
				'unique'=>true,
				'after'=>'id'
			]
		]);
	}
	public function down()
	{

	}
}
?>