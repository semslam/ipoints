
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Recreate extends CI_Migration
{
  public function up(){
    $this->dbforge->add_field('id');
		$otherFields = [
			'reference'=>[
				'type'=>'VARCHAR',
				'constraint'=>65,
				//'unique'=>true
      ],
      'type'=>[
				'type'=>'ENUM("credit","debit")',
				//'constraint'=>13
      ],
			'value'=>[
				'type'=>'INT',
				//'constraint'=>13
      ],
      'user_id'=>[
				'type'=>'INT',
				//'constraint'=>13
			],
			'description'=>[
				'type'=>'VARCHAR',
				'constraint'=>255
			],
			'sender_id'=>[
				'type'=>'INT',
				'null'=>true
			],
			'receiver_id'=>[
				'type'=>'INT',
				'null'=>true
      ],
      'current_balance'=>[
				'type'=>'INT',
				'default'=>0
			]
		];
		$this->dbforge->add_field($otherFields);
    $this->dbforge->add_field("`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
    $this->dbforge->drop_table('transaction');
		$this->dbforge->create_table('transactions', TRUE);
  }

  public function down(){

  }
}
?>