<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_menu extends CI_Migration
{
	public function up()
	{
		$this->db->truncate('privileges');
	
		$privileges = array(
			array('id' => '6','group_id' => '1','menu_id' => '1'),
			array('id' => '7','group_id' => '1','menu_id' => '2'),
			array('id' => '8','group_id' => '1','menu_id' => '3'),
			array('id' => '9','group_id' => '1','menu_id' => '4')
		  );
		$this->db->insert_batch('privileges', $privileges);
	}
	public function down()
	{

	}
}
?>