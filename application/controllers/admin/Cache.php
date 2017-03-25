<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cache extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function clear(){
		
		$feedback = '';
		
		if($this->isPostRequest()){
			
			$feedback = getSuccessTip('刷新成功');
			
		}
		
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
}
