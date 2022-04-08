<?php

class IpointTransfer extends MY_Model { 

    public function __construct(){
		parent::__construct();
		$this->load->library('sms');
		//$this->load->library('emailer');
		$this->load->library('Untils');
		$this->load->model('MessageTemplate_m', 'messagetemplate_m');
		$this->load->model('user_m');
		$this->load->model('User');
		$this->load->model('userBalance');
		$this->load->model('product_m');
		$this->load->model('Transaction');
		$this->load->model('MessageQueue');
    }
    
    public static function getTableName(){
        return 'user_balance';
    }
    
    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public static function fetchUserBalancesView($id){
        $transfer =  new static();
        if(!empty($id)){
			$wallets = $transfer->userBalance->get_user_balance($id);
			$data ='';$footer ='';
			$data .='<div class="table-responsive"><table class="table">';
			$data .='<thead>';
			$data .='<tr>';
			$data .='<th>Wallet Name</th>';
			$data .='<th class="text-right">Balance</th>';
			$data .='<th class="text-right">Over-draft Limit</th>';
			$data .='<th class="text-center">Transfer</th>';
			$data .='</tr>';
			$data .='</thead>';
			$footer .='</table></div>';
			if($wallets != null){
				$data .='<tbody>';
			foreach ($wallets as $wallet){

				// if($wallet->product_name == I_SAVINGS){
				// 	continue;
				// }
				
				$data .='<tr>';
				$data .='<td class="nowrap">'.$wallet->product_name.'</td>';
				$data .='<td class="text-center">'.$wallet->balance.'</td>';
				$data .='<td class="text-center">'.(int)$wallet->overdraft_limit.'</td>';
				$data .='<td class="text-center">';
				$data .='<a href="#" title="Click to choose the wallet you are transferring to" class="walletInfo" data-id="'.$wallet->wallet_id.'" ><i class="fa fa-exchange fa-2x blinking"></i></a>';	
				$data .='</td>';
				$data .='</tr>';
			}
				$data .='</tbody>';
				$data .=$footer;
				return $data;
			}else{
				$data .='<tbody>';
				$data .='<tr>';
				$data .='<td COLSPAN=2 class="nowrap">No Record Found</td>';
				$data .='</tr>';
				$data .='</tbody>';
				$data .=$footer;
				return $data;
			}
		}
    }


