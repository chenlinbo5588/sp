<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{
		$this->display('my/index');
	}
	
	
	public function set_city()
	{
		$this->load->library('Common_District_Service');
		
		$this->assign('d1',$this->common_district_service->getDistrictByPid(0));
		$this->seoTitle('设置您的所在地');
		$this->display('my/set_city');
	}
	
}
