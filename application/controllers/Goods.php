<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends MyYdzj_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Goods_service');
	}
	
	
	public function index()
	{
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'gmt_modify ASC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$s = new SphinxClient;
		$s->setServer("localhost",9312 );
		$s->setMatchMode(SPH_MATCH_EXTENDED);
		$s->setRankingMode(SPH_RANK_NONE);
		$s->setMaxQueryTime(3);
		$result = $s->query("乔");
		
		//var_dump($result);
		
		
		$searchKeys['name'] = $this->input->get_post('name');
		$searchKeys['code'] = $this->input->get_post('code');
		$searchKeys['size'] = $this->input->get_post('size');
		$searchKeys['cnum'] = $this->input->get_post('cnum');
		
		
		/*
		foreach($searchKeys as $sk => $sv){
			if(empty($sv)){
				continue;
			}
			
			if($sk != 'cnum'){
				$condition['where']['goods_'.$sk] = trim($sv);
			}else{
				$condition['where'][$sk] = trim($sv);
			}
		}
		*/
		
		$list = $this->Goods_Recent_Model->getList(
			$condition
		);
		
		
		$uid = array();
		foreach($list['data'] as $item){
			$uid[] = $item['uid'];
		}
		
		$userList = $this->Member_Model->getUserListByIds($uid,'uid,nickname,qq,mobile');
		//print_r($userList);
		
		$this->assign('userList',$userList);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->display();
	}
}
