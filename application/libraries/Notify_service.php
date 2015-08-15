<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * 
 */
class Notify_Service extends Base_Service {


	public function __construct(){
		parent::__construct();
		
	}
	
	
	
	public function sendEmail(){
		
		/*
		//@todo mail 模版,邮件链接
		$this->email->from('tdkc_of_cixi@163.com', '运动之家');
		$this->email->to('104071152@qq.com');
		//$this->email->cc('another@another-example.com');
		//$this->email->bcc('them@their-example.com');
		
		$this->email->subject('【运动之家 邮件激活】');
		$this->email->message('尊敬的用户 '.$this->input->post('nickname').',欢迎你加入运动之家， 点击以下链接进行邮件激活,链接2小时内有效');
		if(true || $this->email->send()){
			$this->assign('mailed',true);
		}
		*/
						
	}
	
	
	
	
}
