<?php

class Migration_modify_settings extends CI_Migration {

    public function up() {
        $data = [
            'meta_value' => '<p>UIC Innovations is Africa’s 1st ‘Incidental Mass Access to Financial <br>Services’ (IMAFS) platform. <br>We are an indigenous business and technology value added service company, <br>set up to revolutionize the African Mobile Financial Services space <br>through dynamic innovation and disruptive business models.<br>This socially smart business model creatively harnesses the power of  <br>successful business enterprise to achieve a marriage of business <br>success and social development thus making it more robust and sustainable.</p>'
        ];
        $this->db->where('meta_key', 'about');
        $this->db->update('settings', $data);
    }

}