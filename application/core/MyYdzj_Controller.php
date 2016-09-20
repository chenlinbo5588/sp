<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	
	private $_pushObject;
	
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			//$this->session->unset_userdata(array($this->_lastVisitKey,$this->_profileKey));
			redirect('member/login');
		}
		
		
		$this->_pushObject = $this->base_service->getPushObject();
		$this->assign('pushConfig',config_item('huanxin'));
		
	}
}



