<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Hp extends MyYdzj_Controller {
	
	private $_mtime ;
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library('Hp_service');
		
		$this->_mtime = array(
			'不限' => '',
			'10分钟内' => '-600 seconds',
			'20分钟内' => '-1200 seconds',
			'30分钟内' => '-1800 seconds',
			'1小时内' => '-3600 seconds',
			'2小时内' => '-7200 seconds',
		);
	}
	
	
	private function _prepareParam($pageParam){
		$searchKeys['sex'] = intval($this->input->get_post('sex'));
		
		
		//尺码
		$searchKeys['s1'] = floatval($this->input->get_post('s1'));
		$searchKeys['s2'] = floatval($this->input->get_post('s2'));
		
		//价格范围
		$searchKeys['pr1'] = intval($this->input->get_post('pr1'));
		$searchKeys['pr2'] = intval($this->input->get_post('pr2'));
		
		$searchKeys['mtime'] = trim($this->input->get_post('mtime'));
		
		$searchCondition = array(
			'pager' => $pageParam,
			'order' => 'gmt_modify DESC',
			'fields' => array(
				'goods_name' => $this->input->get_post('gn'),
				'goods_code' => $this->input->get_post('gc'),
			)
		);
		
		//性别
		if($searchKeys['sex']){
			$searchCondition['fields']['sex'] = array($searchKeys['sex']);
		}
		
		if($searchKeys['s1'] || $searchKeys['s2']){
			$sizeOrderedValue = orderValue(array($searchKeys['s1'],$searchKeys['s2']));
			$searchCondition['fields']['goods_size'] = $sizeOrderedValue;
		}
		
		if($searchKeys['pr1'] || $searchKeys['pr2']){
			$prOrderedValue = orderValue(array($searchKeys['pr1'],$searchKeys['pr2']),10000);
			$searchCondition['fields']['price_max'] = $prOrderedValue;
		}
		
		if($searchKeys['mtime'] && $this->_mtime[$searchKeys['mtime']]){
			$searchCondition['fields']['gmt_modify'] = array(
				strtotime($this->_mtime[$searchKeys['mtime']],$this->_reqtime),
				$this->_reqtime
			);
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
	
	public function index()
	{
		$searchCondition = $this->_prepareParam($this->_preparePager());
		
		$this->hp_service->setServer(0);
		
		$results = $this->hp_service->query($searchCondition);
		
		//print_r($results);
		$uid = array();
		
		if($results){
			$this->assign('list',$results['data']);
			$this->assign('page',$results['pager']);
			
			foreach($results['data'] as $item){
				$uid[] = $item['uid'];
			}
			$userList = $this->Member_Model->getUserListByIds($uid,'uid,nickname,qq,mobile');
			$this->assign('userList',$userList);
		}
		
		
		$this->assign('mtime',$this->_mtime);
		$this->display();
	}
	
	
	public function add(){
		
		$initRow = array(0);
		$feedback = '';
		
		
		$postData = array();
		
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$lastPub = $this->hp_service->getUserLastPub($this->_loginUID);
				
				/*
				$hpBatch = $this->load->get_config('split_hp_batch');
				$flexiHash = new Flexihash();
				
				$flexiHash->addTargets($hpBatch);
				$tableId = $flexiHash->lookup($this->_loginUID);
				
				$this->load->model('Hp_Batch_Model');
				$this->Hp_Batch_Model->setTableId($tableId);
				
				$lastPub = $this->getCacheObject()->get($ctrlKey);
				if(empty($lastPub)){
					$lastPub = $this->Hp_Batch_Model->getList(array(
						'where' => array(
							'uid'=> $this->_loginUID,
						),
						'order' => 'batch_id DESC',
						'limit' => 1 
					));
					
					if($lastPub){
						$this->getCacheObject()->save($ctrlKey,$lastPub,CACHE_ONE_DAY);
					}
				}
				
				*/
				
				/*
				$this->form_validation->set_rules('goods_code[]','货号','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_name[]','货名','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_color[]','颜色','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_size[]','尺码','required|is_numeric|greater_than[0]|less_than[60]');
				$this->form_validation->set_rules('quantity[]','数量','required|is_natural_no_zero|less_than[100]');
				$this->form_validation->set_rules('sex[]','性别','required|in_list[0,1]');
				$this->form_validation->set_rules('price_max[]','最高价','required|is_numeric|greater_than[0]|less_than[100000]');
				*/
				
				$validationKey = array(
					'goods_code' => array(
						'title' => '货号',
						'rules' => 'required|min_length[1]|max_length[10]'
					),
					'goods_name' => array(
						'title' => '货名',
						'rules' => 'required|min_length[1]|max_length[10]'
					),
					'goods_color' => array(
						'title' => '颜色',
						'rules' => 'required|min_length[1]|max_length[10]'
					),
					'goods_size' => array(
						'title' => '尺码',
						'rules' => 'required|is_numeric|greater_than[0]|less_than[60]'
					),
					'quantity' => array(
						'title' => '数量',
						'rules' => 'required|is_natural_no_zero|less_than[100]'
					),
					'sex' => array(
						'title' => '性别',
						'rules' => 'required|in_list[1,2]'
					),
					'price_max' => array(
						'title' => '最高价',
						'rules' => 'required|is_numeric|greater_than[0]|less_than[100000]'
					),
					'send_zone' => array(
						'title' => '发货地址',
						'rules' => 'required|max_length[10]'
					),
					'send_day' => array(
						'title' => '发货时间',
						'rules' => 'required|valid_date'
					)
				);
				
				foreach($validationKey as $key => $item){
					$postData[$key] = $this->input->post($key,true);
				}
				
				// 提交了多少行
				$rowCount = intval(count($postData['goods_code']));
				
				if($rowCount == 0){
					$initRow = array(0);
				}else{
					//最多20行,保护机制
					if($rowCount > 20){
						$rowCount = 20;
					}
					$initRow = range(0,$rowCount - 1);
				}
				
				$remainSeconds = $this->hp_service->getPubTimeRemain($this->_reqtime,$this->_loginUID);
				if($remainSeconds){
					$feedback = getErrorTip('求货发布冻结时间内还剩'. $remainSeconds.'秒,请稍候尝试');
					break;
				}
				
				if($rowCount == 0){
					$feedback = getErrorTip('请提供求货信息');
					break;
				}
				
				//用于数据校验得数组
				$data = array();
				foreach($validationKey as $key => $validation){
					foreach($initRow as $item){
						$dk = "{$key}{$item}";
						$data[$dk] = $postData[$key][$item];
						if($key == 'send_zone' || $key == 'send_day'){
							if(!empty($data[$dk])){
								$this->form_validation->set_rules($dk,$validation['title'],$validation['rules']);
							}
						}else{
							$this->form_validation->set_rules($dk,$validation['title'],$validation['rules']);
						}
					}
				}
				
				$this->form_validation->set_data($data);
				if(!$this->form_validation->run()){
					//$feedback = $this->form_validation->error_string('','');
					$feedback = getErrorTip('数据校验失败');
					break;
				}
				
				$insertData = array();
				$fieldNames = array_keys($validationKey);
				
				$date_key = date("Ymd",$this->_reqtime);
				$ip = $this->input->ip_address();
				
				foreach($initRow as $rowIndex){
					$rowData = array(
						'date_key' => $date_key,
						'ip' => $ip,
						'gmt_create' => $this->_reqtime,
						'uid' => $this->_loginUID
					);
					
					foreach($fieldNames as $field){
						if($field == 'send_day' && !empty($postData[$field][$rowIndex])){
							$rowData[$field] = strtotime($postData[$field][$rowIndex]);
						}
						
						$rowData[$field] = $postData[$field][$rowIndex];
					}
					
					$insertData[] = $rowData;
				}
				
				$rowsInserted = $this->hp_service->addHp($insertData,$this->_reqtime,$this->_loginUID);
				
				if($rowsInserted){
					$feedback = getSuccessTip('求货发布成功,您发布的信息蒋在一分钟内开始生效');
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
	
}
