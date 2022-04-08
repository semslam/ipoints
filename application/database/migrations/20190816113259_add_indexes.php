<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_indexes extends CI_Migration
{
	public function up()
	{
		$this->db->simple_query('ALTER TABLE wip_transaction ADD INDEX (request_id)');
		$this->db->simple_query('ALTER TABLE wip_transaction ADD INDEX (request_id, `status`)');
	}
	public function down()
	{

	}
}
?>