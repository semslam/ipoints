<?php
require(APPPATH.'/libraries/REST_Controller.php');
/**
 * REST Server for the CodeIgniter UICI iPoints Administration Service
 *
 * @package REST Server
 * @require "php": ">=5.4.0",
 *		    "codeigniter/framework": "^3.0.4",
 *		    "guzzlehttp/guzzle": "~6.0"
 * @author  
 * @verion  1.0
 */
class Api extends REST_Controller
{ 
    /************************************************************************************************
		                                 List of Methods
	*************************************************************************************************
		1. user_post
		2. iPointsTransferRequest_post
		3. user_get
		4. subscribers_get
		5. erpData_get
		6. subscriberServiceAllocationList_get
		7. iPoints_post
		8. serviceRequestShoppingCart_post
	*************************************************************************************************/
	
	/*
		1) This method shall be exposed to 3rd party clients to post subscriber data in json format.
		This data may also come from registration forms.
		Please note user registration OTP management shall be handled by Facebook activity KIT
		This data shall be stored in the WIP table or pushed to wordpress for new account creation
		wordpress user types include:
		- subscriber
		- merchants
		- underwriters		
	*/
    function user_post()
    {
		
    }
	/*
		1) This method shall be used to transfer iPoints from one user to another.
			points can not be transfered across purpose or wallet.
	*/
    function iPointsTransferRequest_post()
    {
		
    }
	
	/*
		3) This method may be used by the background workers to get info about particular subscribers.
		This method shall be used by the background workers to push data to the call center CRM system.
	*/
    function user_get()
    {
        if(!$this->get('phone'))
        {
            $this->response(NULL, 400);
        }
 
        $user = $this->user_model->get( $this->get('phone') );
         
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }
 
        else
        {
            $this->response(NULL, 404);
        }
    }
    
	/*
		4) This method shall be used by the background workers to push data to the wordpress point system from the WIP table.
	*/
    function subscribers_get()
    {
        $subscribers = $this->user_model->get_all();
         
        if($subscribers)
        {
            $this->response($subscribers, 200);
			// execute the logic to interact with wordpress users table and point system here
        }
 
        else
        {
			// log all failures here
            $this->response(NULL, 404);
        }
    }
    
	/*
		5) This method shall be used by the background workers to push data to the ERP system.
	*/
    function erpData_get()
    {
        $erp_data = $this->erp_data_model->get_all();
         
        if($erp_data)
        {
            $this->response($erp_data, 200);
			// execute the logic to push data to an external erp system
        }
 
        else
        {
			// log all failures here
            $this->response(NULL, 404);
        }
    }
    
	/*
		6) This method shall be used by the background workers to push data to the 
		service providers based on subscribers iPoints type and balance.
	*/
    function subscriberServiceAllocationList_get()
    {
        $subscriberServiceAllocationList = $this->subscriberServiceAllocationList_model->get_all();
         
        if($subscriberServiceAllocationList)
        {
            $this->response($subscriberServiceAllocationList, 200);
			// execute the logic to interact with wordpress users table and point system here
        }
 
        else
        {
			// log all failures here
            $this->response(NULL, 404);
        }
    }
    
	/*
		7) This method shall be used by the ipoints purchase shopping cart.
		All ipoints to be bought shall be designated for:
		- iLife
		- iHealth
		- iSavings
		- iPension
		The iPoints create API shall be called to post data into wordpress.
	*/
    function iPoints_post()
    {
		
    }
    
	/*
		8) This method shall be used by the subscribers to purchase products based on
		designated iPoints:
		- iLife
		- iHealth
		- iSavings
		- iPension
		The iPoints update/deduction API shall be called to post data into wordpress.
		The system shall always check to confirm the iPoints balance based on iPoints purpose (iLife, iHealth etc)
	*/
    function serviceRequestShoppingCart_post()
    {
		
    }

    /*
		8) This method shall be used get the subscriber wallets balance based on
		designated iPoints:
		- iLife
		- iHealth
		- iSavings
		- iPension
		The iPoints update/deduction API shall be called to post data into wordpress.
		The system shall always check to confirm the iPoints balance based on iPoints purpose (iLife, iHealth etc)
	*/
   public function walletRequest_post()
    {
		if(empty($this->post('mobile_number')))
        {
            $this->response('You pass no phone', 400);
        }
 
        //$user = $this->user_model->get( $this->get('phone') );
        $user = '4000';
         
        if($user){
            $this->response($user, 200); // 200 being the HTTP response code
        }else{
            $this->response('Wallet does not exist', 404);
        }
    }
    



}
?>