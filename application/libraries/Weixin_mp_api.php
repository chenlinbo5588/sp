<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台 API 实现
 */

class Weixin_Mp_Api extends Http_Client {
    
    protected $_mpConfig = array();
    
    //新的 access token
    public static $_mpAccessToken = '';
    
    //老的 access token ,防止新的解密失败
    public static $_mpAccessTokenOld = '';
    
    
    public $msgCrypt = null;
    private $_menuStr = null;
    
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * 获得控制器
     */
    public function getCI(){
    	return $this->_CI;
    	
    }
    
    public function initSetting($config){
        $this->_mpConfig = $config;
        
        $this->msgCrypt = new WXBizMsgCrypt($config['token'],$config['EncodingAESKey'],$config['appid']);
        
        $this->_CI->load->model('Mp_Ticket_Model');
        $this->_CI->load->model('Wx_Customer_Model');
        
        $this->_CI->Mp_Ticket_Model->deleteByWhere(array(
        	'gmt_create <=' => $this->_CI->_reqtime - 86400
        ));
        
        /**
         * 获得 AccessToken ,每小时刷新一次
         */
        $tickets = $this->_CI->Mp_Ticket_Model->getList(array(
            'where' => array(
            	'appid' => $this->_mpConfig['appid'],
                'gmt_create >= ' => $this->_CI->_reqtime - 3600
            ),
            'order' => 'id DESC',
            'limit' => 1
        ));
        
        if(!empty($tickets)){
        	Weixin_Mp_Api::$_mpAccessToken = $tickets[0]['access_token'];
        }else{
            $this->getAccessToken();
        }
        
        $this->_menuStr = <<< EOF
                {
    "button": [
        {
            "name": "慈溪土勘", 
            "sub_button": [
                {
                    "type": "click", 
                    "name": "公司简介", 
                    "key": "tk_company_intro"
                },
                {
                    "type": "click", 
                    "name": "联系电话",
                    "key": "tk_company_address"
                },
                {
                    "type": "click", 
                    "name": "新闻纪要",
                    "key": "tk_company_news"
                }
            ]
        },
        {
            "name": "业务查询", 
            "sub_button": [
                {
                    "type": "click", 
                    "name": "我当前的业务",
                    "key": "tk_busi_my"
                    
                },
                {
                    "type": "click", 
                    "name": "土地相关业务查询", 
                    "key": "tk_busi_tudi"
                    
                }
            ]
        },
        {
            "name": "更多", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "慈溪之窗", 
                    "url": "http://www.cxmap.cn/index.php?m=house&q=new"
                }
            ]
        }
        
    ]
}
EOF;

    }
    
    public function checkSignature(){
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        
        $token = $this->_mpConfig['token'];
        
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
    
    
    public function getAccessToken(){
        $param = array(
            'url' => '/cgi-bin/token?grant_type=client_credential&appid='.$this->_mpConfig['appid'].'&secret='.$this->_mpConfig['app_secret'],
            'method' => 'get'
        );
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        if($result['access_token']){
            Weixin_Mp_Api::$_mpAccessToken = $result['access_token'];
            
            $row = $this->_CI->Mp_Ticket_Model->_add(array('appid' => $this->_mpConfig['appid'], 'access_token' => $result['access_token'],'expire_in' => $result['expires_in'], 'gmt_create' => $this->_CI->_reqtime));
        }
    }
    
    
    
    
    /**
     * 获得用户信息
     * @param type $openId 
     */
    public function getUserInfoByOpenId($openId){
        $customer =  $this->_CI->Wx_Customer_Model->getFirstByKey($openId,'openid');
        if(empty($customer)){
            $param = array(
                'url' => '/cgi-bin/user/info?access_token='.Weixin_Mp_Api::$_mpAccessToken.'&openid='.$openId.'&lang=zh_CN',
                'method' => 'get'
            );
            //file_put_contents("debug.txt",print_r($param,true),FILE_APPEND);
            $respone = $this->request($param);
            $result = json_decode($respone,true);
        }
        
        //file_put_contents("debug.txt",print_r($result,true),FILE_APPEND);
        if(empty($customer) && !empty($result)){
            $this->_CI->Wx_Customer_Model->_add($result,true);
            $customer = $result;
        }else if(($this->_CI->_reqtime - $customer['gmt_modify']) >  86400 ){
            //自动更新
            $this->_CI->Wx_Customer_Model->update($result, array('openid' => $openId));
        }
        
        return $customer;
    }
    
    
    /**
     * http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=TYPE
     * 
     * {"errcode":0,"errmsg":"ok"}
     */
    
    public function uploadMedia($file , $type = 'image'){
        
        $param = array(
            'url' => 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.Weixin_Mp_Api::$_mpAccessToken .'&type='.$type,
            'method' => 'post',
            'data' => array(
            	'media' => '@'.$file
            )
        );
        
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        return $result;
    }
    
    /**
     * https://api.weixin.qq.com/cgi-bin/menu/create?access_token=ACCESS_TOKEN
     * 
     * {"errcode":0,"errmsg":"ok"}
     */
    
    public function menu_create(){
        
        $param = array(
            'url' => '/cgi-bin/menu/create?access_token='.Weixin_Mp_Api::$_mpAccessToken,
            'method' => 'post',
            'data' => $this->_menuStr
        );
        //print_r($param);
        /**
         * Array ( [url] => /cgi-bin/menu/create?access_token=JduZsXEethGKUBQ-CzMONF61IWOIYsCCiWmc11Oi4Yw3LX0nOGqHSi1xgQp5_FiKZVO9KgFSKB4yJyfsoO-SgmcvT85PKG8qwMjx6JxkQsA [method] => post [data] => { "button": [ { "name": "鎱堟邯鍦熷牚", "sub_button": [ { "type": "click", "name": "鍏徃绠€浠�", "key": "company_intro" }, { "type": "click", "name": "鍥㈤槦鎴愬憳", "key": "team_intro" } ] } ] } ) string(27) 
         * 
         * "{"errcode":0,"errmsg":"ok"}" 1
         */
        
        
        $respone = $this->request($param);
        //var_dump($respone);
        $result = json_decode($respone,true);
        
        return $result;
    }
    
    
    /***
     * 用户菜单事件
     */
    public function user_event($postStr){
        $message = '';
        $isError =  $this->msgCrypt->decryptMsg($_GET['msg_signature'],$_GET['timestamp'],$_GET['nonce'],$postStr,$message);
        //file_put_contents("debug.txt",$message);
        
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
			$filename = APPPATH.'libraries'.DIRECTORY_SEPARATOR.$this->_mpConfig['folder'].DIRECTORY_SEPARATOR.$className.'.php';
        }
        
        if(file_exists($filePath)){
            include_once($filePath);
            $responseObj = new $className();
            $responseObj->delegate = $this;
            
            $encryptMsg = '';
	        $return = $this->msgCrypt->encryptMsg($responseObj->response($message),$_GET['timestamp'],$_GET['nonce'],$encryptMsg);
	        
	        if(ErrorCode::$OK == $return){
	            echo $encryptMsg;
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
            'url' => '/cgi-bin/media/uploadnews?access_token='.Weixin_Mp_Api::$_mpAccessToken ,
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
            'url' => '/cgi-bin/message/mass/preview?access_token='.Weixin_Mp_Api::$_mpAccessToken ,
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
            'url' => '/cgi-bin/message/mass/send?access_token='.Weixin_Mp_Api::$_mpAccessToken ,
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

