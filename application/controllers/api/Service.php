<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends Wx_Controller {
	
	
	public function __construct(){
		parent::__construct();
        
    	$this->load->library(array('Staff_service','Cart'));
    	$this->form_validation->set_error_delimiters('','');
    	
	}
	
	public function index()
	{
		

	}
	
	
	/**
	 * 获得查询条件
	 */
	public function getSearchCondition(){
		
		$serviceTypeName = $this->input->get_post('serviceTypeName');
		$data = array(
	        'serviceTypeName' => $serviceTypeName
		);
		
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('serviceTypeName','服务类型','required|in_list[月嫂,保姆,护工]');
		
		
		if($this->form_validation->run() != false){
			$searchCondition = $this->staff_service->getSearchCondition($serviceTypeName);
			$this->jsonOutput2(RESP_SUCCESS,array('conditionList' => $searchCondition));
			
		}else{
			$this->jsonOutput2(RESP_ERROR,array('detail' => $this->form_validation->error_string()));
		}
		
	}
	
	/**
	 * 
	 */
	private function _getBetweenCondition($pText,$field,&$pCondition){
		
		if(strpos($pText,'个') !== false){
			$con = substr($pText,0,strpos($pText,'个'));
			
			if(strpos($con,'-') !== false){
				$rangeVal = explode('-',$con);
				
				$pCondition['where']["{$field} >= "] = $rangeVal[0];
				$pCondition['where']["{$field} <="] = $rangeVal[1];
				
			}else{
				$pCondition['where']["{$field} >="] = $con;
			}
		}
	}
	
	/**
	 * 搜索月嫂
	 */
	public function getStaffList(){
		
		$serveTypeName = $this->input->get_post('serviceTypeName');
		
		$page = 1;
		$searchKeys = array(
			'where' => array(),
			'page' => 1,
			'order' => 'id DESC'
		);
		
		//file_put_contents("search.txt",print_r($this->postJson,true));
		
		if($this->postJson){
			$searchKeys['page'] = intval($this->postJson['page']);
			
			if(empty($searchKeys['page'])){
				$searchKeys['page'] = 1;
			}
			
			$whereIn = array();
			$groupNameList = array_keys($this->postJson['select']);
			$groupNameField = array(
				'服务区域' => 'region',
				'籍贯' => 'jiguan',
				'学历' => 'degree',
				'属相' => 'shu',
				$serveTypeName.'薪资' => 'salary',
			);
						
			foreach($groupNameField as $groupKey => $groupNameField){
				if($this->postJson['select'][$groupKey]){
					$whereIn[] = array('key' => $groupNameField ,'value' => $this->postJson['select'][$groupKey]);
				}
			}
			
			if($whereIn){
				$searchKeys['where_in'] = $whereIn;
			}
			
			$this->_getBetweenCondition($this->postJson['select'][$serveTypeName.'服务数量'][0],'service_cnt', $searchKeys);
			$this->_getBetweenCondition($this->postJson['select'][$serveTypeName.'经验月份'][0],'work_month', $searchKeys);
			
			
			if('有' == $this->postJson['select']['双胞胎经验'][0]){
				$searchKeys['where']['sbt_exp'] = 1;
			}elseif('无' == $this->postJson['select']['双胞胎经验'][0]){
				$searchKeys['where']['sbt_exp'] = 0;
			}
			
			if('有' == $this->postJson['select']['早产儿经验'][0]){
				$searchKeys['where']['zcbaby_exp'] = 1;
			}elseif('无' == $this->postJson['select']['早产儿经验'][0]){
				$searchKeys['where']['zcbaby_exp'] = 0;
			}
			
			if('有' == $this->postJson['select']['照片信息'][0]){
				$searchKeys['where']['has_photo'] = 1;
			}elseif('无' == $this->postJson['select']['照片信息'][0]){
				$searchKeys['where']['has_photo'] = 0;
			}
			
			
			if('有' == $this->postJson['select']['视频信息'][0]){
				$searchKeys['where']['has_video'] = 1;
			}elseif('无' == $this->postJson['select']['视频信息'][0]){
				$searchKeys['where']['has_video'] = 0;
			}
		}
		
		//file_put_contents("search.txt",print_r($searchKeys,true),FILE_APPEND);
		
		$data = $this->staff_service->getStaffListByCondition($serveTypeName,$searchKeys);
		
		//file_put_contents("search.txt",print_r($data,true),FILE_APPEND);
		
		$this->jsonOutput2(RESP_SUCCESS,$data);
	}
	
	
	
	/**
	 * 获得详情
	 */
	public function getStaffInfo(){
		$staffId = $this->input->get_post('id');
		
		$staffInfo = $this->staff_service->getStaffDetail($staffId,array('photos' => true));
		
		$this->jsonOutput2(RESP_SUCCESS,array('info' => $staffInfo));
	}
	
	
	
	/**
	 * 添加到购物车
	 */
	public function addCart(){
		
		if($this->isPostRequest()){
			
			for($i = 0 ; $i < 1; $i++){
				
				$this->form_validation->set_data($this->postJson);
				$this->form_validation->set_rules('staff_id','服务人员标识','required');
			
				if(!$this->form_validation->run()){
					$this->jsonOutput2("参数错误",$this->form_validation->error_array());
					break;
				}
				
				
				$staffList = $this->staff_service->getStaffList(array(
					'where' => array(
						'id' => $this->postJson['staff_id']
					)
				));
				
				if(empty($staffList[0])){
					$this->jsonOutput2('获取信息失败');
					break;
				}
				
				$list = $this->cart->contents();
				if($list){
					$firstCartItem = array();
					foreach($list as $rowKey => $cartItem){
						$firstCartItem = $cartItem;
						break;
					}
					
					if($firstCartItem['options']['service_type'] != $staffList[0]['service_type']){
						$this->jsonOutput2('预约单中暂时只能加入同类型的的服务人员。');
						break;
					}
				}
				
				$data = array(
			        'id'      => $staffList[0]['id'],
			        'qty'     => 1,
			        'price'   => 1,
			        'name'    => $staffList[0]['show_name'],
			        'options' => array('service_type' => $staffList[0]['service_type'])
				);
				
				$return = $this->cart->insert($data);
				
				$this->jsonOutput2(RESP_SUCCESS);
			}
			
		}else{
			$this->jsonOutput2(RESP_ERROR);
		}
		
	}
	
	
	/**
	 * 获得购物车
	 */
	public function getCart(){
		$list = $this->cart->contents(true);
		
		$data = array(
			'staff' => array(),
			'amount' => intval($this->_getSiteSetting('service_prepay_amount')),
			'address' => $this->_getSiteSetting('company_address')
		);
		
		if($list){
			//涉及到的 id 列表
			$staffIds = array();
			foreach($list as $rowId => $cartItem){
				$staffIds[] = $cartItem['id'];
			}
			
			$staffList = $this->staff_service->getStaffList(array(
				'where_in' => array(
					array('key' => 'id','value' => $staffIds)
				)
			),'id');
			
			//再次遍历
			foreach($list as $rowId => $cartItem){
				$list[$rowId] = array_merge($cartItem,$staffList[$cartItem['id']]);
			}
			
			$this->jsonOutput2(RESP_SUCCESS,array_merge($data,array('staff' => $list)));
			
		}else{
			$this->jsonOutput2(RESP_SUCCESS,$data);
		}
		
	}
	
	
	/**
	 * 移除记录
	 */
	public function removeCart(){
		
		if($this->isPostRequest()){
			
			
			for($i = 0 ; $i < 1; $i++){
				
				$this->form_validation->set_data($this->postJson);
				$this->form_validation->set_rules('rowKey','预约记录标识','required');
			
				if(!$this->form_validation->run()){
					$this->jsonOutput2("参数错误",$this->form_validation->error_array());
					break;
				}
				
				$flag = $this->cart->remove($this->postJson['rowKey']);
				
				if($flag){
					$this->jsonOutput2(RESP_SUCCESS);
				}else{
					$this->jsonOutput2("移除失败");
				}
			}
			
		}else{
			$this->jsonOutput2("请求非法");
		}
		
	}
	
}
