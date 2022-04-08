
      <?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      class Migration_Create_wip_bulk_requests extends CI_Migration {

        public function up(){
            $this->dbforge->add_field('id');
            $otherFields = [
            'request_id'=>[
                'type'=>'VARCHAR',
                'constraint'=>65,
                'unique'=>true
            ],
            'txn_reference'=>[
              'type'=>'VARCHAR',
              'constraint'=>65,
              'unique'=>true
            ],
            'user_id'=>[
              'type'=>'INT',
              //'constraint'=>13
            ],
            'total_transaction_value'=>[
              'type'=>'INT',
            ],
            'recipients_count'=>[
              'type'=>'INT',
            ],
            'status'=>[
              'type'=>'ENUM("pending","processing","completed")',
              'default'=>'pending'
            ],
          ];
            $this->dbforge->add_field($otherFields);
            $this->dbforge->add_field("`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
            $this->dbforge->add_field("`updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
            $this->dbforge->add_key('user_id');
            $this->dbforge->create_table('wip_bulk_transfer_requests', true);
        }

        public function down(){

        }
      }
      ?>