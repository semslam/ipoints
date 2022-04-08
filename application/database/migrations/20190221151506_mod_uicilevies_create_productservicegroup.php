<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Mod_uicilevies_create_productservicegroup extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ),
            'description' => array(
                'type' => 'TEXT',
            ),
			'created_at' => array(
			  'type' => 'DATETIME',
			),
			'updated_at' => array(
			  'type' => 'DATETIME',
			)
        ));
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('product_service_group', true);
		
		$this->dbforge->add_column('uici_levies', [
			'description'=>[
				'type'=>'TEXT',
				'after'=>'created_at'
			]
		]);
		

		$this->dbforge->add_column('products', [
			'charge_commission_id'=>[
				'type'=>'INT',
				'constraint'=>11,
				'null'=>true,
				'default'=>null,
				'after'=>'allowable_tenure'
			],
			'psg_id'=>[
				'type'=>'INT',
				'constraint'=>11,
				'null'=>true,
				'default'=>null,
				'after'=>'charge_commission_id'
			]
		]);
		
		 $this->db->simple_query('ALTER TABLE products ADD CONSTRAINT fk_of_uici_levies_id FOREIGN KEY (`charge_commission_id`) REFERENCES uici_levies(id)');
		// $this->db->simple_query('ALTER TABLE products ADD CONSTRAINT fk_of_product_service_group_id FOREIGN KEY (`psg_id`) REFERENCES product_service_group(id)');
	}
	public function down()
	{

	}
}
?>