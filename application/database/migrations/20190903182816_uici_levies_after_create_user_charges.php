<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Uici_levies_after_create_user_charges extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('uici_levies', [
			'levies_name' => [
				'type' => 'VARCHAR',
				'constraint' => 250,
				'null' => true,
				'after'=>'id',
			],
			'type' => [
				'type' => 'ENUM("system","user","all")',
				'null' => false,
				'default'=>'all',
				'after'=>'value',
			],
			'value_type' => [
				'type' => 'ENUM("percentage","iPoints")',
				'null' => false,
				'default'=>'iPoints',
				'after'=>'type',
			]
		]);

		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
			),
			'status' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
				'null' => false,
				'default'=>'0',
			),
			'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
				'null' => false
            ),
			'uici_levies' => array(
				'type' => 'INT',
				'constraint' => 11,
				'null' => false,
			),
			'created_at' => array(
			  'type' => 'DATETIME',
			),
			'updated_at' => array(
			  'type' => 'DATETIME',
			)
		));
		
		
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('user_charges', true);


	}
	public function down()
	{

	}
}
?>