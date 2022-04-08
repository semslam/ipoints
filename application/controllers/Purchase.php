<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller 
{
	const LOGGED_IN = 'online';
	const LOGGED_OUT = 'offline';
	const DEFAULT_MAIL_DOMAIN = 'ipoints.ng';

	private $user;
	private $status;
	private static $PREF = [
		self::LOGGED_IN => [
			'view' => 'components/main',
			'sub_view' => 'purchase/online',
		],
		self::LOGGED_OUT => [
			'view' => 'components/layout',
			'sub_view' => 'purchase/offline'
		]
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('paymentProcessors/Flutterwave');
		$this->load->model('Product');
		$this->load->model('User');
		$this->load->model('PaymentPurchase');
		$this->load->model('User_m');
		$this->load->model('Wallet');
		$this->load->model('Uici_levies');
		$this->load->model('MessageQueue');
		$this->load->model('Transaction');
		// validate whether user is logged in or not
		$this->status = $this->hasSession() ? self::LOGGED_IN : self::LOGGED_OUT;
		$this->data = [];
		if ($this->status === self::LOGGED_IN) {
			$this->initLayoutVars();
		}
	}

	protected function initLayoutVars()
	{
		$this->load->model('menu_m');
		$this->load->model('group_m');
		$this->load->library('MenuHandler');

		// Get Authenticated User
		$this->data['active_user'] = $this->session->userdata('active_user');
		$this->data['active_user_group'] = $this->group_m->get_group($this->data['active_user']->group_id);
		$this->data['list_menu'] = $this->menu_m->get_menu($this->data['active_user']->group_id);
		$this->data['active_menu'] = MenuHandler::active_menu($this->data['list_menu']);
		$this->data['menu_style'] = 'default';
	}

	/**
	 * Product Form
	 *
	 * @access 	public
	 * @param 	
	 * @return 	view
	 */
	public function product()
	{
		$this->data['title'] = 'iPoints';
		$this->data['subview'] = self::$PREF[$this->status]['sub_view'];
		$this->data['wallets'] = $this->wallets();
		$this->data['products'] = $this->products();
		$this->load->view(self::$PREF[$this->status]['view'], $this->data);
	}

	public function getPaymentFingerprint()
	{
		header('Content-Type: application/json');

		try {
			$reqData = $this->input->get();
			if (! $this->validateUser($reqData)) {
				http_response_code(400);
				exit(json_encode([
					'status' => 'failed',
					'message' => 'Failed to validate user',
				]));
			}
			$this->form_validation->set_data($reqData);
			$this->form_validation->set_rules('product', 'Product', ['numeric']);
			$this->form_validation->set_rules('wallet', 'Wallet', ['numeric']);
			$this->form_validation->set_rules('topup_amount', 'Top-Up Value', ['numeric']);
			$this->form_validation->set_rules('prod_tenure', 'Product Tenure', ['numeric']);
			
			if (! $this->form_validation->run() 
				|| (empty($reqData['product']) && empty($reqData['wallet']))
				|| ((empty($reqData['topup_amount']) && empty($reqData['wallet']))
				&& (empty($reqData['prod_tenure']) && empty($reqData['product'])))
			) {
				
				http_response_code(400);
				exit(json_encode([
					'status' => 'failed',
					'message' => strip_tags(validation_errors()) ?: 'Required fields are invalid or missing',
				]));
			}
			// set product
			$productId = ($reqData['product']??'') ?: null;
			$mWallet = ($reqData['wallet']??'') ?: null;
			// @TODO change magic number 1 to input from request
			$tenure = $reqData['prod_tenure']??1;

			// check for top-up wallet request
			if (!empty($mWallet)) {
				$wallet = Wallet::findOne(['id' => $mWallet]);
				$qty = $reqData['topup_amount'];
				$description = sprintf('Top-Up %s Wallet',$wallet->name);
			}
			elseif (($wallet = Wallet::findOne(['product_id' => $productId]))) {
				$product = Product::findOne(['id' => $productId]);
				$qty = $product->price * $reqData['prod_tenure'];
				$description = sprintf('Product purchase for %s', $product->product_name);
			}
			else {
				log_message('error', "Could not resolve wallet for productId ==> $productId, walletId ==> $mWallet");
				throw new Exception('Oops! Something went wrong please try again later');
			}
			log_message('debug', print_r($wallet, true));
			$userName = $this->user->email ?: $this->user->mobile_number;
			$email = $this->user->email ?: $this->user->mobile_number.'@'.self::DEFAULT_MAIL_DOMAIN;
			$info = [
				'user_name' => $userName,
				'email' => $email,
				'user_id' => $this->user->id,
				'description' => $description,
				'product_id' => $productId,	// Do not take product id from wallet to signify topup
				'wallet_id' => $wallet->id,
				'tenure' => $tenure,
				'quantity' => $qty,
				'amount' => $qty * $this->Uici_levies->getUiciLevieValue(IPOINT_UNIT_PRICE_KEY)->value
			];

			exit(json_encode([
				'status' => 'succeeded',
				'data' => [
					'payload' => $this->flutterwave->generatePaymentFingerprint($info),
					'inSession' => $this->hasSession() ? true : false,
					'userName' => $userName,
					'doInline' => strcasecmp(ENVIRONMENT,'production') !==0
				]
			]));
		} catch (Exception $ex) {
			log_message('error', $ex->getMessage());
			http_response_code(500);
			exit(json_encode([
				'status' => 'failed',
				'message' => 'An error occurred. Please try again later.'
			]));
		}
	}

	public function processPayment()
	{
		header('Content-Type: application/json');
		$reqData = $this->security->xss_clean($this->input->post());
		log_message(
			'info',
			'Flutterwave notify payment request ===> '
			.print_r([$reqData, $this->input->raw_input_stream], true));
		$this->form_validation->set_data($reqData);
		$this->form_validation->set_rules('txRef', 'Transaction Ref', ['required']);
		$this->form_validation->set_rules('flwRef', 'FLW Ref', ['required']);
		$this->form_validation->set_rules('amount', 'Amount', ['required', 'numeric']);
		$this->form_validation->set_rules('currency', 'Currency', ['required']);

		if (! $this->form_validation->run()) {
			http_response_code(400);
			exit(json_encode([
				'status' => 'failed',
				'message' => strip_tags(validation_errors()),
			]));
		}

		try {
			$paymentRef = $reqData['txRef'];
			$flwRef = $reqData['flwRef'];
			$amount = $reqData['amount'];
			$currency = $reqData['currency'];
			
			$purchase = $this->flutterwave->verifyPayment($flwRef, $amount, $currency);
			if (!$purchase) {
				http_response_code(200);
				exit(json_encode([
					'status' => 'failed',
					'message' => 'Payment waiting verification'
				]));
			}
			elseif ($purchase instanceof PaymentPurchase) {
				
				$action = '';$done= false;
				try {
					$purchase->isNew = false;	// An update requires that it's false
					$transferred = true;
					$system_user = User::getSystemUser();
					$wallet_isavings = Wallet::walletById($purchase->wallet_id);
					$processResult = true;$quantity =0;$processed = true;
					if($wallet_isavings->name == I_SAVINGS){
						$processResult = EspiTransaction::transferOrDepositIsavingsOnEspi('deposit',$system_user->id,$purchase->user_id,$purchase->quantity,$purchase->payment_ref,$purchase->description,FALSE);
						//$quantity = EspiTransaction::calculatePercentage($purchase->quantity)['iSavingsBalance'];
					}
					log_message('INFO', 'Pushase Value====>'.$purchase->quantity);
					log_message('INFO', 'Pushase Result====>'.$processResult);
					$this->db->trans_start();
							log_message('INFO', 'Credit Wallet====>'.$wallet_isavings->name);
							if($processResult){
								$processed = Transaction::credit(
								$purchase->user_id, 
								//($wallet_isavings->name == I_SAVINGS)? $quantity : $purchase->quantity, 
								$purchase->quantity, 
								$purchase->wallet_id, 
								$purchase->payment_ref, 
								$purchase->description, 
								$system_user->id,
								false);

								//ipoints sales commission charges
								$system_commission = Wallet::walletByName(I_SALES_COMMISSION);
								$admin_result = Transaction::credit(
								$system_user->id, 
								($purchase->amount - $purchase->quantity), 
								$system_commission->id, 
								$purchase->payment_ref, 
								`Top-UP {$wallet_isavings->name} wallet, iPoint sales commission Charge, Payment Reference:: {$purchase->payment_ref}`, 
								$purchase->user_id, 
								FALSE);
								$processed =($processed && $admin_result)? true: false;
							}
						if ($processed && !empty($purchase->product_id)) {
							$processed = Transaction::payForProduct(
								$purchase->user_id,
								$purchase->product_id,
								$purchase->wallet_id
							);
						}
					if ($processed && $processResult) {
						$purchase->processing_status = PaymentPurchase::STATUS_PAYMENT_PROCESSED;
						$done = $purchase->save();
					}else if(!$processResult){
						$purchase->description." failed";
						$purchase->processing_status = PaymentPurchase::STATUS_PAYMENT_UNPROCESSED;
						$done = $purchase->save();
					}
					if (!$done || !$processed || $this->db->trans_status() === FALSE) {
						throw new Exception('Payment purchase processing failed');
					}
					$this->db->trans_complete();
					$action = ONLINE_TOPUP_WALLET_SUCCESS;
				} catch (Exception $e) {
					$this->db->trans_rollback();
					$action = ONLINE_TOPUP_WALLET_FAILED;
					log_message('error', $e->getMessage().$e->getTraceAsString());
				} finally {
					http_response_code(200);
					echo json_encode([
						'status' => 'succeeded',
						'message' => 'Payment verified'
					]);
				}
				$user  =$this->User_m->get_user($purchase->user_id);
				if($purchase->quantity >= 50 || !empty($user->email)){
					$wallet = Wallet::walletById($purchase->wallet_id);
					$contact = (!empty($user->mobile_number))? $user->mobile_number : $user->email;
					$type = (!empty($user->email))?  MESSAGE_EMAIL : MESSAGE_SMS;
					$variable = array(
					$purchase->quantity, 
					$wallet->name, 
					$purchase->payment_ref);
					MessageQueue::messageCommit($contact, $type, $action, $variable);
				}
				
			}
			else {
				throw new Exception('Payment verification failed. Try again!');
			}
		} catch (Exception $e) {
			http_response_code(400);
			exit(json_encode([
				'status' => 'failed',
				'message' => $e->getMessage()
			]));
		}
	}

	/**
	 * check if this is offline and either mail 
	 * or phone is set in request
	 */
	protected function validateUser($req=[])
	{
		if (! $this->hasSession()) {
			$emailorphone = $req['emailorphone']??'';
			if (empty($emailorphone)) {
				return false;
			}
			
			$where = [ 'mobile_number' => $this->sms->cleanPhoneNumber($emailorphone)];
			$or = [ 'email' => $emailorphone ];
			$user = User::find($where)->or_where($or)->one();
			return ! empty($user) ? ($this->user = $user) : false;
		}
		return $this->user = $this->session->active_user;
	}
	
	protected function hasSession()
	{
		return $this->session->active_user ?: false;
	}

	protected function wallets()
	{
		$this->load->model('Wallet');
		return Wallet::findAll([ 'can_topup' => true ]);
	}

	protected function products()
	{
		$this->load->model('Product');
		return $this->db
			->select('p.*')
			->from('products p')
			->join('wallets w', 'p.id = w.product_id')
			->where('p.base_product_yn',1)
			->get()
			->result_object('Product');
		// return Product::findAll();
	}

}
