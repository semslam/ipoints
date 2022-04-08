<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_meassage_queue extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
			),
			'recipient_type' => array(
                'type' => 'ENUM("single","multi")',
				'null' => false,
				'default'=>'single',
            ),
			'recipient' => array(
                'type' => 'VARCHAR',
                'constraint' => 125,
				'null' => false
            ),
            'message_template_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ),
            'message_type' => array(
                'type' => 'ENUM("static","dynamic")',
				'null' => false,
				'default'=>'static',
			),
            'type' => array(
                'type' => 'ENUM("Email","Sms")',
				'null' => false,
				'default'=>'Email',
			),
			'message_variable' => array(
                'type' => 'TEXT',
				'null' => true,
			),
			'message_subject' => array(
                'type' => 'VARCHAR',
                'constraint' => 150,
				'null' => true,
			),
			'message_body' => array(
                'type' => 'TEXT',
                'null' => false,
            ),
            'status' => array(
                'type'=>'ENUM("pending","sent","failed")',
				'null'=>false,
				'default'=>'pending',
			),
			'error_note' => array(
                'type' => 'TEXT',
                'null' => true,
			),
			'attempt_set' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
			),
			'attempt_no' => array(
                'type' => 'INT',
				'constraint' => 11,
				'default'=> 0,
			),
			'priority' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
			),
			'charge' => array(
                'type'=>'ENUM("free","paid","arrears","settle")',
				'null'=>false,
				'default'=>'free',
			),
			'attachment_url' => array(
                'type' => 'TEXT',
                'null' => true,
			),
			'created_at' => array(
			  'type' => 'DATETIME',
			),
			'updated_at' => array(
			  'type' => 'DATETIME',
			)
		));
		
		
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('message_queue', true);
		$this->db->simple_query('ALTER TABLE message_queue ADD CONSTRAINT fk_of_message_template_id FOREIGN KEY (`message_template_id`) REFERENCES  message_template(id)');
		
		$this->dbforge->drop_column('message_template', 'target_user');

		$this->dbforge->modify_column('message_template', [
            'message_channel' => [
                'type' => 'ENUM("Email","Sms")',
				'null' => false,
				'default'=>'Email',
			]
        ]);


		$this->dbforge->add_column('message_template', [
			'charge'=>[
				'type'=>'ENUM("free","paid")',
				'null'=>false,
				'default'=>'free',
				'after'=>'message_channel'
			],
			'attempt_no' => [
                'type' => 'INT',
                'constraint' => 11,
				'null' => true,
				'after'=>'charge'
			],
			'priority' => [
                'type'=>'ENUM("1","2","3","4","5","6","7","8","9")',
				'null' => false,
				'default'=>'1',
				'after'=>'attempt_no'
			]
		]);

    

	}
	public function down()
	{

	}
}
?>