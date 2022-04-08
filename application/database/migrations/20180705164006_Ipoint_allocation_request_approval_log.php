<?php

class Migration_ipoint_allocation_request_approval_log extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'request_ref' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
			'qty' => array(
                'type' => 'INT'
            ),
            'recipient_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'service_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'request_by' => array(
                'type' => 'INT'
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'date_created' => array(
                'type' => 'DATETIME'
            ),
            'date_updated' => array(
                'type' => 'DATETIME'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ipoint_allocation_request_approval_log');
    }

    public function down() {
        $this->dbforge->drop_table('ipoint_allocation_request_approval_log');
    }

}
