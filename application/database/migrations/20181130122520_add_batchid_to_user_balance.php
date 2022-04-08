<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_batchid_to_user_balance extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('user_balance', [
			'batch_id'=>[
				'type'=>'VARCHAR',
				'constraint'=>64,
				'null'=>true,
				'default'=>null
			]
		]);
		
		$this->db->simple_query('ALTER TABLE user_balance ADD INDEX batch_id_indx (`batch_id`)');
		$this->db->simple_query('ALTER TABLE `transaction` ADD UNIQUE INDEX trans_uniq_identifier (`reference`, `type`, `sender_id`, `receiver_id`)');
		$this->db->simple_query('ALTER TABLE `products` DROP FOREIGN KEY product_provider_fk');
		$this->db->simple_query('ALTER TABLE `products` ADD CONSTRAINT product_provider_fk FOREIGN KEY (provider_id) REFERENCES users(id)');
	}

	public function down() { }
}
?>