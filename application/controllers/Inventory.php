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
		///echo date("Y-m-d H:i:s",$this->_reqtime);
		//$searchCondition = $this->_prepareParam($this->_preparePager());
		
		$userInventory = $this->inventory_service->getUserCurrentInventory($this->_loginUID);
		//print_r($list);
		
		$pager = $this->_preparePager();
		$results = pageArrayGenerator($pager,$userInventory['slot_num']);
		
		if($results){
			$this->assign('list',$userInventory);
			$this->assign('page',$results['pager']);
		}
		
		//print_r($userInventory);
		
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
				$this->jsonOutput('发送成功',$return);
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
		$initRow = array(0);
		$feedback = '';
		
		$postData = array();
		$userInventory = $this->inventory_service->getUserCurrentInventory($this->_loginUID);
		
		$userInventorySlot = $userInventory['slot_config'][$slotId];
		
		if($userInventorySlot['goods_code'] && $this->isPostRequest()){
			
			
			
			
			for($i = 0; $i < 1; $i++){
				
				$this->load->config('hp');
				$validationKey = config_item('hp_validation');
				
				foreach($validationKey['inventory'] as $key){
					$postData[$key] = $this->input->post($key,true);
				}
				
				// 提交了多少行
				$rowCount = intval(count($postData['goods_color']));
				
				if($rowCount == 0){
					$initRow = array(0);
				}else{
					//最多20行,保护机制
					if($rowCount > $userInventorySlot['max_cnt']){
						$rowCount = $userInventorySlot['max_cnt'];
					}
					$initRow = range(0,$rowCount - 1);
				}
				
				
				$slotInfo = $this->inventory_service->getSlotDetail($slotId,$this->_loginUID,'gmt_modify');
				$remainSeconds = $this->_reqtime - $slotInfo['gmt_modify'];
				if($remainSeconds < 10){
					$feedback = getErrorTip('库存更新冻结时间内还剩'.(15 - $remainSeconds).'秒,请稍候尝试');
					break;
				}
				
				
				if($rowCount == 0){
					$feedback = getErrorTip('请提供货品信息');
					break;
				}
				
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
				
				$insertData = array(
					'ip' => $this->input->ip_address(),
					'gmt_modify' => $this->_reqtime,
					'uid' => $this->_loginUID,
					'slot_id' => $userInventorySlot['id']
				);
				
				$goodsList = array();
				foreach($initRow as $rowIndex){
					$rowData = array(
						'goods_code' => $userInventorySlot['goods_code']
					);
					
					foreach($validationKey['inventory'] as $field){
						$rowData[$field] = $postData[$field][$rowIndex];
					}
					
					$goodsList[] = $rowData;
				}
				
				$insertData['goods_list'] = $goodsList;
				$rowsAffected = $this->inventory_service->updateSlotGoodsInfo($insertData,$this->_loginUID);
				
				if($rowsAffected){
					$feedback = getSuccessTip('库存更新成功');
					
				}else{
					$errorInfo = $this->Member_Inventory_Model->get_error_info();
					$feedback = getErrorTip(str_replace(array('{code}','{message}'),array($errorInfo['code'],$errorInfo['message']),"系统错误,{code}:{message}"));
				}
				
			}
		}else{
			if($userInventorySlot['goods_code'] == ''){
				 $needGoodsCodeFirst = "1";
			}else{
				
				// 获取货柜货品列表
				$slotInfo = $this->inventory_service->getSlotDetail($slotId,$this->_loginUID,'goods_list,enable,gmt_modify');
				
				if($slotInfo['goods_list']){
					$slotInfo['goods_list'] = json_decode($slotInfo['goods_list'],true);
					$postData = array();
					foreach($slotInfo['goods_list'] as $goods){
						$postData['goods_color'][] = $goods['goods_color'];
						$postData['goods_size'][] = $goods['goods_size'];
						$postData['quantity'][] = $goods['quantity'];
						$postData['sex'][] = $goods['sex'];
						$postData['price_min'][] = $goods['price_min'];
					}
					$initRow = range(0,count($slotInfo['goods_list']) - 1);
				}
				
				//print_r($slotInfo);
			}
		}
		
		
		if($userInventorySlot['max_cnt']){
			$this->assign('maxRowPerSlot',$userInventorySlot['max_cnt']);
		}else{
			$this->assign('maxRowPerSlot',50);
		}
		
		$this->assign('userInventorySlot',$userInventorySlot);
		$this->assign('slotId',$slotId);
		$this->assign('goodsCodeFirst',$needGoodsCodeFirst);
		$this->assign('postData',$postData);
		$this->assign('initRow',$initRow);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	
	/**
	 * 最近发布求货删除
	 */
	public function recent_del(){
		
		$id = $this->input->post('id');
		if($id && $this->isPostRequest()){
			
			for($i = 0 ; $i < 1; $i++){
				$seconds = $this->_delFreqControl();
				
				if($seconds){
					$this->jsonOutput('抱歉,当前操作冻结解除还剩'.$seconds.'秒');
					break;
				}
				
				if(!is_array($id)){
					$id = (array)$id;
				}
				
				$condition = array(
					'where' => array(
						'uid' => $this->_loginUID
					),
					'where_in' => array(
						array('key' => 'goods_id','value' => $id)
					)
				);
				
				$rows = $this->hp_service->deleteUserRecentHp($condition);
				$this->input->set_cookie('dt',$this->_reqtime,CACHE_ONE_DAY);
				
				$this->jsonOutput('删除成功',array('id' => $id));
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
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
