<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台 API 实现
 */

class Weixin_Xcx_Api extends Weixin_api {
    
    
    public function __construct(){
        parent::__construct();
        
    }
    
    
    /**
     * 小程序获取用户信息
     */
    public function getWeixinUserByCode($code){
    	
    	$param = array(
            'url' => "/sns/jscode2session?appid={$this->_mpConfig['appid']}&secret={$this->_mpConfig['app_secret']}&js_code={$code}&grant_type=authorization_code" ,
            'method' => 'get',
        );
        
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        return $result;
    }
    
    
    
    /**
     * 获得用户信息
     * @param type $openId 
     */
    public function getUserInfoByOpenId($openId){
    	
    	$param = array(
	        'url' => '/cgi-bin/user/info?access_token='.$this->_mpAccessToken.'&openid='.$openId.'&lang=zh_CN',
	        'method' => 'get'
	    );
	    
	    $respone = $this->request($param);
	    $result = json_decode($respone,true);
        
        return $result;
            
    	
    }
    
    
    
    /**
     * 发送模版消息给用户
     */
    public function sendTemplateMsg($data){
    	$param = array(
	        'url' => '/cgi-bin/message/wxopen/template/send?access_token='.$this->_mpAccessToken,
	        'method' => 'POST'
	    );
	    
	    $param['data'] = json_encode($data);
	    
		$respone = $this->request($param);
	    $result = json_decode($respone,true);
        
        return $result;
    	
    }
    
    
    /**
     * 获得小程序模版消息
     */
    public function getTemplateList($offset,$count){
    	
    	$param = array(
	        'url' => '/cgi-bin/wxopen/template/library/list?access_token='.$this->_mpAccessToken,
	        'method' => 'POST',
	    );
	    
	    $param['data'] = json_encode(array(
        	'offset' => $offset,
        	'count' => $count
        ));
	    
	   
		$respone = $this->request($param);
	    $result = json_decode($respone,true);
        
        return $result;
        
    }
   
   
   public function createQR($id,$path,$imgurl){
	 	
		$data = array(
		  'data'=>json_encode(array(
			  'scene'=>$id, 
			  'page'=>'pages/index/index',
			  'width'=>430,
			  'auto_color'=>false,)),
		  'method' =>"POST"
		);   	 
	
		$url = "/wxa/getwxacodeunlimit?access_token=".$this->_mpAccessToken;
		$data['url'] =$url;
		
	    $da = $this->request($data);
		
	  	if($da){
	 		$fanhuistr = file_put_contents($path,$da);
	 		$information =array(
	 			'imgurl' => $imgurl,
	 		);
	 		return array('data' => $information);
	  	}else{
	  		return false;
	  	}
	}
    
}

