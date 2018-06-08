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
        
        $this->_CI->load->model(array('Wx_Customer_Model'));
        
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
        //file_put_contents('debug3.txt',print_r($param,true));
        $respone = $this->request($param);
        
        //file_put_contents('debug3.txt',print_r($respone,true),FILE_APPEND);
        
        $result = json_decode($respone,true);
        
        return $result;
    }
    
    
    /**
     * 绑定用户
     */
    public function getBindUser($wxResp){
		
		if($wxResp['unionid']){
			$userInfo = $this->_CI->Wx_Customer_Model->getFirstByKey($wxResp['unionid'],'unionid');
			
		}else if($wxResp['openid']){
			$userInfo = $this->_CI->Wx_Customer_Model->getById(array(
				'where' => array(
					'openid' => $wxResp['openid'],
					'appid' => $this->_xcxConfig['appid']
				)
			));
		}
		
		return $userInfo;
	}
	
	
	/**
	 * 登记微信用户信息
	 */
	public function addWxUser($wxResp){
		
		$result['appid'] = $this->_xcxConfig['appid'];
        $result = array_merge($result,$wxResp);
        
        return $this->_CI->Wx_Customer_Model->_add($result,false);
	}
}

