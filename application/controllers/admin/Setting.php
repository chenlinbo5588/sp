<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	
	public function base(){
		$this->display();
	}
	
	
	
	
	public function dump(){
		$this->display();
	}
}