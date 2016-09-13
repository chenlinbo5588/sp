<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Goods extends MyYdzj_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Goods_service');
	}
	
	
	private function _processCode($code){
		//print_r($code);
		$code = str_replace(array("\r\n","\n",'-',' ',"\t"),'',trim($code));
		$code = str_replace('，',',',$code);
		
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
		
		//$searchKeys['gn'] = $this->input->get_post('gn');
		$searchKeys['gc'] = $this->input->get_post('gc');
		$searchKeys['s1'] = $this->input->get_post('s1');
		$searchKeys['s2'] = $this->input->get_post('s2');
		$searchKeys['cnum'] = $this->input->get_post('cnum');
		
		
		$avaibleCode = $this->_processCode($searchKeys['gc']);
		
		$client = new SphinxClient;
		$client->setServer("localhost",9312);
		$client->setMatchMode(SPH_MATCH_EXTENDED);
		//$client->setRankingMode(SPH_RANK_NONE);
		//$client->setMaxQueryTime(10);
		//$client->setSortMode(SPH_SORT_ATTR_ASC,'uid');
		$client->setSortMode(SPH_SORT_ATTR_DESC,'gmt_modify');
		$client->SetLimits(($pageParam['current_page'] - 1) * $pageParam['page_size'], $pageParam['page_size']);
		$client->SetFilter ('status', array (0));
		
		
		if(empty($searchKeys['s1']) && !empty($searchKeys['s2'])){
			$client->SetFilterRange('goods_size',0,floatval($searchKeys['s2']));
		}else if(!empty($searchKeys['s1']) && empty($searchKeys['s2'])){
			$client->SetFilterRange('goods_size',floatval($searchKeys['s2']),50);
		}else if(!empty($searchKeys['s1']) && !empty($searchKeys['s2'])){
			$s1 = floatval($searchKeys['s1']);
			$s2 = floatval($searchKeys['s2']);
			
			if($s1 > $s2){
				$client->SetFilterRange('goods_size',$s2,$s1);
			}else{
				$client->SetFilterRange('goods_size',$s1,$s2);
			}
		}
		
		
		if($searchKeys['cnum']){
			$client->SetFilterRange('cnum',intval($searchKeys['cnum']),1000);
		}
		
		
		//print_r($avaibleCode);
		
		if($avaibleCode){
			$results = $client->query(str_replace(',','|',$avaibleCode),'goods_recent');
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
		
		//var_dump($results);
		
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
			$list = $this->Goods_Recent_Model->getList($condition);
			//print_r($pager);
			$this->assign('page',$list['pager']);
			$this->assign('list',$list['data']);
		}
		
		//print_r($result);
		
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
