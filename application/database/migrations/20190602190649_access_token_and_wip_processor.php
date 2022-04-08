<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Access_token_and_wip_processor extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('access_tokens', [
			'type'=>[
				'type' => 'ENUM("auth","oauth")',
				'null'=>false,
				'default'=>'oauth',
				'after'=>'id'
			]
		]);


		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
			),
			'access_token_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'null' => true,
			),
			'status' => array(
                'type' => 'ENUM("pending","processing","completed")',
				'null' => false,
				'default'=>'pending',
            ),
			'type' => array(
                'type' => 'VARCHAR',
                'constraint' => 125,
				'null' => false
            ),
            'data' => array(
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
		$this->dbforge->create_table('wip_oauth_process', true);

		$this->db->simple_query('ALTER TABLE wip_oauth_process ADD CONSTRAINT fk_of_access_token_id FOREIGN KEY (`access_token_id`) REFERENCES access_tokens(id)');
		
				
				
	}
	public function down()
	{

	}
}
?>