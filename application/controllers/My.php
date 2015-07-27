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
					'd1' => $this->input->post('d1'),
					'd2' => $this->input->post('d2'),
					'd3' => $this->input->post('d3'),
					'd4' => intval($this->input->post('d4'))
				));
				
				$this->jsonOutput('设置成功',array(
					'url' => site_url('my/index')
				));
			}else{
				$this->jsonOutput('',array(
					'formhash' => $this->security->get_csrf_hash(),
					'errormsg' => $this->form_validation->error_array()
				));
			}
		}else{
			$this->assign('d1',$this->common_district_service->getDistrictByPid(0));
			$this->seoTitle('设置您的所在地');
			$this->display('my/set_city');
		}
		
		
	}
	
}
