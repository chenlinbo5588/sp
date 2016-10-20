<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class My_req extends MyYdzj_Controller {
	
	
	private $_isExpired ;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Hp_service');
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
			$dateValue = orderValue(array($searchKeys['sdate'],$searchKeys['edate']),$this->_reqtime);
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
	public function recent()
	{
		
		
		$this->_breadCrumbs[] = array(
			'title' => '最近求货',
			'url' => $this->uri->uri_string
		);
		
		
		///echo date("Y-m-d H:i:s",$this->_reqtime);
		$searchCondition = $this->_prepareParam($this->_preparePager());
		$this->hp_service->setServer(0);
		
		$results = $this->hp_service->query($searchCondition);
		
		if($results){
			$this->assign('list',$results['data']);
			$this->assign('page',$results['pager']);
		}
		
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
	 * 求货删除 , 最近或者历史
	 */
	public function delete(){
		
		$id = $this->input->post('id');
		$source = $this->input->get_post('source');
		
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
				
				switch($source){
					case 'recent':
						$rows = $this->hp_service->deleteUserRecentHp($condition);
						break;
					case 'history':
						$rows = $this->hp_service->deleteUserHistoryHp($condition,$this->_loginUID);
						break;
					default:
						break;
				}
				
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
				$updateData = array(
					'gmt_modify' => $this->_reqtime,
					'username' => $this->_profile['basic']['username'],
					'qq' => $this->_profile['basic']['qq'],
					'email' => $this->_profile['basic']['email'],
					'mobile' => $this->_profile['basic']['mobile'],
				);
				
				$rows = $this->hp_service->reactiveUserHpReq($id,$updateData,$this->_loginUID);
				$this->jsonOutput('重新激活成功');
			}
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	/**
	 * 发布历史
	 */
	public function history(){
		
		$condition['pager'] = $this->_preparePager();
		$condition['order'] = 'goods_id DESC';
		
		
		$seach['sdate'] = $this->input->get_post('sdate');
		$seach['edate'] = $this->input->get('edate');
		
		if($seach['sdate']){
			$seach['sdate'] = str_replace('-','',$seach['sdate']);
			$condition['where']['date_key >='] =  $seach['sdate'];
		}
		
		if($seach['edate']){
			$seach['edate'] = str_replace('-','',$seach['edate']);
			$condition['where']['date_key <='] =  $seach['edate'];
		}
		
		$results = $this->hp_service->getPubHistory($condition,$this->_loginUID);
		
		
		if($results){
			$this->assign('list',$results['data']);
			$this->assign('page',$results['pager']);
		}
		
		
		$this->_breadCrumbs[] = array(
			'title' => '历史求货',
			'url' => $this->uri->uri_string
		);
		
		$this->display();
	}

}
