<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function focuspic(){
		
		$feedback = '';
		if($this->isPostRequest()){
			
			
			
		}
		
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
}
