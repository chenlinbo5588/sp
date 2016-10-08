<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hp_service extends Base_service {
	
	private $_sphixClient ;
	
	
	public function __construct(){
		parent::__construct();
		$this->_sphixClient = new SphinxClient;
		self::$CI->load->config('sphinx');
		
	}
	
	
	/**
	 * 最多一次差50个货号
	 */
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
	}
	
	
	/**
	 * 设置服务器
	 */
	public function setServer($serverIndex){
		$configList = config_item('sphinx');
		
		$this->_sphixClient->setServer($configList[$serverIndex]['host'],$configList[$serverIndex]['port']);
		$this->_sphixClient->setMatchMode(SPH_MATCH_EXTENDED);
		//$this->_sphixClient->setRankingMode(SPH_RANK_NONE);
		//$this->_sphixClient->setMaxQueryTime(10);
		
	}
	
	
	
	
	/**
	 * 
	 */
	public function query($condition,$source = 'hp_recent'){
		
		/*
		foreach($condition['fields'] as $field){
			switch($field['type']){
				case 'Filter':
					$this->_sphixClient->SetFilter($field['name'], $field['value']);
					break;
				case 'FloatRange':
					$this->_sphixClient->SetFilterFloatRange($field['name'],$field['value1'],$field['value2']);
					break;
				case 'FilterRange':
					$this->_sphixClient->SetFilterRange($field['name'],$field['value1'],$field['value2']);
					break;
				default:
					break;
			}
		}
		*/
		
		
		if($condition['fields']['uid']){
			$this->_sphixClient->SetFilter('uid',$condition['fields']['uid']);
		}
		
		if($condition['fields']['sex']){
			$this->_sphixClient->SetFilter('sex',$condition['fields']['sex']);
		}
		
		if($condition['fields']['goods_size']){
			$this->_sphixClient->SetFilterFloatRange('goods_size',$condition['fields']['goods_size'][0],$condition['fields']['goods_size'][1]);
		}
		
		if($condition['fields']['price_max']){
			$this->_sphixClient->SetFilterRange('price_max',$condition['fields']['price_max'][0],$condition['fields']['price_max'][1]);
		}
		
		//print_r($avaibleCode);
		$avaibleCode = $this->_processCode($condition['fields']['goods_code']);
		$queryStr = array();
		
		if($avaibleCode){
			$queryStr[] = "@goods_code ".str_replace(',','|',$avaibleCode);
		}
		
		if($condition['fields']['goods_name']){
			$queryStr[] = "@goods_name {$condition['fields']['goods_name']}";
		}
		
		$this->_sphixClient->setSortMode(SPH_SORT_ATTR_DESC,$condition['order_field']);
		
		if($condition['pager']){
			$this->_sphixClient->SetLimits(($condition['pager']['current_page'] - 1) * $condition['pager']['page_size'], $condition['pager']['page_size']);
		}
		
		if($queryStr){
			$results = $this->_sphixClient->query(implode(' ',$queryStr),$source);
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
			$results = $this->_sphixClient->query('',$source);
		}
		
		return $results;
	}
	
}
