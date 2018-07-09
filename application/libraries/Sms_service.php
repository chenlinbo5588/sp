<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_service extends Base_service {

	private $_msgTemplateModel = null;
	private $_curl = null;
	
	public function __construct(){
		parent::__construct();
		
		$this->_curl = new Http_Client();
		
	}
	
	public function sendMessage($mobile,$code){
		
		//@todo 完善
		//$mobile = '17855827686';
		return true;
		
		$text="【清风官网】您的验证码是{$code}。如非本人操作，请忽略本短信";
		$data=array('text'=>$text,'apikey'=>config_item('SMS_apikey'),'mobile'=>$mobile);
		
		$resp = $this->_curl->request(array(
			'moreheader' => array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'),
			'method' => 'post',
			'url' => 'http://yunpian.com/v1/sms/send.json',
			'data' => http_build_query($data)
		));
		
		
		$json = json_decode($resp,true);
		
		if(0 == $json['code']){
			return true;
		}else{
			return false;
		}
	}
	
}
