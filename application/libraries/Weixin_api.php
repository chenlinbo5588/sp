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
    
    public function __construct(){
        parent::__construct();
        
        $this->_CI = get_instance();
        $this->_CI->load->config('weixin');
        
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
    
    
}