    public static function fetchTransferForm($wallet_id,$user_id,$email,$phone){
        $transfer =  new static();
		if(!empty($user_id)){
			$contact = (!empty($email))? $email : $phone;
				$info = $transfer->userBalance->get_balance_info($wallet_id,$user_id);
				
					$wallets = $transfer->userBalance->get_wallets();
					$data ='';
					$data .='<div class="table-responsive transfer_form">';
					$data .='<table class="table">';
					$data .='<tbody><tr>Wallet Summary:</tr><tr>';
					$data .='<td class="nowrap">Wallet Name</td>';
					$data .='<td class="nowrap"></td>';
					$data .='<td class="nowrap">Wallet Balance (iPoints)</td>';
					$data .='</tr><tr>';
					$data .='<td class="nowrap">'.$info->wallet_name.'</td>';
					$data .='<td class="nowrap"></td>';
					$data .='<td class="nowrap">'.number_format($info->balance, 2, '.', ',').'</td>';
					$data .='</tr></tbody></table>';
					$data .='<form style="text-align: left;" id="form-transfer">';
					$data .='<div class="form-group col-md-12">';
					$data .='<label for=""> Choose recipients\'s wallets type:</label>';
					$data .='<select class="form-control wallet_id" name="wallet_id">';
					$data .='<option value="">Choose Wallet</option>';
					foreach($wallets as $wallet){
						if($wallet->name == I_POINT){
							continue;
						}
						$data .='<option data-walt="'.$wallet->name.'" value="'.$wallet->id.'">'.$wallet->name.'</option>';
					}
					$data .='</select> <div class="validation-message" data-field="wallet_id"></div></div>';
					$data .='<div class="form-group col-md-12">';
					$data .='<input class="sender_wallet_id" value="'.$info->wallet_id.'"  name="sender_wallet_id" type="hidden">';
					$data .='<label for="">Recipient Phone Or Email</label>';
					$data .='<input class="form-control recipient_contact" placeholder="Enter Recipient email or phone" name="recipient_contact" type="text">';
					$data .='<div class="validation-message" data-field="recipient_contact"></div>';
					$data .='</div><div class="form-group col-md-12">';
					$data .='<label for=""> Number of ipoints to transfer:</label>';
					$data .='<input class="form-control value" placeholder="Enter number of iPoints" name="value" type="text">';
					$data .='<div class="validation-message" data-field="value"></div></div>';
					$data .='<div class="form-group col-md-12">';
					$data .='<button class="btn btn-success" id="transfer_btn" type="button">Transfer</button></div>';
					
					$data .='</form></div>';
					// Transfering details
					$data .='<div class="transfer_detail" style="display: none;">';
					$data.='<div class="table-responsive">';
					$data.='<table class="table"><tbody><tr>';
					$data.='<td class="nowrap">'.$contact.'</td>';
					$data.='<td class="nowrap"><i class="fa fa-long-arrow-right fa-2x" aria-hidden="true"></i></td>';
					$data.='<td class="nowrap recipient_contact"></td></tr>';
					$data.='<tr><td class="nowrap">'.$info->wallet_name.'</td>';
					$data.='<td class="nowrap">TO</td>';
					$data.='<td class="nowrap recipient_wallet"></td>';
					$data.='</tr><tr>';
					$data.='<td class="nowrap transfer_value"></td>';
					$data.='<td class="nowrap"><i class="fa fa-exchange fa-2x"></td>';
					$data.='<td class="nowrap transfer_value"></td></tr><tr>';
					$data.='<td class="nowrap"><div class="form-group">';
					$data.='<button class="btn btn-dangar" id="transfer_cancle" type="button">Cancel</button></div>';
					$data.='</td><td></td><td class="nowrap">';
					$data.='<div class="form-group">';
					$data.='<button class="btn btn-success" id="transfer_process" type="button">Continue</button></div></td></tr>';
					$data.='</tbody></table></div>';
					$data .='</div>';
					return $data;
				//}
			
		}else{
			$data ='';
			$data .='<div class="form-group">';
				$data .='<h1>Session Expired</h1>';
			$data .='</div>';
			return $data;
		}
    }

	public static function resolveRecipientIdentity($recipient) {
		return $recipient->name 
			?: $recipient->business_name
			?: $recipient->email
			?: $recipient->mobile_number;
	}

