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
    	
    	
    
        $welcomeText = "慈溪市土地勘测规划设计院有限公司是慈溪市国土资源局所属的原国有企业（慈溪市土地勘测规划设计院）改制后的股份制民营企业。";
      
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
