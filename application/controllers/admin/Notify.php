<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notify extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function member(){
		
		$uid = $this->input->get_post('uid');
		$memberInfo = $this->Member_Model->getFirstByKey($uid,'uid');
		
		$this->display();
	}
	
	
	public function team(){
		$this->display();
	}
}
