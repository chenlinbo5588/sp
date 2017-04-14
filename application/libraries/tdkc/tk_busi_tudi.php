<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tk_Busi_Tudi {
    
    public function __construct(){

    }
    
    /**
     *
     * @param type $message 
     */
    public function response($message){
    	$now = time();
    	$tips = "";
    	
        $ci = $this->delegate->getCI();
        $customer = $this->delegate->getUserInfoByOpenId($message['FromUserName']);
        
        $encrypted_openid = $ci->encrypt->encode($customer['openid']);
        
        $bindUrl = site_url("bind/step2").'?openid='.urlencode($encrypted_openid);
        if(empty($customer['mobile'])){
            $tips = <<< EOF
您还没有绑定手机哦,绑定手机号码之后可以进行相关业务查询。
<a href="$bindUrl">点击这里，立即绑定</a>
EOF;
        }else{
            $tips = <<< EOF
即将上线，敬请期待!
EOF;
        }
        

        $respXML = <<< EOF
<xml>
<ToUserName><![CDATA[$message[FromUserName]]]></ToUserName>
<FromUserName><![CDATA[$message[ToUserName]]]></FromUserName>
<CreateTime>$now</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[$tips]]></Content>
</xml> 
EOF;
        return $respXML;
    }
    
}
?>
