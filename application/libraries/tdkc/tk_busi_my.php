<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tk_Busi_My {
    
    public $delegate;
    
    public function __construct(){

    }
    
    /**
     *
     * @param type $message 
     */
    public function response($message){
    	
    	$now = time();
    	$tips = "";
    	
        $customer = $this->delegate->getUserInfoByOpenId($message['FromUserName']);
        
        $ci = $this->delegate->getCI();
        $encrypted_openid = $ci->encrypt->encode($customer['openid']);
        
        $bindUrl = site_url("bind/step2").'?openid='.urlencode($encrypted_openid);
        
        if(empty($customer['mobile'])){
            $tips = <<< EOF
您还没有绑定手机哦,绑定手机号码之后就可以查到待领取资料情况。
<a href="$bindUrl">点击这里，立即绑定</a>
EOF;
        }else{
        	
        	/**
        	 * 获得待领取资料
        	 * 条件是已经完成的 没有被领取的
        	 */
        	$ci->load->model('Taizhang_Model');
        	$docs = $ci->Taizhang_Model->getList(array(
        		'select' => 'name,nature',
        		'where' => array(
        			'contacter_mobile' => $customer['mobile'],
        			'complete_time !=' => '0000-00-00',
        			'get_doc' => 0
        		),
        		'order' => 'createtime DESC '
        	));
        	
        	$text = '';
        	if($docs){
        		$tempDoc = array();
        		foreach($docs as $dk => $doc){
        			$tempDoc[] = ($dk + 1)."：{$doc['name']}\n";
        		}
        		$text = "待领取资料如下:\n";
        		$text .= implode("",$tempDoc);
        		$text .= "请您到浙江省宁波市慈溪市孙塘南路98号(金一路口交叉口)二楼业务大厅领取资料";
        		
        	}else{
        		$text = "您没有需要待领取的资料。感谢对我们工作的支持！";
        	}
        	
            $tips = <<< EOF
$text
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
