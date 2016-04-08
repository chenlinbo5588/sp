<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Authority extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function user(){
		$this->display();
	}
	
	
	public function user_add(){
		$this->display();
	}
	
	
	public function role(){
		$this->display();
	}
	
	
	public function role_add(){
		$this->display();
	}
	
}
