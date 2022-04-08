<?php

class Migration_summary_migrations extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'account_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
			'section' => array(
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => false,
            ),
            'action' => array(
                'type' => 'VARCHAR',
                'constraint' => 32,
                'null' => false,
            ),
            'when' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'uri' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('activity_tracking', true);


        // drop table config_data
        
        $this->dbforge->drop_table('config_data', true);

        // ipoints_source_type table
        
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 10,
                'auto_increment' => true
            ),
            'type' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ),
            'created' => array(
                'type' => 'DATETIME',
                'null' => false,
            ),
            'updated' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ipoints_source_type', true);
        $this->db->insert_batch('ipoints_source_type', [
            [
                'type' => 'credit',
                'created' => date('Y-m-d H:i:s'),
                'updated' => null
            ],
            [
                'type' => 'flutterwave',
                'created' => date('Y-m-d H:i:s'),
                'updated' => null
            ],
            [
                'type' => 'NIBSS',
                'created' => date('Y-m-d H:i:s'),
                'updated' => null
            ],
        ]);


        // ipoints_allocation_request_approval_log table
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ),
            'request_ref' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ),
            'qty' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'recipient_phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ),
            'service_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ),
            'request_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ),
            'request_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'date_created' => array(
                'type' => 'DATETIME',
                'null' => false,
            ),
            'date_updated' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('ipoints_allocation_request_approval_log', true);


        // message_template table 

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ),
            'message_template' => array(
                'type' => 'TEXT',
                'null' => false
            ),
            'action' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ),
            'message_channel' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ),
            'target_user' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'last_updated_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ),
            'created' => array(
                'type' => 'DATETIME',
                'null' => false,
            ),
            'updated' => array(
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('message_template', true);


        // modify products table
        
        $this->dbforge->drop_column('products', 'stock');


        // drop rat table

        $this->dbforge->drop_table('rat', true);


        // update settings table

        $this->db->insert_batch('settings', [
            [
                'meta_key' => 'privacy_policy',
                'meta_value' => 'Privacy Policy of UIC Innovations LTD (UICI)\r\n\r\nUICI operates the iPoints (Loyalty-Reward-as-a-Service company), which provides the SERVICE.\r\nThis page is used to inform website visitors regarding our policies with the collection, use, and disclosure of Personal Information if anyone decided to use our Service, the iPoints (Loyalty-Reward-as-a-Service company).\r\nIf you choose to use our Service, then you agree to the collection and use of information in relation with this policy. The Personal Information that we collect are used for providing and improving the Service. We will not use or share your information with anyone except as described in this Privacy Policy.\r\nThe terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, which is accessible at Website URL, unless otherwise defined in this Privacy Policy.\r\nInformation Collection and Use\r\nFor a better experience while using our Service, we may require you to provide us with certain personally identifiable information, including but not limited to your name, phone number, and postal address. The information that we collect will be used to contact or identify you.\r\nLog Data\r\nWe want to inform you that whenever you visit our Service, we collect information that your browser sends to us that is called Log Data. This Log Data may include information such as your computer\'s Internet Protocol (“IP”) address, browser version, pages of our Service that you visit, the time and date of your visit, the time spent on those pages, and other statistics.\r\nCookies\r\nCookies are files with small amount of data that is commonly used an anonymous unique identifier. These are sent to your browser from the website that you visit and are stored on your computer\'s hard drive.\r\nOur website uses these “cookies” to collection information and to improve our Service. You have the option to either accept or refuse these cookies, and know when a cookie is being sent to your computer. If you choose to refuse our cookies, you may not be able to use some portions of our Service.\r\nService Providers\r\nWe may employ third-party companies and individuals due to the following reasons:\r\n1.	To facilitate our Service\r\n2.	To provide the Service on our behalf\r\n3.	To perform Service-related services; or\r\n4.	To assist us in analysing how our Service is used.\r\n\r\n\r\nWe want to inform our Service users that these third parties have access to your Personal Information. The reason is to perform the tasks assigned to them on our behalf. However, they are obligated not to disclose or use the information for any other purpose.\r\nSecurity\r\nWe value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.\r\nLinks to Other Sites\r\nOur Service may contain links to other sites. If you click on a third-party link, you will be directed to that site. Note that these external sites are not operated by us. Therefore, we strongly advise you to review the Privacy Policy of these websites. We have no control over, and assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.\r\nChildren\'s Privacy\r\nOur Services do not address anyone under the age of 13. We do not knowingly collect personal identifiable information from children under 13. In the case we discover that a child under 13 has provided us with personal information, we immediately delete this from our servers. If you are a parent or guardian and you are aware that your child has provided us with personal information, please contact us so that we will be able to do necessary actions.\r\nChanges to This Privacy Policy\r\nWe may update our Privacy Policy from time to time. Thus, we advise you to review this page periodically for any changes. We will notify you of any changes by posting the new Privacy Policy on this page. These changes are effective immediately, after they are posted on this page.\r\nContact Us\r\nIf you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us via WhatsAPP chat channel by clicking on the “Chat via WhatsAPP” button above.',
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'meta_key' => 'whatsapp_number',
                'meta_value' => '+2349099387285',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'meta_key' => 'whats_data_message',
                'meta_value' => 'Hi! I would like to know more about UICI iPoints.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'meta_key' => 'session_timer',
                'meta_value' => '10000',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'meta_key' => 'no_reply_email',
                'meta_value' => 'noreply@example.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'meta_key' => 'admin_email',
                'meta_value' => 'noreply@example.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
            [
                'meta_key' => 'otp_life_time',
                'meta_value' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => null
            ],
        ]);


        // rename states_in_nigeria to state_tbl

        $this->dbforge->rename_table('states_in_nigeria', 'state_tbl');
        $this->dbforge->modify_column('state_tbl', [
            'name' => [
                'name' => 'state_name',
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ]
        ]);


        // modify users table

        $this->dbforge->add_column('users', [
            'otp' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => null,
                'null' => true,
                'after' => 'password'
            ]
        ]);
        $this->dbforge->add_column('users', [
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
                'after' => 'otp'
            ]
        ]);
        $this->dbforge->add_column('users', [
            'referrer' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
                'default' => null,
                'after' => 'rc_number'
            ]
        ]);
        $this->db->simple_query('ALTER TABLE `users` ADD INDEX KEY (group_id)');


        // alter user_levy table
        
        $this->dbforge->add_column('user_levy', [
            'next_due_date' => [
                'type' => 'DATETIME',
                'null' => false,
                'after' => 'outstanding_balance'
            ]
        ]);


        // alter wip_failed_log

        $this->db->simple_query("ALTER TABLE wip_failed_log ADD UNIQUE KEY (`request_id`)");


        // alter wip_processed_log

        $this->db->simple_query("ALTER TABLE wip_processed_log ADD UNIQUE KEY (`request_id`)");


        // alter wip_transaction table

        $this->dbforge->modify_column('wip_transaction', [
            'request_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ]
        ]);
        $this->dbforge->add_column('wip_transaction', [
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null
            ]
        ]);
        $this->dbforge->add_column('wip_transaction', [
            'ipoints_source' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ]
        ]);
    }

}