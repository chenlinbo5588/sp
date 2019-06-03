<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 关注 
 */
class CompanyProfile {
    public $delegate;
    
    public function __construct(){

    }
    
    
    public function response($message){
    	
    	
    
        $welcomeText = "这是菜单1";
      
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
