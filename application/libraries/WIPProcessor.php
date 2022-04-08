<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class WIPProcessor extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->model('UserBalance');
        $this->load->library("Utilities");
        $this->load->library("pdolib");
      }

    public static function accesptRequest($data){
        $request =  new static();
      return $request->db->insert('wip_oauth_process',$data);
    }

    public static function fulfillment($wip){
      //$com =  new static();
      switch ($wip->type) {
        case 'withdrawer':
        // convert the data to json
            $data['contact'] = '2348092951533';
            $data['reference'] ='234frvs#@FHJDaaAaFDwds@$CwmDSZedsR^$$Dgds' ;
            $data['bank_name'] = 'UBA';
            $data['account_number'] = '0208633432';
            $data['amount'] = 100;
            $data['password'] = '12345678';
          return UserBalance::withdrawerProcess($data);
          
            break;
        default:
           echo '';
    }
  
    }
  
  }