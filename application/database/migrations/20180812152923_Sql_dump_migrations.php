<?php

class Migration_Sql_dump_migrations extends CI_Migration {

    public function up() 
    {
        $this->dbforge->modify_column('settings', [
            'updated_at' => [
                'type' => 'datetime',
                'default' => null,
                'null' => true
            ]
        ]);

        $this->db->insert_batch('settings', [
            [
                'meta_key' => 'use_front_template',
                'meta_value' => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'meta_key' => 'use_admin_template',
                'meta_value' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'meta_key' => 'more_about_uici',
                'meta_value' => '<p>We are a social enterprise setup to revolutionize the African Mobile Financial Services space through dynamic innovation and disruptive business models. Our aim to leverage the power of our ‘Incidental Coverage Model’ to facilitate universal financial services in Africa and other emerging markets.</p><p>UIC Innovations is a registered Loyalty-As-A-Service company in Nigeria. We are the original promoters of the iInsurance brand and other financial inclusion products to include iPensions and iSavings.</p><p>The iInsurance is a mobile technology driven Loyalty-Currency-For-Insurance business model developed by UICI to achieve universal insurance coverage in Nigeria and subsequently, Africa.</p><h2><b>Vision</b></h2><p>To be the leading loyalty-for-service company in Africa.</p><h2><b>Mission Statement</b></h2><ol><li>To add value to all stakeholders in the financial services industry through innovation, mutually beneficial collaborations and dynamic business processes. </li><li>To accelerate financial inclusion for every African through cutting edge technology and a uniquely sustainable business model. </li><li>To impact positively on the standard of living of every African through easier access to services. </li></ol><p>At UIC Innovations all members of our team are highly qualified and equipped to perform their duties to their fullest capacity and potential. Client satisfaction is our main focus, reached through innovative and cost-effective services.</p>',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'meta_key' => 'vision_mission',
                'meta_value' => '<h2><b>Vision</b></h2><p>To be the leading loyalty-for-service company in Africa.</p><h2><b>Mission Statement</b></h2><ol><li>To add value to all stakeholders in the financial services industry through innovation, mutually beneficial collaborations and dynamic business processes. </li><li>To accelerate financial inclusion for every African through cutting edge technology and a uniquely sustainable business model. </li><li>To impact positively on the standard of living of every African through easier access to services. </li></ol>',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        $this->db->insert('uici_levies', [
            'levy_type' => 'sms_charge',
            'amt' => 60,
            'created' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {

    }
}