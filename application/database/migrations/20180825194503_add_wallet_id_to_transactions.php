<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_wallet_id_to_transactions extends CI_Migration
{
	public function up()
	{
		$columns = [
			"wallet_id"=>[
				"type"=>"INT",
				"after"=>"user_id"
			]
		];
		$this->dbforge->add_column('transactions',$columns);
	}
	public function down()
	{

	}
}
?>