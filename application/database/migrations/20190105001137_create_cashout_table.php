<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_cashout_table extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'user_balance_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
            ),
            'status' => array(
                'type'=>'ENUM("processing","pending","processed","cancel")',
				'null'=>false,
				'default'=>'pending',
			),
			'author_by' => array(
				'type' => 'INT',
				'constraint' => 11
			),
			'created_at' => array(
			  'type' => 'DATETIME',
			),
			'updated_at' => array(
			  'type' => 'DATETIME',
			)
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('cash_out', true);
	}
	public function down()
	{

	}
}
?>