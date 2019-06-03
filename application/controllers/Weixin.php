<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 消息控制
 */
class Weixin extends Ydzj_Controller {
	
    public function __construct(){
        parent::__construct();
        
    	$this->load->library('Weixin_mp_api');
    }
    
    /**
     * 更新 AccessToken ,每小时刷新一次
     */
         
    public function refreshTicket(){
    	if(!$this->input->is_cli_request()){
    		die(0);
    	}
    	
    	
    	$groupName = '';
    	if(empty($_SERVER['argv'][3])){
    		die(0);
    	}
    	
    	
    	$groupName = $_SERVER['argv'][3];
    	if(!in_array($groupName,array('mp','mp_xcxTdkc'))){
    		die(0);
    	}
    	
    	
    	$this->Mp_Ticket_Model->deleteByCondition(array(
        	'where' => array(
        		'gmt_create <=' => $this->_reqtime - 86400
        	)
        ));
        
        $mpConfig = config_item($groupName);
    	
    	$tickets = $this->Mp_Ticket_Model->getList(array(
            'where' => array(
            	'appid' => $mpConfig['appid'],
                'gmt_create >= ' => $this->_reqtime - 3600
            ),
            'order' => 'id DESC',
            'limit' => 2
        ));
        
        if(empty($tickets)){
        	
        	echo "need refresh\n";
	        $ticket = $this->weixin_mp_api->getAccessToken($mpConfig);
	        
	        print_r($ticket);
	    	if($ticket){
	    		$row = $this->Mp_Ticket_Model->_add(array('appid' => $mpConfig['appid'], 'access_token' => $ticket['access_token'],'expire_in' => $ticket['expires_in'], 'gmt_create' => $this->_reqtime));
	    	}
        }else{
        	
        	echo "ticket is fresh\n";
        }
    }
    
    
    public function index()
    {
        
        //echo md5(mt_rand());
        //$this->display();
    }
    
    
    /**
     * 创建刷新菜单
     */
    public function menu(){
    	if($this->input->is_cli_request()){
    		
    		
    		
    	}
    }
    
    
    public function devmessage(){
    	$mpConfig = config_item('mp_test');
    	
        $this->weixin_mp_api->initSetting($mpConfig);
        
        /*
    	if($this->weixin_mp_api->checkSignature($mpConfig['token'])){
    		echo $_GET["echostr"];
    	}
    	*/
    	
        if($this->weixin_mp_api->checkSignature($mpConfig['token'])){
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            $message = $this->Weixin_mp_api->user_event($postStr);
            $this->weixin_mp_api->responseMessage($message);
        }
    	
    }
    
    
    
    /**
     * 该URL用于接收已授权公众号的消息和事件，消息内容、消息格式、签名方式、加密方式与普通公众号接收的一致，
     * 唯一区别在于签名token和加密symmetric_key使用的是服务方申请时所填写的信息。
     * 由于消息具体内容不会变更，故根据消息内容里的ToUserName，服务方是可以区分出具体消息所属的公众号。
     * 另外，考虑到服务需要接收大量的授权公众号的消息，为了便于做业务分流和业务隔离，必须提供如下形式的url：www.abc.com/aaa/$APPID$/bbb/cgi，
     * 其中$APPID$在最终信息推送时会替换成推送信息所属的已授权公众号的appid。
     */
    public function message(){
        
        ///signature=ce40a74f36a702b9754b9b120623e1c749e81315&timestamp=1418015929&nonce=2097909623&encrypt_type=aes&msg_signature=0e35c960849aa61f38f73fae23bc9e667644dce9 
        
        
        $mpConfig = config_item('mp');
        $this->weixin_mp_api->initSetting($mpConfig);
		/*
    	if($this->weixin_mp_api->checkSignature($mpConfig['token'])){
    		echo $_GET["echostr"];
    	}
		die(0);
		*/
        
        //valid signature , option
        if($this->weixin_mp_api->checkSignature($mpConfig['token'])){
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            
            //?signature=21b04411b91ca74175cf9a4f2bb56ccaa712bb08&timestamp=1418017063&nonce=459089760&encrypt_type=aes&msg_signature=5f0b52560150baab17196a5c571ea1f7633a7f3d
            /*
            $postStr = '<xml>
    <ToUserName><![CDATA[gh_798a07a654ad]]></ToUserName>
    <Encrypt><![CDATA[edGy0veIVq0X6CWPPOIWHwBu2c5IZ8TqZcCzJN7j5WHVcHSd9TWpYdW+cb6prxPWmb9EqK3eXhUM21o4rS8redlTjI0l2PeZyOm9sPBOUIOJQKxF4pIJYCz2XMxeD9GSBJgXS1ixLL/oQSotq938LUKXZ1FCQelduQmaG1zAdWz5pX1udUC9oTZQtpZohjtwV4R7mMFTMWz/NdTk0fwCU/s5mj6uKM3WcYFDiL1767taJW0vejhZj1bRiVyFWwn84rGbfaXHseeMeodAj0ivYNmWgT09Yz1SMwQaHetsXpK3mTGvEogmK8B1kKfdg4H30102U4nIpsbs+MlxD7nRNW9NtA1ONKvjUXK/BVjQX0UfYe4SsD7/dINId8MS2hNwu0D1uQ1LaLnvdf6WJkCxK7vxWXLhqx+vE8CtV9Iym4dD/6rfGqIdEbHrVslclCqlYthpWcsiftyNwhM+NxIElw==]]></Encrypt>
</xml>';
            */
            $message = $this->weixin_mp_api->user_event($postStr);

            $this->weixin_mp_api->responseMessage($message);
        }
    }
    
}
