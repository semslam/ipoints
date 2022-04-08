<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Update_products_data extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('wallets', [
			'can_topup' => [
				'type' => 'TINYINT',
				'constraint' => 1,
				'null' => false,
				'default' => 1,
				'after' => 'can_user_inherit'
			]
		]);

		// sanitize products and wallet tables
		$this->db->simple_query('ALTER TABLE `services_log` DROP FOREIGN KEY fk_product_id');
		$this->db->simple_query('ALTER TABLE `wallets` DROP FOREIGN KEY product_id_fk');
		$this->dbforge->drop_column('products', 'id');
		$this->db->simple_query("DELETE FROM `products` WHERE product_name IN ('iPoints','iSavings')");
		$this->dbforge->add_column('products', [
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'auto_increment' => true,
				'unique' => true,
				'first' => true
			]
		]);
		$this->db->simple_query('ALTER TABLE `products` ADD CONSTRAINT products_id_pk PRIMARY KEY');
		$this->db->simple_query("UPDATE `wallets` SET `type`='system', product_id=null WHERE `name` IN ('iPoints','iSavings')");
		$this->db->simple_query("UPDATE `wallets` SET can_topup=0 WHERE type='system' AND `name`!='iPoints'");
		$this->db->simple_query("UPDATE `wallets` w INNER JOIN `products` p ON w.name = p.product_name SET w.product_id = p.id");
		$this->db->simple_query('ALTER TABLE `wallets` ADD CONSTRAINT product_id_fk FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON UPDATE CASCADE');
		$this->db->simple_query('ALTER TABLE `services_log` ADD CONSTRAINT fk_product_id FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON UPDATE CASCADE');

		// Modify payment_purchases table
		$this->dbforge->add_column('payment_purchases', [
			'tenure' => [
				'type' => 'int',
				'constraint' => 2,
				'unsigned' => TRUE,
				'null' => true,
				'default' => null,
				'after' => 'quantity',
				// 'comment' => 'Number of months of subscription'
			]
		]);
		
		$this->dbforge->modify_column('payment_purchases', [
			'product_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'null' => true,
				'default' => null
			],
			'wallet_cover_id' => [
				'name' => 'wallet_id',
				'type' => 'int',
				'constraint' => 11,
				'unsigned' => TRUE,
				'after' => 'user_id',
				'null' => false,
			]
		]);
	}

	public function down() {	}
}
