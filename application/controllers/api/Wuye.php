<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wuye extends Wx_Controller {
	
	public function __construct(){
		parent::__construct();
    	
    	
    	
	}
	
	
	/**
	 * 获得 微信用户关联  业主信息
	 */
	public function getYezhuHouseList(){
		if($this->yezhuInfo){
			
			$d = $this->wuye_service->getYezhuHouseListByYezhu($this->yezhuInfo);
			
			if($d){
				$this->jsonOutput2(RESP_SUCCESS,$d);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	}
	
	
	/**
	 * 获得房屋详情
	 */
	public function getYezhuHouseDetail(){
		
		if($this->yezhuInfo){
			$id = $this->input->get('id');
			
			for($i = 0; $i < 1; $i++){
				
				$data = array(
					'id' => $id
				);
				
				$this->form_validation->set_data($data);
				$this->form_validation->set_rules('id','物业ID','required|in_db_list['.$this->House_Model->getTableRealName().'.id]');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput2($this->form_validation->error_html());
					break;
				}
				
				$houseInfo = $this->wuye_service->getYezhuHouseDetail($id,$this->yezhuInfo);
			
				if($houseInfo){
					$this->jsonOutput2(RESP_SUCCESS,$houseInfo);
				}else{
					$this->jsonOutput2(RESP_ERROR);
				}
			}
			
		}else{
			$this->jsonOutput2(UNBINDED,$this->unBind);
		}
	
	}
	
	
}
