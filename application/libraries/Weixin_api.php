<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台 API 实现
 */


require_once(WEIXIN_PATH.'errorCode.php');
require_once(WEIXIN_PATH.'sha1.php');
require_once(WEIXIN_PATH.'xmlparse.php');
require_once(WEIXIN_PATH.'pkcs7Encoder.php');
require_once(WEIXIN_PATH.'wxBizMsgCrypt.php');


class Weixin_api extends Http_Client {
    
    public $_CI = null;
    
    
    protected $_mpConfig = array();
    
    //新的 access token
    protected $_mpAccessToken = '';
    
    //老的 access token ,防止新的解密失败
    protected $_mpAccessTokenOld = '';
    
    
    public $msgCrypt = null;
    
    
    public function __construct(){
        parent::__construct();
        
        $this->_CI = get_instance();
        $this->_CI->load->config('weixin');
        
        $this->_CI->load->model(array('Mp_Ticket_Model','Recommend_Model','Recommend_Detail_Model'));
        
        $this->setBaseUrl('https://api.weixin.qq.com');
    }
    
    
    /**
     * 获得控制器
     */
    public function getCI(){
    	return $this->_CI;
    }
    
    
    public function checkSignature($token){
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        
        
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    
    
    
    /**
     * 
     */
    public function fetchToken(){
        $tickets = $this->_CI->Mp_Ticket_Model->getList(array(
            'where' => array(
            	'appid' => $this->_mpConfig['appid'],
                'gmt_create >= ' => $this->_CI->_reqtime - 10800
            ),
            'order' => 'id DESC',
            'limit' => 2
        ));
        
        if(!empty($tickets)){
        	return $tickets[0]['access_token'];
        }else{
        	//log_message('error',"Ticket已失效,更新处理异常");
            return '';
        }
    	
    }
    
    
    /**
     * 获得当前配置
     */
    public function getSetting(){
    	return $this->_mpConfig;
    }
    
    
    public function initSetting($config){
        $this->_mpConfig = $config;
        
        $this->msgCrypt = new WXBizMsgCrypt($config['token'],$config['EncodingAESKey'],$config['appid']);
        
        $this->_mpAccessToken = $this->fetchToken();


    }
    
    
    public function getAccessToken($pConfig){
        $param = array(
            'url' => '/cgi-bin/token?grant_type=client_credential&appid='.$pConfig['appid'].'&secret='.$pConfig['app_secret'],
            'method' => 'get'
        );
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        if($result['access_token']){
        	return $result;
        }else{
        	log_message('error',"获取Ticket失败,".$respone);
        	return '';
        }
    }
    
}

