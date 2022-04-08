<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_withdraw_request extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
			),
			'transaction_reference' => array(
                'type' => 'VARCHAR',
                'constraint' => 11,
                'null' => false,
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
                'type'=>'ENUM("processing","pending","approved","cancel")',
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
		$this->dbforge->create_table('withdraw_request', true);
		
		$this->db->simple_query('ALTER TABLE withdraw_request ADD CONSTRAINT fk_of_withdraw_request_id FOREIGN KEY (`user_balance_id`) REFERENCES user_balance(id)');
		$this->db->simple_query('TABLE `withdraw_request` ADD INDEX(`transaction_reference`)');
	}
	public function down()
	{
		$this->dbforge->drop_table('cash_out');
	}
}
?>