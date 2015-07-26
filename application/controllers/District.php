<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class District extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index()
	{
		
		$this->load->library('Common_District_Service');
		$param = $this->uri->uri_to_assoc();
		
		$upid = $param['upid'];
		
		if(empty($upid)){
			$upid = 0;
		}
		
		$ds = $this->common_district_service->getDistrictByPid($upid);
		$this->jsonOutput('',$ds,86400);
	}
}
