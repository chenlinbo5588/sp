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
    	$this->_CI->load->model(array('Huanxin_Token_Model','Huanxin_Retry_Model'));
    	if($config){
    		$this->initConfig($config);
    	}
    }
    
    
    /*
     * 刷新
     */
    public function refreshAccessToken(){
        /**
         * 获得 AccessToken
         */
        $tickets = $this->_CI->Huanxin_Token_Model->getFirstByKey($this->appid);
        
        if(!empty($tickets)){
        	self::$accessToken = $tickets['access_token'];
        	
        	if(($tickets['gmt_modify'] + $tickets['expires_in']) <= $this->_CI->input->server('REQUEST_TIME')){
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
    	$str = json_encode(array(
    		'username' => $mobile,
    		'nickname' => $nickname,
    		'password' => $password
    	));
    	
    	$param = array(
    		'url' => '/users',
    		'method' => 'post',
    		'data' => $str
    	);
    	
    	$resp = $this->request($param);
    	$json = json_decode($resp,true);
    	
    	//file_put_contents('debug.txt',print_r($json,true));
    	
    	if($json['entities']){
    		/*
    		$this->_CI->Huanxin_Model->_add(array(
				'uid' => $uid,
				'username' => $json['entities'][0]['username']
			),true);
			*/
    	}else{
    		if($json['error'] == 'duplicate_unique_property_exists'){
    			$this->_CI->Huanxin_Retry_Model->deleteByWhere(array(
	    			'uid' => $uid
	    		));
    		}else{
    			$this->_CI->Huanxin_Retry_Model->_add(array(
	    			'uid' => $uid
	    		),true);
    		}
    	}
    	
    	return $json;
    }
    
    
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
    
    
    public function getToken($mobile){
    	$str = http_build_query(array(
    		'accid' => $mobile
    	));
    	
    	$param = array(
            'url' => '/user/refreshToken.action',
            'method' => 'post',
            'data' => $str
        );
        
        $respone = $this->request($param);
        return json_decode($respone,true);
    }
    
}
