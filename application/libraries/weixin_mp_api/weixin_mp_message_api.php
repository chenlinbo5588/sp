<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台API
 */

class Weixin_Mp_Message_Api extends Weixin_Mp_Api {
    
    private $_menuStr = null;
    
    public function __construct($config){
        parent::__construct($config);
    }
    
    public function getMessageTopHTML(){
    	return '<div style="color:#E47070;font-size:14px;">【点击关注】&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;&uarr;</div>';
    }
    
    
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
