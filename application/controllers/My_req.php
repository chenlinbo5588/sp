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
	 * 删除货品
	 */
	public function recent_del(){
		
		
		
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
	
	
	/**
	 * 
	 */
	public function history(){
		
		$condition = $this->_preparePager();
		
		$results = $this->hp_service->getPubHistory();
		
		
		if($results){
			$this->assign('list',$results['data']);
			$this->assign('page',$results['pager']);
		}
		
		$this->display('my_req/recent');
	}

}
