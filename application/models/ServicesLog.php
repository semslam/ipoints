<?php

class ServicesLog extends MY_Model
{
    public static function getTableName()
    {
        return 'services_log';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public static function getUniqueBatch(){
        $seedbroker = '';
        if(func_num_args() > 0){
            $seedbroker = implode('_',func_get_args());
            return 'SVL_'.$seedbroker.'_'.uniqid();
        }
        return 'SVL_'.uniqid();
    }

    public static function getAllActiveProduct(PDO $db){ 
        try{
            $query = " SELECT sl.id,sl.expiring_date
            FROM services_log sl  WHERE is_active = 1";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }
    }

    public static function findActive($user_id,$product_id =NULL){
        $product = (!is_null($product_id))? "OR product_id = {$product_id}" : "";
        return SELF::findOne("user_id = {$user_id} {$product} AND is_active = 1 AND expiring_date > NOW()");
    }


    public static function productServicesSubscription(
    $mobile_number='',$user_id,$value,$wallet_id,$product_id,$cover_period,$charge_wallet_id,$commission_wallet_id,$input_start_date,$input_end_date,
    $isolated = FALSE){
		$services_log  =  new static();
        $services_log->load->library('Untils');
		$services_log->load->model('Transaction');

		if($isolated){
            SELF::startDbTransaction();
        }
		try{
			$start_date;
			$end_date;
			if(empty($input_end_date)){
                       // do some months calculating here
					  $start_date = date("Y-m-d", $input_start_date);
                      $end_date  = date("Y-m-d", strtotime("+".$cover_period." month", strtotime($start_date)));	  
			}else{
					$start_date = date("Y-m-d", strtotime($input_start_date));
                    $end_date = date("Y-m-d", strtotime($input_end_date));
                }
				if(Untils::daysBetween($start_date,$end_date)<1){
					throw new UserException('The Start date can not be less than End date');
				}
				//Check the expiry product date
                $is_active = (strtotime($end_date) >= strtotime(date("Y/m/d")))? 0 : 1;
                $services['value'] = $value * $cover_period;
			    $services['user_id'] = $user_id;
			    $services['cover_period'] = $cover_period;
			    $services['product_id'] = $product_id; // choose product
				$services['is_active'] = $is_active;
				$services['purchase_date'] = $start_date;
                $services['expiring_date'] = $end_date;
                $processed = Transaction::transfer(
                    $user_id, 
                    $wallet_id, 
                    $charge_wallet_id, 
                    $commission_wallet_id, 
                    $services['value'], 
                    '', //$reference = 
                     TRUE);
                $sub_response = $services_log->db->insert('services_log',$services);
				if($isolated && $processed){
                    SELF::endDbTransaction($sub_response);         
                    return  true;
                }
                return  false;
			
		}catch(Exception $ex){
			if($isolated){
				SELF::endDbTransaction(FALSE);
			}
			throw $ex;
		}
    }
}