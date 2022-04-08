<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fundamental extends Base_Controller
{
 
    // const IPOINT_UNIT_PRICE_KEY = 'iPoint_unit_price';

    public function __construct()
    {
        parent::__construct();
        $this->load->library("pdolib");
        $this->load->library('Exports');
        $this->load->library('Sms');
        $this->load->library('Untils');
        $this->load->model('AnnualFee');
        $this->load->model('Wallet');
        $this->load->model('Product');
        $this->load->model('UserBalance');
        $this->load->model('Setting_m');
        $this->load->model('WalletServiceGroup');
        $this->load->library('utilities/ExcelFactory');
    }


    public function annual_fee()
    {
        $this->data['headStyles'] = [
           'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
           BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
        ];
        $this->data['footerScripts'] = [
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
            BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
            BACKOFFICE_HTML_PATH .'/js/fundamental.js'
        ];
        
        // $this->data['ipoint_unit'] = $this->Setting_m->findSystemConstantByKey(SELF::IPOINT_UNIT_PRICE_KEY)->meta_value;
        $this->data['title'] = 'UIC Annual Fee';
        $this->data['subview'] = 'backend/annuals';
        $this->load->view('components/main', $this->data);
    }

    public function annual($rowno = 0)
    {
        $rowperpage = SELF::$rowperpage;
		$count = $rowno;
        $rowno = $this->rowperpage_and_rowno($rowperpage,$rowno);
        
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        $data['total_paid'] = $this->input->post('total_paid');
        $data['txn_reference'] = $this->input->post('txn_reference');
        $data['user_id'] = $this->input->post('customerId');
        $data['limit'] = $rowperpage;
        $data['offset'] = $rowno;
        $rules = [];
        
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

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $this->load->library("pdolib");
            $db = $this->pdolib->getPDO();
            $annual = AnnualFee::getAnnualFee($db, $data);
            $allCount  = AnnualFee::$result_count['allCount'];
            $result['pagination'] = $this->pagination('fundamental/annual',(int)$allCount,$rowperpage,$count);
			$result['annuals'] = $annual;
			$result['row'] = $rowno;
            echo json_encode(['value'=>'success', 'result'=> $result]);
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


    public function annualExportReport(){
      
        $db = $this->pdolib->getPDO();
        $data =[];
        $start_date = $this->input->get('start_date');
        $end_date	= $this->input->get('end_date');
        $data['txn_reference'] = $this->input->get('txn_reference');
        $data['total_paid'] = $this->input->get('total_paid');
        $data['user_id'] = $this->input->get('customerId');
    if(!(empty($start_date) && empty($end_date))){
        $data['start_date'] = $this->input->get('start_date');
        $data['end_date'] 	= $this->input->get('end_date');
    }else if(empty($data['txn_reference']) &&empty($data['total_paid']) && empty($data['txn_reference'])){
        $data['start_date'] = date('Y-m-d', strtotime('today - 365 days'));;
        $data['end_date'] = date('Y-m-d');
    }

    $report = AnnualFee::getAnnualFee($db,$data,$isExport=true);
    return ExcelFactory::createExcel($report,[],[],'annualSubscription');
}



    public function wallets()
    {
        $this->data['headStyles'] = [
           'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
           BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
        ];
        $this->data['footerScripts'] = [
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
            BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
            BACKOFFICE_HTML_PATH .'/js/fundamental.js'
        ];

        $this->data['title'] = 'UIC Wallet';
        $this->data['subview'] = 'backend/wallets';
        $this->load->view('components/main', $this->data);
    }

    public function createWallet()
    {
        $id = $this->input->post('id');
        $data['name'] = $this->input->post('wallet');
        $data['type'] = $this->input->post('type');
        $data['product_id'] = $this->input->post('product');
        $data['wsg_id'] = $this->input->post('service_group');
        $data['can_user_inherit'] = $this->input->post('can_user_inherit');
        $data['can_topup'] = $this->input->post('can_topup');
        

        $rules = [];
        
        $rules = [
            [
                'field' => 'wallet',
                'label' => 'Wallet Name',
                'rules' => 'trim|required'
            ],[
                'field' => 'type',
                'label' => 'Type',
                'rules' => 'trim|required|in_list[system,product,savings,commission]'
            ],[
                'field' => 'product',
                'label' => 'Product',
                'rules' => 'trim'
            ],[
                'field' => 'can_user_inherit',
                'label' => 'Can User Inherit',
                'rules' => 'trim|required|in_list[0,1]'
            ],[
                'field' => 'can_topup',
                'label' => 'Can Topup',
                'rules' => 'trim|required|in_list[0,1]'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            if (!empty($id)) {
                $data['id'] = $this->input->post('id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = Wallet::updateByPk($id, $data);
                if (!$result) {
                    echo json_encode(['value'=>'Wallet was not updated, Please Try Again']);
                }
                echo json_encode(['value'=>'success']);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $wallet = new Wallet();
                $wallet->name = $data['name'];
                $wallet->type = $data['type'];
                $wallet->product_id = empty($data['product_id'])? null : $data['product_id'];
                $wallet->wsg_id = empty($data['wsg_id'])? null : $data['wsg_id'];
                $wallet->can_user_inherit = $data['can_user_inherit'];
                $wallet->can_topup = $data['can_topup'];
                $wallet->created_at = $data['created_at'];
                $wallet->save();
                echo json_encode(['value'=>'success']);
            }
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    public function loadWallets()
    {
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        header('Content-Type: application/json');
        
        $this->load->library("pdolib");
        $db = $this->pdolib->getPDO();
        $wallet = Wallet::getWallets($db, $data);
        echo json_encode(['value'=>'success', 'wallets'=> $wallet]);
    }

    public function walletsList()
    {
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        $data['txn_reference'] = $this->input->post('txn_reference');
        $data['user_id'] = $this->input->post('user_id');

        $rules = [];
        
        $rules = [
            [
                'field' => 'start_date',
                'label' => 'Start Date',
                'rules' => 'trim|required|regex_match[/\d{4}\-\d{2}-\d{2}/]'
            ],[
                'field' => 'end_date',
                'label' => 'End Date',
                'rules' => 'trim|required|regex_match[/\d{4}\-\d{2}-\d{2}/]'
            ],[
                'field' => 'txn_reference',
                'label' => 'Reference',
                'rules' => 'trim|required'
            ],[
                'field' => 'user_id',
                'label' => 'User',
                'rules' => 'trim|required'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $this->load->library("pdolib");
            $db = $this->pdolib->getPDO();
            $wallet = Wallet::getWallets($db, $data);
            echo json_encode(['value'=>'success', 'wallets'=> $wallet]);
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


    public function products()
    {
        $this->data['headStyles'] = [
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
            BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
         ];
        $this->data['footerScripts'] = [
             'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
             BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
             BACKOFFICE_HTML_PATH .'/js/fundamental.js'
         ];
 
        $this->data['title'] = 'UIC Product Services';
        $this->data['subview'] = 'backend/products';
        $this->load->view('components/main', $this->data);
    }

    public function createProduct()
    {
        $id = $this->input->post('id');
        $data['product_name'] = $this->input->post('product_name');
        $data['price'] = $this->input->post('price');
        $data['is_insurance_prod'] = $this->input->post('insurance_prod');
        $data['base_product_yn'] = $this->input->post('base_product');
        $data['allowable_tenure'] = $this->input->post('allowable_tenure');
        $data['charge_commission_id'] = $this->input->post('charge_commission');
        $data['commission_wallet_id'] = $this->input->post('commission_wallet');
        $data['provider_id'] = $this->input->post('customerId');
        $data['is_group_prod'] = $this->input->post('group_prod');
        $data['images'] = $this->input->post('image');
        $data['description'] = $this->input->post('description');
        

        $rules = [];
        
        $rules = [
            [
                'field' => 'product_name',
                'label' => 'Product Name',
                'rules' => 'trim|required'
            ],[
                'field' => 'price',
                'label' => 'Price',
                'rules' => 'trim|required'
            ],[
                'field' => 'insurance_prod',
                'label' => 'Insurance Product',
                'rules' => 'trim|required'
            ],[
                'field' => 'base_product',
                'label' => 'Base Product',
                'rules' => 'trim|required'
            ],[
                'field' => 'allowable_tenure',
                'label' => 'Allowable Tenure',
                'rules' => 'trim|required'
            ],[
                'field' => 'customerId',
                'label' => 'Provider',
                'rules' => 'trim|required'
            ],[
                'field' => 'group_prod',
                'label' => 'Group Product',
                'rules' => 'trim|required'
            ],[
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            if (!empty($id)) {
                $data['id'] = $this->input->post('id');
                $data['author_by'] = $this->session->userdata('active_user')->id;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = Product::updateByPk($id, $data);
                if (!$result) {
                     exit(json_encode(['value'=>'Product was not updated Successfully, Please Try Again']));
                }
                echo json_encode(['value'=>'success']);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $product = new Product();
                $product->product_name = $data['product_name'];
                $product->price = $data['price'];
                $product->is_insurance_prod = $data['is_insurance_prod'];
                $product->base_product_yn = $data['base_product_yn'];
                $product->allowable_tenure = $data['allowable_tenure'];
                $product->provider_id = $data['provider_id'];
                $product->is_group_prod = $data['is_group_prod'];
                $product->charge_commission_id =  empty($data['charge_commission_id'])? null : $data['charge_commission_id'];
                $product->commission_wallet_id =  $data['commission_wallet_id'];
                $product->images = '[]';
                $product->description = $data['description'];
                $product->author_by = $this->session->userdata('active_user')->id;
                $product->created_at = $data['created_at'];
                $product->save();
                echo json_encode(['value'=>'success']);
            }
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    public function loadProduct()
    {
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        header('Content-Type: application/json');
        
        $this->load->library("pdolib");
        $db = $this->pdolib->getPDO();
        $product = Product::getProducts($db,$data);
        echo json_encode(['value'=>'success', 'products'=> $product]);
    }

    public function productsList()
    {
        $data['id'] = $this->input->post('product');
        $data['is_insurance_prod'] = $this->input->post('insurance_prod');
        $data['allowable_tenure'] = $this->input->post('allowable_tenure');
        $data['provider'] = $this->input->post('customerId');

        $rules = [];
        $rules = [
            [
                'field' => 'product',
                'label' => 'Product Name',
                'rules' => 'trim'
            ],[
                'field' => 'allowable_tenure',
                'label' => 'Allowable Tenure',
                'rules' => 'trim'
            ],[
                'field' => 'insurance_prod',
                'label' => 'Insurance Product',
                'rules' => 'trim'
            ],[
                'field' => 'customerId',
                'label' => 'Provider',
                'rules' => 'trim'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $this->load->library("pdolib");
            $db = $this->pdolib->getPDO();
            $product = Product::getProducts($db, $data);
            echo json_encode(['value'=>'success', 'products'=> $product]);
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


    public function benefits()
    {
        $this->data['headStyles'] = [
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
            BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
         ];
        $this->data['footerScripts'] = [
             'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
             BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
             BACKOFFICE_HTML_PATH .'/js/fundamental.js'
         ];
 
        $this->data['title'] = 'UIC Product Benefit';
        $this->data['subview'] = 'backend/benefits';
        $this->load->view('components/main', $this->data);
    }

    public function createBenefit()
    {
        $id = $this->input->post('id');
        $data['amount'] = $this->input->post('amount');
        $data['name'] = $this->input->post('name');
        $data['status'] = $this->input->post('pb-status');
        $data['note'] = $this->input->post('note');
        

        $rules = [];

        if(!empty($id)){
            $rules = [
                [
                    'field' => 'product',
                    'label' => 'Product Name',
                    'rules' => 'trim|required'
                ]
            ];
        }
        
        $rules = [
            [
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'trim|required'
            ],[
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required'
            ],[
                'field' => 'pb-status',
                'label' => 'Status',
                'rules' => 'trim|required'
            ],[
                'field' => 'note',
                'label' => 'Note',
                'rules' => 'trim|required'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            if (!empty($id)) {
                $data['id'] = $this->input->post('id');
                $data['author_by'] = $this->session->userdata('active_user')->id;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = ProductBenefit::updateByPk($id, $data);
                if (!$result) {
                    echo json_encode(['value'=>'Product Benefit was not updated, Please Try Again']);
                }
                echo json_encode(['value'=>'success']);
            } else {
                $data['product_id'] = $this->input->post('product');
                $data['created_at'] = date('Y-m-d H:i:s');
                $benefit = new ProductBenefit();
                $benefit->amount = $data['amount'];
                $benefit->name = $data['name'];
                $benefit->product_id = $data['product_id'];
                $benefit->status = $data['status'];
                $benefit->note = $data['note'];
                $benefit->author_by = $this->session->userdata('active_user')->id;;
                $benefit->created_at = $data['created_at'];
                $benefit->save();
                echo json_encode(['value'=>'success']);
            }
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    public function loadBenefit()
    {
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        header('Content-Type: application/json');
        
        $this->load->library("pdolib");
        $db = $this->pdolib->getPDO();
        $productBenefit = ProductBenefit::getProductBenefit($db, $data);
        echo json_encode(['value'=>'success', 'productBenefits'=> $productBenefit]);
    }

    public function productsBenefitList()
    {
        $data['amount'] = $this->input->post('amount');
        $data['product'] = $this->input->post('product');
        $data['status'] = $this->input->post('pb-status');

        $rules = [];
        $rules = [
            [
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'trim|required'
            ],[
                'field' => 'product',
                'label' => 'Product',
                'rules' => 'trim|required'
            ],[
                'field' => 'pb-status',
                'label' => 'Status',
                'rules' => 'trim|required'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $this->load->library("pdolib");
            $db = $this->pdolib->getPDO();
            $productBenefit = ProductBenefit::getProductBenefit($db, $data);
            echo json_encode(['value'=>'success', 'productBenefits'=> $productBenefit]);
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


    public function overdraft()
    {
        $this->data['headStyles'] = [
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
            BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
         ];
        $this->data['footerScripts'] = [
             'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
             BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
         ];
        $this->data['title'] = 'Set Overdraft Balance';
        $this->data['subview'] = 'backend/overdraft_users_balance';
        $this->load->view('components/main', $this->data);
    }


    public function overdraftManager()
    {
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        $data['group'] = "Merchant";
        header('Content-Type: application/json');
        
        $this->load->library("pdolib");
        $db = $this->pdolib->getPDO();
        $userBalances = UserBalance::getUserBalance($db,$data);
        echo json_encode(['value'=>'success', 'userBalances'=> $userBalances]);
    }

    public function overdraftFitterManager(){

        $data['can_overdraft'] = $this->input->post('can_overdraft');
        $data['user_id'] = $this->input->post('customerId');
        $data['group'] = "Merchant";
        
        $rules = [];
        
        $rules = [
            [
                'field' => 'can_overdraft',
                'label' => 'Can Overdraft',
                'rules' => 'trim'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $this->load->library("pdolib");
            $db = $this->pdolib->getPDO();
            $userBalances = UserBalance::getUserBalance($db, $data);
            echo json_encode(['value'=>'success', 'userBalances'=> $userBalances]);
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    public function setOverdraft(){
        
        $id = $this->input->post('id');
        $data['can_overdraft'] = $this->input->post('can_overdraft');
        $data['overdraft_limit'] = $this->input->post('overdraft_limit');
       
        $rules = [];
        
        $rules = [
            [
                'field' => 'can_overdraft',
                'label' => 'Can Overdraft',
                'rules' => 'trim|required|in_list[0,1]'
            ],[
                'field' => 'overdraft_limit',
                'label' => 'Overdraft Limit',
                'rules' => 'trim|required'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $author_by_email = $this->session->userdata('active_user')->email;
            $data['updated'] = date('Y-m-d H:i:s');
            $result = UserBalance::updateByPk($id, $data);
            if (!$result) {
                exit(json_encode(['value'=>'Overdraft was not successfully set, Please Try Again']));
            }
            //selet user with wallet id
            $overdraft_limit = $data['overdraft_limit'];
            $userBalance = UserBalance::fetchUserBalanceInfoById($id);
            $contact = (!empty($userBalance->email))?  $userBalance->email : $userBalance->mobile_number;
            $type = (!empty($userBalance->email))?  MESSAGE_EMAIL : MESSAGE_SMS;
            $variable = array($userBalance->wallet_name,$overdraft_limit);
            MessageQueue::messageCommit($contact, $type, IPOINT_OVERDRAFT, $variable);
            log_message('INFO','Authorize '.$author_by_email.' set an overdraft of '.$overdraft_limit.' Amount in a '.$userBalance->wallet_name.'wallet, For User '.$contact.' initiate date '.$data['updated']);
            echo json_encode(['value'=>'success']);
            
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    public function wallet_service_group()
    {
        $this->data['headStyles'] = [
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
            BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
         ];
        $this->data['footerScripts'] = [
             'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
             BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
             BACKOFFICE_HTML_PATH .'/js/fundamental.js'
         ];
 
        $this->data['title'] = 'UIC Wallet Service Group';
        $this->data['subview'] = 'backend/wallet_service_group';
        $this->load->view('components/main', $this->data);
    }

    public function createServiceGroup()
    {
        $id = $this->input->post('id');
        $data['name'] = $this->input->post('name');
        $data['description'] = $this->input->post('description');
        

        $rules = [];
        
        $rules = [
            [
                'field' => 'name',
                'label' => 'Service Group Name',
                'rules' => 'trim|required'
            ],[
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            if (!empty($id)) {
                $data['id'] = $this->input->post('id');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $result = WalletServiceGroup::updateByPk($id, $data);
                if (!$result) {
                     exit(json_encode(['value'=>'Service Group was not updated Successfully, Please Try Again']));
                }
                echo json_encode(['value'=>'success']);
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $serviceGroup = new WalletServiceGroup();
                $serviceGroup->name = $data['name'];
                $serviceGroup->description = $data['description'];
                $serviceGroup->created_at = $data['created_at'];
                $serviceGroup->save();
                echo json_encode(['value'=>'success']);
            }
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }

    public function loadServiceGroup()
    {
        $data['start_date'] = $this->input->post('start_date');
        $data['end_date'] = $this->input->post('end_date');
        header('Content-Type: application/json');
        
        $this->load->library("pdolib");
        $db = $this->pdolib->getPDO();
        $serviceGroups = WalletServiceGroup::getServiceGroup($db,$data);
        echo json_encode(['value'=>'success', 'serviceGroups'=> $serviceGroups]);
    }

    public function serviceGroupList(){
        $data['id'] = $this->input->post('id');

        $rules = [];
        $rules = [
            [
                'field' => 'id',
                'label' => 'Service Group Name',
                'rules' => 'trim'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $this->load->library("pdolib");
            $db = $this->pdolib->getPDO();
            $serviceGroups = WalletServiceGroup::getServiceGroup($db,$data);
            echo json_encode(['value'=>'success', 'serviceGroups'=> $serviceGroups]);
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }
}