    public static function transferProcess($sender_id,
    $email,$phone,$name,$business_name,$sender_group,
    $sender_walletId,$recipient_wallet,$transfer_value,
    $recipient_username ){
        $transfer =  new static();
        $data =[];
			$recipient = null;$password="";
			if(SELF::usernameVerification($recipient_username)){
				$contact = $transfer->sms->cleanPhoneNumber($recipient_username);
				$data['mobile_number'] = $contact;
				$user = User::findOne(['mobile_number'=>$data['mobile_number']]);
				$defaultPass = $transfer->untils->autoGeneratorPwd(8);
				$defaultPassHash = password_hash($defaultPass, PASSWORD_DEFAULT);
				$password = (is_null($user))? $defaultPass:"";
				$recipient = $transfer->untils->auto_create_user($data['mobile_number'],$defaultPassHash);
				if(is_null($recipient)){
                    throw new Exception('Destination user account does not exist.');
				}
			}else{
				$recipient = $transfer->user_m->attempt('email',$recipient_username);
				if(is_null($recipient)){
                    throw new Exception('Sorry, an account cannot be auto created for email on transfer, The recipient needs to create an account on the platform before processing the transfer.');
				}
			}
			// todo
			// subscriber can not transfer from any of his choosing wallet to ipoint wallet
			$recipientWallet = Wallet::walletByName(I_POINT);
			//$sender_group = User::fetchUserGroup($sender_id);

			if($sender_group == SUBSCRIBER && $recipientWallet->id == $recipient_wallet){
				throw new Exception('Destination user can not receive iPoint transfer');
			}
			$recipient_group = User::fetchByGroupId($recipient->group_id);
			if($sender_group == MERCHANT && $recipient_group->group_name == SUBSCRIBER && $recipientWallet->id == $recipient_wallet){
				throw new Exception('Destination user can not receive iPoint transfer');
			}

			if($sender_group == MERCHANT && $recipient_group->group_name == MERCHANT && $recipientWallet->id != $recipient_wallet){
				throw new Exception('Destination user can only receive iPoint transfer');
			}
			$reference =Transaction::getUniqueReference('Transfer_',$sender_id,$sender_walletId, $recipient->id, $recipient_wallet, $transfer_value);
			$transferResult = true;$wallet_isavings = Wallet::walletById($recipient_wallet);$isavings = null;
			// apply transaction to the proccess 
				
				log_message('info',$wallet_isavings->name.' <===== WALLET NAME =====> '.I_SAVINGS);
				if($wallet_isavings->name == I_SAVINGS){
					$description = 'User '.$sender_id.' Transfer from '.$wallet_isavings->name.' wallet to User '. $recipient->id.' iSavings wallet';
					$transactionType =($sender_walletId == $recipient_wallet)? 'transfer': 'deposit';
					$transferResult = EspiTransaction::transferOrDepositIsavingsOnEspi($transactionType,$sender_id,$recipient->id,$transfer_value,$reference,$description,FALSE);
				}

				log_message('info','Transfer Result ========____'.$transferResult);
				SELF::startDbTransaction();
			
				if($transferResult){
					$debitResult =Transaction::debit($sender_id, 
					$transfer_value,
					 $sender_walletId, $reference, $description = '', $recipient->id,FALSE);
					$creditResult =Transaction::credit($recipient->id, 
					//($wallet_isavings->name == I_SAVINGS)? EspiTransaction::calculatePercentage($transfer_value)['iSavingsBalance']: $transfer_value, 
					$transfer_value, 
					$recipient_wallet, $reference, $description = '', $sender_id,FALSE);
					$transferResult = ($debitResult && $creditResult)? true : false;
				}
				
			if($transferResult){
				SELF::endDbTransaction(true);
				$sender_contact = (empty($email))? $phone : $email;
				$sender_name = (empty($name))? ((empty($business_name))? $sender_contact : $business_name) : $name;
				$recipient_contact = (empty($recipient->email))? $recipient->mobile_number : $recipient->email;
				$recipient_name = self::resolveRecipientIdentity($recipient);
				if(!empty($sender_id) && ($transfer_value >= 50 || !empty($email))){
					//sender message
					$wallet = Wallet::walletById($sender_walletId);
					$contact = (!empty($email))?  $email : $phone;
					$type = (!empty($email))?  MESSAGE_EMAIL : MESSAGE_SMS;
					
					$variable = array($sender_name,$transfer_value,$wallet->name,$recipient_name);
					MessageQueue::messageCommit($contact, $type, TRANSFER_DEBIT, $variable);
				}

				if(($transfer_value >= 50 || !empty($recipient->email)) && empty($password)){
					//recipient message
					$wallet = Wallet::walletById($recipient_wallet);
					$contact = (!empty($recipient->email))?  $recipient->email : $recipient->mobile_number;
					$type = (!empty($recipient->email))?  MESSAGE_EMAIL : MESSAGE_SMS;
					$variable = array($recipient_name,$transfer_value,$wallet->name,$sender_name);
					MessageQueue::messageCommit($contact, $type, TRANSFER_CREDIT, $variable);
					return TRUE;
				}else if($transfer_value >= 50 && !empty($password)){
					$variable = array($recipient_name,$transfer_value,$password);
					MessageQueue::messageCommit($recipient->mobile_number, MESSAGE_SMS, TRANSFER_CREDIT_NEW_USER, $variable);
				}
				return TRUE;
			}else{
				SELF::endDbTransaction(false);
				return FALSE;
			}
	}
	

    public static function usernameVerification($value){
		// phone return true while email return false
		if(preg_match('/\\A(?:\\+?234|0)?(?:907|909|908|905|906|902|903|904|803|806|813|816|810|814|802|808|812|809|817|818|805|815|807|811|819|804|705|704|702|703|706|708|701|709|707)\\d{7}\\z/', $value)) {
			// $phone is valid
			return true;
		}else if(filter_var($value, FILTER_VALIDATE_EMAIL)){
			// $email is valid
			return false;
		}else{
            throw new Exception('Wrong Email Or Phone'); // NOT_FOUND (404) being the HTTP response code
		}
	}

}        