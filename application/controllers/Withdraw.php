<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Withdraw extends Base_Controller {
    private $userGroup;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User');
        $this->load->model('Wallet');
        $this->load->model('WithdrawRequest');
        $this->load->library("untils");
        $this->load->library("pdolib");
        $this->load->library('Exports');
         $this->load->library('Untils');
         $this->load->library('Sms');
         $this->load->library('utilities/ExcelFactory');
        
        $this->userGroup  = User::fetchUserGroup($this->session->userdata('active_user')->id);
    }

    

	/**
     * List of Users
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function index(){
        $this->data['headStyles'] = [
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
            BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
         ];
         $this->data['footerScripts'] = [
             'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
             BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
         ];
         $system_user = User::systemAccount();
         $iWithdrawerWallet = Wallet::walletByName(I_WITHDRAWER);
         $this->data['iWithdrwer'] = UserBalance::findInfo($system_user->id, $iWithdrawerWallet->id);
         $this->data['pending']  = WithdrawRequest::getWithdrawerStatus('pending');
         $this->data['processing']  = WithdrawRequest::getWithdrawerStatus('processing');
         $this->data['approved']  = WithdrawRequest::getWithdrawerStatus('approved');
         $this->data['cancel']  = WithdrawRequest::getWithdrawerStatus('cancel');
		$this->data['title'] = 'Withdrawer Queue And Process';
		$this->data['subview'] = 'withdraw/withdrawer_list';
		$this->load->view('components/main', $this->data);
    }


    public function requestCommit(){
        $this->data['headStyles'] = [
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
            BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
         ];
         $this->data['footerScripts'] = [
             'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
             BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
         ];

        $this->data['banks'] = BANKS;
		$this->data['title'] = 'Withdrawer Request';
		$this->data['subview'] = 'withdraw/withdrawer_request';
		$this->load->view('components/main', $this->data);
    }


    public function createWithdrawerRequest(){
        //get a value of one 
        // get the user balance checke if current balance is grather than tresh role 
        // if true minus treshrole out of the current balance save it in cashout 
        // if else return not anough balance 
        $data['amount'] = $this->input->post('amount');
        $data['account_number'] = $this->input->post('account_number');
        $data['bank_name'] = $this->input->post('bank_name');

        $rules = [
            [
                'field' => 'bank_name',
                'label' => 'Bank Name',
                'rules' => 'trim|required'
            ],[
                'field' => 'account_number',
                'label' => 'Account Number',
                'rules' => 'trim|required|min_length[10]|max_length[10]'
            ],[
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'trim|required'
            ]
        ];
        header('Content-Type: application/json');

        $mobile_number = $this->session->userdata('active_user')->mobile_number;
        $email = $this->session->userdata('active_user')->email;
        $group_name = $this->session->userdata('active_user')->group_name;
        $name = $this->session->userdata('active_user')->name;

		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{

                if($group_name != SUBSCRIBER && $group_name != UNDERWRITER){
                    exit(json_encode(['bank_name'=> 'This user is not authorize to make withdrawer request']));
                }
        
                if(!empty($mobile_number)){
                    $username = $this->sms->cleanPhoneNumber($mobile_number);
                    $data['contact'] = $username;	
                }else{ 
                    $data['contact'] = $email;
                }

                if($group_name == SUBSCRIBER){
                    $wallet = I_SAVINGS;
                }elseif($group_name == UNDERWRITER){
                    $wallet = I_INCOME;
                }

                $data['reference'] ='';
                $withdrawerResponse =  UserBalance::withdrawerProcess($data,false);

                if($withdrawerResponse){
                    $db = $this->pdolib->getPDO();
                    $action = WITHDRAW_REQUEST;
                    $info['report_type'] = WITHDRAWER_REQUEST;
                    // $info['frequency'] = DAILY;
                    // $info['dispatcher_type'] = INDIVIDUAL;
                    $reports = ReportSubscription::getReportSubscription($db,$info);
                    //dump sql file in a path and return part url
                    $contact =  array_column($reports, 'email');
                    $type = MESSAGE_EMAIL;
                    unset($reports);
                    $variable = array($name,$wallet,$data['amount']);
                    MessageQueue::messageCommit($contact, $type, $action, $variable);
                }
                $response = ($withdrawerResponse)? ['value'=>'success'] : ['bank_name'=> 'Request failed try again'] ;
                
                echo json_encode($response);
 
            }catch(Throwable $ue){
                echo json_encode(['amount'=> $ue->getMessage()]);
            }
        
        }else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    
    public function loadWithdrawers()
    {
        $data['start_date'] = date('Y-m-d H:i:s', strtotime('today - 60 days'));;
        $data['end_date'] = date('Y-m-d H:i:s');
        //$data['status'] = 'pending';
        if($this->userGroup['group_name'] != ADMINISTRATOR){
            $data['user_id'] = $this->session->userdata('active_user')->id;
        }
        header('Content-Type: application/json');
        
        $db = $this->pdolib->getPDO();
        $withdrawer = WithdrawRequest::getWithdrawers($db, $data);
        echo json_encode(['value'=>'success', 'withdrawers'=> $withdrawer]);
    }


    public function filterWithdrawer(){
        
		$data['user_id'] = $this->input->post('customerId');
        $data['amount'] = $this->input->post('amount');
        $data['reference'] = $this->input->post('w-reference');
		$data['status'] = $this->input->post('w-status');
		$data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        if($this->userGroup['group_name'] != ADMINISTRATOR){
            unset($data['user_id']);
            $data['user_id'] = $this->session->userdata('active_user')->id;
        }
        

        $rules = [
            [
                'field' => 'customerId',
                'label' => 'User',
                'rules' => 'trim'
            ],
            [
                'field' => 'w-status',
                'label' => 'Status',
                'rules' => 'trim|in_list[pending,processing,approved,cancel]'
            ],
            [
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'trim'
            ]
        ];
        
        
        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
			$db = $this->pdolib->getPDO();
            $withdrawer = WithdrawRequest::getWithdrawers($db, $data);
            echo json_encode(['value'=>'success', 'withdrawers'=> $withdrawer]);
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


    public function withdrawerExportReport(){
        $db = $this->pdolib->getPDO();
        $data =[];
        $data['reference']	= $this->input->get('reference');
        $data['user_id'] = $this->input->get('customerId');
        $data['start_date'] = $this->input->get('start_date');
        $data['end_date'] 	= $this->input->get('end_date');
        $data['amount'] = $this->input->get('amount');
        $data['status'] = $this->input->get('status');
        if($this->userGroup['group_name'] != ADMINISTRATOR){
            unset($data['user_id']);
            $data['user_id'] = $this->session->userdata('active_user')->id;
        }
        //var_dump('<pre>',$data);
        $report = WithdrawRequest::getWithdrawers($db,$data,$isExport=true);
        //return $this->exports->exportExcel($report, []);
        return ExcelFactory::createExcel($report,[],[],'withdrawerExportReport');
    }



    public function processWithdrawer(){
      
        $data['id'] = $this->input->post('requestId');
        $data['reference'] = $this->input->post('reference');
        $data['status'] = $this->input->post('w-status');
        $data['authorize_id'] = $this->session->userdata('active_user')->id;
        
        $rules = [];
        
        $rules = [
            [
                'field' => 'reference',
                'label' => 'Request Id',
                'rules' => 'trim|required'
            ],[
                'field' => 'w-status',
                'label' => 'Status',
                'rules' => 'trim|required|in_list[processing,approved,cancel]'
            ]
        ];

        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{
                // get request with id
                $withdrawer = WithdrawRequest::findById($data['id']);

                if($withdrawer->transaction_reference !=  $data['reference']){
                    exit(json_encode(['w-status'=>'The reference is not existing']));
                }

                if($withdrawer->status == 'approved'){
                    exit(json_encode(['w-status'=>'This request has already been approved.']));
                }
                if($withdrawer->status == 'cancel'){
                    exit(json_encode(['w-status'=>'This request has already been canceled.']));
                }
                $admin = User::fetchUserGroup($data['authorize_id']);
                
                if($admin['group_name'] != ADMINISTRATOR && ($data['status'] == 'approved' || $data['status'] == 'processing')){
                    exit(json_encode(['w-status'=>'Sorry, Only administrator is allowed to process this request']));
                }

                $data['user_balance_id']= $withdrawer->user_balance_id;
                $data['amount']= $withdrawer->amount;
                $withdrawerReverse = UserBalance::withdrawerReverse($data);
                //$withdrawerReverse = true;
            
                if($withdrawerReverse){
                    $db = $this->pdolib->getPDO();
                    $action = "";
                    $contact ="";
                    $type = "";
                   $userBalance = UserBalance::fetchUserBalanceInfoById($withdrawer->user_balance_id);
                    if($data['status'] == 'cancel' && $admin['group_name'] == ADMINISTRATOR){
                        $action = WITHDRAW_CANCEL;
                        $contact = (empty($userBalance->mobile_number))?  $userBalance->email :$userBalance->mobile_number;
                        $type = (empty($userBalance->mobile_number))?  MESSAGE_EMAIL : MESSAGE_SMS;
                    }elseif($data['status']== 'approved'){
                        $action = WITHDRAW_APPROVED;
                        $contact = (empty($userBalance->mobile_number))?  $userBalance->email :$userBalance->mobile_number;
                        $type = (empty($userBalance->mobile_number))?  MESSAGE_EMAIL : MESSAGE_SMS;
                    }elseif($data['status']== 'cancel' ){
                        $action = WITHDRAW_CANCEL_BY_ADMIN;
                        $info['report_type'] = WITHDRAWER_CANCELLATION;
                        // $info['frequency'] = DAILY;
                        // $info['dispatcher_type'] = INDIVIDUAL;
                        $reports = ReportSubscription::getReportSubscription($db,$info);
                        //dump sql file in a path and return part url
                        $contact =  array_column($reports, 'email');
                        $type = MESSAGE_EMAIL;
                        unset($reports);
                    }
                    $variable = array($userBalance->name,$userBalance->wallet_name,$withdrawer->amount);
                    
                    MessageQueue::messageCommit($contact, $type, $action, $variable);
                    echo json_encode(['value'=> 'success']);
                }else echo json_encode(['value'=> 'Request failed try again']);
                
            }catch(Throwable $ue){
                echo json_encode(['w-status'=> $ue->getMessage()]);
            }
            
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


}