<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Inventory extends MyYdzj_Controller {
	
	
	private $_isExpired ;
	private $_maxRowPerSlot;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Inventory_service');
		$this->assign('reqtime',$this->_reqtime);
		
		$this->_isExpired = array(
			'0' => '不限',
			'1' => '已过期',
			'2' => '未过期',
		);
		
		$this->_maxRowPerSlot = 50;
		
		$this->assign('maxRowPerSlot',$this->_maxRowPerSlot);
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
	 * 最近求货
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
	
	
	public function add_item(){
		
		$initRow = array(0);
		$feedback = '';
		
		$postData = array();
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$this->load->config('hp');
				$validationKey = config_item('hp_validation');
				
				foreach($validationKey['inventory'] as $key){
					$postData[$key] = $this->input->post($key,true);
				}
				
				// 提交了多少行
				$rowCount = intval(count($postData['goods_code']));
				
				if($rowCount == 0){
					$initRow = array(0);
				}else{
					//最多20行,保护机制
					if($rowCount > $this->_maxRowPerSlot){
						$rowCount = $this->_maxRowPerSlot;
					}
					$initRow = range(0,$rowCount - 1);
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
					$feedback = getErrorTip($this->form_validation->error_string('',''));
					break;
				}
				
				$insertData = array();
				
				$date_key = date("Ymd",$this->_reqtime);
				$ip = $this->input->ip_address();
				
				foreach($initRow as $rowIndex){
					$rowData = array(
						'date_key' => $date_key,
						'ip' => $ip,
						'gmt_create' => $this->_reqtime,
						'uid' => $this->_loginUID
					);
					
					foreach($validationKey['inventory'] as $field){
						$rowData[$field] = $postData[$field][$rowIndex];
					}
					
					$insertData[] = $rowData;
				}
				
				//$rowsInserted = $this->hp_service->addHp($insertData,$this->_reqtime,$this->_loginUID);
				
				if($rowsInserted){
					$feedback = getSuccessTip('库存更新成功');
				}else{
					$errorInfo = $this->Hp_Recent_Model->get_error_info();
					$feedback = getErrorTip(str_replace(array('{code}','{message}'),array($errorInfo['code'],$errorInfo['message']),"系统错误,{code}:{message}"));
				}
				
			}
		}
		
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
