<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Template_message_b extends CI_Migration
{
	
	public function up()
	{
		$message_template = array(
			array('id' => '1','message_subject' => 'OTP For activating','message_template' => 'UICI Opt Number: {0}<br> Please click on this line to activate your porter <a href="https://ipoints.ng/auth/activate">www.ipoints.ng/auth/activate</a><br>
				Notice!!! The OTP Expired after an hour','action' => 'register','message_channel' => 'Email','target_user' => '4','created' => '2018-08-08 16:22:10','updated' => '2018-08-31 12:41:48'),
			array('id' => '2','message_subject' => '','message_template' => 'UICI has created a default password for you. 
		Input your phone number as your username and {0} as your password.
		Change the password latter on the platform.
		Visit www.uici.ng/auth/login to login.','action' => 'forgot-password','message_channel' => 'Sms','target_user' => '5','created' => '2018-08-08 15:26:16','updated' => '2018-08-08 19:28:14'),
			array('id' => '3','message_subject' => '','message_template' => 'Your UICI authentication OTP is: {0}\'. PHP_EOL .\'
				Notice!!! The OTP Expired after an hour','action' => 'register','message_channel' => 'Sms','target_user' => '5','created' => '2018-08-08 11:24:00','updated' => '2018-08-08 11:14:12'),
			array('id' => '4','message_subject' => '','message_template' => 'A UICI account has automatically been created for you. Input your phone number as your User name and {0} as your password. Visit www.uici.ng/auth/login to login.','action' => 'auto-user-otp','message_channel' => 'Sms','target_user' => '3','created' => '2018-08-08 17:27:21','updated' => '2018-08-08 15:23:18'),
			array('id' => '5','message_subject' => 'Forgot Password','message_template' => 'A UICI has created a default password for you.<br> Input your email address as your username and {0} as your password. <br>You can later change the password when login<br> Visit www.uici.ng/auth/login to login.','action' => 'forgot password','message_channel' => 'Email','target_user' => '3','created' => '2018-08-08 15:25:17','updated' => '2018-08-09 17:21:31'),
			array('id' => '6','message_subject' => 'SMS Debit','message_template' => 'this is template ','action' => 'sms-debit','message_channel' => 'Sms','target_user' => '1','created' => '2018-08-14 12:35:43','updated' => '2018-08-14 12:35:43'),
			array('id' => '7','message_subject' => 'Transfer Credit','message_template' => 'Dear {0},
		You have received {1} Ipoint from {2}.
		Log on www.ipoints.ng/auth/login to check your balance.','action' => 'transfer-credit','message_channel' => 'Sms','target_user' => '3','created' => '2018-08-31 00:00:00','updated' => '2018-08-31 12:43:36'),
			array('id' => '8','message_subject' => 'Transfer Credit','message_template' => 'Dear {0},<br> you have received {1} Ipoint from {2}.
		Log on<a href="www.ipoints.ng/auth/login"> www.ipoints.ng/auth/login</a> to check your balance.','action' => 'transfer-credit','message_channel' => 'Email','target_user' => '3','created' => '2018-08-31 00:00:00','updated' => '2018-08-31 12:45:12'),
			array('id' => '9','message_subject' => 'Transfer Debit','message_template' => 'Transfer Debit','action' => 'transfer-debit','message_channel' => 'Sms','target_user' => '3','created' => '2018-08-31 00:16:00','updated' => '2018-08-31 08:00:00'),
			array('id' => '10','message_subject' => 'Transfer Debit','message_template' => 'Transfer Debit','action' => 'transfer-debit','message_channel' => 'Email','target_user' => '3','created' => '2018-08-31 00:18:00','updated' => '2018-08-31 04:00:00'),
			array('id' => '11','message_subject' => 'iPoint Forgot Password','message_template' => 'UICI has created a default password for you.<br> 
		Input your email as your username and {0} as your password.<br>
		Change the password latter on the platform.
		Visit <a href="www.ipoints.ng">www.uici.ng/auth/login</a> to login.','action' => 'forgot-password','message_channel' => 'Email','target_user' => '3','created' => '2018-08-31 03:00:00','updated' => '2018-08-31 03:00:00')
		);

		$this->db->insert_batch('message_template', $message_template);

		  
	}
	public function down()
	{

	}
}
?>