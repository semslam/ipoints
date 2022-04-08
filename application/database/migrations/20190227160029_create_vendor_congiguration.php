<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_vendor_congiguration extends CI_Migration
{
	public function up()
	{

		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'vendor_id' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'settle_automatically' => array(
                'type' => 'BOOLEAN',
            ),
			'created_at' => array(
			  'type' => 'DATETIME',
			),
			'updated_at' => array(
			  'type' => 'DATETIME',
			)
        ));
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('vendor_configuration', true);
		
		$this->db->simple_query('ALTER TABLE vendor_configuration ADD CONSTRAINT fk_of_vendor_configuration_id FOREIGN KEY (`vendor_id`) REFERENCES users(id)');

	}
	public function down()
	{

	}
}
?>