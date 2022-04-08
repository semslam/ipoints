<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Drop_psg_id_in_products_and_add_it_in_wallet extends CI_Migration
{
	public function up()
	{


		
		
		$this->db->simple_query('ALTER TABLE products DROP FOREIGN KEY fk_of_product_service_group_id');
		$this->dbforge->drop_column('products', 'psg_id');
			
		$this->dbforge->rename_table('product_service_group', 'wallet_service_group');


		$this->dbforge->add_column('wallets', [
			'wsg_id'=>[
				'type'=>'INT',
				'constraint'=>11,
				'null'=>true,
				'default'=>null,
				'after'=>'product_id'
			]
		]);
		$this->db->simple_query('ALTER TABLE wallets ADD CONSTRAINT fk_of_wallet_service_group_id FOREIGN KEY (`wsg_id`) REFERENCES walltet_service_group(id)');

	}
	public function down()
	{

	}
}
?>