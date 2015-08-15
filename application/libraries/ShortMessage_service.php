<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ShortMessage_Service extends Base_Service {

	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 发送手机短信
	 * 
	 * @todo 需要添加实现
	 */
	public function sendMessage($phone,$content = ''){
		return true;
	}
	
	
	
}
