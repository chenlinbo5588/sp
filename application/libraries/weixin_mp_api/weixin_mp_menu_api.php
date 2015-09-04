<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台API
 */

class Weixin_Mp_Menu_Api extends Weixin_Mp_Api {
    
    private $_menuStr = null;
    
    public function __construct($config){
        parent::__construct($config);
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
    
    
    public function user_event($postStr){
        $message = '';
        $isError =  $this->_msgCrypt->decryptMsg($_GET['msg_signature'],$_GET['timestamp'],$_GET['nonce'],$postStr,$message);
        //file_put_contents("debug.txt",$message);
        if($isError){
            $this->log(config_item('log_threshold'));
        }
        
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
}
