<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 关注 
 */
class viewNews {
    public $delegate;
    
    public function __construct(){

    }
    
    
    public function response($message){
        $param = '';
        $number = 0;
    	foreach($message['newinfo'] as $key => $value){
			$item = <<< EOF
		   <item>
      <Title><![CDATA[$value[title]]]></Title>
      <PicUrl><![CDATA[$value[img_url]]]></PicUrl>
      <Url><![CDATA[$value[url]]]></Url>
		</item>
EOF;
			$param = $param.$item;
			$number++ ;
		}
 	
    	//$ci = $this->delegate->getCI();
    	
        $welcomeText = "这是新闻";//.$ci->_siteSetting['site_name'];
        $respXML = <<< EOF
<xml>
<ToUserName><![CDATA[$message[FromUserName]]]></ToUserName>
<FromUserName><![CDATA[$message[ToUserName]]]></FromUserName>
<CreateTime>$now</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
  <ArticleCount>$number</ArticleCount>
  <Articles>
$param
  </Articles>
</xml> 
EOF;
        return $respXML;
    }
}
