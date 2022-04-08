<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Mod_uicilevies_value_to_text extends CI_Migration
{
	public function up()
	{
		$this->dbforge->modify_column('uici_levies', [
            'value' => [
                'type' => 'text',
                'null' => false,
			]
        ]);
	}
	public function down()
	{

	}
}
?>