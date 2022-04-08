<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Change_column_in_wip_transaction extends CI_Migration
{
	public function up()
	{
		$this->dbforge->modify_column('wip_transaction',[
			'ipoints_source'=>[
				'name' => 'message',
				'type'=>'ENUM("new","old","done")',
				'after'=>'notes'
			],
			
		]);

	}
	public function down()
	{

	}
}
?>