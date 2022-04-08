<?php

class Subscription
{
	// This function process anuual subscription fee in bash
	public static function massSubscribe($benchmark)
	{
    $limit = 1000;
		$processed = 0; 
		$ci =& get_instance();
    $ci->load->model('UserBalance');
		$ci->load->model('Transaction');

		$wallet = Wallet::walletByName(I_SAVINGS);
		// find the count of user_balance that grater than equal to the banchmark
    $query = UserBalance::find(['balance >='=>$benchmark])
      ->join('users','user_balance.user_id = users.id')
      ->where(['users.group_id'=>4]);               
    $count = $query->count();
		echo "Found {$count} records!".PHP_EOL;
		log_message('INFO',"Found {$count} records!");
		$successful = 0;
		$loops = ceil($count/$limit);
		echo "Looping numbers {$loops} !".PHP_EOL;
    while($loops >= 1){
      echo "starting another batch...".PHP_EOL;
      log_message('INFO','starting another batch... '.$loops);
      //pull out user_balance where benchmark is grater than or equal to with a limitation 1000 record at once
	  // this is worng it has to fetch that have not subscribe for annual fee or it expaired
	  $balances = UserBalance::find(['balance >='=>$benchmark])
        ->join('users','user_balance.user_id = users.id')
        ->where(['users.group_id'=>4, 'user_balance.wallet_id !='=>$wallet->id])
        ->limit($limit)
        ->asArray()
		->all();
		
      foreach($balances as $balance){
        echo "Processing subscription for user id {$balance['user_id']} with wallet {$balance['wallet_id']} and has balance {$balance['balance']}".PHP_EOL;
        log_message('INFO',"Processing subscription for user id {$balance['user_id']} with wallet {$balance['wallet_id']} and has balance {$balance['balance']}");
        try {
          $sub = Transaction::paySubscription($balance['user_id'],$balance['wallet_id'],true);
          if ($sub){
            echo "Subscription for user id ".$balance['user_id']." successfully paid!".PHP_EOL;
            log_message('INFO',"Subscription for user id ".$balance['user_id']." successfully paid!");
            $successful += 1;
          }
          else {
            //New subscription cannot be created while one is active or not expired
            echo "User id ".$balance['user_id']." New subscription cannot be created while one is active or not expired".PHP_EOL;
            log_message('INFO',"User id ".$balance['user_id']." New subscription cannot be created while one is active or not expired");            
          }
        } catch(Exception $ex){
          echo $ex->getMessage().PHP_EOL;
          log_message('INFO',$ex->getMessage());
        }
      }
      $loops --;
    }
    //var_dump($balances);
    echo "Successful: {$successful}".PHP_EOL;
    log_message('INFO',"Successful: {$successful}");
	}
	
