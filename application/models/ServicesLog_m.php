<?php

class ServicesLog_m extends MY_Model {   

    function __construct()
    {
        parent::__construct();
        $this->load->library('datagrid');
        $this->load->model('User');
        $this->load->model('Product');
        $this->load->model('Wallet');
        $this->load->model('Transaction');
        $this->load->library("pdolib");
        $this->load->model('ReportSubscription');
        $this->load->library('utilities/ExcelFactory');
    }
    const SUBSCRIPTION_LIMIT = 5000;

    public static function getTableName(){
        return 'services_log';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    /**
     * Datagrid Data
     *
     * @access  public
     * @param   
     * @return  json(array)
     */

    static function fetchProductSubscriptionInGroup(PDO $db, $data, $isExport=false){
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  sl.expiring_date  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['batch_id'])){
            $where[] =" sl.batch_id = '".$data['batch_id']."'";
        }if(!empty($data['provider_id'])){
            $where[] =' sl.provider_id = '.(int)$data['provider_id'];
        }if(!empty($data['is_active'])){
            $where[] =' sl.is_active = '.(int)$data['is_active'];
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $where = $where .= (!$isExport)?' GROUP BY sl.batch_id  LIMIT 150':' GROUP BY sl.batch_id';

        try{
            $query = " SELECT 
            sl.batch_id,
            CONCAT(COALESCE(`u`.`name`, `u`.`business_name`,'') , ' (',COALESCE(`u`.`mobile_number`,`u`. `email`,''), ')') `provider_name`,
            COUNT(*) AS users, 
            p.product_name , sl.value AS billing_price,
            (COUNT(*)* sl.value) AS total_billing, 
            sl.cover_period, 
            sl.is_active, 
            sl.purchase_date, 
            sl.expiring_date,
            sl.product_id FROM `services_log` sl 
            LEFT JOIN products p ON p.id = sl.product_id
            LEFT JOIN users u ON u.id = p.provider_id ".$where;

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


    public static function  fetchBatchSubscriptionReport(PDO $db,$data, $isExport=false){

        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  sl.purchase_date  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['batch_id'])){
            $where[] =" sl.batch_id = '".$data['batch_id']."'";
        }if(!empty($data['product_id'])){
            $where[] =" w.product_id = ".(int)$data['product_id'];
        }if(!empty($data['provider_id'])){
            $where[] =' sl.provider_id = '.(int)$data['provider_id'];
        }if(!empty($data['is_active'])){
            $where[] =' sl.is_active = '.(int)$data['is_active'];
        }

        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $where = $where .= (!$isExport)?' GROUP BY sl.batch_id  LIMIT 150':' '; 

        try{
            $query = "SELECT  
            u.id,
            u.name,
            u.mobile_number,
            u.gender,
            FLOOR(DATEDIFF(CURRENT_DATE, u.birth_date)/365.25) AS age,
            u.address,
            st.state_name,
            lg.name AS local_govt,
            u.next_of_kin_phone,
            sl.batch_id,
            p.product_name,
            sl.value AS billing_price,
            FORMAT(p.price - ((p.price * ul.value)/100),2) AS product_commission,
            sl.purchase_date,
            sl.expiring_date
            FROM users u
            LEFT JOIN user_balance ub ON ub.user_id = u.id
            LEFT JOIN services_log sl ON sl.user_id = u.id
            left JOIN wallets w ON w.id  = ub.wallet_id
            LEFT JOIN products p ON p.id  = w.product_id
            LEFT JOIN uici_levies ul ON ul.id  = p.charge_commission_id
            LEFT JOIN state_tbl st ON st.state_id = u.states
            LEFT JOIN local_govts lg ON lg.id = u.lga ".$where;

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

    public static function fetchUsersWithSubscribeableCount($product_id,$billing_price){
        $subscriptionList =  new static();
        $query = $subscriptionList->db->from('users u')
        ->select('COUNT(*) AS subscribers')
        ->join('user_balance AS ub ', 'ub.user_id = u.id', 'INNER')
        ->join('wallets AS w', 'w.id = ub.wallet_id', 'LEFT')
        ->where('w.product_id', $product_id)
        ->where('ub.balance >', $billing_price)
        ->where('NOT EXISTS (SELECT * FROM services_log sl WHERE (sl.user_id, sl.product_id) = (u.id, w.product_id) AND DATE(sl.expiring_date) >= DATE(NOW()))',NULL, FALSE)
        ->where('u.status', 1)
        ->where('LENGTH(u.name)>0 AND LENGTH(u.gender)>0 AND LENGTH(u.address)>0')
        ->where('LENGTH(u.next_of_kin)>0 AND LENGTH(u.next_of_kin_phone)>0')
        ->where('LENGTH(u.birth_date)>0')
        ->get();
        return $query->row();
    }

    public static function fetchUsersWithSubscribeableBalances($product_id,$billing_price,$limit){
        $subscriptionList =  new static();
        $query = $subscriptionList->db->from('users u');
        $subscriptionList->db->select('u.id as user_id, u.mobile_number, w.product_id,w.id as wallet_id, ub.balance');
        $subscriptionList->db->join('user_balance AS ub ', 'ub.user_id = u.id', 'INNER');
        $subscriptionList->db->join('wallets AS w', 'w.id = ub.wallet_id', 'LEFT');
        $subscriptionList->db->where('w.product_id', $product_id);
        $subscriptionList->db->where('ub.balance >', $billing_price);
        $subscriptionList->db->where('NOT EXISTS (SELECT * FROM services_log sl WHERE (sl.user_id, sl.product_id) = (u.id, w.product_id) AND DATE(sl.expiring_date) >= DATE(NOW()))',NULL, FALSE);
        $subscriptionList->db->where('u.status', 1);
        $subscriptionList->db->where('LENGTH(u.name)>0 AND LENGTH(u.gender)>0 AND LENGTH(u.address)>0');
        $subscriptionList->db->where('LENGTH(u.next_of_kin)>0 AND LENGTH(u.next_of_kin_phone)>0');
        $subscriptionList->db->where('LENGTH(u.birth_date)>0');
        $subscriptionList->db->limit($limit);
        return $subscriptionList->db->get()->result();
    }

    public static function processProductSubscription($product_id, $start_date, $end_date, $eligible_user_count, $billing_price, $percentage){

        $subscription = new static();
       
        $subscription->load->model('MessageTemplate_m', 'messagetemplate_m');
        $message_action = PRODUCT_SERVICES_SUBSCRIPTION;
        $message_type = MESSAGE_SMS;
        $message_template = $subscription->messagetemplate_m->get_template(['message_channel'=> $message_type,'action'=>$message_action]);
    
            $product = Product::fetchProductDetails($product_id);
            if(!is_null($product)){
                $prefix = $product->product_name.'-';
                $batch_id = Transaction::getUniqueReference($prefix.'ProdSub',$billing_price);
    
                log_message('info','STARTED PROCESS____________________________'.$product->id.'>>Time>>>'. microtime());
                
                $loops =0;
                $loops = ceil($eligible_user_count/SELF::SUBSCRIPTION_LIMIT);
                log_message('info','5000 Bulk Transfer Bash Records need to be processed '.$loops);

                $product_wallet = Wallet::walletByName(I_INCOME);
			    $system_user = User::systemAccount();
                $beneficiaries = 0;
                while($loops >= 1){

                    log_message('info','While processing is '.$loops);


                    $subscriberBalances = SELF::fetchUsersWithSubscribeableBalances($product_id,$billing_price,SELF::SUBSCRIPTION_LIMIT);
                    
                    $services_log;
                    SELF::startDbTransaction();
                    $inTransaction = false;
                    foreach($subscriberBalances as $subscriberBalance){
                        //$process = Transaction::debit()
                      
                        
                        try{
    
                            log_message('info','process time>>>>>> '. microtime());

                                $current_balance =  $subscriberBalance->balance - $percentage;
                                $product_rice =  $billing_price - $percentage;
                               
                                if($current_balance >= $product_rice){
                                    $commission_reference = Transaction::getUniqueReference($prefix.'ProdSubCommCharge',
                                    $subscriberBalance->user_id,
                                     $subscriberBalance->wallet_id,
                                     $system_user->id,
                                     $product->commission_wallet_id);

                                    $commission = Transaction::transfer(
                                        $subscriberBalance->user_id,
                                        $subscriberBalance->wallet_id,
                                        $system_user->id,
                                        $product->commission_wallet_id,
                                        $percentage,
                                        $commission_reference,
                                        true
                                    );

                                    $billing_reference = Transaction::getUniqueReference($prefix.'ProdSubBilling',
                                        $subscriberBalance->user_id,
                                        $subscriberBalance->wallet_id,
                                        $product->provider_id,
                                        $product_wallet->id);

                                    $product_billing = Transaction::transfer(
                                        $subscriberBalance->user_id,
                                        $subscriberBalance->wallet_id,
                                        $product->provider_id,
                                        $product_wallet->id,
                                        $product_rice,
                                        $billing_reference,
                                        true
                                    );

                                    $inTransaction = ($commission && $product_billing);
                                    if($inTransaction){
                                       
                                        $services_log[] = array(
                                            'batch_id'=>$batch_id,
                                            'user_id'=>$subscriberBalance->user_id,
                                            'product_id'=>$subscriberBalance->product_id,
                                            'value'=>$product_rice,
                                            'cover_period'=>$product->allowable_tenure,
                                            'is_active'=> 1,
                                            'purchase_date'=>$start_date,
                                            'expiring_date'=>$end_date,
                                        );
                                        
                                        $message_variable = array($subscriberBalance->product_id, $billing_price, $start_date, $end_date);
                                        MessageQueue::messageCommitWithExternalTemplate($message_template, $subscriberBalance->mobile_number, $message_type, $message_action, MessageQueue::arrayToStringWithComma($message_variable));
                                        log_message('info','process end time>>>>>> '. microtime());
                                    }else{  
                                        log_message('info','Commission And Product Billing Error>>>> '. microtime());
                                        SELF::endDbTransaction(false);
                                    } 
                                    
                                    
                                }

                        } catch(Throwable $ex){
                            log_message('info',"Error processing this request...".$ex->getMessage());
                            if($inTransaction){
                                SELF::endDbTransaction(false);
                            }
                        }
                        
                    }
                    $loops--;
                    $result = $subscription->db->insert_batch('services_log', $services_log);
                   
                    $heap = count($services_log);
                    log_message('info','Collation Heap Insert Process>>>> '.$result.'=='.$heap.'>>>Time>>>>'. microtime());
                    $commit = ($result == $heap)? true : false;
                    if($commit){$beneficiaries+= $heap;}
                    log_message('info',print_r($services_log ,true));
                    SELF::endDbTransaction($commit);
                    unset($services_log);
                }
               
                //admin report generation and mail sending
                $db = $subscription->pdolib->getPDO();
                $data['product_id']=$product->id;
                $data['batch_id']= $batch_id;
                $report = SELF::fetchBatchSubscriptionReport($db,$data, true);
                $directory_path = ExcelFactory::dumpExcelInDirectory($report,[],[],$prefix.'productSubscriptionReport-From-'.$start_date.'-To-'.$end_date,(($product->allowable_tenure*28) * 24*60*60));
                $info['report_type'] = PRODUCT_SUBSCRIPTION;
                $info['frequency'] = MONTHLY;
                $info['dispatcher_type'] = GROUP;
                $reports = ReportSubscription::getReportSubscription($db,$info);
                //dump sql file in a path and return part url
                $emails =  array_column($reports, 'email');
                unset($reports);
                //Beneficiaries {0} Product Name {1} Product Price {2} Commission Charge {3} Total Billing {4} Total Commission {5} Purchase Date {6} Expiring Date {7}
                $message_variable = array($beneficiaries,
                $product->product_name,
                $product->price,
                 $billing_price,
                 $percentage,
                 $billing_price * $beneficiaries,
                 $percentage * $beneficiaries,
                  $start_date, $end_date);
                MessageQueue::messageCommitWithAttach($emails, MESSAGE_EMAIL, PRODUCT_SUBSCRIPTION_REPORT, $message_variable, $directory_path);
                log_message('info','END PROCESS____________________________'.$product->id.'>>Time>>>'. microtime());
                return true;
            } else {
                log_message('info',"Request not found!...");
            }
           
    }
}