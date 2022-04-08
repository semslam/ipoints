<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//Base_Controller
class MessageTemplate extends Base_Controller {

	public function __construct()
	{
	  parent::__construct();
	  $this->load->library("pdolib");
	  $this->load->model("User_m");
	  $this->load->library('utilities/ExcelFactory');
	 
	}


	public function index(){
		$this->data['headStyles'] = [
            BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css',
			BACKOFFICE_HTML_PATH . '/css/summernote.css',
		  ];
		$this->data['footerScripts'] = [
            BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js',
			'//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js',
			BACKOFFICE_HTML_PATH . '/js/summernote.js',
			
		  ];

		$this->data['actions'] = [
			REGISTER
		,FORGOT_PASSWORD
		,AUTO_USER
		,RE_GENERATE_OTP
		,TRANSFER_CREDIT
		,TRANSFER_DEBIT
		,SMS_DEBIT
		,IPOINT_OVERDRAFT
		,MERCHANT_OFFLINE_PAYMENT
		,PRODUCT_SERVICES_SUBSCRIPTION
		,PRODUCT_SUBSCRIPTION_REPORT
		,OFFLINE_PAYMENT_IPOINT_PURCHASE_REQUEST
		,OFFLINE_PAYMENT_PRODUCT_PURCHASE
		,OFFLINE_PAYMENT_WALLET_FUND
		,WITHDRAW_REQUEST
		,WITHDRAW_APPROVED
		,WITHDRAW_CANCEL_BY_ADMIN
		,WITHDRAW_CANCEL
		,IPIN_GENERATOR
		,NEW_WIP_TRANSACTION
		,OLD_WIP_TRANSACTION
		,CUMULATIVE_ISAVINGS_REPORT
		,ONLINE_TOPUP_WALLET_SUCCESS
		,ONLINE_TOPUP_WALLET_FAILED
		,BULK_TRANSFER
		,ESPI_PROCESS_ERROR_REPORT
		,OFFLINE_PAYMENT_REQUEST_VOID
		,NEW_WIP_TRANSACTION_ISAVING
		,OLD_WIP_TRANSACTION_ISAVING
		,TRANSFER_CREDIT_NEW_USER
	];



		$this->data['title'] = 'Message Template';
		$this->data['subview'] = 'message_template/create_update';
		$this->load->view('components/main', $this->data);
	}

