<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offline extends Base_Controller
{
    const  PENDING = 0;
    const PROCESSOR_ID =2;
    public function __construct()
	{
	  parent::__construct();
	  $this->load->library("pdolib");
      $this->load->library('Exports');
      $this->load->library('Sms');
      $this->load->library('Untils');
      $this->load->model('Transaction');
      $this->load->model('EspiTransaction');
      $this->load->model('User');
      $this->load->model('User_m');
      $this->load->model('Wallet');
      $this->load->model('Uici_levies');
      $this->load->model('PaymentPurchase');
      $this->load->library('utilities/ExcelFactory');
	 
	}

    public function index(){
        $db = $this->pdolib->getPDO();
		$this->data['headStyles'] = [
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		];
		$this->data['footerScripts'] = [
			'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
			BACKOFFICE_HTML_PATH . '/js/offline.js'
          ];
        
          $this->data['ipoint_unit'] = $this->Uici_levies->getUiciLevieValue(IPOINT_UNIT_PRICE_KEY)->value;
          $this->data['title'] = 'Offline Payment Approver';
		$this->data['subview'] = 'backend/offline';
		$this->load->view('components/main', $this->data);

    }

    public function loadPaymentPurchase(){
        $data['status'] = SELF::PENDING; 
        $group_name = $this->session->userdata('active_user')->group_name;
        if(empty($data['id']) && $group_name == MERCHANT){
            $data['id'] = $this->session->userdata('active_user')->id;
        }
		$db = $this->pdolib->getPDO();
		header('Content-Type: application/json');
        $offpayment = PaymentPurchase::findByPreference($db,$data);
		echo json_encode(['value'=>'success','paymentPurchases'=> $offpayment]);	
    }
    
	public function getFitterPaymentPurchase(){
		header('Content-Type: application/json');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');
		$data['fitter'] = $this->input->post('fitter');
		$data['reference'] = $this->input->post('reference');
		$data['wallet'] = $this->input->post('wallet');
		$data['product'] = $this->input->post('product');
		$data['amount'] = $this->input->post('amount');
		$data['status'] = $this->input->post('purchase_status');
		$data['payment_processor'] = $this->input->post('payment_processor');
        $data['id'] = $this->input->post('customerId');
        $group_name = $this->session->userdata('active_user')->group_name;
        if(empty($data['id']) && $group_name == MERCHANT){
            $data['id'] = $this->session->userdata('active_user')->id;
        }
        
        $rules = [];
		if($data['fitter'] == 'date_range'){
			$rules = [
                [
                    'field' => 'start_date',
                    'label' => 'Start Date',
                    'rules' => 'trim|regex_match[/\d{4}\-\d{2}-\d{2}/]'
                ],[
                    'field' => 'end_date',
                    'label' => 'End Date',
                    'rules' => 'trim|regex_match[/\d{4}\-\d{2}-\d{2}/]'
                ]
            ];
    
		}else if($data['fitter']=='reference'){
			$rules = [
				[
					'field' => 'reference',
					'label' => 'Reference',
					'rules' => 'trim|required'
				]
			];
		}else if($data['fitter']=='amount'){
            $rules = [
				[
					'field' => 'amount',
					'label' => 'Settle',
					'rules' => 'trim|required'
				]
			];
        }else if($data['fitter']=='status-s'){
            $rules = [
				[
					'field' => 'purchase_status',
					'label' => 'Purchase Status',
					'rules' => 'trim|required'
				]
			];
        }else if($data['fitter']=='processor'){
            $rules = [
				[
					'field' => 'payment_processor',
					'label' => 'Payment Processor',
					'rules' => 'trim|required'
				]
			];
        }else if($data['fitter']=='wallets'){
            $rules = [
				[
					'field' => 'wallet',
					'label' => 'Wallet',
					'rules' => 'trim|required'
				]
			];
        }else if($data['fitter']=='products'){
            $rules = [
				[
					'field' => 'product',
					'label' => 'Product',
					'rules' => 'trim|required'
				]
			];
        }
        
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            // var_dump(print_r($data));
			$db = $this->pdolib->getPDO();
			$paymentPurchase = PaymentPurchase::findByPreference($db,$data);
		    echo json_encode(['value'=>'success','paymentPurchases'=> $paymentPurchase]);	
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }
    
    public function postApprovedOfflinePayment(){
		
		$data['approved'] = $this->input->post('approved');
		$data['id'] = $this->input->post('id');
		$rules = [];
		if($data['approved'] == 1){
			$rules = [
                [
                    'field' => 'approved_date',
                    'label' => 'Approved Date',
                    'rules' => 'trim|required|regex_match[/\d{4}\-\d{2}-\d{2}/]'
                ]
            ];
    
		}else if($data['is_settled'] == 1){
			$rules = [
                [
                    'field' => 'settled_date',
                    'label' => 'Settled Date',
                    'rules' => 'trim|required|regex_match[/\d{4}\-\d{2}-\d{2}/]'
                ]
            ];
		}else if($data['i-status']=='approved'){
            $rules = [
				[
					'field' => 'approved',
					'label' => 'Approved',
					'rules' => 'trim|required'
				]
			];
        }

        $rules = [
            [
                'field' => 'i-status',
                'label' => 'Status',
                'rules' => 'trim|required|in_list[approved,canceled]'
            ]
        ];

        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
			$this->load->model('dashboard_m');
			$this->load->library("pdolib");
			$db = $this->pdolib->getPDO();
			$offpayment = PaymentPurchase::findByPreference($db,$data);
		    echo json_encode(['value'=>'success']);	
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }
    
    public function createOfflinePayment(){
        header('Content-Type: application/json');
        //var_dump(print_r($this->session->userdata('active_user')));//group_name
        $group_name = $this->session->userdata('active_user')->group_name;
        $user_id = $this->session->userdata('active_user')->id;
        
		$data['id'] = $this->input->post('customerId');
		$data['amount'] = $this->input->post('amount');
		$data['wallet'] = $this->input->post('wallet');
		$data['product'] = $this->input->post('product');
		$data['ipoint'] = $this->input->post('ipoint');
		$data['services'] = $this->input->post('services');
		$data['reference'] = $this->input->post('reference');
        $data['description'] = $this->input->post('description');

        $rules = [];
        $wallet = Wallet::walletByName(I_POINT);
        if($group_name != MERCHANT){

            if(empty($data['id'])){
                exit(json_encode(['customerId'=>'The User Phone Or Email field is required']));
            }
            if(empty($data['services'])){
                exit(json_encode(['services'=>'The Payment Type field is required']));
            }
            if($data['services'] == 'wallet' && empty($data['wallet'])){
                exit(json_encode(['wallet'=>'The Wallet field is required']));
            }
            if($data['services'] == 'product' && empty($data['product'])){
                exit(json_encode(['product'=>'The Product field is required']));
            }
            
        }else{
            $data['id'] = $user_id;
            $data['wallet'] = $wallet->id;
        }
        
        
        $rules = [
            [
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'trim|required|greater_than[0]'
            ],[
                'field' => 'reference',
                'label' => 'Reference',
                'rules' => 'trim|required'
            ],[
                'field' => 'ipoint',
                'label' => 'iPoint',
                'rules' => 'trim|required|greater_than[0]'
            ],[
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required'
            ]
        ];
        $user = $this->User_m->get_user($data['id']);
        //var_dump($user->group_name);
        if(($user->group_name != MERCHANT) && ($user->group_name != SUBSCRIBER)){
            exit(json_encode(['customerId'=>'This user in not authorize to made a request']));
        }
        if(($user->group_name != MERCHANT) && ($data['wallet']==$wallet->id)){
            exit(json_encode(['wallet'=>'This user is not authorize to make a request on ipoint wallet']));
        }
        if(($user->group_name == MERCHANT) && ($data['wallet']!=$wallet->id)){
            exit(json_encode(['wallet'=>'Only Merchants are authorize to make a request on ipoint wallet']));
        }
        
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {

            $paymentPurchase = new PaymentPurchase();
            $paymentPurchase->user_id = $data['id'];
            $paymentPurchase->amount =  $data['amount'];
            $paymentPurchase->quantity = $data['ipoint'];
            $paymentPurchase->processing_status = PaymentPurchase::STATUS_PAYMENT_UNPROCESSED;
            $paymentPurchase->payment_processor = SELF::PROCESSOR_ID;
            $paymentPurchase->product_id = $data['product'];
            $paymentPurchase->wallet_id =  $data['wallet'];
            $paymentPurchase->payment_ref = $data['reference'];
            $paymentPurchase->requested_by = ($group_name == ADMINISTRATOR)?  $user_id :$data['id'];
            $paymentPurchase->description = $data['description'];
            $paymentPurchase->created_at = date('Y-m-d H:i:s');
            $result = $paymentPurchase->save();
            if($result){
                $product_or_wallet ='';
                if(!empty($data['wallet'])){
                    $wallet = Wallet::walletById($data['wallet']);
                    $product_or_wallet = $wallet->name;
                }
               
                $user = $this->User_m->get_user($data['id']);
                $db = $this->pdolib->getPDO();
                $info['report_type'] = OFFLINE_PAYMENT_REQUEST;
                // $info['frequency'] = DAILY;
                // $info['dispatcher_type'] = GROUP;
                $reports = ReportSubscription::getReportSubscription($db,$info);
                if(!empty($reports)){
                    $emails =  array_column($reports, 'email');
                    unset($reports);
                    
                   // variables
                    // group_name==>{0}
                    // wallet name ==>{1}
                    // payment reference=={2}
                    // amount in naira ==> {3}
                    // ipoints value ==> {4}
                    // date and time ==> {5}
                    $name = (empty($user->name))? $user->business_name : $user->name;
                    $variable = array($name,$product_or_wallet,$data['reference'],$data['amount'],$data['ipoint'],date('Y-m-d H:i:s'));
                    MessageQueue::messageCommit($emails, MESSAGE_EMAIL, OFFLINE_PAYMENT_IPOINT_PURCHASE_REQUEST, $variable);
                }
                echo json_encode(['value'=>'success']);	
            }else echo json_encode(['quantity'=>'Offline Payment Request Failed, Please try again']);
            
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
	}

    public function walletList(){
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $wallet = PaymentPurchase::getWallets($db);
        echo json_encode(['value'=>'success','wallets'=>$wallet]);
    }

    public function productList(){
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $product = PaymentPurchase::getProducts($db);
        echo json_encode(['value'=>'success','products'=>$product]);
    }

    public function wallet_service_group(){
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $service_group = PaymentPurchase::getService_group($db);
        echo json_encode(['value'=>'success','service_group'=>$service_group]);
    }

    public function charge_commissions(){
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $charge_commission = PaymentPurchase::getCharge_commission($db);
        echo json_encode(['value'=>'success','charge_commissions'=>$charge_commission]);
    }

    public function commission_wallet(){
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $commission_wallet = Wallet::getWallets($db,['type'=>'commission']);
        echo json_encode(['value'=>'success','commission_wallets'=>$commission_wallet]);
    }

    public function paymentProcessorList(){
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $paymentProcessor = PaymentPurchase::getPaymentProcessor($db);
        echo json_encode(['value'=>'success','paymentProcessors'=>$paymentProcessor]);
    }

    public function approveRequest(){
       
        $data['purchase'] = $this->input->post('id');
        $data['approved'] = $this->input->post('process-payment');
        if($data['approved'] == 1){
            $data['approved'] = $this->input->post('process-payment');
        }else if($this->input->post('void-payment')== 2){
            $data['approved'] = $this->input->post('void-payment');
        }
        $data['user_id'] = $this->session->userdata('active_user')->id;
        $group_name = $this->session->userdata('active_user')->group_name;
        $rules = [];

        if($data['approved'] == 1){
            $rules = [
                [
                    'field' => 'process-payment',
                    'label' => 'Approve Payment',
                    'rules' => 'trim|required|greater_than[0]'
                ]
            ];
        }else{
            $rules = [
                [
                    'field' => 'void-payment',
                    'label' => 'Void Payment',
                    'rules' => 'trim|required|greater_than[0]'
                ]
            ];
        }
        
        

        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{
                $pendingPayment = PaymentPurchase::findById($data['purchase']);
                if(empty($pendingPayment)){
                    exit(json_encode(['customerId'=>'Offline Payment Transaction Doesn\'t Exist']));
                }
                if($pendingPayment->processing_status ==  PaymentPurchase::STATUS_PAYMENT_PROCESSED){
                    exit(json_encode(['customerId'=>'The transaction was processed before']));
                }
                $system_user = User::getSystemUser();
                
                if($group_name != ADMINISTRATOR){
                    exit(json_encode(['customerId'=>'Sorry, You are not allowed to approve this transaction']));
                }
                
                $user = User::findById($pendingPayment->user_id);
                
                $purchase['approved_by'] = $data['user_id'];
                $purchase['updated_at'] = date('Y-m-d H:i:s');
                
                //if($result){
                    $type = (!empty($pendingPayment->wallet_id)) ? 'wallet' : 'product';
                    $name = (empty($user->name))? $user->business_name:$user->name;
                    if($data['approved'] == PaymentPurchase::STATUS_PAYMENT_VOID){
                        $purchase['processing_status'] = PaymentPurchase::STATUS_PAYMENT_VOID;
                        PaymentPurchase::updateByPk($pendingPayment->id,$purchase);
                        exit(json_encode(['value'=>'success']));
                    } 
                    $espiResult = true;$wallet_isavings = Wallet::walletById($pendingPayment->wallet_id);$transfer_value = 0;
                    if($wallet_isavings->name == I_SAVINGS){
                        $espiResult = EspiTransaction::transferOrDepositIsavingsOnEspi('deposit',$system_user->id,$pendingPayment->user_id,$pendingPayment->quantity,$pendingPayment->payment_ref,$pendingPayment->description,FALSE);
						//$transfer_value = EspiTransaction::calculatePercentage($pendingPayment->quantity)['iSavingsBalance'];
                    }
                    
                    // credit offline payment wallet
                    if(!$espiResult){
                        $purchase['processing_status'] =($espiResult)? PaymentPurchase::STATUS_PAYMENT_PROCESSED : PaymentPurchase::STATUS_PAYMENT_UNPROCESSED;
                        PaymentPurchase::updateByPk($pendingPayment->id,$purchase);
                        exit(json_encode(['customerId'=>'Transaction was not successful']));
                    } 
                    $this->db->trans_rollback();
                   
                   $creditWallet = Transaction::credit(
                    $pendingPayment->user_id, 
                    //($wallet_isavings->name == I_SAVINGS)? $transfer_value : $pendingPayment->quantity, 
                    $pendingPayment->quantity, 
                    $pendingPayment->wallet_id, 
                    $pendingPayment->payment_ref, 
                    $pendingPayment->description, 
                    $system_user->id, 
                    FALSE);
                     // add admin 1.20 charges credit
                     $ipoint_sales_commission = Transaction::offline_charge_amount($pendingPayment->amount,$pendingPayment->quantity);
                     $system_commission = Wallet::walletByName(I_SALES_COMMISSION);
                    // credit ipoints commission wallet
                     $admin_result = Transaction::credit(
                     $system_user->id, 
                     $ipoint_sales_commission, 
                     $system_commission->id, 
                     $pendingPayment->payment_ref, 
                     'Offline Payment iPoint commission Charge, Payment Reference '.$pendingPayment->payment_ref, 
                     $user->id, 
                     FALSE);
                     $transaction = ($creditWallet &&  $admin_result)? true : false;
                     $purchase['processing_status'] =($transaction)? PaymentPurchase::STATUS_PAYMENT_PROCESSED : PaymentPurchase::STATUS_PAYMENT_UNPROCESSED;
                     PaymentPurchase::updateByPk($pendingPayment->id,$purchase);
                    if($type == 'wallet'&& ($pendingPayment->quantity >= 50 || !empty($user->email))){
                        $wallet = Wallet::walletById($pendingPayment->wallet_id);
                        $contact = (empty($user->mobile_number))?  $user->email :$user->mobile_number;
                        $type = (!empty($user->email))?  MESSAGE_EMAIL : MESSAGE_SMS;
                        $variable = array((!empty($user->name))? $user->name:$user->business_name,$wallet->name,$pendingPayment->quantity);
                        MessageQueue::messageCommit($contact, $type, OFFLINE_PAYMENT_WALLET_FUND, $variable);

                        if($transaction){
                            $this->db->trans_commit();
                            echo json_encode(['value'=>'success']);
                        }else{
                            $this->db->trans_rollback();
                            echo json_encode(['process-payment'=>'Transaction was not successful']);
                        } 
                        
                    }else{
                        // for offline payment product purchase workfolw
                        //$system_iIncome = Wallet::getForIncome(I_SALES_COMMISSION);
                        //OFFLINE_PAYMENT_PRODUCT_PURCHASE;
                    }
                    
                // }else{
                //     $this->db->trans_rollback();
                //     echo json_encode(['process-payment'=>'Transaction was not successful']);
                // }
                	
            }catch(UserException $ue){
                echo json_encode(['process-payment'=> $ue->getMessage()]);
            }
            
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    public function merchant_create_request(){
        $db = $this->pdolib->getPDO();
        $this->data['headStyles'] = [
            BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
          'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
          BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
        ];
        $this->data['footerScripts'] = [
            BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
          'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
          BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
          BACKOFFICE_HTML_PATH . '/js/offline.js'
        ];
        
        $this->data['ipoint_unit'] = $this->Uici_levies->getUiciLevieValue(IPOINT_UNIT_PRICE_KEY)->value;
        $this->data['title'] = 'Offline Merchant Payment Request';
        $this->data['subview'] = 'backend/merchant_offline_purchase';
        $this->load->view('components/main', $this->data);
    }

    public function create_request(){
        $db = $this->pdolib->getPDO();
        $this->data['headStyles'] = [
            BACKOFFICE_HTML_PATH .'/css/toastr.min.css',
          'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
          BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
        ];
        $this->data['footerScripts'] = [
            BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
          'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
          BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
          BACKOFFICE_HTML_PATH . '/js/offline.js'
        ];
       
        $this->data['ipoint_unit'] = $this->Uici_levies->getUiciLevieValue(IPOINT_UNIT_PRICE_KEY)->value;
        $this->data['title'] = 'Offline Purchase Payment Create Request';
        $this->data['subview'] = 'backend/admin_offline_request';
        $this->load->view('components/main', $this->data);
    }

    public function purchase_manager(){
        $db = $this->pdolib->getPDO();
        $this->data['headStyles'] = [
          'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
          BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
        ];
      $this->data['footerScripts'] = [
          'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
          BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
          BACKOFFICE_HTML_PATH . '/js/offline.js'
        ];
        $this->data['ipoint_unit'] = $this->Uici_levies->getUiciLevieValue(IPOINT_UNIT_PRICE_KEY)->value;
        $this->data['title'] = 'Offline Payment iPoint Purchase List';
        $this->data['subview'] = 'backend/purchase_list';
        $this->load->view('components/main', $this->data);
    }

    public function userPaymentPurchase(){

        $data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');
		$data['fitter'] = $this->input->post('fitter');
		$data['reference'] = $this->input->post('reference');
		$data['wallet'] = $this->input->post('wallet');
		$data['product'] = $this->input->post('product');
		$data['amount'] = $this->input->post('amount');
		$data['status'] = $this->input->post('purchase_status');
		$data['payment_processor'] = $this->input->post('payment_processor');
        $data['id'] = $this->session->userdata('active_user')->id;
        header('Content-Type: application/json');
        if(!empty($data['fitter'])){
            $rules = [];
            if($data['fitter'] == 'date_range'){
                $rules = [
                    [
                        'field' => 'start_date',
                        'label' => 'Start Date',
                        'rules' => 'trim|regex_match[/\d{4}\-\d{2}-\d{2}/]'
                    ],[
                        'field' => 'end_date',
                        'label' => 'End Date',
                        'rules' => 'trim|regex_match[/\d{4}\-\d{2}-\d{2}/]'
                    ]
                ];
        
            }else if($data['fitter']=='reference'){
                $rules = [
                    [
                        'field' => 'reference',
                        'label' => 'Reference',
                        'rules' => 'trim|required'
                    ]
                ];
            }else if($data['fitter']=='amount'){
                $rules = [
                    [
                        'field' => 'amount',
                        'label' => 'Settle',
                        'rules' => 'trim|required'
                    ]
                ];
            }else if($data['fitter']=='status-s'){
                $rules = [
                    [
                        'field' => 'purchase_status',
                        'label' => 'Purchase Status',
                        'rules' => 'trim|required'
                    ]
                ];
            }else if($data['fitter']=='processor'){
                $rules = [
                    [
                        'field' => 'payment_processor',
                        'label' => 'Payment Processor',
                        'rules' => 'trim|required'
                    ]
                ];
            }else if($data['fitter']=='wallets'){
                $rules = [
                    [
                        'field' => 'wallet',
                        'label' => 'Wallet',
                        'rules' => 'trim|required'
                    ]
                ];
            }else if($data['fitter']=='products'){
                $rules = [
                    [
                        'field' => 'product',
                        'label' => 'Product',
                        'rules' => 'trim|required'
                    ]
                ];
            }
            
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run()) {
                $db = $this->pdolib->getPDO();
                
                $paymentPurchase = PaymentPurchase::findByPreference($db,$data);
                echo json_encode(['value'=>'success','paymentPurchases'=> $paymentPurchase]);	
            }else {
                echo json_encode($this->form_validation->get_all_errors());
            }
        }else{
            $db = $this->pdolib->getPDO();
           // var_dump($data);
            $paymentPurchase = PaymentPurchase::findByPreference($db,$data);
            echo json_encode(['value'=>'success','paymentPurchases'=> $paymentPurchase]);	
        }
         
    }
    
    public function paymentExportReport(){
        $db = $this->pdolib->getPDO();
        $data['start_date'] = $this->input->get('start_date');
		$data['end_date'] = $this->input->get('end_date');
		$data['fitter'] = $this->input->get('fitter');
		$data['reference'] = $this->input->get('reference');
		$data['wallet'] = $this->input->get('wallet');
		$data['product'] = $this->input->get('product');
		$data['amount'] = $this->input->get('amount');
		$data['status'] = $this->input->get('purchase_status');
		$data['payment_processor'] = $this->input->get('payment_processor');
        $data['id'] = $this->input->get('customerId');

        if(empty($data)){
            $data['status'] = SELF::PENDING; 
        }
        //var_dump('<pre>',$data);
        $paymentPurchase = PaymentPurchase::findByPreference($db,$data,$isExport=true);
        
        return ExcelFactory::createExcel($paymentPurchase,[],[],'PaymentProcessor');
    }
}