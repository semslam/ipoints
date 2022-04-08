<?php

class IpinGeneration extends MY_Model 
{   
    const BATCH_PROCESS_LIMIT = 5000;
    const INEW = 'new';
    const ACTIVE = 'active';
    const CANCEL = 'cancel';
    const USED = 'used';

    public static function getTableName()
    {
        return 'ipin_generate';
    }

    public static function getPrimaryKey()
    {
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public static function generateIpin($batch_quantities,$ipin_value,$debit_amount,$wallet,$user_balance){
      $generatepin = new static();  
      $generatepin->load->library("Untils");
      $generatepin->load->model('Transaction');
      //$prefix = ''.$generatepin->untils->otpGenerator(4).''.$user_balance->wallet_id;
      //$batch_generator =  md5(uniqid($prefix).mt_rand(1000,9999));
      //echo $batch_generator;
      $ipingenerator =  $generatepin->untils->otpGenerator(16);
     
      // get system id 
      // get ipin wallet
        
      $system_user = User::systemAccount();
      $iPinWallet = Wallet::walletByName(I_PIN);
      SELF::startDbTransaction();
      $reference = Transaction::getUniqueReference('GEN_Ipin',$user_balance->user_id, $user_balance->wallet_id);
        $tranferresult = Transaction::transfer(
            $user_balance->user_id, //user id
            $user_balance->wallet_id, // user wallet id
            $system_user->id, // get system id 
            $iPinWallet->id, // get ipin wallet
            $debit_amount,// amount as $debit_amount
            $reference, 
            TRUE 
        );

               // $loops =0;
               $iterator = 0;
                $count =SELF::BATCH_PROCESS_LIMIT; 
                //$loops = ceil($batch_quantities/SELF::BATCH_PROCESS_LIMIT);
                log_message('INFO',"ipin generating start time.....". microtime());
                $ipinTransaction = false;

                $left = $batch_quantities;
                while ($left > 0) {
                    $batch = ($left > SELF::BATCH_PROCESS_LIMIT)?SELF::BATCH_PROCESS_LIMIT:$left;
                    //process batch
                    
                    try{
                        for( $x = 1; $x <= $batch; $x++){
                          
                            $ipin_batches[] = array(
                                'batch_id'=>$reference,
                                'merchant_id'=>$user_balance->user_id,
                                'ipin_value'=>$ipin_value,
                                'ipin_id'=> Untils::voucherPinGenerator(),
                                'wallet_id'=> $wallet,
                                'status'=>SELF::INEW,
                                'created_at'=> date('Y-m-d H:i:s'),
                            );
                           
                            
                        }
                        //var_dump(print_r($ipin_batches,true));
                        $batches =count($ipin_batches);
                        $ipingenresult = $generatepin->db->insert_batch('ipin_generate',$ipin_batches);
                        $ipinTransaction = (($ipingenresult == $batches) && $tranferresult)? true: false;
                
                        if(!$ipinTransaction){
                            log_message('info',"ipin generator Error  Uncall at the count of.... ".$batches);
                            SELF::endDbTransaction(false);
                            exit;
                        }
                        $iterator += $batches;
                         unset($ipin_batches);
                    } catch(Exception $ex){
                            log_message('info',"Exception ipin generator Error .... ".$ex->getMessage());
                                SELF::endDbTransaction(false);
                                break;
                            
                    }               
                    $left -= $batch;
                }
                log_message('INFO',"ipin generating End time.....". microtime());
                $results = ($iterator == $batch_quantities)? true : false;
                if($results){
                    $user = User::findById($user_balance->user_id);
                    $contact = (!empty($user->email))?  $user->email : $user->mobile_number;
                    $variable = array($reference,$ipin_value,$debit_amount, $batch_quantities);
                    MessageQueue::messageCommit($contact, MESSAGE_EMAIL, IPIN_GENERATOR, $variable);
                }
                SELF::endDbTransaction($results);
                // if(!$ipinTransaction)break;

        return $results;
    }


    public static function cancelOrActivateIpin($status,$batch_id,$user_balance){
        $generatepin = new static();
        $generatepin->load->library("Untils");
      $generatepin->load->model('Transaction');
           $result  =  SELF::getBatchIdAndCancel($batch_id,$user_balance->user_id);
        if(empty($result->batch_id) || ($result->batch_id != $batch_id && $result->cancel > 0)){
            throw new UserException("The batch id those not exist OR The batch can\'t be cancel ");
        }
        
        $data['updated_at']= date('Y-m-d H:i:s');
        if($status == 1 && !intval($result->cancel) > 0){
            $amount = (intval($result->ipin_value) * (intval($result->new) + intval($result->active)));
            $system_user = User::systemAccount();
            $iPinWallet = Wallet::walletByName(I_PIN);
            SELF::startDbTransaction();
            $reference = Transaction::getUniqueReference('Ipin_Cancel',$user_balance->user_id, $user_balance->wallet_id);
            $tranferResult = Transaction::transfer(
                $system_user->id, // get system id 
                $iPinWallet->id, // get ipin wallet
                $user_balance->user_id, //user id
                $user_balance->wallet_id, // user wallet id
                $amount,// amount as $debit_amount
                $reference, 
                TRUE 
            );
            $data['status'] = 'cancel';

            $ipinResult = SELF::update(['batch_id'=>$batch_id, 'status'=>'new','status'=>'active'],$data);
           $result = ($ipinResult && $tranferResult)? true : false;
            SELF::endDbTransaction($result);
            return $result;
        }else if($status == 0){
            $data['status'] = 'active';
            return SELF::update(['batch_id'=>$batch_id],$data);
        }else{
            throw new UserException("You have choose wrong status, Please Try Again");
        }
        
    }

    static function ipinVoucherProcess($voucher, $user_id){

       $ipinResult = SELF::getIpinVoucherByBatchId($voucher);
       if(empty($ipinResult->ipin_id)){
            throw new UserException("This voucher doesn't exist");
       }else if($ipinResult->status == 'new'){
           //not activate
           throw new UserException("This voucher is not active");
       }else if($ipinResult->status == 'used'){
           //this voucher as been used by another subscriber
           throw new UserException("This voucher as been used by another subscriber");

       }else if($ipinResult->status == 'cancel'){
            //This voucher is no longer valued
            throw new UserException("This voucher is no longer valued");
       }
       SELF::startDbTransaction();
       $reference = Transaction::getUniqueReference('Load_Ipin',$user_id, $ipinResult->wallet_id);
       $creditResult =Transaction::credit(
                        $user_id,
                        $ipinResult->ipin_value,// amount
                        $ipinResult->wallet_id,// wallet
                                    '',// reference
                                    $ipinResult->merchant_id,// form
                                    TRUE
                                );

        $data['status'] = 'used';
        $data['used_by'] = $user_id;
        $data['updated_at']= date('Y-m-d H:i:s');
        $ipinUsed = SELF::update(['ipin_id'=>$ipinResult->ipin_id],$data);

         $result=($ipinUsed & $creditResult)? true:false;
        SELF::endDbTransaction($result);
        return $result;
    }


    static function generateIpinBatchReports(PDO $db, $data, $isExport=false){
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  ig.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['batch_id'])){
            $where[] =" ig.batch_id = '".$data['batch_id']."'";
        }if(!empty($data['merchant_id'])){
            $where[] =' ig.merchant_id = '.(int)$data['merchant_id'];
        }if(!empty($data['wallet_id'])){
            $where[] =' ig.wallet_id = '.(int)$data['wallet_id'];
        }if(!empty($data['ipin_value'])){
            $where[] =' ig.ipin_value = '.(int)$data['ipin_value'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $where = $where .= (!$isExport)?' GROUP BY ig.batch_id ORDER BY batch_name DESC LIMIT 150':' GROUP BY ig.batch_id';
        try{
            $query = " SELECT ig.batch_id as batch_name,
            COUNT(*) * ig.ipin_value as total_ipoints ,
            ig.ipin_value , COUNT(*) batch_count,w.name as wallet_name, 
            SUM(status = 'new') as new_status, 
            SUM(status = 'active') as active_status, 
            SUM(ig.status = 'cancel') as cancel_status, 
            SUM(ig.status = 'used') as used_status, 
            ig.created_at  
            FROM ipin_generate ig 
            LEFT JOIN `wallets` as `w` ON `w`.`id` = `ig`.`wallet_id`".$where;

            if ($isExport) {
                return $query;
            }
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }  
    }


    static function generateReports(PDO $db, $data, $isExport=false){
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  ig.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['batch_id'])){
            $where[] =" ig.batch_id = '".$data['batch_id']."'";
        }if(!empty($data['merchant_id'])){
            $where[] =' ig.merchant_id = '.(int)$data['merchant_id'];
        }if(!empty($data['wallet_id'])){
            $where[] =' ig.wallet_id = '.(int)$data['wallet_id'];
        }if(!empty($data['ipin_value'])){
            $where[] =' ig.ipin_value = '.(int)$data['ipin_value'];
        }if(!empty($data['ipin_id'])){
            $where[] =' ig.ipin_id = "'.$data['ipin_id']."'";
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $where = $where .= (!$isExport)?' GROUP BY ig.id  LIMIT 150':' ';

        try{
            $query = " SELECT ig.batch_id ,ig.ipin_value, w.name as wallet_name, ig.ipin_id, ig.status, CONCAT(COALESCE(`us`.`name`, `us`.`business_name`,'') , ' (',COALESCE(`us`.`mobile_number`,`us`. `email`,''), ')') `user_name`, ig.created_at, ig.updated_at
            FROM ipin_generate ig 
            LEFT JOIN `wallets` as `w` ON `w`.`id` = `ig`.`wallet_id`
            LEFT JOIN `users` as `us` ON `us`.`id` = `ig`.`used_by` ".$where;

            if ($isExport) {
                return $query;
            }
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }  
    }

    public static function getBatchIdAndCancel($batch_id,$merchant_id){
        $ipin = new static();
        $query = $ipin->db->from('ipin_generate ig')
        ->select("ig.batch_id, SUM(ig.status = 'cancel') as cancel, COUNT(*) * ig.ipin_value as total_ipoints , COUNT(*) batch_count, SUM(ig.status = 'used') as used,SUM(ig.status = 'active') as active,SUM(ig.status = 'new') as new, ig.ipin_value")
        ->where('ig.batch_id',$batch_id)
        ->where('ig.merchant_id',$merchant_id)
        ->get();
		return $query->row();
    }

    public static function getIpinVoucherByBatchId($ipin_id){
        $ipin = new static();
        $query = $ipin->db->from('ipin_generate ig')
        ->select("ig.*")
        ->where('ig.ipin_id',$ipin_id)
        ->get();
		return $query->row();
    }


}    