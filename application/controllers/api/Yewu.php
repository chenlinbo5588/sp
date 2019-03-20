<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yewu extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	$this->load->library('Yewu_service');
	}
	
	public function setYewu(){
		if($this->userInfo){
			
			$workCategoryList = WorkCategory::$typeName;
			$mobile = $this->postJson['mobile'];
			$workCategory = $this->postJson['work_category']; 
			$realName = $this->postJson['real_name']; 
			$yewuDescribe = $this->postJson['yewu_describe']; 
			$serviceArea = $this->postJson['service_area'];
			$companyName = $this->postJson['company_name'];
			//getBasicData
			$serviceAreaList = $this->basic_data_service->getTopChildList('服务区域');
		
			/*$this->form_validation->set_data($this->postJson);
  			$this->form_validation->set_rules('workCategory','工作类别','required|in_list['.implode(',',array_keys($workCategoryList)).']');
  			$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
  			$this->form_validation->set_rules('real_name','办证使用名字','required');
  			$this->form_validation->set_rules('yewu_describe','业务描述','max_length[255]');
  			$this->form_validation->set_rules('service_area','服务区域','required|in_list['.implode(',',array_keys($serviceAreaList)).']');
  			
  			if(!$this->form_validation->run()){
				$this->jsonOutput2($this->form_validation->error_first_html());
				break;
			}*/
			
			$yewuInfo = array(
  				'mobile' => $mobile,
  				'work_category' => $workCategory,
  				'real_name' => $realName,
  				'yewu_describe' => $yewuDescribe,
  				'service_area' => $serviceAreaList[$serviceArea]['id'],
  				'user_id' => $this->userInfo['uid'],
				'add_uid'	=>  $this->userInfo['uid'],
				'add_username'	=>  $this->userInfo['name'],
				'company_name' => $companyName,
			);
			
			$newYewuId = $this->Yewu_Model->_add($yewuInfo);
			if($newYewuId){
				$this->jsonOutput2(RESP_SUCCESS);
			}
			
		}
	}
	
	public function getYewuList(){
		$id = $this->userInfo['uid'];
		if($this->userInfo){
			
			$data = $this->Yewu_Model->getList(array(
								'where' => array(
									'user_id' => $id 
								)
							));
			foreach($data  as $key => $item){
				$data[$key]['time'] =date("Y-m-d H:i",$data[$key]['gmt_create']) ;
				
			}
			$yewuList = array(
				'data' =>$data ,
			);
			if($yewuList){
				$this->jsonOutput2(RESP_SUCCESS,$yewuList);
			}
		}
	}
	
	public function getUserType(){
		
		
		$type = array(
			'type' => $this->userInfo['user_type'],
		);
		
		
		$this->jsonOutput2(RESP_SUCCESS,$type);
	

		
	}

	
	
}
