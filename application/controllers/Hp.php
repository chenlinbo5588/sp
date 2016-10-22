<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Hp extends MyYdzj_Controller {
	
	private $_mtime ;
	private $_maxRowPerReq;
	
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
			'2小时以上' => '-7200 seconds',
		);
		
		$this->_maxRowPerReq = 20;
		
		$this->assign('maxRowPerReq',$this->_maxRowPerReq);
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
				'kw' => $this->input->get_post('kw'),
				//'kw'=> 'asdsd#42.5'
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
		
		/*
		if($searchKeys['pr1'] || $searchKeys['pr2']){
			$prOrderedValue = orderValue(array($searchKeys['pr1'],$searchKeys['pr2']),10000);
			$searchCondition['fields']['price_max'] = $prOrderedValue;
		}
		*/
		
		if($searchKeys['mtime'] && $this->_mtime[$searchKeys['mtime']]){
			
			if('2小时以上' == $searchKeys['mtime']){
				$searchCondition['fields']['gmt_modify'] = array(
					0,
					strtotime($this->_mtime[$searchKeys['mtime']],$this->_reqtime)
				);
			}else{
				$searchCondition['fields']['gmt_modify'] = array(
					strtotime($this->_mtime[$searchKeys['mtime']],$this->_reqtime),
					$this->_reqtime
				);
			}
			
		}
		
		//print_r($searchCondition);
		
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
			/*
			foreach($results['data'] as $item){
				$uid[] = $item['uid'];
			}
			$userList = $this->Member_Model->getUserListByIds($uid,'uid,nickname,qq,mobile');
			$this->assign('userList',$userList);
			*/
		}
		
		
		$this->_breadCrumbs[] = array(
			'title' => '求货查询',
			'url' => $this->uri->uri_string
		);
		
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
				$this->form_validation->set_rules('goods_code[]','货号','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_name[]','货名','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_color[]','颜色','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_size[]','尺码','required|is_numeric|greater_than[0]|less_than[60]');
				$this->form_validation->set_rules('quantity[]','数量','required|is_natural_no_zero|less_than[100]');
				$this->form_validation->set_rules('sex[]','性别','required|in_list[0,1]');
				$this->form_validation->set_rules('price_max[]','最高价','required|is_numeric|greater_than[0]|less_than[100000]');
				*/
				
				$this->load->config('hp');
				
				$validationKey = config_item('hp_validation');
				
				foreach($validationKey['hp_req'] as $value){
					$postData[$value] = $this->input->post($value,true);
					$postData[$value] = $postData[$value];
				}
				
				// 提交了多少行
				$rowCount = intval(count($postData['goods_code']));
				
				if($rowCount == 0){
					$initRow = array(0);
				}else{
					//最多行,保护机制
					if($rowCount > $this->_maxRowPerReq){
						$rowCount = $this->_maxRowPerReq;
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
				
				foreach($validationKey['hp_req'] as $key){
					foreach($initRow as $item){
						$dk = "{$key}{$item}";
						$data[$dk] = $postData[$key][$item];
						if($key == 'send_zone' || $key == 'send_day' || 'price_status' == $key){
							if(!empty($data[$dk])){
								$this->form_validation->set_rules($dk,$validationKey['rule_list'][$key]['title'],$validationKey['rule_list'][$key]['rules']);
							}
						}else{
							$this->form_validation->set_rules($dk,$validationKey['rule_list'][$key]['title'],$validationKey['rule_list'][$key]['rules']);
						}
					}
				}
				
				/*
				foreach($validationKey['rule_list'] as $key => $validation){
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
				*/
				
				$this->form_validation->set_data($data);
				if(!$this->form_validation->run()){
					//$feedback = getErrorTip($this->form_validation->error_string('',''));
					$feedback = getErrorTip('数据输入格式有误,请检查录入格式');
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
						'gmt_modify' => $this->_reqtime,
						'uid' => $this->_loginUID,
						'username' => $this->_profile['basic']['username'],
						'qq' => $this->_profile['basic']['qq'],
						'email' => $this->_profile['basic']['email'],
						'mobile' => $this->_profile['basic']['mobile'],
					);
					
					foreach($validationKey['hp_req'] as $field){
						$postData[$field][$rowIndex] = trim($postData[$field][$rowIndex]);
						
						if($field == 'send_day'){
							if(empty($postData[$field][$rowIndex])){
								$rowData[$field] = 0;
							}else{
								$rowData[$field] = strtotime($postData[$field][$rowIndex]);
							}
						}else if($field == 'goods_size'){
							if(is_numeric($postData[$field][$rowIndex])){
								//数字
								$rowData['goods_size'] = $postData[$field][$rowIndex];
								$rowData['goods_csize'] = $rowData['goods_size'];
							}else{
								//非数字
								$rowData['goods_size'] = 0;
								$rowData['goods_csize'] = $postData[$field][$rowIndex];
							}
						}else{
							$rowData[$field] = $postData[$field][$rowIndex];
						}
					}
					
					//关键，创建一个完整的词组 ，防止分词  关系到精确匹配的问题
					$rowData['search_code'] = strtolower(code_replace($rowData['goods_code']));
					
					//重要   用户后台自动匹配的字段， 货号&尺码  确定一个货品
					$rowData['kw'] = $rowData['search_code'].strtolower(str_replace(array('.','#'),'',$rowData['goods_csize']));
					
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
		
		$this->_breadCrumbs[] = array(
			'title' => '求货区',
			'url' => 'hp/index'
		);
		$this->_breadCrumbs[] = array(
			'title' => '求货发布',
			'url' => $this->uri->uri_string
		);
		
		$this->assign('postData',$postData);
		$this->assign('initRow',$initRow);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
}
