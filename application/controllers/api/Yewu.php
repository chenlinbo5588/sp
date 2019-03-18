<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yewu extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Wuye_service','Basic_data_service');
	}
	
	public function setYewu(){
		if($this->userInfo){
			
			$workCategoryList = WorkCategory::$typeName;
			$mobile = $this->postJson['mobile'];
			$workCategory = $this->postJson['work_category']; 
			$realName = $this->postJson['real_name']; 
			$yewuDescribe = $this->postJson['yewu_describe']; 
			$serviceArea = $this->postJson['service_area'];
			$serviceAreaList = $this->basic_data_service->getTopChildList('服务区域');
			$this->form_validation->set_data($this->postJson);
  			$this->form_validation->set_rules('workCategory','工作类别','required|in_list['.implode(',',array_keys($workCategoryList)).']');
  			$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
  			$this->form_validation->set_rules('real_name','办证使用名字','required');
  			$this->form_validation->set_rules('yewu_describe','业务描述','max_length[255]');
  			$this->form_validation->set_rules('service_area','服务区域','required|in_list['.implode(',',array_keys($serviceAreaList)).']');
  			
  			if(!$this->form_validation->run()){
				$this->jsonOutput2($this->form_validation->error_first_html());
				break;
			}
			$yewuInfo = array(
  				'mobile' => $mobile,
  				'work_category' => $workCategory,
  				'real_name' => $realName,
  				'yewu_describe' => $realName,
  				'service_area' => $serviceArea,
				'add_uid'	=>  $this->userInfo['uid'],
				'add_username'	=>  $this->userInfo['name'],
			);
			
			$newYewuId = $this->Yewu_Model->_add($yewuInfo);
			if($newYewuId){
				$this->jsonOutput2(RESP_SUCCESS);
			}
			
		}
	}
	
}
