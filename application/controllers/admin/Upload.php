<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	public function param(){
		
		$this->display();
	}
	
	public function default_thumb(){
		
		$this->display();
	}
	
	public function login(){
		
		$this->display();
	}
	
}
