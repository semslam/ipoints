<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PaginationTesting extends Base_Controller{

   public function __construct(){
      parent::__construct();
      $this->load->helper('url');
      $this->load->library('pagination');
      $this->load->database();
   }

   /**
    * Get All Data from this method.
    *
    * @return Response
   */
   public function index(){
    $this->data['title'] = 'Pagination Table';
    $this->data['subview'] = 'template/a_pagination';
    $this->load->view('components/main', $this->data);
   }

   public function boot(){
    $this->data['title'] = 'Pagination Table';
    $this->data['subview'] = 'template/b_pagination_boostrap';
    $this->load->view('components/main', $this->data);
   }

   /**
    * Get All Data from this method.
    *
    * @return Response
   */
   public function loadRecord($rowno=0){

        $rowperpage = 10;
    //     $mult = 0;
    //     $int =0;
    //     $count = $rowno;
    //    if($rowno != 0){
    //      $rowno = ($rowno-1) * $rowperpage;
    //      $int += $rowno;
    //      for($i=0; $i < $count; $i++){
    //         $mult += $rowperpage;
    //      }
    //     }else{
    //         $int =1;
    //         $mult = $rowperpage;
    //     }

        $allcount = $this->db->count_all('users');
        //echo '/n Showing '.$int.' <<<<TO>>>> '.$mult.'<<<<OF>>>>'.$allcount.'<<<<>>>>';

       $this->db->limit($rowperpage, $this->rowperpage_and_rowno($rowperpage,$rowno));
      // $this->db->limit($rowperpage,$rowno);
       $users_record = $this->db->get('users')->result_array();


    //    $config['base_url'] = base_url().'paginationTesting/loadRecord';
    //    $config['use_page_numbers'] = TRUE;
    //    $config['total_rows'] = $allcount;
    //    $config['per_page'] = $rowperpage;

    //    $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul sytle="border: 1px solid #ddd;" class="pagination">';
    //    $config['full_tag_close']   = '</ul></nav></div>';
    //    $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
    //    $config['num_tag_close']    = '</span></li>';
    //    $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
    //    $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
    //    $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
    //    $config['next_tag_close']  = '<span aria-hidden="true"></span></span></li>';
    //    $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
    //    $config['prev_tag_close']  = '</span></li>';
    //    $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
    //    $config['first_tag_close'] = '</span></li>';
    //    $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
    //    $config['last_tag_close']  = '</span></li>';

    //    $this->pagination->initialize($config);

     //  $data['pagination'] = $this->pagination->create_links();
       $data['pagination'] = $this->pagination('paginationTesting/loadRecord',$allcount,$rowperpage,$rowno);
       $data['result'] = $users_record;
       $data['row'] = $rowno;

    //    var_dump('<pre>',$data['pagination']);
    //    var_dump('<pre>',$data['result']);
    //    var_dump('<pre>',$data['row']);exit;

       echo json_encode($data);
    }


    public function tableInfo(){

    }

    
}