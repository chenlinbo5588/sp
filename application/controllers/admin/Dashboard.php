<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();

	}

	
	public function aboutus(){
		
		$this->display('dashboard/aboutus');
	}

	
}