	public static function massProductSubscribeAndExportList($productId, $start, $end)
	{
		$ci =& get_instance();
		$ci->load->model('Product');
		$ci->load->library('utilities/ExcelFactory');
		$product = Product::findByPK($productId);
		$reportName = 'Billing Report - '.$start;
		if (! $product || !$product->provider_id) {
			throw new Exception('Invalid product! Production subscription halted');
		}
		$reportName = $product->product_name . ' '.$reportName;
		$header = [
			'serial' => 'SN',
			'name' => 'Full Name', 
			'birth_date' => 'Date of Birth', 
			'age' => 'Age',
			'mobile_number' => 'Mobile Phone', 
			'gender' => 'Gender', 
			'address' => 'Address',
			'benefits' => 'Life Insurance Benefit',
			'pstart' => 'Cover Start Date',
			'pend' => 'Cover End Date',
			'next_of_kin' => 'Life Benefit Beneficiary Name',
			'next_of_kin_phone' => 'Life Benefit Beneficiary Mobile Phone'
		];
		$sql = self::getSubscribeableUsersSQL($productId, $start, $end, $hasValidKycs=true);
		if ($sql === false) { 
			throw new Exception('Invalid parameters'); 
		}
		
		$ci->db->trans_begin();
		try {
			$batchId = implode("_", [$productId, microtime(true), getmypid()]);
			// lock and deduct subscribers' wallets
			$query = "INSERT INTO user_balance (user_id, wallet_id, balance, updated, pending_annual_charge)
				SELECT user_id, wallet_id, (0 - ABS(price+IF(has_annual_sub, 0, annual_fee))), NOW(), IF(has_annual_sub, 0, annual_fee) 
				FROM ($sql FOR UPDATE) sq
				ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance), batch_id = '$batchId', pending_annual_charge = pending_annual_charge + VALUES(pending_annual_charge)";
			$ci->db->query($query);

			// get subscribers affected
			$userBalUpdated = $ci->db->query("SELECT count(*) cnt FROM user_balance WHERE batch_id = '$batchId'")->first_row()->cnt;
			
			$sql = self::getSubscribeableUsersSQL($productId, $start, $end, $hasValidKycs=true, $batchId);
			log_message(
        'debug',
        'Locked/debited Subscribeable subscribers query ==> ' . $sql
      );
			if ($sql === false) { 
				throw new Exception('Invalid parameters'); 
			}

			// insert into service_products
			$query = "INSERT INTO services_log (user_id, product_id, value, cover_period, purchase_date, expiring_date)
				SELECT user_id, product_id, price, 1 cover_period, pstart, pend 
				FROM ($sql) sq";
			$ci->db->query($query);
			$productsTotal = $ci->db->affected_rows();

			// create transaction credit trails for underwriter
			$trxnTypeCredit = Transaction::CREDIT;
			$query = "INSERT INTO transactions (`reference`, `type`, `value`, user_id, wallet_id, `description`, `sender_id`, `receiver_id`, current_balance, created_at)
				SELECT '$batchId', '$trxnTypeCredit', price, provider_id, income_wallet, 'Product purchase credit', user_id, provider_id, @bal_accm:=@bal_accm + price, NOW() 
				FROM (select ABS(price) price, provider_id, income_wallet, user_id
					from ($sql) sq 
					left join (
						select user_id uid, wallet_id wid, @bal_accm:=IFNULL(balance, 0)
						from user_balance 
					) bb 
					on bb.uid = sq.provider_id and bb.wid = sq.income_wallet
				) par";
			log_message('debug', 'Underwriter Transaction Log CREDIT query ==> ' . $query);
			$ci->db->query($query);

			// create transaction debit trails for subscriber
			$trxnTypeDebit = Transaction::DEBIT;
			$query = "INSERT INTO transactions (`reference`, `type`, `value`, user_id, wallet_id, `description`, `sender_id`, `receiver_id`, current_balance, created_at)
				SELECT '$batchId', '$trxnTypeDebit', ABS(price), user_id, wallet_id, 'Product purchase debit', user_id, provider_id
        , user_balance + sq.pac, NOW() 
				FROM ($sql) sq ";
			log_message('debug', 'Subscriber Transaction Log DEBIT query ==> ' . $query);
			$ci->db->query($query);

			// credit underwriter
			$query = "INSERT INTO user_balance (user_id, wallet_id, balance, updated)
				SELECT provider_id, income_wallet, sum_price, NOW() 
				FROM (SELECT provider_id, income_wallet, SUM(ABS(price)) sum_price FROM ($sql) sq2 
				GROUP BY provider_id, income_wallet) sq
				ON DUPLICATE KEY UPDATE balance = balance + VALUES(balance), batch_id = '$batchId'";
			log_message('debug', 'Underwriter Income-Wallet Credit query ==> ' . $query);
			$ci->db->query($query);

			// check queries execution
			if ($ci->db->trans_status() === false) { 
				throw new Exception('Transantion failed'); 
			}
			if ($userBalUpdated != $productsTotal) {
				log_message('debug', ['balsUp' => $userBalUpdated, 'prods' => $productsTotal]);
				throw new Exception('Data Inconsistency! Products subscription failed.');
			}
			
			// create excel
			ExcelFactory::createExcel($sql, $bindings=[], $header, $reportName);

			// reset user balances batch_ids locks
			$ci->db->where(['batch_id' => $batchId ])
				->update('user_balance', [ 'batch_id' => null ]);
			// commit
			$ci->db->trans_commit();
			return true;
		} catch (Exception $e) {
			$ci->db->trans_rollback();
			log_message('error', $e->getMessage() . $e->getTraceAsString());
			throw $e;
		}
	}

	public static function getSubscribeableUsersSQL($productId, $start, $end, $hasValidKycs=false, $batchId=null)
	{
		if (! isset($productId, $start, $end)) {
			return false;
		}
		$ci =& get_instance();
    $dbBuilder = $ci->db
      ->select("(@sn:=@sn+1) `serial`, u.name, u.birth_date, u.id user_id
        , p.id   product_id, ub.wallet_id, ub.balance user_balance
        , FLOOR(DATEDIFF(CURRENT_DATE, u.birth_date)/365.25) age, p.provider_id
        , u.mobile_number, u.gender, u.address, 0 benefits, p.price
        , mw.id income_wallet, '$start' AS `pstart`, '$end' AS `pend`
        , u.next_of_kin, u.next_of_kin_phone, ul.value annual_fee
        , ub.pending_annual_charge pac
        , IF(us.end_date > NOW() OR ub.pending_annual_charge > 0, 1, 0) has_annual_sub"
      , false)
      ->from('user_balance ub')
      ->join(
        'wallets w',
        'w.id = ub.wallet_id AND w.product_id IS NOT NULL', 
        'inner'
        )
      ->join(
        'products p',
        'p.id = w.product_id AND p.provider_id IS NOT NULL',
        'inner'
        )
      ->join('users u', 'u.id = ub.user_id AND u.status = 1', 'inner')
      ->join('user_subscriptions us', 'us.user_id = u.id', 'left')
      ->join('wallets mw', "mw.name = '".Wallet::GENERIC_INCOME_WALLET."'", 'inner')
      ->join('uici_levies ul', "ul.name = 'user_annual_subscription'", 'inner')
      ->join('(select @sn:=0) cnt', '1=1', 'inner')
      ->where('p.id', $productId=2)
      ->group_start()
        ->where('(us.is_active AND us.end_date > NOW())')
        ->or_group_start()
          ->where('IF(us.is_active AND us.end_date > NOW(), 0, 1) = 1', null, false)
          ->where('ub.balance > p.price + ul.value', null, false)
          ->or_where('ub.pending_annual_charge > 0')
        ->group_end()
      ->group_end();
		// only valid KYCs
		if ($hasValidKycs) {
      $dbBuilder
        ->where('LENGTH(u.name)>0 AND LENGTH(u.gender)>0 AND LENGTH(u.address)>0')
        ->where('LENGTH(u.next_of_kin)>0 AND LENGTH(u.next_of_kin_phone)>0')
        ->where('LENGTH(u.birth_date)>0');
		}
		// about to lock down balance
		if (empty($batchId)) {
			$dbBuilder
				->where('ub.balance >= p.price AND ub.batch_id IS NULL')
				->where("NOT EXISTS (SELECT * FROM services_log sl
					WHERE (sl.user_id, sl.product_id) = (u.id, p.id) 
					AND DATE(sl.expiring_date) >= '$start')", '', false);
		}
		else {
			$dbBuilder->where('ub.batch_id', $batchId);
		}
		return $dbBuilder->get_compiled_select();
	}


	public static function manualProductSubscription(Array $process = []){	
		//// requaired perameters mobile_number,user_id,value,
		//wallet_id,cover_period,user_charge_id,charge_wallet_id,start_date,end_date
		$ci =& get_instance();
		$ci->load->model('ServicesLog');
		$is_product_active = ServicesLog::findActive($process['user_id'],$process['product']);
		//after commission check if the user still eligible for the product
		$ci->db->trans_begin();
        if(!is_null($is_product_active)){
            //the user services product is active if not null
            return false;
		}
		$charge = false;
		$charges_proccess = Transaction::charges_proccess($process['amount'],$process['commission']);
			$current_balance =  $process['balance'] - $charges_proccess['userBalance'];
				if($current_balance >= $process['amount']){
					
						// balance = 3600 - 
						//$charges_proccess = Transaction::charges_proccess($process['amount'],$process['commission']);
						// user_id, user_wallet_id, commission charges, admin_id, commission_wallet_id            
					$process_ = Transaction::transfer(
						$process['user_id'], 
						$process['wallet_id'], 
						$process['system_id'], 
						$process['product_commission_wallet'], 
						$charges_proccess['adminPercent'],
						'ExpairedProdCommiCharge_'.$process['user_id'].'_'.$process['wallet_id'], 
						FALSE 
					);
					
					$infos = ($process_)? 'Transaction Successful' : 'Transaction Faild';
					log_message('info',$infos);
					$charge = ($process_)? true : false;
				}
		
		// product percentage charge
    	$ci->load->library('Untils');
		$ci->load->model('Transaction');
		if(!$charge){
			$ci->db->trans_rollback();
			return false;
		}
		try{
			$services['value'] = $process['amount'] * $process['cover_period'];
			$processed = Transaction::transfer(
				$process['user_id'], 
				$process['wallet_id'], 
				$process['provider'], 
				$process['income_wallet'], 
				$services['value'], 
				'ExpairedProdSub_'.$process['user_id'].'_'.$process['wallet_id'], 
				 FALSE );
			if($processed){
				$services['user_id'] = $process['user_id']; 
				$services['product_id'] = $process['wallet_id'];
				$start_date;
				$end_date;
				if(empty($process['start_date']) && empty($process['end_date'])){
					   // do some months calculating here
					$start_date = date("Y-m-d");
					$end_date  = date("Y-m-d", strtotime("+".$process['cover_period']." month", strtotime($start_date)));
					$services['cover_period'] = $process['cover_period'];
				}else{
					$start_date = $process['start_date'];
					$end_date = $process['end_date'];
					$year1 = date('Y', strtotime($start_date));
					$year2 = date('Y', strtotime($end_date));
					$month1 = date('m', strtotime($start_date));
					$month2 = date('m', strtotime($end_date));
					$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
					$services['cover_period'] = (!$diff)? 1:$diff;
				}
				if(Untils::daysBetween($start_date,$end_date)<1){
					throw new UserException('The start date can not be less than end date');
				}
				//Check the expiry product date
				
				$is_active = (strtotime(date("Y/m/d h:i:s")) >= strtotime($end_date))? 0 : 1;
				$services['batch_id'] = $process['batch_id'];
				$services['is_active'] = $is_active;
				$services['purchase_date'] = $start_date;
				$services['expiring_date'] = $end_date;
				$sub_response = $ci->db->insert('services_log',$services);
				
				if($sub_response){$ci->db->trans_commit();}else{$ci->db->trans_rollback();}
				return ($sub_response)? true:false;
			}
		}catch(Exception $ex ){
			$ci->db->trans_rollback();
			throw $ex;
		}



		// product services subscription process :: done
		// return ServicesLog::productServicesSubscription(
		// $mobile_number='',
		// $user_id,
		// $value,
		// $wallet_id,
		// $product_id,
		// $cover_period,
		// $charge_wallet_id,
		// $commission_wallet_id,
		// $input_start_date,
		// $input_end_date,
		// $isolated);
		// annula charge process

		
	}
	//usertType= new:old
	//userId
	// ipointValue
	//benchmark
	public static function annualFeeCollector($userType,$userId,$ipointValue,$benchmark=50){
		if($userType == 'new'){
			$result =Transaction::charges_proccess($ipointValue,$benchmark);
			//caculate the met to be take and return the balance
			// insert into user_subscription
			return $balance;
		}else{
			//caculate the met to be take and return the balance
			// upload into user_subscription
		}

	}
}
