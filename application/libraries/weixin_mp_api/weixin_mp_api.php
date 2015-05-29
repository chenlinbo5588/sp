<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 公众平台API
 */

class Weixin_Mp_Api extends Weixin_Api {
    
    protected $_mpConfig = array();
    public static $_mpAccessToken = '';
    public $_msgCrypt = null;
    
    public function __construct($config){
        parent::__construct();
        $this->_mpConfig = $config;
        $this->_msgCrypt = new WXBizMsgCrypt($config['token'],$config['EncodingAESKey'],$config['appid']);
        
        $this->_CI->load->model('Mp_Ticket_Model');
        $this->_CI->load->model('Customer_Model');
        $this->_CI->load->helper('url');
        
        
        $this->_CI->Mp_Ticket_Model->deleteByWhere(array(
        	'gmt_create <=' => time() - 86400
        ));
        
        /**
         * 获得 AccessToken 
         */
        $tickets = $this->_CI->Mp_Ticket_Model->getList(array(
            'where' => array(
                'gmt_create >= ' => time() - 7200
            ),
            'limit' => 1
        ));
        
        if(!empty($tickets['data'])){
            Weixin_Mp_Api::$_mpAccessToken = $tickets['data'][0]['access_token'];
        }else{
            $this->getAccessToken();
        }
    }
    
    public function checkSignature(){
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        
        $token = $this->_mpConfig['token'];
        
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    
    
    public function getAccessToken(){
        $param = array(
            'url' => '/cgi-bin/token?grant_type=client_credential&appid='.$this->_mpConfig['appid'].'&secret='.$this->_mpConfig['app_secret'],
            'method' => 'get'
        );
        $respone = $this->request($param);
        $result = json_decode($respone,true);
        
        if($result['access_token']){
            Weixin_Mp_Api::$_mpAccessToken = $result['access_token'];
            $row = $this->_CI->Mp_Ticket_Model->add(array('access_token' => $result['access_token'],'expire_in' => $result['expires_in'], 'gmt_create' => time()));
        }
    }
    
    /**
     * 获得用户信息
     * @param type $openId 
     */
    public function getUserInfoByOpenId($openId){
        $customer =  $this->_CI->Customer_Model->queryById($openId,'openid');
        if(empty($customer)){
            $param = array(
                'url' => '/cgi-bin/user/info?access_token='.Weixin_Mp_Api::$_mpAccessToken.'&openid='.$openId.'&lang=zh_CN',
                'method' => 'get'
            );
            //file_put_contents("debug.txt",print_r($param,true),FILE_APPEND);
            $respone = $this->request($param);
            $result = json_decode($respone,true);
        }
        
        //file_put_contents("debug.txt",print_r($result,true),FILE_APPEND);
        if(empty($customer) && !empty($result)){
            $this->_CI->Customer_Model->add($result);
            $customer = $result;
        }else if((time() - $customer['gmt_modify']) >  86400 ){
            //自动更新
            $this->_CI->Customer_Model->update($result);
        }
        
        return $customer;
    }
}
?>
