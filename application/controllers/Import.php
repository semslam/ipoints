<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends Base_Controller
{
	private $connection;
    public function __construct()
	{
	  parent::__construct();
	  $this->load->library("pdolib");
      $this->load->library('Exports');
      $this->load->library('Sms');
      $this->load->library('Untils');
      $this->load->model('Transaction');
      $this->load->model('User');
      $this->load->model('Setting_m');
      $this->load->model('PaymentPurchase');
      $this->load->model('Imports');
      $this->load->model('UserBalance');
	  $this->load->library('csvimport');
	  $this->load->library('utilities/Subscription');
	  $this->load->library('utilities/ExcelFactory');
	  //$this->connection = new AMQPStreamConnection('139.59.197.222', 5672, 'guest', '!!universal_queue!!');
	  log_message("INFO", "Connected successfully to Queue");
	}

	const FAILED = 'Failed';
	const SUCCESSFUL = 'Successful';

    public function index(){
        
          $this->data['title'] = 'Import And Update Uncomplited KYC';
		$this->data['subview'] = 'imports/import_users';
		$this->load->view('components/main', $this->data);

    }

    function load_data()
	{
		$result = $this->csv_import_model->select();
		$output = '
		 <h3 align="center">Imported User Details from CSV File</h3>
        <div class="table-responsive">
        	<table class="table table-bordered table-striped">
        		<tr>
        			<th>Sr. No</th>
        			<th>First Name</th>
        			<th>Last Name</th>
        			<th>Phone</th>
        			<th>Email Address</th>
        		</tr>
		';
		$count = 0;
		if($result->num_rows() > 0)
		{
			foreach($result->result() as $row)
			{
				$count = $count + 1;
				$output .= '
				<tr>
					<td>'.$count.'</td>
					<td>'.$row->first_name.'</td>
					<td>'.$row->last_name.'</td>
					<td>'.$row->phone.'</td>
					<td>'.$row->email.'</td>
				</tr>
				';
			}
		}
		else
		{
			$output .= '
			<tr>
	    		<td colspan="5" align="center">Data not Available</td>
	    	</tr>
			';
		}
		$output .= '</table></div>';
		echo $output;
	}

	function importCsv()
	{		
		header('Content-Type: application/json');
		$result = 0;
		try{
			if(empty($_FILES['file']["tmp_name"])){
				exit(json_encode(['value'=>'File can not be empty OR Excel file size exceeds 8MB','results'=> $result]));
			}else if (($_FILES["file"]["size"] > 8000000)) {
				//2000000
				exit(json_encode(['value'=>'Excel file size exceeds 8MB','results'=> $result]));
			}
			
			//$file_data = $this->csvimport->get_array($_FILES['file']["tmp_name"]);
			$file_data = ExcelFactory::readExcel($_FILES['file']["tmp_name"]);
		foreach($file_data as $row)
		{
			// The next_of_kin phone number can accept two or more numbers the system should format each number and save as an array.
			$data[] = array(
				'mobile_number'	=> $this->sms->cleanPhoneNumber(($this->sms->validatePhoneNumber($row["mobile_number"]))? $row["mobile_number"] :'' ),
				'name'	=>	$row["name"],
        		'gender'=>	$row["gender"],
        		'birth_date'=>	$row["birth_date"],
        		'address' =>	$row["address"],
        		'next_of_kin'=>	$row["next_of_kin"],
        		'next_of_kin_phone'=> $this->sms->cleanPhoneNumber(($this->sms->validatePhoneNumber($row["next_of_kin_phone"]))? $row["next_of_kin_phone"] :'' ),
        		'updated_at'=>	date('Y-m-d H:i:s'),
			);
		}
		$result = $this->db->update_batch('users',$data, 'mobile_number');
		
        echo json_encode(['value'=>'success','results'=> $result]);
	   }catch(Exception $e){
		echo json_encode(['value'=> $e->getMessage(),'results'=> $result]);
		}
		
        
	}

	public function import_elig_subscribers(){
		$this->data['headStyles'] = [
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
		 ];
		 $this->data['footerScripts'] = [
			 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			 BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
			 BACKOFFICE_HTML_PATH .'/js/fundamental.js'
		 ];
        
		$this->data['title'] = 'Import Eligible Subscriber Product';
	  	$this->data['subview'] = 'imports/import_subscriber_lsit';
	  	$this->load->view('components/main', $this->data);

  }


  function eligSubscriberImportCsv()
	{
		header('Content-Type: application/json');

		$data['batch_id'] = $this->input->post('batch_id');
		$data['amount'] = $this->input->post('amount');
		$data['product'] = $this->input->post('product');
		$data['start_date'] = $this->input->post('start_date');
		$data['end_date'] = $this->input->post('end_date');
		$rules = [
			[
				'field' => 'batch_id',
				'label' => 'Batch Id',
				'rules' => 'trim|required'
			],[
				'field' => 'amount',
				'label' => 'Amount',
				'rules' => 'trim|required'
			],[
				'field' => 'product',
				'label' => 'Product Type',
				'rules' => 'trim|required'
			],[
				'field' => 'start_date',
				'label' => 'Start Date',
				'rules' => 'trim|required'
			],[
				'field' => 'end_date',
				'label' => 'End Date',
				'rules' => 'trim|required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$result = 0;
			$subscribers = null;
		try{

			$success =[];
			$failed = [];
			if(empty($_FILES['file'])){
				exit(json_encode(['value'=>'File can not be empty','results'=> $result, 'subscribers'=> []]));
			}

			if(strtotime($data['end_date']) >= strtotime(date("Y/m/d"))){
				exit(json_encode(['value'=>'The product expiring date cannot be greater than the current date.','results'=> $result, 'subscribers'=> []]));
			}
			
			
			$file_data = ExcelFactory::readExcel($_FILES['file']["tmp_name"]);
			$this->load->library("pdolib");
			$db = $this->pdolib->getPDO();

			$product_commission_wallet = Wallet::findByProductId($data['product']);
			$product_income = Wallet::getForIncome();
			$system_user = User::systemAccount();
		foreach($file_data as $row)
		{
				$info = UserBalance::getSubscriberInfo($db,['mobile_number'=>$this->sms->cleanPhoneNumber(($this->sms->validatePhoneNumber($row["mobile_number"]))? $row["mobile_number"] :'' ),'product'=>$data['product'],'amount'=>$data['amount']]);
				if(is_array($info)){
					$success[] = array(
						'user_id'	=> $info['id'],
						'batch_id'	=> $data['batch_id'],
						'mobile_number'	=> $info['mobile_number'],
						'balance'	=> $info['balance'],
						'amount'=> $data['amount'],
						'wallet_id'=> $info['wallet_id'],
						'product'=> $data['product'],
						'income_wallet'=> $product_income->id,
						'cover_period'=> $info['cover_period'],
						'start_date'=>$data['start_date'],
						'end_date'=>$data['end_date'],
						'commission'=>$info['commission_percent'],
						'provider'=>$info['provider_id'],
						'product_commission_wallet'=>$product_commission_wallet->product_id,
						'system_id'=>$system_user->id
					);
				}else{
					$failed[] = array(
						'mobile_number'	=> $row["mobile_number"],
						'status'	=> SELF::FAILED,
					);
				}
			
		}
		$subscribers = SELF::process_elig_subscriber($success,$failed);
		
        echo json_encode(['value'=>'success','results'=> $result,'subscribers'=> $subscribers]);
	   }catch(Exception $e){
		echo json_encode(['value'=> $e->getMessage(),'results'=> $result,'subscribers'=> []]);
		}
		}	
        
	}


	private static function process_elig_subscriber($success,$failed){
		
		//1, select user information and the wallet eg user_id, balance, wallet_id, wallet_name and mobile_number
		//2, get the list of product percent charges
		//3, 	
		
		// the actor and wallet invorve
		// user number
		// product percentage wallet
		// product wallet
		// annual wallet
		$successful = array();
		foreach($success as $process){
			
				$is_product_active = ServicesLog::findActive($process['user_id'],$process ['product']);
				//after commission check if the user still eligible for the product
				$charge = false;
				if(!is_null($is_product_active)){
					//the user services product is active
					$failed[] = array(
						'mobile_number'=>$process['mobile_number'],
						'status'=>SELF::FAILED
					);
				}else{
					// //var_dump('<pre>',$is_product_active);
					// $charges_proccess = Transaction::charges_proccess($process['amount'],$process['commission']);
					// $current_balance =  $process['balance'] - $charges_proccess['userBalance'];
					// if($current_balance >= $process['amount']){
					// 	// balance = 3600 - 
					// 	//$charges_proccess = Transaction::charges_proccess($process['amount'],$process['commission']);
					// 	// user_id, user_wallet_id, commission charges, admin_id, commission_wallet_id            
					// 	$process = Transaction::transfer(
					// 		$process['user_id'], 
					// 		$process['wallet_id'], 
					// 		$process['system_id'], 
					// 		$process['product_commission_wallet'], 
					// 		$charges_proccess['adminPercent'], 
					// 		TRUE 
					// 	);
					
					// 	$info = ($process)? 'Transaction Successful' : 'Transaction Faild';
					// 	log_message('info',$info);
					// 	$charge = ($process)? true : false;
					// }
					
					// if($charge){
						// $re = Subscription::manualProductSubscription(
						// $process['mobile_number'],// subscriber number
						// $process['user_id'],// subscriber id
						// $process['batch_id'],// subscriber id
						// $process['amount'], // subscription amount
						// $process['wallet_id'],// subscriber wallet id
						// $process['product'],// product id
						// $process['cover_period'],// cover period of the product
						// $process['provider'],// the product provider wallet
						// $process['product_commission_wallet'],// wallet which the commission is going to
						// $process['start_date'],// start date of the product
						// $process['end_date'],// end date of the product
						// FALSE);
		
						$re = Subscription::manualProductSubscription($process);
					if($re){	
						//annual fee here
						//var_dump($re." Process Complete");
						log_message("INFO",$re." Process Complete");
						$successful[] = array(
							'mobile_number'=>$process['mobile_number'],
							'status'=>SELF::SUCCESSFUL
						);
						$process=  Transaction::paySubscription($process['user_id'],$process['wallet_id'],TRUE);
						log_message("INFO", ($process)? 'Annual Subscription Successful' : 'Annual Subscription Faild');
					}else{
						$failed[] = array(
							'mobile_number'=>$process['mobile_number'],
							'status'=>SELF::FAILED
						);
						log_message("INFO", $product_commission." Process faild");
					}
				}
			}
			return $result = array_merge($successful,$failed);
		// take the product percetage charge 
		// tranfer from the subscriber wallet to product commission wallet
		// transfer from the subscriber wallet to the wallet dedicate for the product
		// subscribe the subscribers in a service_log
		// transfer from the subscriber wallet to annual fee wallet if he/she have to 70 ipoints let (optional)

	}


	public function bulk_ipoint_gifting(){
		$this->data['headStyles'] = [
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			BACKOFFICE_HTML_PATH .'/css/bootstrap-datetimepicker.min.css',
		 ];
		 $this->data['footerScripts'] = [
			 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js',
			 BACKOFFICE_HTML_PATH .'/js/bootstrap-datetimepicker.min.js',
			 BACKOFFICE_HTML_PATH .'/js/fundamental.js'
		 ];
        
		$this->data['title'] = 'Bulk Ipoint Gifting';
	  	$this->data['subview'] = 'imports/bulk_ipoint_gifting';
	  	$this->load->view('components/main', $this->data);

  }

  	public	function bullk_gifting_process()
	{
		$channel = $this->connection->channel();
		header('Content-Type: application/json');

		$data['wallet']  = $this->input->post('wallet');
		$data['client_id'] = $this->session->userdata('active_user')->id;
		
		$rules = [
			[
				'field' => 'wallet',
				'label' => 'Wallet',
				'rules' => 'trim|required'
			]
		];
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run()) {
			$result = 0;
		try{
			if(empty($_FILES['file']["tmp_name"])){
				exit(json_encode(['value'=>'The file can not be empty OR Excel file is too large to be processed','results'=> $result]));
			}else if (($_FILES["file"]["size"] > 8000000)) {
				//2000000 
				exit(json_encode(['value'=>'Excel file is too large to be processed','results'=> $result]));
			}
			$file_data = ExcelFactory::readExcel($_FILES['file']["tmp_name"]);
			if(empty($file_data)){
                throw new Exception('The system can not process empty record');
            }
			$count = count($file_data);
			$data['request_id'] = Imports::requestIdGenerator();
			
			$result = Imports::loadExcel($file_data ,$data);
			$channel->queue_declare('ref_queue', false, true, false, false);
			log_message('INFO',"The uploaded file has ". $count . " records and the request_id is ". $data['request_id']);
			// $message_for_queue = array("request_id"=>$data['request_id'],"num_records"=>$count);
			// $msg = new AMQPMessage(json_encode($message_for_queue), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
			// $channel->basic_publish($msg, '', 'ref_queue');
			// log_message('INFO', "Message ". json_encode($message_for_queue) . " was successfully published to queue ref_queue.");
        echo json_encode(['value'=>'success','results'=> $count]);
	   }catch(Throwable $e){
		echo json_encode(['value'=> $e->getMessage(),'results'=> $result]);
		}
	}	       
	}
}