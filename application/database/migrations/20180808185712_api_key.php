
      <?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      class Migration_Api_key extends CI_Migration {

        public function up(){
          $this->dbforge->drop_table('api_keys',TRUE);

          $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'ip_addresses' => array(
                'type' => 'VARCHAR',
                'constraint' => 56
            ),
            'api_key' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'level' => array(
                'type' => 'int',
                'constraint' => 2
            ),
            'ignore_limits' => array(
                'type' => 'tinyint',
                'constraint' => 11
            ),
            'date_created' => array(
                'type' => 'DATETIME'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('api_keys', true);

        $this->db->simple_query('ALTER TABLE api_keys ADD CONSTRAINT fk_user_id FOREIGN KEY (`user_id`) REFERENCES users(id)');

        }

        public function down(){
          $this->dbforge->drop_table('api_keys');
        }
      }
      ?>