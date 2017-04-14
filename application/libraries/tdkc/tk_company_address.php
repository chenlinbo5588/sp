<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tk_Company_Address {
    public $delegate;
    
    public function __construct(){
        
    }
    
    /**
     *
     * @param type $message 
     */
    public function response($message){
        
        $now = time();
        
        
        $contactsText = <<< EOF
测绘联系电话:
袁辉 13968239000 569000
陈赞波 13738464688 564688
规划联系电话：
诸寅娣 13506746276 566276
严焕强 13586788858 568858
房、产证待办：
陈劲松 13958264225 564225
EOF;
        
        
        $respXML = <<< EOF
<xml>
<ToUserName><![CDATA[$message[FromUserName]]]></ToUserName>
<FromUserName><![CDATA[$message[ToUserName]]]></FromUserName>
<CreateTime>$now</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[$contactsText]]></Content>
</xml> 
EOF;

		return $respXML;
    }
}
