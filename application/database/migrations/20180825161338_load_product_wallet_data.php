<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Load_product_wallet_data extends CI_Migration
{
	public function up()
	{
		$this->load->model('Wallet');
		Wallet::insert([
			'name'=>'iIncome',
			'type'=>Wallet::TYPE_SYSTEM,
			'can_user_inherit'=>1
		]);
		$this->load->model('Product');
		$products = Product::findAll();
		foreach($products as $product){
			$product->getWallet();
		}


	}
	public function down()
	{

	}
}
?>