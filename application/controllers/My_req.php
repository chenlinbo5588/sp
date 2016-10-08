<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class My_req extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Hp_Recent_Model');
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
			'order_field' => 'gmt_modify',
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
	
	
	/**
	 * 最近求货
	 */
	public function recent()
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
		
		$searchCondition = $this->_prepareParam($pageParam);
		
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
			
		}
		
		$this->display();
	}
	

	
	public function history(){
		
		
		
		
		$this->display('my_req/recent');
	}

}
