<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_fela_transaction extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
			),
			'request' => array(
                'type' => 'VARCHAR',
                'constraint' => 125,
				'null' => false,
				'unique'=> true
			),
			'reference' => array(
                'type' => 'VARCHAR',
                'constraint' => 125,
				'null' => false
            ),
			'status' => array(
                'type' => 'ENUM("completed","pending","failed")',
				'null' => false,
				'default'=>'pending',
			),
			'value' => array(
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
			),
			'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
            ),
			'recipient_count' => array(
                'type' => 'INT',
                'constraint' => 11,
				'null' => false
            ),
            'type' => array(
                'type' => 'ENUM("credit","debit","deposit","refund")',
				'null' => false,
				'default'=>'credit',
            ),
			'sender' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
			),
			'description' => array(
                'type' => 'TEXT',
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
		$this->dbforge->create_table('espi_transaction', true);
	}
	public function down()
	{

	}
}
?>