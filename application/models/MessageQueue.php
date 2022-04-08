<?php

class MessageQueue extends MY_Model
{
    const STATUS_PENDING ='pending';
    const STATUS_SENT ='sent';
    const STATUS_FAILED ='failed';
    const VARIABLE_STATIC ='static';
    const VARIABLE_DYNAMIC ='dynamic';
    const SINGLE ='single';
    const MULTI ='multi';
    const FREE = 'free';
    const PAID = 'paid';
    const ARREARS = 'arrears';
   
    public static function getTableName(){
        return 'message_queue';
    }

    public static function getPrimaryKey(){
        return SELF::DEFAULT_PRIMARY_KEY;
    }

    public function beforeSave(){
        $this->updated_at = date('Y-m-d H:i:s');
        if($this->isNew){
            $this->created_at = $this->updated_at;
        } 
    }
    
    public static function findById($id){
        return self::findOne(['id' => $id]);
    }

    public static function messageCommitWithAttach($contact, $type = MESSAGE_EMAIL, $action, Array $variable = [], $directory_path){
        // save the datils
        // call message_template table an get some info
        $messageCommit = new static();//$input['message_channel'] $input['action']
        $messageCommit->load->model('MessageTemplate_m', 'messagetemplate_m');
        //implode(',', [$transaction['qty'],$defaultPass]);
        $message_variable;
        if(!empty($variable)){
            $message_variable = implode(',', $variable);
        }else $message_variable = '';

        $recipients = $contact;
        $recipient_type = SELF::SINGLE;
        if(is_array($contact)){
            $recipients = implode(',', $contact);
            $recipient_type = SELF::MULTI;
        }

        $message_template = $messageCommit->messagetemplate_m->get_template(['message_channel'=> $type,'action'=>$action]);
        SELF::queuecommitment($recipients, $recipient_type,$message_template, $type, $action, $message_variable, $directory_path);
    }

    public static function messageCommitWithExternalTemplate($template, $contact, $type, $action, $message_variable){
        // save the datils
       // call message_template table an get some info
       echo "MESSAGE TYPE+++++++++++++".$type;
       $recipient_type = SELF::SINGLE;
       SELF::queuecommitment($contact ,$recipient_type ,$template ,$type ,$action ,$message_variable , '');
   }

    public static function messageCommit($contact, $type = MESSAGE_EMAIL, $action, Array $variable = []){
         // save the datils
        // call message_template table an get some info
        $messageCommit = new static();//$input['message_channel'] $input['action']
        $messageCommit->load->model('MessageTemplate_m', 'messagetemplate_m');
        $message_variable;
        if(!empty($variable)){
            $message_variable = implode(',', $variable);
        }else $message_variable = '';

        $recipients = $contact;
        $recipient_type = SELF::SINGLE;
        if(is_array($contact)){
            $recipients = implode(',', $contact);
            $recipient_type = SELF::MULTI;
        }
        $message_template = $messageCommit->messagetemplate_m->get_template(['message_channel'=> $type,'action'=>$action]);
        SELF::queuecommitment($recipients,$recipient_type,$message_template, $type, $action, $message_variable, '');
    }

