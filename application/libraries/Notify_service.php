<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * 
 */
class Notify_Service extends Base_Service {

	private $_reqObj = null;
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library('Weixin_api');
		
		$this->_reqObj = self::$CI->weixin_api;
	}
	
	
	public function sendEmail(){
		
		/*
		//@todo mail 模版,邮件链接
		$this->email->from('tdkc_of_cixi@163.com', '运动之家');
		$this->email->to('11111@qq.com');
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');
		
		$this->email->subject('【运动之家 邮件激活】');
		$this->email->message('尊敬的用户 '.$this->input->post('nickname').',欢迎你加入运动之家， 点击以下链接进行邮件激活,链接2小时内有效');
		if(true || $this->email->send()){
			$this->assign('mailed',true);
		}
		*/
						
	}
	
	
	/**
	 * 发送短信
	 */
	public function sendSMS($mobile,$code){
		$text="【赢金财经直播间】您的验证码是$code";
	
		$data=array('text'=>$text,'apikey'=>config_item('SMS_apikey'),'mobile'=>$mobile);
		
		
		$resp = $this->_reqObj->request(array(
			'moreheader' => array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'),
			'method' => 'post',
			'url' => 'http://yunpian.com/v1/sms/send.json',
			'data' => http_build_query($data)
		));
		
		//file_put_contents("debug.txt",print_r($resp,true));
		
		$json = json_decode($resp,true);
		return $json;
		
	}
	
	
}
