
      <?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      class Migration_Service_log extends CI_Migration {

        public function up(){

          $this->dbforge->drop_table('services_log',TRUE);

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
            'product_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'value' => array(
                'type' => 'INT',
                'constraint' => 25
            ),
            'cover_period' => array(
                'type' => 'INT',
                'constraint' => 25
            ),
            'purchase_date' => array(
                'type' => 'DATETIME',
            ),
            'expiring_date' => array(
                'type' => 'DATETIME'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('services_log', true);

        $this->db->simple_query('ALTER TABLE services_log ADD CONSTRAINT fk_user_id FOREIGN KEY (`user_id`) REFERENCES users(id)');
        $this->db->simple_query('ALTER TABLE services_log ADD CONSTRAINT fk_product_id FOREIGN KEY (`product_id`) REFERENCES products(id)');

        }

        public function down(){
          $this->dbforge->drop_table('services_log');
        }
      }
      ?>