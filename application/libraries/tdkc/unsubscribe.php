<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 关注 
 */
class Unsubscribe {
    public $delegate;
    
    public function __construct(){

    }
    
    
    public function response($message){
    	
    	$ci = $this->delegate->getCI();
    	
    	$customer = $this->delegate->getUserInfoByOpenId($message['FromUserName']);
    	$ci->Wx_Customer_Model->update(array('subscribe' => 0), array('openid' => $customer['openid']));
    	
        
        return "";
    }
}