   private static function queuecommitment($contact,$recipient_type, $message_template, $type, $action, $variable, $directory_path =''){
        
        if(!is_null($message_template)){
            $messageQueue = new  MessageQueue();
            $messageQueue->recipient = $contact;
            $messageQueue->recipient_type = $recipient_type;
            $messageQueue->message_template_id = $message_template->id;
            $messageQueue->message_type = (empty($variable))? SELF::VARIABLE_STATIC : SELF::VARIABLE_DYNAMIC;
            $messageQueue->type = $type;
            $messageQueue->message_variable = $variable ?: '';
            $messageQueue->message_subject = (!empty($message_template->message_subject))? $message_template->message_subject : '';
            $messageQueue->message_body = $message_template->message_template;
            $messageQueue->status = SELF::STATUS_PENDING;
            $messageQueue->attempt_set = $message_template->attempt_no;
            $messageQueue->priority = $message_template->priority;
            $messageQueue->charge = $message_template->charge;
            $messageQueue->attachment_url = $directory_path;
            $messageQueue->created_at = date('Y-m-d H:i:s');
           $message = $messageQueue->save();
           if(!$message){
            log_message('INFO','Queuecommitment Error::__ User Contact '.$contact.' message faied to commit');
           }
           log_message('INFO','Queuecommitment Success::__ User Contact '.$contact.' message successful to commit');
        }
        
    }
const MESSAGE_PROCESS_LIMIT = 1000;
    public static function processQueueMessage($type,$priority){

        //$queueMessages = SELF::find(['type'=>$type,'priority'=>$priority,'status'=>SELF::STATUS_PENDING])->limit(2)->all();
        $queueMessagesPending = SELF::count(['type'=>$type,'priority'=>$priority,'status'=>SELF::STATUS_PENDING]);
        log_message('info','Message Queue Count <<<<< '.$queueMessagesPending.' >>>>>---<<<<< '.$type.' >>>>>---<<<<< '.$priority.' >>>>>');
        $loops =0;
        $loops = ceil($queueMessagesPending/SELF::MESSAGE_PROCESS_LIMIT);
        log_message('info','1000 Message Queue Batch Records need to be processed '.$loops);
        
        //var_dump(print_r($queueMessages, true));exit;
        log_message('info','STARTED PROCESS____________________________'.$queueMessagesPending.'>>>Time>>>'. microtime());
        SELF::startDbTransaction();
        while($loops >= 1){

            log_message('info','While loop processing is '.$loops);

            $queueMessages = SELF::find(['type'=>$type,
            'priority'=>$priority,
            'status'=>SELF::STATUS_PENDING])->limit(SELF::MESSAGE_PROCESS_LIMIT)->all();

            foreach($queueMessages as $queueMessage){
                log_message('info','Foreach process start time>>>>>> '. microtime());

                if(!empty($queueMessage->id)){

                    try{
                        $attempt = $queueMessage->attempt_no;
                        $request = false;
                        //ob_start();
                        if($type == MESSAGE_EMAIL){
                            $emails;
                            if(strpos($queueMessage->recipient,',') !== false){
                                $emails = explode(',',$queueMessage->recipient);
                            }else{ $emails = $queueMessage->recipient;}
                            
                            $request= SELF::emailMesg($emails,$queueMessage->message_subject,$queueMessage->message_body,$queueMessage->message_variable,$queueMessage->attachment_url);
                            //var_dump($request);
                            ++$attempt;
                            echo 'Email attempt>>>>>>'.$attempt.PHP_EOL;
                        }elseif($type == MESSAGE_SMS){
                            $request = SELF::smsMesg($queueMessage->recipient,$queueMessage->message_body, $queueMessage->message_variable);
                            echo 'SMS RESULT>>>>>>'.PHP_EOL;
                            var_dump($request);
                            ++$attempt;
                            echo 'SMS attempt>>>>>>'.$attempt.PHP_EOL;
                        }
                        //check if the attempt is equal to number of set attempt, if true  and sending request is false set attempt to faild
                        $data['attempt_no'] = $attempt;
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        $data['charge'] = ($queueMessage->charge == SELF::FREE)? SELF::FREE : ((($queueMessage->charge == SELF::PAID) && $request)? SELF::ARREARS : SELF::PAID);
                        echo 'Message charge >>>>>>'.$data['charge'].PHP_EOL;
                        if($queueMessage->attempt_set == $attempt){
            
                            $data['status']=   ($request)?  SELF::STATUS_SENT : SELF::STATUS_FAILED;
                            echo 'Message status >>>attempt finsh>>>>>'.$data['status'].PHP_EOL;
            
                        }else{ 
                            $data['status']= ($request)?  SELF::STATUS_SENT : SELF::STATUS_PENDING;
                            echo 'Message status>>>>>>'.$data['status'].PHP_EOL;
                        }
                        $data['updated_at'] = date('Y-m-d H:i:s');
                        SELF::update(['id'=>$queueMessage->id],$data);
                        //update in table if faild or sent
                    }catch(Throwable $e){
                        log_message('info',"Error processing this request->>>>>>".$e->getMessage());
                        ++$attempt;
                        echo '---'.$type.' attempt-->>>>>>'.$attempt.PHP_EOL;
                        $data['attempt_no'] = $attempt;
                        if($queueMessage->attempt_set == $attempt){
                            $data['status']=  SELF::STATUS_FAILED;
                            echo '---Message Error status >>>attempt finsh>>>>>'.$data['status'].PHP_EOL;
                        }else{ $data['status']=  SELF::STATUS_PENDING; echo '---Message Error status>>>>>>'.$data['status'];}

                        
                        $data['attempt_no'] =  $attempt;
                        $data['updated_at'] = date('Y-m-d H:i:s');      
                        $data['error_note'] = $e->getMessage();
                        $messageResult = SELF::update(['id'=>$queueMessage->id],$data);

                         echo '-----Message Result >>>>>>--'.$messageResult.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL;
            
                    }
            
                }else{
                    echo 'Message Queue is empty >>>>>'.microtime();
                    log_message('info','Message Queue is empty >>>>>'.microtime());
                } 

                log_message('info','Foreach process end time >>>>>>> '.microtime());
            }
            $loops--;
        }
        log_message('info','ENDED PROCESS____________________________'.$queueMessagesPending.'>>>Time>>>'. microtime());
        SELF::endDbTransaction(true);
    }

