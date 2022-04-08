<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiKeys extends Base_Controller{
   // private $userGroup;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User');
        $this->load->model('ApiKey');
        $this->load->library("Untils");
        $this->load->library("pdolib");
        $this->load->library("utilities/Apilib");
        
    }

    

	/**
     * List of Users
     *
     * @access 	public
     * @param 	
     * @return 	view
     */
	
	public function index(){
       
		$this->data['title'] = 'Generate API Public key and Private Key';
		$this->data['subview'] = 'key/generate_pub_pri_key';
		$this->load->view('components/main', $this->data);
    }

    public function generateAPIKey()
    {
        $data['id'] = $this->input->post('user_id');
        $rules = [
            [
                'field' => 'user_id',
                'label' => 'User',
                'rules' => 'trim|required'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $accessToken = AccessToken::findATUserId($data['id']);
            $public_key = ApiKey::generatePublicKey();
            $private_key = ApiKey::generatePrivateKey();
            // check if the user is eligible to have api
            $user_group = User::fetchUserGroup($data['id']);
            if($user_group['group_name'] == VENDOR || $user_group['group_name'] == MERCHANT || $user_group['group_name'] == UNDERWRITER){
                if (!empty($accessToken)) {
                    $data['public_key'] = $public_key;
                    $data['private_key'] = $private_key;
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $result = ApiKey::updateByPk($accessToken->app_id, $data);
                    if (!$result) {
                         exit(json_encode(['value'=>'The system failed to regenerate keys, Please Try Again']));
                    }
                    $response = ($result)? 'success':'The system failed to generate keys';
                    $keys = ($result)?['public'=>$public_key,'private'=>$private_key] :[];
                    echo json_encode(['value'=> $response, 'keys'=> $keys]);
                } else {
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $user_id = (int)$data['id'];
                    $app_id = rand($user_id,5 + $user_id);
                    $this->db->trans_start();
                    $apiKey = new ApiKey();
                    $apiKey->app_id = $app_id;
                    $apiKey->public_key = $public_key;
                    $apiKey->private_key = $private_key;
                    $apiKey->access_control = 'all';
                    $apiKey->ips = '271.0.0.0.1';
                    $apiKey->last_access = $data['created_at'];
                    $apiKey->created_at = $data['created_at'];
                    $AP = $apiKey->save();
                    $accessToken = new AccessToken();
                    $accessToken->app_id = $app_id;
                    $accessToken->user_id = $user_id;
                    $accessToken->token = $this->untils->autoGeneratorPwd(65);
                    $accessToken->expiry = time() + Apilib::SESSION_LIFESPAN;
                    $AT = $accessToken->save();
                   $response = ($AP && $AT)? 'success':'The system failed to generate keys';
                   //return public key and private key to UI
                    if($response != 'success'){
                        $this->db->trans_rollback();
                    }
                    $this->db->trans_commit();
                   $keys = ($response)?['public'=>$public_key,'private'=>$private_key] :[];
                    echo json_encode(['value'=> $response, 'keys'=> $keys]);
                }
            }else{
                echo json_encode(['user_id'=>'This user is not eligible to have keys']);
            }
            
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


    public function getAPIKey(){
        $data['id'] = $this->input->post('user_id');
        $rules = [
            [
                'field' => 'user_id',
                'label' => 'User',
                'rules' => 'trim|required'
            ]
        ];

        header('Content-Type: application/json');
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run()) {
            $accessToken = AccessToken::findATUserId($data['id']);
            if(empty($accessToken)){//The user doesn't have API keys. 
                exit(json_encode(['user_id'=> 'The user doesn\'t have API keys']));
            }
            $apiKey = ApiKey::findAppId($accessToken->app_id);
            $response = (!empty($accessToken) && !empty($apiKey))? 'success':'The user doesn\'t have API keys';
            //return public key and private key to UI
            $keys = ($response)?['public'=>$apiKey->public_key,'private'=>$apiKey->private_key] :[];
            echo json_encode(['value'=> $response, 'keys'=> $keys]);
        } else {
            echo json_encode($this->form_validation->get_all_errors());
        }
    }


    public function apiUsers(){
        header('Content-Type: application/json');
        $db = $this->pdolib->getPDO();
        $apiUsers = User::getAPIUsers($db);
        echo json_encode(['value'=>'success','apiusers'=>$apiUsers]);
    }



}