<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_gifting_config extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
			),
			'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
				'null' => true
            ),
			'wallet_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'null' => true,
			),
			'message_temp' => array(
                'type' => 'TEXT',
                'null' => true,
			),
			'process_type' => array(
                'type' => 'ENUM("default","espi")',
				'null' => false,
				'default'=>'default',
			),
			'message_designate' => array(
                'type' => 'ENUM("old","new","all")',
				'null' => false,
				'default'=>'all',
            ),
			'send_message' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
				'null' => false,
				'default'=>'0',
			),
			'config_type' => array(
                'type' => 'ENUM("bulk","single")',
				'null' => false,
				'default'=>'bulk',
            ),
			'created_at' => array(
			  'type' => 'DATETIME',
			),
			'updated_at' => array(
			  'type' => 'DATETIME',
			),
			'modified_by' => array(
                'type' => 'INT',
                'constraint' => 11,
				'null' => true
            )
		));
		
		
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('gifting_config', true);

		$this->dbforge->add_column('user_charges', [
			'modified_by' => [
				'type' => 'INT',
                'constraint' => 11,
				'null' => true,
				'after'=>'updated_at',
			]
		]);

		$this->dbforge->add_column('wip_bulk_transfer_requests', [
			'wallet_id' => [
				'type' => 'INT',
                'constraint' => 11,
				'null' => true,
				'after'=>'user_id',
			]
		]);

	}
	public function down()
	{

	}
}
?>