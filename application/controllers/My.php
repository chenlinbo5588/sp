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
		
		if($this->isPostRequest()){
			
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('d1','省','required|is_natural_no_zero');
			$this->form_validation->set_rules('d2','市','required|is_natural_no_zero');
			$this->form_validation->set_rules('d3','县','required|is_natural_no_zero');
			
			if($this->input->post('d4')){
				$this->form_validation->set_rules('d4','街道/镇乡','is_natural_no_zero');
			}
			
			if($this->form_validation->run() !== FALSE){
				$this->load->library('Member_Service');
				$result = $this->member_service->set_city(array(
					'uid' => $this->_profile['memberinfo']['uid'],
					'd1' => intval($this->input->post('d1')),
					'd2' => intval($this->input->post('d2')),
					'd3' => intval($this->input->post('d3')),
					'd4' => intval($this->input->post('d4'))
				));
				
				$this->member_service->refreshProfile($this->_profile['memberinfo']['email']);
				
				$url = $this->input->post('returnUrl');
				if(empty($url) || !isLocalUrl($url)){
					$url = site_url('my/index');
				}
				
				$this->jsonOutput('设置成功',array(
					'url' => str_replace("+", "%20", $url)
				));
				
			}else{
				$this->jsonOutput('',array(
					'formhash' => $this->security->get_csrf_hash(),
					'errormsg' => $this->form_validation->error_array()
				));
			}
		}else{
			
			$ds = array(
				'd1' => $this->_profile['memberinfo']['d1'],
				'd2' => $this->_profile['memberinfo']['d2'],
				'd3' => $this->_profile['memberinfo']['d3'],
				'd4' => $this->_profile['memberinfo']['d4']
			);
			
			//print_r($ds);
			$this->assign('d1',$this->common_district_service->getDistrictByPid(0));
			
			if($ds['d1'] > 0){
				$this->assign('d2',$this->common_district_service->getDistrictByPid($ds['d1']));
			}
			
			if($ds['d2'] > 0){
				$this->assign('d3',$this->common_district_service->getDistrictByPid($ds['d2']));
			}
			
			if($ds['d3'] > 0){
				$this->assign('d4',$this->common_district_service->getDistrictByPid($ds['d3']));
			}
			
			$this->seoTitle('设置您的所在地');
			$this->display('my/set_city');
		}
		
	}
	
}
