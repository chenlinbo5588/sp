<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台 API 实现
 */

class Weixin_Mp_Api extends Weixin_api {
    
    
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    ///////////////////@todo 待重构/////////////////////////
    
    
    
    
    
    /**
     * http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=TYPE
     * 
     * {"errcode":0,"errmsg":"ok"}
     */
    
    public function uploadImg($file){
        
        $param = array(
            'url' => '/cgi-bin/media/uploadimg?access_token='.$this->_mpAccessToken,
            'method' => 'post',
            'data' => array(
            	'media' => '@'.$file
            )
        );
        
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        return $result;
    }
    
    
    
    /***
     * 用户菜单事件
     */
    public function user_event($postStr){
        $message = '';
        $isError =  $this->msgCrypt->decryptMsg($_GET['msg_signature'],$_GET['timestamp'],$_GET['nonce'],$postStr,$message);
        
        try {
            if(!$isError){
                $xml = new DOMDocument();
                $xml->loadXML($message);
                
                
                $rt['ToUserName'] = $xml->getElementsByTagName('ToUserName');
                $rt['FromUserName'] = $xml->getElementsByTagName('FromUserName');
                $rt['MsgType'] = $xml->getElementsByTagName('MsgType');
                $rt['Event'] = $xml->getElementsByTagName('Event');
                $rt['EventKey'] = $xml->getElementsByTagName('EventKey');

				
                $toUserName = $rt['ToUserName']->item(0)->nodeValue;
                $fromUserName = $rt['FromUserName']->item(0)->nodeValue;
                $msgType = $rt['MsgType']->item(0)->nodeValue;
                $event = $rt['Event']->item(0)->nodeValue;
                $eventKey = $rt['EventKey']->item(0)->nodeValue;
                return array('code' => 0,'ToUserName' => $toUserName,'FromUserName' => $fromUserName, 'MsgType' => $msgType,'Event' => $event , 'EventKey' => $eventKey);
            }else{
                return array('code' => ErrorCode::$ParseXmlError,'ToUserName' => null,'FromUserName' => null, 'MsgType' => null,'Event' => null, 'EventKey' => null);
            }
		} catch (Exception $e) {
			//print $e . "\n";
			return array('code' => ErrorCode::$ParseXmlError,'ToUserName' => null,'FromUserName' => null, 'MsgType' => null,'Event' => null, 'EventKey' => null);
		}
        
    }
    
    
    
    /**
     * 
     */
    public function responseMessage($message){
    	
    	$filePath = '';

    	if('CLICK' == strtoupper($message['Event'])){
            /** 自定义菜单事件 key **/
            $className = strtolower($message['EventKey']);
            
            $filePath = APPPATH.'libraries'.DIRECTORY_SEPARATOR.$this->_mpConfig['folder'].DIRECTORY_SEPARATOR.$className.'.php';
            
        }else if($message['Event']){
        	$className = strtolower($message['Event']);

			/*
            if(in_array($className,array('subscribe','unsubscribe','scan','location','view'))){
                
            }
            */
			$filePath = APPPATH.'libraries'.DIRECTORY_SEPARATOR.$this->_mpConfig['folder'].DIRECTORY_SEPARATOR.$className.'.php';
        }
        
        if(file_exists($filePath)){
            include_once($filePath);
            $responseObj = new $className();
            $responseObj->delegate = $this;
            
            $encryptMsg = '';
            $respMessage = $responseObj->response($message);
            
            if(!empty($respMessage)){
            	$return = $this->msgCrypt->encryptMsg($respMessage,$_GET['timestamp'],$_GET['nonce'],$encryptMsg);
		        if(ErrorCode::$OK == $return){
		            echo $encryptMsg;
		        }
            }else{
            	echo "";
            }
        }
    }
    
    
    /**
     * 顶部公共部分
     */
    public function getMessageTopHTML(){
    	return '<div style="color:#E47070;font-size:14px;">【点击关注】&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;</div>';
    }
    
    
    /**
     * 底部公共部分
     */
    public function getMessageBottomHTML(){
    	return '<div style="color:#E47070;font-size:14px;">点击阅读原文会有更多收获...</div><div style="color:#E47070;font-size:14px;">&darr;&darr;&darr;&darr;&darr;&darr;&darr;&darr;&nbsp;</div>';
    	
    }
    
    /**
     * http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=TYPE
     * 
     * {"errcode":0,"errmsg":"ok"}
     * 上传图文消息
     */
    public function uploadnews($info){
        
        $param = array(
            'url' => '/cgi-bin/media/uploadnews?access_token='.$this->_mpAccessToken ,
            'method' => 'post',
        );
        
        $url = base_url("wxnews/detail/id/{$info['id']}");
        
        if($info['jump_url']){
        	$url = $info['jump_url'];
        }
        
        $info['content'] = $this->getMessageTopHTML().$info['content'] . $this->getMessageBottomHTML();
        $info['content'] = addslashes(str_replace(array("\r\n", "\r", "\n"), "", $info['content']));
        
        $info['digest'] = addslashes($info['digest']);
        
        
        $param['data'] = <<< EOF
 {
   "articles": [
		 {
             "thumb_media_id":"{$info[media_id]}",
             "author":"",
			 "title":"{$info[title]}",
			 "content_source_url":"{$url}",
			 "content":"{$info[content]}",
			 "digest":"{$info[digest]}",
             "show_cover_pic":"0"
		 }
   ]
}
EOF;

        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        return $result;
    }
    
    
    /**
     * 预览消息 
     */
    public function preview($media_id){
    	
    	
    	$param = array(
            'url' => '/cgi-bin/message/mass/preview?access_token='.$this->_mpAccessToken ,
            'method' => 'post',
        );
        
        $param['data'] = <<< EOF
{
   "touser":"otBYfs1fulwVFWLKJ8naDJ5Shnjs", 
   "mpnews":{              
       "media_id":"{$media_id}"    
    },
   "msgtype":"mpnews" 
}
EOF;

        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        return $result;
    	
    }
    
    /**
     * 群发消息
     */
    public function sendMessageByOpenIds($media_id , $openids){
    	$param = array(
            'url' => '/cgi-bin/message/mass/send?access_token='.$this->_mpAccessToken ,
            'method' => 'post',
        );
        
        $ids = '';
        if(is_array($openids)){
        	$ids = json_encode($openids);
        }else{
        	$ids = '["{$openids}"]';
        }
        
        
        
        $param['data'] = <<< EOF
{
   "touser":{$ids},
   "mpnews":{              
       "media_id":"{$media_id}"    
    },
   "msgtype":"mpnews"
}
EOF;
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        return $result;
    }
    
    
    
}

