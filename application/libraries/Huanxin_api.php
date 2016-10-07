<?php
defined('BASEPATH') OR exit('No direct script access allowed');


define('HuanXin_RESP_OK',200);

/**
 * 
 */
class Huanxin_api extends Http_Client {
	public $appid;
    public $appKey;
    public $appSecret;
    public $client_id;
    public $openType;
    
    public static $accessToken;
    
    
    public function __construct($config = array()){
    	parent::__construct();
    	$this->_CI->load->model(array('Huanxin_Token_Model','Huanxin_Model','Huanxin_Retry_Model'));
    	if($config){
    		$this->initConfig($config);
    	}
    }
    
    
    /*
     * 刷新 管理员 token
     */
    public function refreshAccessToken(){
        /**
         * 获得 AccessToken
         */
        $tickets = $this->_CI->Huanxin_Token_Model->getFirstByKey($this->appid);
        
        if(!empty($tickets)){
        	self::$accessToken = $tickets['access_token'];
        	
        	//防止对方已经过期， 这里先提前一天重新更新
        	if(($tickets['gmt_modify'] + $tickets['expires_in'] + 86400) <= $this->_CI->input->server('REQUEST_TIME')){
        		//过期了
        		$resp = $this->getAccessToken();
        		
        		//var_dump($resp);
        		self::$accessToken = $resp['access_token'];
        		
        		if($resp['access_token']){
        			$this->_CI->Huanxin_Token_Model->update($resp,array('id' => $this->appid));
        		}
        	}
        }
    }
    
    
    public function initConfig($config){
    	$this->appid = $config['appid'];
    	$this->appKey = $config['appkey'];
    	$this->baseURL = $config['url'].str_replace('#','/',$this->appKey);
    	$this->appSecret = $config['secret'];
    	$this->client_id = $config['client_id'];
    	$this->open_type = $config['open_type'];
    }
    
    
    public function getDefaultHttpHeader(){
    	if($this->open_type){
    		return array(
				'Content-Type: application/json',
    			'Authorization: Bearer '.self::$accessToken
    		);
    	}else{
    		return array('Content-Type: application/json');
    	}
    }
    
    
    
    /**
     * 
     */
    public function createId($uid,$mobile,$nickname,$password = ''){
    	$str = array(
    		'username' => $mobile,
    		'nickname' => $nickname,
    		'password' => md5(config_item('encryption_key').$password)
    	);
    	
    	$param = array(
    		'url' => '/users',
    		'method' => 'post',
    		'data' => json_encode($str)
    	);
    	
    	$resp = $this->request($param);
    	$json = json_decode($resp,true);
    	
    	//file_put_contents('debug.txt',print_r($json,true));
    	
    	if(!$json['entities']){
    		if($json['error'] == 'duplicate_unique_property_exists'){
    			$this->_CI->Huanxin_Retry_Model->deleteByWhere(array(
	    			'uid' => $uid
	    		));
    		}else{
    			$this->_CI->Huanxin_Retry_Model->_add(array_merge(array(
	    			'uid' => $uid,
	    			'action' => 'create'
	    		),$str),true);
    		}
    	}else{
    		$this->_CI->Huanxin_Model->_add(array_merge(array(
    			'uid' => $uid
    		),$str),true);
    	}
    	
    	return $json;
    }
    
    /**
     * 获得管理员 token
     */
    public function getAccessToken(){
    	$str = '{"grant_type":"client_credentials","client_id":"'.$this->client_id.'","client_secret":"'.$this->appSecret.'"}';
    	
    	$param = array(
    		'url' => '/token',
    		'method' => 'post',
    		'data' => $str
    	);
    	
    	$json = $this->request($param);
    	return json_decode($json,true);
    	
    }
    
    /**
     * 更新密码
     */
    public function updatePassword($uid,$username,$newpassword){
    	$str = array(
    		'newpassword' => md5(config_item('encryption_key').$newpassword)
    	);
    	
    	$param = array(
    		'url' => "/users/{$username}/password",
    		'method' => 'post',
    		'data' => json_encode($str)
    	);
    	
    	$resp = $this->request($param);
    	$json = json_decode($resp,true);
    	
    	if($json['action'] != 'set user password'){
    		$this->_CI->Huanxin_Retry_Model->_add(array_merge(array(
    			'uid' => $uid,
    			'password' => $str['newpassword'],
    			'action' => 'password'
    		),$str),true);
    	}
    	
    	return $json;
    }
    
    /**
     * 发送文本消息
     */
    public function sendText($userList,$message = '',$from = ''){
    	$str = array(
    		"target_type" => "users",
    		"target" => $userList, // 注意这里需要用数组，数组长度建议不大于20，即使只有一个用户，
                                   // 也要用数组 ['u1']，给用户发送时数组元素是用户名，给群组发送时  
                                   // 数组元素是groupid
           "msg" => array(
		        "type" => "txt",
		        "msg" => $message //消息内容，参考[[start:100serverintegration:30chatlog|聊天记录]]里的bodies内容
		   )
    	);
    	
    	if($str){
    		$str['from'] = $from;
    	}
    	
    	$param = array(
            'url' => '/messages',
            'method' => 'post',
            'data' => json_encode($str)
        );
        
        $respone = $this->request($param);
        
        return json_decode($respone,true);
    }
}