    public static function emailMesg($email,$message_subject,$message_template,$message_variable,$directory_path = ''){
            $emailMessage = new static();
            $emailMessage->load->library('emailer');
            $emailer = new Emailer();
            $emailer->addRecipient($email);
            $emailer->subject = $message_subject;
            $message = '';
            if(!empty($message_variable)){
                $variables = explode(',',$message_variable);
                $message =  SELF::template_process(
                    $variables,
                    $message_template
                );
            }else $message = $message_template;
          
			if(!empty($directory_path)){
				$emailer->addAttachmentDir($directory_path);
			}
			$emailer->addMessage(stripcslashes($message));
            $emailResult = $emailer->send();
            log_message('INFO', 'Email sending notication '. $emailResult);
            return $emailResult;
    }
    
    public static function smsMesg($mobile_number, $message_template, $variable = ''){
        $smsMessage = new static();
        $smsMessage->load->library('sms');
        $message = '';
        if(!empty($variable)){
            $variables = explode(',',$variable);
            $message =  SELF::template_process(
                $variables,
                $message_template
            );
        }else $message = $message_template;

        $smsResult= $smsMessage->sms->sendOTP($mobile_number,$message);
        log_message('INFO', 'SMS sending notication '. $smsResult);
        return $smsResult;
    }

    public static function checkSMSBalnces(){
        $smsMessage = new static();
        $smsMessage->load->library('sms');
       return $smsMessage->sms->checkSMSBalance();
    }
    
    public static function template_process($values,$template){
		$placeholder = [];
		foreach($values as $key=>$val) 
			$placeholder[] = "{{$key}}";
		    $bodytag = str_replace(
			$placeholder,
			$values, 
			$template);
		
		return $bodytag;
    }

    public static function fitterMessageQueue(PDO $db,$data,$isExport = false){
        $where = [];
        if(!(empty($data['start_date']) && empty($data['end_date']))){
            $where[] ="  mq.created_at  BETWEEN  '".$data['start_date']."' AND '".$data['end_date']."'";
        }if(!empty($data['recipient_type'])){
            $where[] =" mq.recipient_type = '".$data['recipient_type']."'";
        }if(!empty($data['recipient'])){
            $where[] =" mq.recipient = '".$data['recipient']."'";
        }if(!empty($data['type'])){
            $where[] =" mq.type = '".$data['type']."'";
        }if(!empty($data['status'])){
            $where[] =" mq.status = '".$data['status']."'";
        }if(!empty($data['charge'])){
            $where[] =" mq.charge = '".$data['charge']."'";
        }if(!empty($data['message_type'])){
            $where[] =" mq.message_type = '".$data['message_type']."'";
        }
        
        $where = $where ? ' WHERE '.implode(' AND ', $where) : '';
        $countWhere = $where;
        $where = $where .= (!$isExport)?" GROUP BY mq.created_at LIMIT ".$data['limit']." OFFSET ".$data['offset'] :" GROUP BY mq.created_at ";

        try{
            $query = " SELECT * 
            FROM message_queue mq ".$where;
            if ($isExport) {
                return $query;
            }

            $fromAndJoin = " FROM message_queue mq ";

        $countQuery = " SELECT COUNT(*) AS allCount {$fromAndJoin} {$countWhere}";
            if(true){
                //var_dump($countQuery);
                $stmt= $db->query($countQuery);
                 SELF::$result_count = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            //var_dump($query);
                   $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex)
        {
            throw $ex;
        }  
    }


    public static function arrayToStringWithComma($message_variable){
        if(!empty($message_variable)){
           return $message_variable = implode(',', $message_variable);
        }else{ 
            return $message_variable = '';
        }
    }

    public static function getMessageQueueStatus($status){
        $queuestatus = new static();
        $query = $queuestatus->db->from('message_queue mq')
        ->select('COUNT(*) status')
        ->where('mq.status',$status)
        ->get();
        return $query->row();
    }

    public static function getMessageQueueCharge($charge){
        $queuecharge = new static();
        $query = $queuecharge->db->from('message_queue mq')
        ->select('COUNT(*) charge')
        ->where('mq.charge',$charge)
        ->get();
        return $query->row();
    }

}