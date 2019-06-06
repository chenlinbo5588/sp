<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 关注 
 */
class Subscribe {
    public $delegate;
    
    public function __construct(){

    }
    
    
    public function response($message){
    	
    	$ci = $this->delegate->getCI();
    	
        $welcomeText = "尊敬的用户，感谢您关注".$ci->_siteSetting['site_name'].'办理业务请回复1';
        $respXML = <<< EOF
<xml>
<ToUserName><![CDATA[$message[FromUserName]]]></ToUserName>
<FromUserName><![CDATA[$message[ToUserName]]]></FromUserName>
<CreateTime>$now</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[$welcomeText]]></Content>
</xml> 
EOF;
        return $respXML;
    }
}

