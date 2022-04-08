<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/datatable/DatatableQueryFormatter.php';
require_once APPPATH . 'libraries/datatable/QueryModifier.php';

class Reports extends Base_Controller implements QueryModifier
{
  use DatatableQueryFormatter;
  
  public function __construct()
    {
        parent::__construct();
        
        $this->load->model('ReportSubscription');
        $this->load->model('Product');
        $this->load->model('User');
        $this->load->library("pdolib");
    }

	public function validSubscribers()
	{
    try {
      $productId = $this->input->post('productId', true);
      $periodStart = $this->input->post('period', true);
      $validKycOnly = $this->input->post('kycOnly', true);
      $export = $this->input->post('exportTo', true);
      $exportToExcel = strcasecmp($export, 'excel') === 0;
      // exit(print_r(compact('productId', 'periodStart','validKycOnly','export','exportToExcel'), true));
      // run subscription check
      $this->load->library('utilities/Subscription');
      $reportName = 'Billing period';
      if (empty($periodStart)) {
        $nextmonth = mktime(0, 0, 0, date("m")+1, date('d'), date("Y"));
      } 
      else {
        $nextmonth = strtotime($periodStart);
        if ($nextmonth < time()) {
          $errmsg = 'startDate cannot be earlier than today';
          log_message('error', $errmsg);
          throw new Exception($errmsg);
        }
        if ($nextmonth === false) {
          log_message('error', 'Invalid date format sent ==> '.$periodStart);
          throw new Exception("Invalid date format! Format required: YYYY-mm-dd");
        }
      }
      $period = mktime(date("H", $nextmonth), date("i", $nextmonth), date("s", $nextmonth)-1, date("m", $nextmonth)+1, date("d", $nextmonth), date("Y", $nextmonth));
      $periodStart = date('Y-m-d', $nextmonth);
      $periodEnd = date('Y-m-d', $period);
      $report = User::getUsersWithSubscribeableBalances($this, $periodStart, $periodEnd, $productId, $validKycOnly, $exportToExcel); 
    } catch (Exception $e) {
      // @TODO Flash error message to user
      log_message('error', $e->getMessage().' :: '.$e->getTraceAsString());
      $report = [
        'data' => [],
        'recordsFiltered' => 0,
        'recordsTotal' => 0,
      ];
    }
    echo json_encode($report);
	}

	public function index()
	{
    $this->data['products'] = $this->getInsuranceProducts() ?? [];
    $this->data['periods'] = $this->getPeriods();
		$this->data['title'] = 'Product/Service Subscription Reports';
		$this->data['subview'] = 'reports/main';
		$this->load->view('components/main', $this->data);
  }
  
  private function getInsuranceProducts()
  {
    
		return $this->db->select('p.*')
			->from('products p')
			->join('wallets w', "p.id = w.product_id AND p.provider_id IS NOT NULL AND w.type = 'product'")
			->get()->result_object('Product');
  }

  private function getPeriods($span=2)
  {
    $periods = [];
    $period = mktime(0, 0, 0, date("m"), 1, date("Y"));
    for ($i=0; $i < $span; $i++) {
      $periodStart = date('Y-m-d', $period);
      $startDisp = date('M Y', $period);
      $periods[] = ['name' => $startDisp, 'value' => $periodStart];
      $period = mktime(0, 0, 0, date("m", $period)+1, 1, date("Y", $period));
    }
    return $periods ?: [];
  }

  public function reports_subscription(){

    $this->data['headStyles'] = [
        BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
      ];
    $this->data['footerScripts'] = [
        BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
        
      ];

        $this->data['types'] = [
          CUMULATIVE,
          SALES,
          PRODUCT_SUBSCRIPTION,
          WITHDRAWER_CANCELLATION,
          WITHDRAWER_REQUEST,
          OFFLINE_PAYMENT_REQUEST
        ];
        $this->data['title'] = 'Auto Email Reports ';
        $this->data['subview'] = 'reports/report_subscription';
		$this->load->view('components/main', $this->data);
  }

  public function loadReportSubscriber(){
    $data['id'] = $this->input->post('id');
        $data['name'] = $this->input->post('name');
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $reportSubscription = ReportSubscription::findByPreference($db, $data);
        echo json_encode(['value'=>'success', 'reportSubscriptions'=> $reportSubscription]);
  }

  public function fitterReportSubscriber(){
        $data['frequency'] = $this->input->post('frequency');
        $data['report_type'] = $this->input->post('report_type');
        $data['dispatcher_type'] = $this->input->post('dispatch');
        $data['status'] = $this->input->post('rs-status');
        $data['user_id'] = $this->input->post('customerId');
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $reportSubscription = ReportSubscription::findByPreference($db, $data);
        echo json_encode(['value'=>'success', 'reportSubscriptions'=> $reportSubscription]);
  }

