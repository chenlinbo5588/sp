<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台 API 实现
 */

class Weixin_Xcx_Api extends Weixin_api {
    
    private $_xcxConfig;
    
    public function __construct(){
        parent::__construct();
        
        
    }
   
    
    public function setConfig($pConfig){
    	$this->_xcxConfig = $pConfig;
    }
    
    
    /**
     * 小程序获取用户信息
     */
    public function getWeixinUserByCode($code){
    	
    	$param = array(
            'url' => "/sns/jscode2session?appid={$this->_xcxConfig['appid']}&secret={$this->_xcxConfig['app_secret']}&js_code={$code}&grant_type=authorization_code" ,
            'method' => 'get',
        );
        
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        return $result;
    }
}

