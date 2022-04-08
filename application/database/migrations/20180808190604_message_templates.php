
      <?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      class Migration_Message_templates extends CI_Migration {

        public function up(){

          $this->dbforge->drop_table('message_template',TRUE);

          $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'message_subject' => array(
                'type' => 'VARCHAR',
                'constraint' => 65
            ),
            'message_template' => array(
                'type' => 'TEXT',
            ),
            'action' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'message_channel' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'target_user' => array(
                'type' => 'int',
                'constraint' => 11
            ),
            'created' => array(
                'type' => 'DATETIME'
            ),
            'updated' => array(
                'type' => 'DATETIME'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('message_template', true);

        $this->db->simple_query('ALTER TABLE message_template ADD CONSTRAINT fk_target_user FOREIGN KEY (target_user) REFERENCES groups(id)');

        }

        public function down(){
          $this->dbforge->drop_table('message_template');
        }
      }
      ?>