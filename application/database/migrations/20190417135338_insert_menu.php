<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Insert_menu extends CI_Migration
{
	public function up()
	{
		//$this->db->truncate('menus');


		$menus = array(
			array('id' => '1','parent_id' => '0','is_have_child' => '4','title' => 'Settings','link' => '','icon' => 'fa fa-cog'),
			array('id' => '2','parent_id' => '1','is_have_child' => '0','title' => 'Users','link' => 'users','icon' => ''),
			array('id' => '3','parent_id' => '1','is_have_child' => '0','title' => 'Groups','link' => 'group','icon' => ''),
			array('id' => '4','parent_id' => '1','is_have_child' => '0','title' => 'Privileges','link' => 'privilege','icon' => ''),
			array('id' => '5','parent_id' => '0','is_have_child' => '5','title' => 'Reports','link' => '','icon' => 'fa fa-pie-chart'),
			array('id' => '6','parent_id' => '5','is_have_child' => '0','title' => 'Report','link' => 'reports','icon' => ''),
			array('id' => '7','parent_id' => '5','is_have_child' => '0','title' => 'Auto Email Report','link' => 'reports/reports_subscription','icon' => ''),
			array('id' => '8','parent_id' => '5','is_have_child' => '0','title' => 'Annual Fee','link' => 'fundamental/annual_fee','icon' => ''),
			array('id' => '9','parent_id' => '0','is_have_child' => '9','title' => 'Fundamental','link' => '','icon' => 'fa fa-cubes'),
			array('id' => '10','parent_id' => '9','is_have_child' => '0','title' => 'Wallets','link' => 'fundamental/wallets','icon' => ''),
			array('id' => '11','parent_id' => '9','is_have_child' => '0','title' => 'Products','link' => 'fundamental/products','icon' => ''),
			array('id' => '12','parent_id' => '9','is_have_child' => '0','title' => 'Product Benefits','link' => 'fundamental/benefits','icon' => ''),
			array('id' => '13','parent_id' => '0','is_have_child' => '13','title' => 'Import','link' => '','icon' => 'fa fa-upload'),
			array('id' => '14','parent_id' => '0','is_have_child' => '14','title' => 'Messages','link' => '','icon' => 'fa fa-envelope'),
			array('id' => '15','parent_id' => '0','is_have_child' => '15','title' => 'Offline Process','link' => '','icon' => 'fa fa-wifi'),
			array('id' => '16','parent_id' => '15','is_have_child' => '0','title' => 'Create Request','link' => 'offline/create_request','icon' => ''),
			array('id' => '17','parent_id' => '15','is_have_child' => '0','title' => 'Offline Approve Manager','link' => 'offline','icon' => ''),
			array('id' => '18','parent_id' => '15','is_have_child' => '0','title' => 'Create Offline Request','link' => 'offline/merchant_create_request','icon' => ''),
			array('id' => '19','parent_id' => '15','is_have_child' => '0','title' => 'Offline Manager','link' => 'offline/purchase_manager','icon' => ''),
			array('id' => '20','parent_id' => '0','is_have_child' => '0','title' => 'User Profile','link' => 'profile','icon' => 'fa fa-user'),
			array('id' => '21','parent_id' => '0','is_have_child' => '21','title' => 'Products','link' => '','icon' => 'fa fa-tags'),
			array('id' => '22','parent_id' => '21','is_have_child' => '0','title' => 'Product Subscription','link' => 'productSubscription','icon' => ''),
			array('id' => '23','parent_id' => '21','is_have_child' => '0','title' => 'Purchase Product','link' => 'purchase/product','icon' => ''),
			array('id' => '24','parent_id' => '0','is_have_child' => '24','title' => 'Configuration Settings','link' => '','icon' => 'fa fa-wrench'),
			array('id' => '25','parent_id' => '0','is_have_child' => '0','title' => 'Change Password','link' => 'change_password','icon' => 'fa fa-unlock-alt'),
			array('id' => '26','parent_id' => '5','is_have_child' => '0','title' => 'Product Manager','link' => 'reports/product_list','icon' => ''),
			array('id' => '27','parent_id' => '5','is_have_child' => '0','title' => 'Subscribers Manager','link' => 'reports/subscriber_list','icon' => ''),
			array('id' => '28','parent_id' => '9','is_have_child' => '0','title' => 'Overdraft Wallet Balance','link' => 'fundamental/overdraft','icon' => ''),
			array('id' => '29','parent_id' => '24','is_have_child' => '0','title' => 'Settings','link' => 'settings ','icon' => ''),
			array('id' => '30','parent_id' => '24','is_have_child' => '0','title' => 'Create Settings','link' => 'settings/createSetting','icon' => ''),
			array('id' => '31','parent_id' => '24','is_have_child' => '0','title' => 'Update Settings','link' => 'settings/update','icon' => ''),
			array('id' => '32','parent_id' => '24','is_have_child' => '0','title' => 'Manage Settings','link' => 'settings/manager','icon' => ''),
			array('id' => '33','parent_id' => '13','is_have_child' => '0','title' => 'Update KYC','link' => 'import','icon' => ''),
			array('id' => '34','parent_id' => '13','is_have_child' => '0','title' => 'Offline Import Subscription','link' => 'import/import_elig_subscribers','icon' => ''),
			array('id' => '35','parent_id' => '1','is_have_child' => '0','title' => 'Api Keys ','link' => 'apiKeys','icon' => ''),
			array('id' => '36','parent_id' => '0','is_have_child' => '20','title' => 'Cash Withdrawer','link' => '','icon' => 'fa fa-money'),
			array('id' => '37','parent_id' => '36','is_have_child' => '0','title' => 'Withdraw Process','link' => 'withdraw','icon' => ''),
			array('id' => '38','parent_id' => '36','is_have_child' => '0','title' => 'Withdraw Request','link' => 'withdraw/requestCommit','icon' => ''),
			array('id' => '39','parent_id' => '9','is_have_child' => '0','title' => 'Wallet Service Group','link' => 'fundamental/wallet_service_group','icon' => ''),
			array('id' => '40','parent_id' => '24','is_have_child' => '0','title' => 'Levies Settings','link' => 'levies','icon' => ''),
			array('id' => '41','parent_id' => '0','is_have_child' => '41','title' => 'iPin Voucher','link' => '','icon' => 'fa fa-pinterest-p'),
			array('id' => '42','parent_id' => '41','is_have_child' => '0','title' => 'Generate iPin Voucher','link' => 'ipingenerates','icon' => ''),
			array('id' => '43','parent_id' => '41','is_have_child' => '0','title' => 'Load Voucher','link' => 'ipingenerates/loadIpinVoucher','icon' => ''),
			array('id' => '44','parent_id' => '14','is_have_child' => '0','title' => 'Message Template','link' => 'messagetemplate','icon' => ''),
			array('id' => '45','parent_id' => '14','is_have_child' => '0','title' => 'Special Message ','link' => 'messagetemplate/subscriber_message','icon' => ''),
			array('id' => '46','parent_id' => '14','is_have_child' => '0','title' => 'Queuing Messages','link' => 'messagetemplate/queue_messages','icon' => ''),
			array('id' => '47','parent_id' => '13','is_have_child' => '0','title' => 'Bulk Ipoint Gifting','link' => 'import/bulk_ipoint_gifting','icon' => ''),
			array('id' => '48','parent_id' => '21','is_have_child' => '0','title' => 'Ipoint Transfer','link' => 'transferIpoint','icon' => ''),
			array('id' => '49','parent_id' => '1','is_have_child' => '0','title' => 'Clients','link' => 'users/client','icon' => ''),
			array('id' => '50','parent_id' => '5','is_have_child' => '0','title' => 'Bulk Ipoint Transfer','link' => 'wipBulkTransfer','icon' => ''),
			array('id' => '51','parent_id' => '5','is_have_child' => '0','title' => 'Wip Bulk Transfer ','link' => 'wipBulkTransfer/bulkTransfer','icon' => '')
		  );
			
		$this->db->insert_batch('menus', $menus);

				// foreach($menus as $menu){
				// 	$this->db->replace('menus', $menu);
				// }

		
		//$this->db->truncate('message_template');
		$message_template = array(
			array('id' => '1','message_subject' => ' OTP For activating','message_template' => ' UICI Opt Number: {0} Please click on this line to activate your porter &amp;lt;a href=&quot;&lt;span style=&quot;font-family: &amp;quot;Helvetica Neue&amp;quot;, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 700;&quot;&gt;www.ipoints.ng/auth/activate&lt;/span&gt;&quot;&amp;gt;&lt;span style=&quot;font-family: &amp;quot;Helvetica Neue&amp;quot;, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 700;&quot;&gt;www.ipoints.ng/auth/activate&lt;/span&gt;&amp;lt;/a&amp;gt;&amp;nbsp; Notice!!! The OTP Expired after an hour','action' => 'register','message_channel' => 'Email','charge' => 'free','attempt_no' => '1','priority' => '1','last_updated_by' => '2','created' => '2018-08-08 16:22:10','updated' => '2019-05-02 13:30:40'),
			array('id' => '2','message_subject' => '','message_template' => 'UICI has created a default password for you. 
				  Input your phone number as your username and {0} as your password.
				  Change the password latter on the platform.
				  Visit www.uici.ng/auth/login to login.','action' => 'forgot-password','message_channel' => 'Sms','charge' => 'free','attempt_no' => '1','priority' => '1','last_updated_by' => '0','created' => '2018-08-08 15:26:16','updated' => '2018-08-08 19:28:14'),
			array('id' => '3','message_subject' => '','message_template' => 'Your UICI authentication OTP is: {0}\'. PHP_EOL .\'
						  Notice!!! The OTP Expired after an hour','action' => 'register','message_channel' => 'Sms','charge' => 'free','attempt_no' => '1','priority' => '1','last_updated_by' => '0','created' => '2018-08-08 11:24:00','updated' => '2018-08-08 11:14:12'),
			array('id' => '4','message_subject' => '','message_template' => 'A UICI account has automatically been created for you. Input your phone number as your User name and {0} as your password. Visit www.uici.ng/auth/login to login.','action' => 'auto-user-otp','message_channel' => 'Sms','charge' => 'free','attempt_no' => '1','priority' => '1','last_updated_by' => '0','created' => '2018-08-08 17:27:21','updated' => '2018-08-08 15:23:18'),
			array('id' => '5','message_subject' => '  Forgot Password','message_template' => '  A UICI has created a default password for you. Input your email address as your username and {0} as your password. You can later change the password when login Visit &lt;a href=&quot;www.points.ng/auth/login&quot;&gt; www.ipoints.ng/auth/login &lt;/a&gt; to login.','action' => 'forgot-password','message_channel' => 'Email','charge' => 'free','attempt_no' => '1','priority' => '1','last_updated_by' => '2','created' => '2018-08-08 15:25:17','updated' => '2019-05-11 19:24:27'),
			array('id' => '6','message_subject' => 'SMS Debit','message_template' => 'this is template ','action' => 'sms-debit','message_channel' => 'Sms','charge' => 'free','attempt_no' => '9','priority' => '1','last_updated_by' => '0','created' => '2018-08-14 12:35:43','updated' => '2018-08-14 12:35:43'),
			array('id' => '7','message_subject' => 'Transfer Credit','message_template' => 'Dear {0},
				  You have received {1} Ipoint in your {2} from {3}.
				  Log on www.ipoints.ng/auth/login to check your balance.','action' => 'transfer-credit','message_channel' => 'Sms','charge' => 'free','attempt_no' => '5','priority' => '3','last_updated_by' => '0','created' => '2018-08-31 00:00:00','updated' => '2018-08-31 12:43:36'),
			array('id' => '8','message_subject' => 'Transfer Credit','message_template' => 'Dear {0},
				  You have received {1} Ipoint in your {2} from {3}.
				  Login<a href="www.ipoints.ng/auth/login"> www.ipoints.ng/auth/login</a> to check your balance. ','action' => 'transfer-credit','message_channel' => 'Email','charge' => 'free','attempt_no' => '5','priority' => '3','last_updated_by' => '0','created' => '2018-08-31 00:00:00','updated' => '2018-08-31 12:45:12'),
			array('id' => '9','message_subject' => 'Transfer Debit','message_template' => 'Dear {0},
				  You\'ve transfer {1} Ipoint from your {2} wallet  to {3}.
				  Login www.ipoints.ng/auth/login to check your balance.','action' => 'transfer-debit','message_channel' => 'Sms','charge' => 'free','attempt_no' => '5','priority' => '1','last_updated_by' => '0','created' => '2018-08-31 00:16:00','updated' => '2018-08-31 08:00:00'),
			array('id' => '10','message_subject' => 'Transfer Debit','message_template' => 'Dear {0},
				  You\'ve transfer {1} Ipoint from your {2} wallet  to {3}.
				  Login<a href="www.ipoints.ng/auth/login"> www.ipoints.ng/auth/login</a> to check your balance. ','action' => 'transfer-debit','message_channel' => 'Email','charge' => 'free','attempt_no' => '5','priority' => '1','last_updated_by' => '0','created' => '2018-08-31 00:18:00','updated' => '2018-08-31 04:00:00'),
			array('id' => '11','message_subject' => 'iPoint Forgot Password','message_template' => 'UICI has created a default password for you.<br> 
				  Input your email as your username and {0} as your password.<br>
				  Change the password latter on the platform.
				  Visit <a href="www.ipoints.ng">www.uici.ng/auth/login</a> to login.','action' => 'forgot-password','message_channel' => 'Email','charge' => 'free','attempt_no' => '1','priority' => '1','last_updated_by' => '0','created' => '2018-08-31 03:00:00','updated' => '2018-08-31 03:00:00'),
			array('id' => '12','message_subject' => ' Offline ipoint purchase approved','message_template' => '<p> Dear {0}, your  iPoint purchase request has been approved.
		  Your {1} wallet has been credited with {2} ipoint quantities.</p><p>visit &lt;a href="ipoints.ng"&gt;&lt;/a&gt;</p>','action' => 'offline-payment-wallet-fund','message_channel' => 'Email','charge' => 'free','attempt_no' => '7','priority' => '7','last_updated_by' => '2','created' => '2018-11-21 00:00:00','updated' => '2019-05-02 13:17:25'),
			array('id' => '13','message_subject' => 'new user bulk transfer template','message_template' => '   iPoint QTY {0} user password {1} ','action' => 'new-wip-transaction','message_channel' => 'Sms','charge' => 'free','attempt_no' => '8','priority' => '1','last_updated_by' => '2','created' => '2018-11-02 00:00:00','updated' => '2019-05-02 13:20:06'),
			array('id' => '14','message_subject' => 'old user bulk transfer template','message_template' => 'Ipoint QTY {0} ','action' => 'old-wip-transaction','message_channel' => 'Sms','charge' => 'free','attempt_no' => '8','priority' => '6','last_updated_by' => '2','created' => '2018-11-02 00:00:00','updated' => '2018-11-02 00:00:00'),
			array('id' => '15','message_subject' => '','message_template' => 'Dear {0}, your  iPoint purchase request has been approved.
		  Your {1} wallet has been credited with {2} ipoint quantities.','action' => 'offline-payment-wallet-fund','message_channel' => 'Sms','charge' => 'free','attempt_no' => '7','priority' => '7','last_updated_by' => '2','created' => '2018-11-02 00:00:00','updated' => '2019-01-18 17:48:59'),
			array('id' => '16','message_subject' => 'Template','message_template' => 'Message {0} Template {1}','action' => 'offline-payment-product-purchase','message_channel' => 'Sms','charge' => 'free','attempt_no' => '5','priority' => '6','last_updated_by' => '2','created' => '2018-11-02 00:00:00','updated' => '2018-11-02 00:00:00'),
			array('id' => '17','message_subject' => 'CUMULATIVE iINSURANCE REPORT FOR LAST WEEK (UICI-WebMaster)','message_template' => 'Hi All,<br/>Hope this mail meets you well. Please find the attached, this just a cumulative testing<br/><br/>Best Regards!','action' => 'cumulative-isavings-report','message_channel' => 'Email','charge' => 'free','attempt_no' => '9','priority' => '9','last_updated_by' => '1','created' => '2019-04-06 00:00:00','updated' => '2019-04-06 00:00:00'),
			array('id' => '18','message_subject' => NULL,'message_template' => ' Dear subscriber,
		  You are eligible for {0} insurance with ipoint value of {1}.
		  The insurance valid between {2} to {3}.
		  
		  Thanks.','action' => 'product-subscription','message_channel' => 'Sms','charge' => 'paid','attempt_no' => '8','priority' => '7','last_updated_by' => '2','created' => '2019-04-23 00:00:00','updated' => '2019-04-24 15:14:08'),
			array('id' => '19','message_subject' => '    Insurance Product Service Subscription Report','message_template' => '<p>    Hi All,
		  Hope this mail meets you well. Please find the attached, this just a product service subscription testing,
		  breaking down 
		  
		  Beneficiaries {0} Product Name {7}</p><p>&nbsp;Product Price {1} 
		  Commission Charge {2} 
		  Total Billing {3} 
		  Total Commission {4} 
		  Purchase Date {5} 
		  Expiring Date {6} 
		  
		  Best Regards! </p>','action' => 'product-subscription-report','message_channel' => 'Email','charge' => 'free','attempt_no' => '8','priority' => '7','last_updated_by' => '2','created' => '2019-04-24 00:00:00','updated' => '2019-04-24 15:35:54'),
			array('id' => '20','message_subject' => 'iPoints Overdraft ','message_template' => 'Dear Merchant,
		  
		  You have been giving an overdraft in {0} wallet, amount {1}','action' => 'ipoint-overdraft','message_channel' => 'Email','charge' => 'free','attempt_no' => '9','priority' => '7','last_updated_by' => '1','created' => '2019-04-26 00:00:00','updated' => '2019-04-26 00:00:00'),
			array('id' => '21','message_subject' => 'Offline Payment ipoint Request','message_template' => '
		  Offline Payment request have be made by group_name==>{0}
		  wallet name ==>{1}
		  payment reference=={2}
		  amount in naira ==> {3}
		  ipoints value ==> {4}
		  date and time ==> {5}
		  ','action' => 'offline-payment-ipoint-purchase-request','message_channel' => 'Email','charge' => 'free','attempt_no' => '8','priority' => '7','last_updated_by' => '1','created' => '2019-04-26 00:00:00','updated' => '2019-04-26 00:00:00'),
			array('id' => '22','message_subject' => 'Withdrawer Request ','message_template' => 'A request has been made by name>>?{0} with wallet>>?{1} in Naira amount>>?{2}','action' => 'withdraw-request','message_channel' => 'Email','charge' => 'free','attempt_no' => '8','priority' => '6','last_updated_by' => '1','created' => '2019-04-27 00:00:00','updated' => '2019-04-27 00:00:00'),
			array('id' => '23','message_subject' => 'Withdrawer  Approved','message_template' => 'Dear name>>{0}, Your wallet>>{1} in Naira amount_in_naira>>{2} has been approved.','action' => 'withdraw-approved','message_channel' => 'Email','charge' => 'free','attempt_no' => '8','priority' => '6','last_updated_by' => '1','created' => '2019-04-27 00:00:00','updated' => '2019-04-27 00:00:00'),
			array('id' => '24','message_subject' => NULL,'message_template' => 'Dear name>>{0}, Your wallet>>{1}  Naira amount_in_naira>>{2} has been approved.','action' => 'withdraw-approved','message_channel' => 'Sms','charge' => 'paid','attempt_no' => '8','priority' => '6','last_updated_by' => '1','created' => '2019-04-27 00:00:00','updated' => '2019-04-13 00:00:00'),
			array('id' => '25','message_subject' => 'Unapproved withdrawer Request ','message_template' => 'Dear name>>?{0}, Your request of wallet>>?{1} naira>>?{3} has been cancel.','action' => 'withdraw-cancel-by-admin','message_channel' => 'Email','charge' => 'free','attempt_no' => '6','priority' => '8','last_updated_by' => '1','created' => '2019-04-27 00:00:00','updated' => '2019-04-27 00:00:00'),
			array('id' => '26','message_subject' => NULL,'message_template' => 'Dear name>>?{0}, Your request of wallet>>?{1} of ipoint_qty>>?{2} icuvalent in naira>>?{3} has been cancel.','action' => 'withdraw-cancel-by-admin','message_channel' => 'Sms','charge' => 'paid','attempt_no' => '6','priority' => '8','last_updated_by' => '1','created' => '2019-04-27 00:00:00','updated' => '2019-04-27 00:00:00'),
			array('id' => '27','message_subject' => 'Withdrawer Cancel','message_template' => 'Withdrawer request has be canceled by name>>?{0} with a wallet>>?{1} naira>>?{2}','action' => 'withdraw-cancel','message_channel' => 'Email','charge' => 'free','attempt_no' => '8','priority' => '6','last_updated_by' => '1','created' => '2019-04-27 00:00:00','updated' => '2019-04-27 00:00:00'),
			array('id' => '28','message_subject' => '','message_template' => 'Withdrawer request has be canceled by name>>?{0} with a wallet>>?{1} naira>>?{2}','action' => 'withdraw-cancel','message_channel' => 'Sms','charge' => 'paid','attempt_no' => '8','priority' => '5','last_updated_by' => '2','created' => '2019-04-29 20:02:24','updated' => NULL),
			array('id' => '29','message_subject' => 'Ipin Generator','message_template' => 'reference>>?{0} ipin_value>>?{1} total_amount>>?{2} quantities>>?{3}','action' => 'ipin-generator','message_channel' => 'Email','charge' => 'free','attempt_no' => '8','priority' => '5','last_updated_by' => '1','created' => '2019-04-30 00:00:00','updated' => '2019-04-30 00:00:00'),
			array('id' => '30','message_subject' => NULL,'message_template' => 'Dear Customer,
		  Your Payment was successful, your account has been topup with {0} ipoint quantities in a {1} wallet,
		  Payment reference {2}','action' => 'online-topup-wallet-success','message_channel' => 'Sms','charge' => 'paid','attempt_no' => '8','priority' => '2','last_updated_by' => '1','created' => '2019-05-02 00:00:00','updated' => '2019-05-02 00:00:00'),
			array('id' => '31','message_subject' => 'Online Top-UP Wallet Successful ','message_template' => 'Dear Customer,
		  Your Payment was successful, your account has been topup with {0} ipoint quantities in a {1} wallet,
		  Payment reference {2}','action' => 'online-topup-wallet-success','message_channel' => 'Email','charge' => 'free','attempt_no' => '9','priority' => '2','last_updated_by' => '1','created' => '2019-05-02 00:00:00','updated' => '2019-05-02 00:00:00'),
			array('id' => '32','message_subject' => NULL,'message_template' => 'Dear Customer,
		  Your topup wallet failed,
		  ipoint quantities {0},
		  
		  Topup wallet {1},
		  
		  Payment reference {2},','action' => 'online-topup-wallet-failed','message_channel' => 'Sms','charge' => 'free','attempt_no' => '8','priority' => '2','last_updated_by' => '1','created' => '2019-05-02 00:00:00','updated' => '2019-05-02 00:00:00'),
			array('id' => '33','message_subject' => 'Online Topup wallet Failed','message_template' => 'Dear Customer,
		  Your topup wallet failed,
		  ipoint quantities {0},
		  
		  Topup wallet {1},
		  
		  Payment reference {2},','action' => 'online-topup-wallet-failed','message_channel' => 'Email','charge' => 'free','attempt_no' => '8','priority' => '2','last_updated_by' => '1','created' => '2019-05-02 00:00:00','updated' => '2019-05-02 00:00:00')
		  );
		  
		$this->db->insert_batch('message_template', $message_template);

			// foreach($message_templates as $message_template){
			// 	$this->db->replace('message_template', $message_template);
			// }
	}
	public function down()
	{

	}
}
?>