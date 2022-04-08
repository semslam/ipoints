<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductSubscription extends Base_Controller{
    
    public function __construct(){
      parent::__construct();
      $this->load->library("pdolib");
      $this->load->library('utilities/ExcelFactory');
      $this->load->model('ServicesLog_m');
    }

    public function index(){

        $this->data['headStyles'] = [
            BACKOFFICE_HTML_PATH . '/css/toastr.min.css',
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH . '/css/bootstrap-datetimepicker.min.css',
		  ];
		$this->data['footerScripts'] = [
            BACKOFFICE_HTML_PATH . '/js/toastr.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			BACKOFFICE_HTML_PATH . '/js/bootstrap-datetimepicker.min.js',
			
		  ];

        $this->data['title'] = 'Product/Services Subscription';
		$this->data['subview'] = 'services_log/product_subscription';
		$this->load->view('components/main', $this->data);

    }

    public function insuranceProduct(){
        header('Content-Type: application/json');
        $product = Product::getInsuranceProducts();
        echo json_encode(['value'=>'success','products'=>$product]);
    }

    public function period(){
        header('Content-Type: application/json');
        $period = $this->getPeriods();
        echo json_encode(['value'=>'success','periods'=>$period]);
    }

    private function getPeriods($span=2){
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


    public function productSubscriptionWithComptKyc(){

        $product_id = $this->input->post('product');
        $periodStart = $this->input->post('period');
        $kyc = $this->input->post('validkyc');
        
			$rules = [
                [
                    'field' => 'product',
                    'label' => 'Product',
                    'rules' => 'trim|required'
                ],[
                    'field' => 'period',
                    'label' => 'Period',
                    'rules' => 'trim|required'
                ],[
                    'field' => 'validkyc',
                    'label' => 'KYC',
                    'rules' => 'trim|required|greater_than[0]'
                ]
            ];
    
		
        header('Content-Type: application/json');
		$this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            try{

                $product = Product::fetchProductDetails($product_id);
                if (empty($periodStart)) {
                    $nextmonth = mktime(0, 0, 0, date("m")+1, date('d'), date("Y"));
                  }else {
                    $nextmonth = strtotime($periodStart);
                    if ($nextmonth < time()) {
                      $errmsg = 'Start date cannot be earlier than today';
                      log_message('error', $errmsg);
                      throw new Exception($errmsg);
                    }
                    if ($nextmonth === false) {
                      log_message('error', 'Invalid date format sent ==> '.$periodStart);
                      throw new Exception("Invalid date format! Format required: YYYY-mm-dd");
                    }
                  }
                  $period = mktime(date("H", $nextmonth), date("i", $nextmonth), date("s", $nextmonth)-1, date("m", $nextmonth)+$product->allowable_tenure, date("d", $nextmonth), date("Y", $nextmonth));
                  $periodStart = date('Y-m-d', $nextmonth);
                  $periodEnd = date('Y-m-d', $period);

                  $charges_proccess = Transaction::charges_proccess($product->price,$product->commission_value);
                  
                  $billing_price = $charges_proccess['userBalance'] + ($product->price*$product->allowable_tenure);
                  $count = ServicesLog_m::fetchUsersWithSubscribeableCount($product->id,$billing_price);

                  //var_dump($period,$periodStart,$periodEnd,$count,$billing_price);

                  if(empty($count->subscribers)){
                    exit(json_encode(['value'=>'No subscriber currently eligible for the '.$product->product_name.' product']));
                  }
                  $percentage = $charges_proccess['userBalance'];
                 Untils::execInBackground("php index.php cli Utilities productSubscriptionProcess $product->id $periodStart $periodEnd $count->subscribers $billing_price $percentage");
                //ServicesLog_m::processProductSubscription($product->id, $periodStart, $periodEnd, $count->subscribers, $billing_price,$percentage);
               
                echo json_encode(['value'=>'success','users'=>$count->subscribers]);	
            }catch(Throwable $ex){
                echo json_encode(['value'=>$ex->getMessage()]);
            }
            
		}else {
            echo json_encode($this->form_validation->get_all_errors());
        }

    }

    public function filterProductSubscriptionBatch(){
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        //$data['provider_id'] = $this->userId;
        $data['provider_id'] = '';
        $data['batch_id'] =  $this->input->post('batch_id');
        $data['product_id'] =  $this->input->post('products');
        $data['start_date'] =  $this->input->post('start_date');
        $data['end_date'] =  $this->input->post('end_date');
        $subscriptions = ServicesLog_m::fetchProductSubscriptionInGroup($db,$data);
		echo json_encode(['value'=>'success','subscriptions'=> $subscriptions]);	
    }

    public function productSubscriptionGroupExportReport(){
        $db = $this->pdolib->getPDO();
        //$data['provider_id'] = $this->userId;
        $data['provider_id'] = '';
        $data['batch_id'] =  $this->input->get('batch_id');
        $data['product_id'] =  $this->input->get('products');
        $data['start_date'] =  $this->input->get('start_date');
        $data['end_date'] =  $this->input->get('end_date');
        
        $subscriptions = ServicesLog_m::fetchProductSubscriptionInGroup($db,$data,$isExport=true);
        
        return ExcelFactory::createExcel($subscriptions,[],[],'productSubscription');
    }

    public function productSubscriptionBatchExportReport(){
        $db = $this->pdolib->getPDO();
        //$data['merchant_id'] = $this->userId;
        $data['provider_id'] = '';
        $data['batch_id'] =  $this->input->get('batch_id');
        $data['product_id'] =  $this->input->get('product_id');
        if(empty($data['batch_id']) && empty($data['product_id'])){
            echo json_encode(['value'=>'Batch id Or Product id can\'t be empty']);
        }
        $subscriptions = ServicesLog_m::fetchBatchSubscriptionReport($db,$data,$isExport=true);

        return ExcelFactory::createExcel($subscriptions,[],[],'productSubscriptionInBatch');
    }
}
