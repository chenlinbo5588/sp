<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Ydzj_Controller {
	
	private $_uid = 0;
	private $_userInfo = null;
	
	
	
	public function __construct(){
		parent::__construct();
		
		$ar = $this->uri->segment_array();
		$this->_uid = empty($ar[3]) == true ? 0 : $ar[3];
		
		$this->load->library('Member_Service');
	}
	
	
	public function info()
	{	
		
		
		$this->display('user/info');
		//$this->member_service->
		
	}
	
	
}
