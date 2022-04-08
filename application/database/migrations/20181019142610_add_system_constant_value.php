<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_system_constant_value extends CI_Migration
{
	public function up()
	{

		$settings = array(
			array('meta_key' => 'iPoint_unit_price','meta_value' => '1.20'),
			array('meta_key' => 'sms_charge','meta_value' => '2')
		  );
	
		$this->db->insert_batch('settings', $settings);

	}
	public function down()
	{

	}
}
?>