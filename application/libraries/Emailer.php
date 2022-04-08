<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emailer{

    protected $ci;
    protected $type;
    public $subject = '';
    public $message = '';
    private $attachments = [];
    private $attachment ='';
    private $email;
    protected $emailLayout = 'layouts/mail';
    private $sms;
    public $recipient = [];
    

    public $emailConfig = [
        'protocol'=>'smtp',
        'smtp_crypto'=>'ssl',
        'smtp_host'=>'send.one.com',
        'smtp_user'=>'no_reply@uicinnovations.com',
        'smtp_pass'=>'!!uici@Devops',
        'smtp_port'=>'465',
        'newline'=>"\r\n",
        'mailtype'=>'html'
    ];
    public function __construct($type='email'){
        $this->ci =& get_instance();
        $this->type = $type;
        $this->ci->load->helper('url');
        $this->init();
        
    }

    private function init(){
        switch($this->type){
            case 'email':
                $this->ci->load->library('email');
                $this->email =& $this->ci->email;
            break;
            case 'sms':
               // $this->ci->load->library('Infobip');
               // $this->sms =& $this->ci->infobip;
            break;
            default:
                throw new Exception('Unknown notification type or notification type is not defined');
        }
    }

    public function addMessage($message){
        $this->message = $message;
    }

    public function addRecipient($recipient){
        if (is_array($recipient)) {
            $this->addRecipients($recipient);
        }
        else $this->recipient[] = $recipient;
    }

    private function addRecipients(Array $recipient =[]){
        $this->recipient += $recipient;
    }

    public function addAttachment($filename,$newName='',$mime='',$disposition='attachment'){
        $this->attachments[] = [
            'filename' => $filename,
            'newname' => $newName,
            'mime' => $mime,
            'disposition' => $disposition,
        ];
    }

    public function addAttachmentDir($directory){
        $this->attachment = $directory;
    }

    public function prepareHTMLMessage($view,$params=[],$useLayout=true){
        $content = $this->ci->load->view('mail/'.$view,$params,true);
        if($useLayout){
            $content = $this->ci->load->view($this->emailLayout,['content'=>$content],true);
        }
        return $content;
    }

    public function send(){
        if($this->type == 'email'){
            $this->email->initialize($this->emailConfig);
            $this->email->from($this->emailConfig['smtp_user'],'UICI')->to($this->recipient)->message($this->message)->subject($this->subject);
            if (!empty($this->attachments)) {   // attach files
                foreach($this->attachments as $attachment) {
                    $filename = $attachment['filename'];
                    $mime = $attachment['mime'];
                    $this->email->attach(
                        $filename,
                        $attachment['disposition'],
                        $attachment['newname'],
                        $mime
                    );
                }
            }elseif(!empty($this->attachment)){
                $this->email->attach($this->attachment);
            }
            if(!$this->email->send(false)){
                throw new Exception($this->email->print_debugger());
            }
            return true;
            //old
            // $this->email->print_debugger();
            // return false;
        } else if($this->type == 'sms'){
            foreach($this->recipient as $recipient){
                $this->sms->sendSMS($this->message,$recipient);
            }
            return true;
        }

        return false;
    }



}
