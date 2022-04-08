<?php

class Migration_modify_products extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('products', [
            'base_product_yn' => [
                'type' => 'tinyint',
                'constraint' => 1,
                'null' => false,
                'default' => 0,
                'after' => 'is_insurance_prod'
            ]
        ]);

        $this->dbforge->modify_column('products', [
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
                'default' => null
            ],
            'price' => [
                'type' => 'int',
                'constraint' => '11',
                'null' => false
            ]
        ]);
        
        // sanitize products table
        $this->db->query('TRUNCATE TABLE products');

        // insert records into products
        $data = [
            [
                'product_name' => 'iPoints',
                'price' => 1,
                'is_insurance_prod' => false,
                'base_product_yn' => true,
                'images' => '[]',
                'description' => 'nill',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'iHealth',
                'price' => 200,
                'is_insurance_prod' => false,
                'base_product_yn' => false,
                'images' => '[]',
                'description' => 'nill',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'iLife',
                'price' => 100,
                'is_insurance_prod' => false,
                'base_product_yn' => false,
                'images' => '[]',
                'description' => 'nill',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'iSavings',
                'price' => 150,
                'is_insurance_prod' => false,
                'base_product_yn' => false,
                'images' => '[]',
                'description' => 'nill',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'product_name' => 'Marine Insurance',
                'price' => 300,
                'is_insurance_prod' => true,
                'base_product_yn' => false,
                'images' => '[]',
                'description' => 'nill',
                'created_at' => date('Y-m-d H:i:s')
            ] 
        ];

        $this->db->insert_batch('products', $data);
    }

}