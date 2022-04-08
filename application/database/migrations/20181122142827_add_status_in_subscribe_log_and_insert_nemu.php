<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Add_status_in_subscribe_log_and_insert_nemu extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_column('services_log',[
			'is_active'=>[
				'type' => 'TINYINT',
				'constraint' => 1,
				'null' => false,
				'default' => 1,
				'after' => 'cover_period'
			],
		]);

		$menus = array(
			array('id' => '1','parent_id' => '0','is_have_child' => '4','title' => 'Settings','link' => '','icon' => 'fa fa-cog'),
			array('id' => '2','parent_id' => '1','is_have_child' => '0','title' => 'Users','link' => 'user','icon' => ''),
			array('id' => '3','parent_id' => '1','is_have_child' => '0','title' => 'Groups','link' => 'group','icon' => ''),
			array('id' => '4','parent_id' => '1','is_have_child' => '0','title' => 'Privileges','link' => 'privilege','icon' => ''),
			array('id' => '5','parent_id' => '0','is_have_child' => '5','title' => 'Reports','link' => '','icon' => 'fa fa-pie-chart'),
			array('id' => '6','parent_id' => '5','is_have_child' => '0','title' => 'Report','link' => 'reports','icon' => ''),
			array('id' => '7','parent_id' => '5','is_have_child' => '0','title' => 'Reports Subscription','link' => 'reports/reports_subscription','icon' => ''),
			array('id' => '8','parent_id' => '5','is_have_child' => '0','title' => 'Annual Fee','link' => 'fundamental/annual_fee','icon' => ''),
			array('id' => '9','parent_id' => '0','is_have_child' => '9','title' => 'Fundamental','link' => '','icon' => 'fa fa-cubes'),
			array('id' => '10','parent_id' => '9','is_have_child' => '0','title' => 'Wallets','link' => 'fundamental/wallets','icon' => ''),
			array('id' => '11','parent_id' => '9','is_have_child' => '0','title' => 'Products','link' => 'fundamental/products','icon' => ''),
			array('id' => '12','parent_id' => '9','is_have_child' => '0','title' => 'Product Benefits','link' => 'fundamental/benefits','icon' => ''),
			array('id' => '13','parent_id' => '0','is_have_child' => '0','title' => 'Update KYC','link' => 'import','icon' => 'fa fa-upload'),
			array('id' => '14','parent_id' => '0','is_have_child' => '0','title' => 'Message Template','link' => 'messagetemplate','icon' => 'fa fa-envelope'),
			array('id' => '15','parent_id' => '0','is_have_child' => '15','title' => 'Offline Process','link' => '','icon' => 'fa fa-wifi'),
			array('id' => '16','parent_id' => '15','is_have_child' => '0','title' => 'Create Request','link' => 'offline/create_request','icon' => ''),
			array('id' => '17','parent_id' => '15','is_have_child' => '0','title' => 'Offline Approve Manager','link' => 'offline','icon' => ''),
			array('id' => '18','parent_id' => '15','is_have_child' => '0','title' => 'M Create Request','link' => 'offline/merchant_create_request','icon' => ''),
			array('id' => '19','parent_id' => '15','is_have_child' => '0','title' => 'M Offline Manager','link' => 'offline/purchase_manager','icon' => ''),
			array('id' => '20','parent_id' => '0','is_have_child' => '0','title' => 'User Profile','link' => 'profile','icon' => 'fa fa-user'),
			array('id' => '21','parent_id' => '0','is_have_child' => '21','title' => 'Products','link' => '','icon' => 'fa fa-tags'),
			array('id' => '22','parent_id' => '21','is_have_child' => '0','title' => 'Product Subscription Log','link' => 'servicesLog','icon' => ''),
			array('id' => '23','parent_id' => '21','is_have_child' => '0','title' => 'Purchase Product','link' => 'purchase/product','icon' => ''),
			array('id' => '24','parent_id' => '0','is_have_child' => '0','title' => 'Configuration Settings','link' => 'settings ','icon' => 'fa fa-wrench'),
			array('id' => '25','parent_id' => '0','is_have_child' => '0','title' => 'Change Password','link' => 'change_password','icon' => 'fa fa-unlock-alt')
		  );
		  

		$this->db->insert_batch('menus', $menus);

	}
	public function down()
	{

	}
}
?>