<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Insert_offline_in_payment_processors_table extends CI_Migration
{
	public function up()
	{
		$payment_processors = array(
			array('name' => 'Offline','charges' => '0.0000','is_flat_charges' => '0','is_web_enabled' => '0')
		  );
	
		$this->db->insert_batch('payment_processors', $payment_processors);
	}
	public function down()
	{

	}
}
?>