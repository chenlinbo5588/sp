<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	public function _remap(){
		
		$this->display('setting/upload');
	}
}