	public function createAndUpdate(){
		header("Content-type:application/json");
		$id = $this->input->post('id');
		$message_template_sms	= addslashes($this->input->post('message_template_sms'));
		$message_template_email = addslashes($this->input->post('message_template_email'));
		//$message_template_email = $this->input->post('message_template_email');
		$message_subject = $this->input->post('message_subject');
		$data['action']   	= $this->input->post('action');
		$data['message_channel']  = $this->input->post('message_channel');
		$data['charge']   	= $this->input->post('charge');
		$data['attempt_no']   = $this->input->post('attempt_no');
		$data['priority']    = $this->input->post('priority');
		$data['last_updated_by']    = $this->session->userdata('active_user')->id;

		$rules = [];

		if(!empty($message_template_sms)){
			$rules = [
				[
				  'field' => 'message_template_sms',
				  'label' => 'Message Template',
				  'rules' => 'required'
				]
			];
			
			$data['message_template'] = $message_template_sms;
		}else if(!empty($message_template_email)){
			$rules = [
				[
				  'field' => 'message_template_email',
				  'label' => 'Message Template',
				  'rules' => 'trim|required'
				  ],[
					'field' => 'message_subject',
					'label' => 'Message Subject',
					'rules' => 'trim|required'
				] 
			];
			$data['message_subject'] = $message_subject;
			$data['message_template'] = $message_template_email;
		}else if(empty($message_template_email) && empty($message_template_sms)){
			echo json_encode(['message_channel'=>'Message template required']);
		}

		$rules = [
			
			[
				'field' => 'action',
				'label' => 'Action',
				'rules' => 'required'
			],
			[
				'field' => 'message_channel',
				'label' => 'Channel',
				'rules' => 'trim|required|in_list[Email,Sms]'
			],
			[
				'field' => 'charge',
				'label' => 'Charge',
				'rules' => 'trim|required|in_list[free,paid]'
			],
			[
				'field' => 'attempt_no',
				'label' => 'Attempt Number',
				'rules' => 'trim|required|in_list[1,2,3,4,5,6,7,8,9]'
			],
			[
				'field' => 'priority',
				'label' => 'Priority',
				'rules' => 'trim|required|in_list[1,2,3,4,5,6,7,8,9]'
			]
		];

		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			
			 $templateResult = MessageTemplate_m::createAndUpdate($id,$data);
			$result = ($templateResult)?['value'=>'success']:['message_subject'=>'Template wasn\'t save successful, Please try again'];
			echo json_encode($result);
		}else{
			echo json_encode($this->form_validation->get_all_errors());
		}

	}


	public function loadAndFitterMessageTemplate(){
		header("Content-type:application/json");
		$data['action']   			= $this->input->post('action');
		$data['message_channel']   		= $this->input->post('message_channel');
		$data['charge']   		= $this->input->post('charge');
		$data['attempt_no']   		= $this->input->post('attempt_no');
		$data['priority']    = $this->input->post('priority');
		$db = $this->pdolib->getPDO();
		 $templates = MessageTemplate_m::fitterTemplateMessage($db,$data);
		 //$template_ = [];
		 foreach($templates as $template){
			//var_dump(stripcslashes($template['message_template']));
			$template_[] =array(
				'id'=> $template['id'],
				'message_subject'=> $template['message_subject'],
				'message_template'=> stripcslashes($template['message_template']),
				'action'=> $template['action'],
				'message_channel'=> $template['message_channel'],
				'charge'=> $template['charge'],
				'attempt_no'=> $template['attempt_no'],
				'priority'=> $template['priority'],
				'last_updated_by'=> $template['last_updated_by'],
				'created'=> $template['created'],
				'updated'=> $template['updated']
				
			);
		 }
		 echo json_encode(['value'=>'success', 'templates'=> $template_]);

	}

	public function walletList(){
        
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $wallet = PaymentPurchase::getWallets($db);
        echo json_encode(['value'=>'success','wallets'=>$wallet]);
    }

    public function subscriber_message(){
        $this->data['title'] = 'Subscriber Notification Message';
		$this->data['subview'] = 'message_template/subscriber_message';
		$this->load->view('components/main', $this->data);
    }

    public function notification_message(){
        
		$data['filter'] = $this->input->post('filter');
		$data['wallet'] = $this->input->post('wallet');
        $data['point'] = $this->input->post('point');
        $filter = $this->input->post('filter');
		$wallet = $this->input->post('wallet');
		$point = $this->input->post('point');
		$message = $this->input->post('message');
        

        $rules = [
            [
                'field' => 'wallet',
                'label' => 'Wallet',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'filter',
                'label' => 'Filter',
                'rules' => 'trim|required|in_list[=,>=,<=,!=]'
            ],
            [
                'field' => 'point',
                'label' => 'Point',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'message',
                'label' => 'Message',
                'rules' => 'trim|required'
            ]
        ];
        
        
        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
			$db = $this->pdolib->getPDO();
            $message_count = UserBalance::users_count($db,$data);
           
            if(!empty($message_count)){
                log_message('INFO','CONTROLER START=============================');
                log_message('INFO',print_r($data, TRUE));
                $count = $message_count['count_users'];
                $theMessage= urlencode($message);
                $thefilter= urlencode($filter);
                Untils::execInBackground("php index.php cli Utilities subscriberSMSProcess $thefilter $wallet $point $count $theMessage");
            echo json_encode(['value'=>'success','counter'=>$message_count['count_users']]);
        }else{
            echo json_encode(['value'=>'no_record', 'empty'=> 'No Record Found']);
        }	
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
	}
	

	public function queue_messages(){
		$this->data['headStyles'] = [
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		  ];
		$this->data['footerScripts'] = [
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
		  ];
		$this->data['pending']  = MessageQueue::count(['status'=>'pending']);
		$this->data['sent']  = MessageQueue::count(['status'=>'sent']);
		$this->data['failed']  = MessageQueue::count(['status'=>'failed']);
		$this->data['paid']  = MessageQueue::count(['charge'=>'paid','status'=>'pending']);
		$this->data['arrears']  = MessageQueue::count(['charge'=>'arrears','status'=>'sent']);
		$this->data['balance']  = MessageQueue::checkSMSBalnces();;
		
	
        $this->data['title'] = 'Process Message Queue';
		$this->data['subview'] = 'message_template/message_queue';
		$this->load->view('components/main', $this->data);
	}

	private function getRecipient($recipient){
		if(!empty($recipient)){
			$user = $this->User_m->get_user($recipient);
		return (!empty($user->mobile_number))? $user->mobile_number: $user->email;

		}
		 return '';
	}
	
	public function filterQueueMessages($rowno = 0){
		$rowperpage = SELF::$rowperpage;
		$count = $rowno;
		$rowno = $this->rowperpage_and_rowno($rowperpage,$rowno);
		$recipient = $this->input->post('customerId');
		$data['recipient']  = $this->getRecipient($recipient);
		$data['recipient_type'] = $this->input->post('recipient_type');
		$data['message_type'] = $this->input->post('message_type');
        $data['type'] = $this->input->post('type');
        $data['charge'] = $this->input->post('charge');
		$data['status'] = $this->input->post('mq-status');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');
		$data['limit'] = $rowperpage;
		$data['offset'] = $rowno;

        if(empty($data)){
			$data['status']	= MessageQueue::STATUS_PENDING;
			$data['recipient_type'] = MessageQueue::SINGLE;
		}
		
        header('Content-Type: application/json');
			$db = $this->pdolib->getPDO();
			$messageQueueing= MessageQueue::fitterMessageQueue($db, $data);
			//1854
			$allCount  = MessageQueue::$result_count['allCount'];
			
			$result['pagination'] = $this->pagination('messageTemplate/filterQueueMessages',(int)$allCount,$rowperpage,$count);
			$result['messageQueueing'] = $messageQueueing;
			$result['row'] = $rowno;
            echo json_encode(['value'=>'success', 'result'=> $result]);	
	}


	public function messageQueueExportReport(){

		$recipient = $this->input->get('customerId');
		$data['recipient']  = $this->getRecipient($recipient);
		$data['recipient_type'] = $this->input->get('recipient_type');
		$data['message_type'] = $this->input->get('message_type');
        $data['type'] = $this->input->get('type');
        $data['charge'] = $this->input->get('charge');
		$data['status'] = $this->input->get('mqstatus');
		$data['start_date'] = $this->input->get('start_date');
		$data['end_date'] = $this->input->get('end_date');
        if(empty($data)){
			$data['status']	= MessageQueue::STATUS_PENDING;
			$data['recipient_type'] = MessageQueue::SINGLE;
		}
			$db = $this->pdolib->getPDO();
			$messageQueueing= MessageQueue::fitterMessageQueue($db, $data,$isExport=true);
            return ExcelFactory::createExcel($messageQueueing,[],[],'MessageQueueing');	
	}

	public function chat_message(){
        $this->data['title'] = 'Chat Instant Message';
		$this->data['subview'] = 'message_template/chat';
		$this->load->view('components/main', $this->data);
	}
	
	public function chats_message(){
        $this->data['title'] = 'Chats Instant Message';
		$this->data['subview'] = 'message_template/chats';
		$this->load->view('components/main', $this->data);
    }



}