  public function createReportSubcriber(){
    $id = $this->input->post('id');
    $data['frequency'] = $this->input->post('frequency');
    $data['report_type'] = $this->input->post('report_type');
    $data['dispatcher_type'] = $this->input->post('dispatch');
    $data['status'] = $this->input->post('rs-status');
   
    $data['author_by'] = $this->session->userdata('active_user')->id;
    

    $rules = [];
    if(empty($id)){
      $rules = [
        [
          'field' => 'customerId',
          'label' => 'Subscriber',
          'rules' => 'trim|required'
        ]
      ];
    }
    
    $rules = [
        [
            'field' => 'frequency',
            'label' => 'Frequency',
            'rules' => 'trim|required'
        ],[
            'field' => 'report_type',
            'label' => 'Report Type',
            'rules' => 'trim|required'
        ],[
            'field' => 'dispatch',
            'label' => 'Dispatcher Type',
            'rules' => 'trim|required'
        ],[
            'field' => 'send_to_all',
            'label' => 'Send To All',
            'rules' => 'trim'
        ],[
          'field' => 'rs-status',
          'label' => 'Status',
          'rules' => 'trim|required|in_list[0,1]'
      ] 
    ];

    header('Content-Type: application/json');
    $this->form_validation->set_rules($rules);
    if ($this->form_validation->run()) {
        if (!empty($id)) {
            $data['id'] = $this->input->post('id');
            $data['updated_at'] = date('Y-m-d H:i:s');
            $result = ReportSubscription::updateByPk($id, $data);
            if (!$result) {
                echo json_encode(['value'=>'Report Subscribtion was not updated successful, Please Try Again']);
            }
            echo json_encode(['value'=>'success']);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s'); 
            $data['user_id'] = $this->input->post('customerId');
            $data['send_to_all'] = $this->input->post('send_to_all');
            $reportSubscription = new ReportSubscription();
            $reportSubscription->frequency = $data['frequency'];
            $reportSubscription->report_type = $data['report_type'];
            $reportSubscription->dispatcher_type = $data['dispatcher_type'];
            $reportSubscription->send_to_all = $data['send_to_all'];
            $reportSubscription->status = $data['status'];
            $reportSubscription->user_id = $data['user_id'];
            $reportSubscription->author_by = $data['author_by'];
            $reportSubscription->updated_at = $data['created_at'];
            $reportSubscription->created_at = $data['created_at'];
            $reportSubscription->save();
            echo json_encode(['value'=>'success']);
        }
    } else {
        echo json_encode($this->form_validation->get_all_errors());
    }
}


  public function subcribeOrUnsubscribeReport(){
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $data['status'] = $this->input->post('status');
        $data['author_by'] = $this->session->userdata('active_user')->id;
        $data['updated_at'] = date('Y-m-d H:i:s');
        header('Content-Type: application/json');
        $result = ReportSubscription::updateByPk($id, $data);
        if (!$result) {
            echo json_encode(['value'=>'Report Subscribtion was not updated successful, Please Try Again']);
        }
        echo json_encode(['value'=>'success']);
  }

  	/**
     *  subscriber_list
     *
     * @access private
     * @param   
     * @return view
     */
	
	public function subscriber_list()
	{
		$this->data['end_date'] = date('Y-m-d'); 
		$this->data['start_date'] = date('Y-m-d', strtotime('today - 365 days'));
		$this->data['title'] = 'User Subscribers Queue';
		$this->data['headStyles'] = [
		'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
		BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		];
		
		$this->data['footerScripts'] = [
		'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
		BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
		BACKOFFICE_HTML_PATH . '/js/dashboard.js'
		];
		$this->data['subview'] = 'reports/subscriber_users_list';
		$this->load->view('components/main', $this->data);
	}

	public function product_list()
	{
		$this->data['end_date'] = date('Y-m-d'); 
		$this->data['start_date'] = date('Y-m-d', strtotime('today - 600 days'));
        $this->data['title'] = 'Product Subscription Manager';
		$this->data['headStyles'] = [
		'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
		BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		];
		
		$this->data['footerScripts'] = [
		'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
		BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
		BACKOFFICE_HTML_PATH . '/js/dashboard.js'
		];
		$this->data['subview'] = 'reports/product_subscribers_list';
		$this->load->view('components/main', $this->data);
  }
  

  
}