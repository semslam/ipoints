<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Sms_charge extends CI_Migration
{
	public function up()
	{
		$wallets = array(
			array('name' => 'SmsCharge','type' => 'system','product_id' => null,'can_user_inherit' => '0','created_at' => '2018-08-31 12:36:01','updated_at' => '2018-08-31 12:36:01')
		  );
	
	$this->db->insert_batch('wallets', $wallets);

	}
	public function down()
	{

	}
}
?>