<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Hp extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Hp_Recent_Model');
	}
	
	/*
	private function _processCode($code){
		//print_r($code);
		$code = str_replace(array("\r\n","\n",'-',' ',"\t",'|'),'',trim($code));
		$code = str_replace('，',',',$code);
		$code = trim($code,',');
		
		$tmpCode = explode(',',$code);
		if(count($tmpCode) > 50){
			return implode(',',array_slice($tmpCode,0,50));
		}
		
		return trim($code,',');
		
		
		$goodsCode = explode("\n",$code);
		//print_r($goodsCode);
		$realCode = array();
		foreach($goodsCode as $line){
			if(trim($line) != ''){
				$line = str_replace('，',',',trim($line));
				$multiVal = explode(',',$line);
				
				foreach($multiVal as $oneValue){
					
					if(trim($oneValue)){
						$realCode[] = str_replace('-','',trim($oneValue));
					}
				}
			}
		}
		
		return $realCode;
		
	}*/
	
	
	public function index()
	{
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$pageParam = array(
			'page_size' => config_item('page_size'),
			'current_page' => $currentPage,
			'call_js' => 'search_page',
			'form_id' => '#formSearch'
		);
		
		$condition = array(
			'order' => 'gmt_modify DESC',
			'pager' => $pageParam
		);
		
		//$searchKeys['gn'] = $this->input->get_post('gn');
		//$searchKeys['gc'] = $this->input->get_post('gc');
		
		
		$searchKeys['sex'] = intval($this->input->get_post('sex'));
		
		//尺码
		$searchKeys['s1'] = floatval($this->input->get_post('s1'));
		$searchKeys['s2'] = floatval($this->input->get_post('s2'));
		
		//价格范围
		$searchKeys['pr1'] = intval($this->input->get_post('pr1'));
		$searchKeys['pr2'] = intval($this->input->get_post('pr2'));
		
		$searchCondition = array(
			'pager' => $pageParam,
			'order_field' => 'gmt_modify',
			'fields' => array(
				'goods_name' => $this->input->get_post('gn'),
				'goods_code' => $this->input->get_post('gc'),
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
		
		$this->load->library('Hp_service');
		$this->hp_service->setServer(0);
		
		$results = $this->hp_service->query($searchCondition);
		
		//print_r($searchCondition);
		//print_r($results);
		//file_put_contents('debug.txt',print_r($results,true));
		
		if($results['matches'] && $results['status'] === 0 && $results['total_found'] > 0){
			$condition['where_in'][] = array(
				'key' => 'goods_id',
				'value' => array_keys($results['matches'])
			);
			
			unset($condition['pager']);
			
			$pager = pageArrayGenerator($pageParam,$results['total_found']);
			
			$list = $this->Hp_Recent_Model->getList($condition);
			$this->assign('list',$list);
			$this->assign('page',$pager['pager']);
			
		}else{
			//print_r($condition);
			$list = $this->Hp_Recent_Model->getList($condition);
			$this->assign('page',$list['pager']);
			$this->assign('list',$list['data']);
		}
		
		$uid = array();
		foreach($list as $item){
			$uid[] = $item['uid'];
		}
		$userList = $this->Member_Model->getUserListByIds($uid,'uid,nickname,qq,mobile');
		//print_r($userList);
		
		$this->assign('userList',$userList);
		$this->display();
	}
	
	
	public function add(){
		
		$initRow = array(0);
		$feedback = '';
		$ctrlKey = 'pubctrl_'.$this->_loginUID;
		
		$postData = array();
		
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
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
				
				// 3 分钟 
				if($lastPub &&  ($this->_reqtime - $lastPub['batch_id']) < 180){
					$freezenSec = $this->_reqtime - $lastPub['batch_id'];
					$feedback = getErrorTip('求货发布冻结时间内还剩'. (180 - $freezenSec).'秒,请稍候尝试');
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
				
				//print_r($insertData);
				$this->Hp_Recent_Model->batchInsert($insertData);
				
				$ctrlData = array(
					'uid' => $this->_loginUID,
					'batch_id' => $this->_reqtime,
					'cnt' => $rowCount
				);
				
				$this->Hp_Batch_Model->_add($ctrlData);
				$this->getCacheObject()->save($ctrlKey,$ctrlData,CACHE_ONE_DAY);
				
				$feedback = getSuccessTip('求货发布成功');
			}
		}
		
		$this->assign('postData',$postData);
		$this->assign('initRow',$initRow);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
}
