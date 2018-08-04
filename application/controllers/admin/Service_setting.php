<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 服务设置
 */
class Service_Setting extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		
		$this->_moduleTitle = '服务设置';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/base','title' => '基本设置'),
		);
	}
	
	
	/**
	 * 基本设置
	 */
	public function base(){
		
		$feedback = '';
		
		
		$settingKey = array(
			'service_prepay_amount',
			'service_staff_maxcnt',
			'service_order_limit',
			'service_booking_status',
			'service_refund_verify',
			'service_closed_reason',
		);
		
		$currentSetting = $this->base_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name' , 'value' => $settingKey)
			)
		));
		
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('service_prepay_amount','预约单预约金','required|is_natural_no_zero');
			$this->form_validation->set_rules('service_staff_maxcnt','单次预约单最大预约人数','required|is_natural');
			$this->form_validation->set_rules('service_order_limit','用户当日最大可预约订单数量','required|is_natural');
			$this->form_validation->set_rules('service_booking_status','预约功能状态','required|in_list[开启,关闭]');
			
			$this->form_validation->set_rules('service_closed_reason','关闭原因','required');
			
			
			if($this->form_validation->run()){
				$data = array();
				foreach($settingKey as $oneKey){
					$temp = array();
					$temp['name'] = $oneKey;
					$temp['value'] = $this->input->post($oneKey);
					
					$data[] = $temp;
				}
				
				//print_r($data);
				
				if($this->base_service->updateSetting($data) >= 0){
					$feedback = getSuccessTip('保存成功');
					
					$currentSetting = $this->base_service->getSettingList(array(
						'where_in' => array(
							array('key' => 'name' , 'value' => $settingKey)
						)
					));
					
				}else{
					$feedback = getErrorTip('保存失败');
				}
			}else{
				$feedback = getErrorTip($this->form_validation->error_string());
			}
		}
		
		$this->assign('currentSetting',$currentSetting);
		$this->assign('feedback',$feedback);
		
		$this->display();
	}
	
	
}
