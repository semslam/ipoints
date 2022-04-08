<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Migration_Create_index_user_balance extends CI_Migration {

  public function up(){
    if(!$this->db->simple_query('ALTER TABLE user_balance ADD UNIQUE user_wallet_combo(user_id, wallet_id)')){
      echo print_r($this->db->error(),true);
      throw new Exception('Error occured');
    }
  }

  public function down(){

  }
}
?>