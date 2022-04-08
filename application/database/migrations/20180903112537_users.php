<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Users extends CI_Migration
{
	public function up()
	{

		//$this->dbforge->drop_table('users',TRUE);

		$this->dbforge->modify_column('users',array(
		  'name' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 45,
			  'null'=>true
		  ),
		  'email' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 45,
			  'null'=>true
		  ),
		  'birth_date' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 23,
			  'null'=>true
		  ),
		  'lga' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 20,
			  'null'=>true
		  ),
		  'address' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 255,
			  'null'=>true
		  ),
		  'business_name' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 65,
			  'null'=>true
		  ),
		  'rc_number' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 20,
			  'null'=>true
		  ),
		  'referrer' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 30,
			  'null'=>true
		  ),
		  'industry' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 50,
			  'null'=>true
		  ),
		  'mobile_number' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 17,
			  'null'=>true,
			  'after'=>'email'
		  ),
		  'next_of_kin' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 50,
			  'null'=>true
		  ),
		  'next_of_kin_phone' => array(
			  'type' => 'VARCHAR',
			  'constraint' => 17
		  ),
		  
	  ));

	}
	public function down()
	{

	}
}
?>