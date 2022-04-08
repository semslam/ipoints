<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Product_benefit_table extends CI_Migration
{
	public function up()
	{

		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '11,2',
                'null' => false,
            ),
            'product_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 65,
                'null' => false,
            ),
            'status' => array(
                'type' => 'BOOLEAN',
                'default' => false,
			),
			'note' => array(
                'type' => 'TEXT',
			),
			'created_at' => array(
                'type' => 'DATETIME'
            ),
            'updated_at' => array(
                'type' => 'DATETIME'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('product_benefit', true);

	}
	public function down()
	{

	}
}
?>