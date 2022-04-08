<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Cumulative_report_log extends CI_Migration
{
	public function up()
	{

		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
            ),
            'file_path' => array(
                'type' => 'varchar',
                'constraint' => 511,
                'null' => false,
			),
			'expires_at' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
        ));
        $this->dbforge->add_field("`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('expires_at');
		$this->dbforge->create_table('cumulative_report_log', true);
		
	}

	public function down()
	{
		$this->dbforge->drop_table('cumulative_report_log');
	}
}
?>