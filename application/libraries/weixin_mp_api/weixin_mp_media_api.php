<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台API
 */

class Weixin_Mp_Media_Api extends Weixin_Mp_Api {
    
    private $_menuStr = null;
    
    public function __construct($config){
        parent::__construct($config);
    }
    
    /**
     * http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type=TYPE
     * 
     * {"errcode":0,"errmsg":"ok"}
     */
    
    public function uploadMedia($file , $type = 'image'){
        
        $param = array(
            'url' => 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.Weixin_Mp_Api::$_mpAccessToken .'&type='.$type,
            'method' => 'post',
            'data' => array(
            	'media' => '@'.$file
            )
        );
        
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        return $result;
    }
}

