<?php

Class MyPracticeModel extends CI_Model {
        // public $title;
        // public $content;
        // public $date;

        public function get_all_db_entries_from_products_table()
        {
                $query = $this->db->get('products');
                return $query->result();
        }

        public function sumAnnualFee()
        {
               $this->db->select('SUM(total_paid) AS total_annual_fee');
               $this->db->from('user_subscriptions');
               $query = $this->db->get();
                return $query->result();
        }

        // public function insert_entry()
        // {
        //         $this->title    = $_POST['title']; // please read the below note
        //         $this->content  = $_POST['content'];
        //         $this->date     = time();

        //         $this->db->insert('entries', $this);
        // }

        // public function update_entry()
        // {
        //         $this->title    = $_POST['title'];
        //         $this->content  = $_POST['content'];
        //         $this->date     = time();

        //         $this->db->update('entries', $this, array('id' => $_POST['id']));
        // }

}