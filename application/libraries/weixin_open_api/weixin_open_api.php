<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 开放平台API
 */

class Weixin_Open_Api extends Weixin_Api {
    
    protected $_openConfig = array();
    
    public function __construct($config){
        parent::__construct();
        $this->_openConfig = $config;
    }
    
    /**
     * 验证签名
     * @return boolean 
     */
    public function checkSignature(){
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
       
        $token = $this->_openConfig['token'];
        
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
