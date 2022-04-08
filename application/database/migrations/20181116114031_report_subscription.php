<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Report_subscription extends CI_Migration
{
	public function up()
	{

		$this->dbforge->add_field([
			'id' => array(
			  'type' => 'INT',
			  'constraint' => '11',
			  'unsigned' => TRUE,
			  'auto_increment' => TRUE,
			),
			'user_id' => array(
			  'type' => 'INT',
			  'constraint' => '11',
			),
			'frequency' => array(
			  'type' => 'VARCHAR',
			  'constraint' => '11'
					  
			),
			'report_type' => array(
			  'type' => 'VARCHAR',
			  'constraint' => '150'
			),
			'send_to_all' => array(
			  'type' => 'VARCHAR',
			  'constraint' => '65'
			),
			'dispatcher_type' => array(
				'type' => 'VARCHAR',
				'constraint' => '56'
			 
			),
			'status' => array(
			  'type' => 'TINYINT',
			  'constraint' => '1',
			  'default' => 0
			),
			'author_by' => array(
				'type' => 'INT',
				'constraint' => '11'
			),
			'created_at' => array(
			  'type' => 'DATETIME',
			),
			'updated_at' => array(
			  'type' => 'DATETIME',
			)
		  ]);
		  $this->dbforge->add_key("id", true);
		  $this->dbforge->create_table('report_subscription', TRUE);

	}
	public function down()
	{

	}
}
?>