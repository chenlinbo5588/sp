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
			$this->jsonOutput2(RESP_ERROR);
		}
	}
	
	
	/**
	 * 获得房屋详情
	 */
	public function getYezhuHouseDetail(){
		
		$id = $this->input->get('id');
		
		if($id && $this->yezhuInfo){
			
			$houseInfo = $this->wuye_service->getYezhuHouseDetail($id,$this->yezhuInfo);
			
			if($houseInfo){
				$this->jsonOutput2(RESP_SUCCESS,$houseInfo);
			}else{
				$this->jsonOutput2(RESP_ERROR);
			}
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}
	
	}
	
	
	/**
	 * 创建 物业费、能耗费、报修订单
	 */
	public function createOrder(){
		
		
		
		
		
		
		
		
		
		
	}
}
