<?php
defined('BASEPATH') OR exit('No direct script access allowed');


define('YunXin_RESP_OK',200);

/**
 * 
 */
class Yunxin_api extends Http_Client {
    
    public $appKey;
    
    public function __construct(){
    	parent::__construct();
    	
    	$ci = get_instance();
    	
    	$ci->load->config('yunxin');
    	$yunxin = config_item('yunxin');
    	$this->_baseURL = $yunxin['url'];
    	$this->appKey = $yunxin['appkey'];
    	$this->appSecret = $yunxin['secret'];
    }
    
    
    
    public function getDefaultHttpHeader(){
    	$headers = $this->custom_header();
    	$headers[] = 'Content-Type: application/x-www-form-urlencoded;charset=utf-8';
    	
    	
    	//print_r($headers);
    	return $headers;
    	
    }
    
    private function custom_header(){
    	$moreParam = array(
    		'AppKey' => $this->appKey,
    		'Nonce' => random_string('alnum',10),
    		'CurTime' => time(),
    	);
    	
    	$moreParam['CheckSum'] = sha1($this->appSecret.$moreParam['Nonce'].$moreParam['CurTime']);
    	
    	$headers = array();
    	foreach($moreParam as $key => $value){
    		$headers[] = "{$key}: {$value}";
    	}
    	
    	return $headers;
    }
    
    
    public function createId($mobile,$nickname){
    	$str = http_build_query(array(
    		'accid' => $mobile,
    		'name' => $nickname
    	
    	));
    	
    	$param = array(
    		'url' => 'user/create.action',
    		'method' => 'post',
    		'data' => $str
    	);
    	
    	$json = $this->request($param);
    	return json_decode($json,true);
    	
    	
    	/*
    	if($resp['code'] == YunXin_RESP_OK){
    		return $resp['info'];
    	}else if ($resp['desc'] == 'already register') {
    		return true;
    	}else{
    		return false;
    	}
    	*/
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
