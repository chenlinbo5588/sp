<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Inventory extends MyYdzj_Controller {
	
	private $_isExpired ;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Inventory_service');
		$this->assign('reqtime',$this->_reqtime);
		
		$this->_isExpired = array(
			'0' => '不限',
			'1' => '已过期',
			'2' => '未过期',
		);
		
		$this->assign('isExpired',$this->_isExpired);
	}
	
	
	
	/**
	 * 准备查询参数
	 */
	private function _prepareParam($pageParam){
		
		//$searchKeys['gn'] = $this->input->get_post('gn');
		//$searchKeys['gc'] = $this->input->get_post('gc');
		
		
		$searchKeys['sex'] = intval($this->input->get_post('sex'));
		
		//尺码
		$searchKeys['s1'] = floatval($this->input->get_post('s1'));
		$searchKeys['s2'] = floatval($this->input->get_post('s2'));
		
		//价格范围
		$searchKeys['pr1'] = intval($this->input->get_post('pr1'));
		$searchKeys['pr2'] = intval($this->input->get_post('pr2'));
		
		//是否已经过期
		$searchKeys['isexpired'] = intval($this->input->get_post('isexpired'));
		
		
		//发布日期
		$searchKeys['sdate'] = $this->input->get_post('sdate');
		$searchKeys['edate'] = $this->input->get_post('edate');
		
		if($searchKeys['sdate']){
			$searchKeys['sdate'] = strtotime($searchKeys['sdate']);
		}else{
			$searchKeys['sdate'] = 0;
		}
		
		if($searchKeys['edate']){
			$searchKeys['edate'] = strtotime($searchKeys['edate']);
			$searchKeys['edate'] = strtotime("+1 day",$searchKeys['edate']);
		}else{
			$searchKeys['edate'] = 0;
		}
		
		//print_r($searchKeys);
		$searchCondition = array(
			'pager' => $pageParam,
			'order' => 'gmt_modify DESC',
			'fields' => array(
				'goods_name' => $this->input->get_post('gn'),
				'goods_code' => $this->input->get_post('gc'),
				'uid' => array($this->_loginUID)
			)
		);
		
		//性别
		if($searchKeys['sex']){
			$searchCondition['fields']['sex'] = array($searchKeys['sex']);
			//$client->SetFilter ('sex', array($searchKeys['sex']));
		}
		
		if($searchKeys['s1'] || $searchKeys['s2']){
			$sizeOrderedValue = orderValue(array($searchKeys['s1'],$searchKeys['s2']));
			$searchCondition['fields']['goods_size'] = $sizeOrderedValue;
		}
		
		if($searchKeys['pr1'] || $searchKeys['pr2']){
			$prOrderedValue = orderValue(array($searchKeys['pr1'],$searchKeys['pr2']),10000);
			$searchCondition['fields']['price_max'] = $prOrderedValue;
		}
		
		if($searchKeys['sdate'] || $searchKeys['edate']){
			$dateValue = orderValue(array($searchKeys['sdate'],$searchKeys['sdate']),$this->_reqtime);
			$searchCondition['fields']['gmt_create'] = $dateValue;
			
		}
		
		
		if($searchKeys['isexpired']){
			if($searchKeys['isexpired'] == 1){
				$searchCondition['fields']['gmt_modify'] = array(
					0,
					$this->_reqtime - config_item('hp_expired')
				);
			
			}else if($searchKeys['isexpired'] == 2){
				$searchCondition['fields']['gmt_modify'] = array(
					$this->_reqtime - config_item('hp_expired'),
					$this->_reqtime
				);
			}
		}
		
		return $searchCondition;
	}
	
	
	private function _preparePager(){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$pageParam = array(
			'page_size' => config_item('page_size'),
			'current_page' => $currentPage,
			'call_js' => 'search_page',
			'form_id' => '#formSearch'
		);
		
		return $pageParam;
	}
	
	/**
	 * 库存 货柜列表
	 */
	public function index()
	{
		//$searchCondition = $this->_prepareParam($this->_preparePager());
		$userSlots = $this->inventory_service->getUserCurrentSlots($this->_loginUID);
		
		$this->assign('list',$userSlots);
		
		/*
		$pager = $this->_preparePager();
		$results = pageArrayGenerator($pager,$userSlots['slot_num']);
		
		if($results){
			$this->assign('list',$userSlots);
			$this->assign('page',$results['pager']);
		}
		*/
		
		$this->display();
	}
	
	
	
	/**
	 * 删除频率控制
	 */
	private function _delFreqControl(){
		
		$deleteTime = $this->input->get_cookie('dt');
		if($deleteTime){
			/*
			file_put_contents('debug.txt',$this->_reqtime."\n");
			file_put_contents('debug.txt',$temp."\n",FILE_APPEND);
			file_put_contents('debug.txt',($this->_reqtime - $temp),FILE_APPEND);
			*/
			
			if(($this->_reqtime - $deleteTime) <= 15){
				return 15 - ($this->_reqtime - $deleteTime);
			}
		}
		
		return 0;
		
	}
	
	
	/**
	 * 配置货柜 对应的货号信息
	 */
	public function slot_title(){
		
		
		$slot_id = trim($this->input->post('slot_id',true));
		$title = trim($this->input->post('title',true));
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_rules('title','required|min_length[1]|max_length[10]');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_string('',''),$this->getFormHash());
					break;
				}
				
				$return = $this->inventory_service->setSlotTitle($slot_id,$title,$this->_loginUID);
				$this->jsonOutput('设置成功',$return);
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	/**
	 * 配置货柜 对应的货号信息
	 */
	public function slot_gc(){
		
		
		$slot_id = trim($this->input->post('slot_id',true));
		$goodsCode = trim($this->input->post('goods_code',true));
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_rules('goods_code','required|min_length[1]|max_length[10]');
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_string('',''),$this->getFormHash());
					break;
				}
				
				$return = $this->inventory_service->setSlotGoodsCode($slot_id,$goodsCode,$this->_loginUID);
				$this->jsonOutput('设置成功',$return);
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	
	/*
	 * 配置货品信息到货柜
	 */
	public function slot_edit(){
		
		$slotId = $this->input->get_post('id');
		
		$needGoodsCodeFirst = "0";
		$initRow = array();
		$feedback = '';
		
		$postData = array();
		$userSlotsList = $this->inventory_service->getUserCurrentSlots($this->_loginUID);
		$userSlot = $userSlotsList['slot_config'][$slotId];
		
		//print_r($userSlotsList);
		
		if($userSlot['goods_code'] && $this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				
				$this->load->config('hp');
				$validationKey = config_item('hp_validation');
				
				foreach($validationKey['inventory'] as $key){
					$postData[$key] = $this->input->post($key,true);
				}
				
				// 提交了多少行
				$rowCount = intval(count($postData['goods_color']));
				
				if($rowCount > $userSlot['max_cnt']){
					//保护机制
					$rowCount = $userSlot['max_cnt'];
				}
				
				$initRow = range(0,$rowCount - 1);
				
				$slotInfo = $this->inventory_service->getSlotDetail($slotId,$this->_loginUID,'gmt_modify');
				$remainSeconds = $this->_reqtime - $slotInfo['gmt_modify'];
				if($remainSeconds < 15 ){
					$feedback = getErrorTip('货柜货品更新冻结时间内还剩'.(15 - $remainSeconds).'秒,请稍候尝试');
					break;
				}
				
				if(0 == $userSlot['cnt']){
					/* 初始添加货品中不能提交空，已有货品的清空下可以提交空行，表示清空货品 */
					if($rowCount == 0){
						$feedback = getErrorTip('请提供货品信息');
						break;
					}
				}
				
				$insertData = array(
					'ip' => $this->input->ip_address(),
					'gmt_modify' => $this->_reqtime,
					'uid' => $this->_loginUID,
					'slot_id' => $userSlot['id']
				);
				
				
				if($rowCount == 0){
					$insertData['goods_list'] = array();
				}else{
					$data = array();
					//用于数据校验得数组
					foreach($validationKey['inventory'] as $key){
						foreach($initRow as $item){
							$dk = "{$key}{$item}";
							$data[$dk] = $postData[$key][$item];
							
							$this->form_validation->set_rules($dk,$validationKey['rule_list'][$key]['title'],$validationKey['rule_list'][$key]['rules']);
						}
					}
					
					$this->form_validation->set_data($data);
					if(!$this->form_validation->run()){
						//$feedback = getErrorTip($this->form_validation->error_string('',''));
						$feedback = getErrorTip('数据输入格式有误,请检查录入格式');
						break;
					}
					
					
					/* 提交了货品 */
					$goodsList = array();
					foreach($initRow as $rowIndex){
						$rowData = array(
							'goods_code' => $userSlot['goods_code']
						);
						
						foreach($validationKey['inventory'] as $field){
							$rowData[$field] = $postData[$field][$rowIndex];
						}
						
						$goodsList[] = $rowData;
					}
					
					$insertData['goods_list'] = $goodsList;
				}
				
				$rowsAffected = $this->inventory_service->updateSlotGoodsInfo($userSlotsList,$insertData,$this->_loginUID);
					
				
				if($rowsAffected){
					$feedback = getSuccessTip('货柜货品更新成功');
					
				}else{
					$errorInfo = $this->Member_Inventory_Model->get_error_info();
					$feedback = getErrorTip(str_replace(array('{code}','{message}'),array($errorInfo['code'],$errorInfo['message']),"系统错误,{code}:{message}"));
				}
				
			}
		}else{
			if($userSlot['goods_code'] == ''){
				 $needGoodsCodeFirst = "1";
			}else{
				
				// 获取货柜货品列表
				$slotInfo = $this->inventory_service->getSlotDetail($slotId,$this->_loginUID,'goods_list,enable,gmt_modify');
				
				if($slotInfo['goods_list']){
					$postData = $this->inventory_service->formatGoodsListAsPostStyle($slotInfo);
					$initRow = range(0,count($slotInfo['goods_list']) - 1);
				}
			}
		}
		
		
		if($userSlot['max_cnt']){
			$this->assign('maxRowPerSlot',$userSlot['max_cnt']);
		}else{
			$this->assign('maxRowPerSlot',50);
		}
		
		
		//print_r($initRow);
		//print_r($postData);
		
		//print_r($userSlot);
		$this->assign('userSlot',$userSlot);
		$this->assign('slotId',$slotId);
		$this->assign('goodsCodeFirst',$needGoodsCodeFirst);
		$this->assign('postData',$postData);
		$this->assign('initRow',$initRow);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	
	/**
	 * 
	 */
	public function reactive(){
		$id = $this->input->post('id');
		if($id && $this->isPostRequest()){
			$remainSeconds = $this->hp_service->getPubTimeRemain($this->_reqtime,$this->_loginUID);
			if($remainSeconds){
				$this->jsonOutput('冻结时间还剩'.$remainSeconds.'秒,请稍后尝试');
			}else{
				$rows = $this->hp_service->reactiveUserHpReq($id,$this->_reqtime,$this->_loginUID);
				$this->jsonOutput('重新激活成功');
			}
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	

}
