<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Hp extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Hp_Recent_Model');
	}
	
	
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
		
		/*
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
		*/
	}
	
	
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
		
		
		$searchKeys['gn'] = $this->input->get_post('gn');
		$searchKeys['gc'] = $this->input->get_post('gc');
		$searchKeys['sex'] = intval($this->input->get_post('sex'));
		
		//尺码
		$searchKeys['s1'] = floatval($this->input->get_post('s1'));
		$searchKeys['s2'] = floatval($this->input->get_post('s2'));
		
		//剩余数量
		$searchKeys['cn1'] = intval($this->input->get_post('cn1'));
		$searchKeys['cn2'] = intval($this->input->get_post('cn2'));
		
		$avaibleCode = $this->_processCode($searchKeys['gc']);
		
		$client = new SphinxClient;
		$client->setServer("localhost",9312);
		$client->setMatchMode(SPH_MATCH_EXTENDED);
		//$client->setRankingMode(SPH_RANK_NONE);
		//$client->setMaxQueryTime(10);
		//$client->setSortMode(SPH_SORT_ATTR_ASC,'uid');
		$client->setSortMode(SPH_SORT_ATTR_DESC,'gmt_modify');
		$client->SetLimits(($pageParam['current_page'] - 1) * $pageParam['page_size'], $pageParam['page_size']);
		//$client->SetFilter ('status', array (0));
		
		
		if($searchKeys['sex']){
			$client->SetFilter ('sex', array($searchKeys['sex']));
		}
		
		if(empty($searchKeys['s1']) && !empty($searchKeys['s2'])){
			if($searchKeys['s2'] > 50){
				$searchKeys['s2'] = 50;
			}
		}else if(!empty($searchKeys['s1']) && empty($searchKeys['s2'])){
			$searchKeys['s2'] = 50;
		}
		
		if($searchKeys['s1'] > $searchKeys['s2']){
			$tmp = $searchKeys['s1'];
			$searchKeys['s1'] = $searchKeys['s2'];
			$searchKeys['s2'] = $tmp;
		}
		
		if($searchKeys['s1'] || $searchKeys['s2'] ){
			$client->SetFilterFloatRange('goods_size',$searchKeys['s1'],$searchKeys['s1']);
		}
		
		if(empty($searchKeys['cn1']) && !empty($searchKeys['cn2'])){
			if($searchKeys['cn2'] > 50){
				$searchKeys['cn2'] = 50;
			}
		}else if(!empty($searchKeys['cn1']) && empty($searchKeys['cn2'])){
			$searchKeys['cn2'] = 50;
		}
		
		if($searchKeys['cn1'] > $searchKeys['cn2']){
			$tmp2 = $searchKeys['cn1'];
			$searchKeys['cn1'] = $searchKeys['cn2'];
			$searchKeys['cn2'] = $tmp2;
		}
		
		if($searchKeys['cn1'] || $searchKeys['cn2'] ){
			$client->SetFilterRange('cnum',$searchKeys['cn1'],$searchKeys['cn2']);
		}
		
		
		//print_r($avaibleCode);
		$queryStr = array();
		if($searchKeys['gn']){
			$queryStr[] = "@goods_name {$searchKeys['gn']}";
		}
		
		if($avaibleCode){
			$queryStr[] = "@goods_code ".str_replace(',','|',$avaibleCode);
		}
		
		if($queryStr){
			$results = $client->query(implode(' ',$queryStr),'goods_recent');
			/*
			 * 
			 * 多个查询
			$codeList = explode(',',$avaibleCode);
			print_r($codeList);
			foreach($codeList as $code){
				$client->AddQuery($code,'goods_recent');
			}
			
			$results = $client->RunQueries();
			*/
		}else{
			$results = $client->query('','goods_recent');
		}
		
		
		//print_r($results);
		//file_put_contents('debug.txt',print_r($results,true));
		
		if($results['matches'] && $results['status'] === 0){
			$condition['where_in'][] = array(
				'key' => 'goods_id',
				'value' => array_keys($results['matches'])
			);
			
			unset($condition['pager']);
			
			$pager = pageArrayGenerator($pageParam,$results['total_found']);
			
			//print_r($condition);
			
			$list = $this->Goods_Recent_Model->getList($condition);
			$this->assign('list',$list);
			$this->assign('page',$pager['pager']);
			
		}else{

			//print_r($condition);
			$list = $this->Hp_Recent_Model->getList($condition);
			$this->assign('page',$list['pager']);
			$this->assign('list',$list['data']);
		}
		
		
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
		$uid = array();
		foreach($list as $item){
			$uid[] = $item['uid'];
		}
		$userList = $this->Member_Model->getUserListByIds($uid,'uid,nickname,qq,mobile');
		//print_r($userList);
		
		$this->assign('userList',$userList);
		
		
		$this->display();
	}
}
