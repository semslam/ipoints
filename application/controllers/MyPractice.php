<?php 
class MyPractice extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('practicemodel/mypracticemodel');
    }

    public function index()
    {
            // echo 'Hello Whole Wide World!';
            $data['title'] = "My Real Title";
            $data['heading'] = "Total Annual Fee";
            $rows = $this->mypracticemodel->sumAnnualFee();
            $data['Amount'] = $rows[0]->total_annual_fee;
            $this->load->view('practice/mypracticeview', $data);
            $this->load->view('practice/footer', $data);


    }

//     public function comments($data)
//         {
//                 echo $data;
//         }
}