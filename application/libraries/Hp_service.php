<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hp_service extends Base_service {
	
	private $_sphixClient ;
	private $_hpRecentModel ;
	private $_hpPubModel;
	private $_hpBatchModel;
	
	private $_hpPubHash;
	private $_hpBatchHash;
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->config('sphinx');
		self::$CI->load->model(array('Hp_Recent_Model','Hp_Pub_Model','Hp_Batch_Model'));
		
		$this->_hpRecentModel = self::$CI->Hp_Recent_Model;
		
		//用户发布历史
		$this->_hpPubModel = self::$CI->Hp_Pub_Model;
		//用户发布批次
		$this->_hpBatchModel = self::$CI->Hp_Batch_Model;
		
		$this->_hpPubHash = new Flexihash();
		$this->_hpBatchHash = new Flexihash();
		
		$pubConfig = self::$CI->load->get_config('split_hp_pub');
		$batchConfig = self::$CI->load->get_config('split_hp_batch');

		$this->_hpPubHash->addTargets($pubConfig);
		$this->_hpBatchHash->addTargets($batchConfig);
		
		$this->_sphixClient = new SphinxClient;
	}
	
	
	/**
	 * 最多一次差50个货号
	 */
	private function _processCode($code){
		//print_r($code);
		$code = str_replace(array("\r\n","\n",'-',' ',"\t",'|'),',',trim($code));
		$code = str_replace('，',',',$code);
		$code = trim($code,',');
		
		$tmpCode = explode(',',$code);
		if(count($tmpCode) > 50){
			return implode(',',array_slice($tmpCode,0,50));
		}
		
		return $code;
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
		$list = array();
		
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
		
		if($condition['fields']['gmt_modify']){
			$this->_sphixClient->SetFilterRange('gmt_modify',$condition['fields']['gmt_modify'][0],$condition['fields']['gmt_modify'][1]);
		}
		
		
		$avaibleCode = $this->_processCode($condition['fields']['goods_code']);
		//print_r($avaibleCode);
		$queryStr = array();
		
		if($avaibleCode){
			$queryStr[] = "@goods_code ".str_replace(',','|',$avaibleCode);
		}
		
		if($condition['fields']['goods_name']){
			$queryStr[] = "@goods_name {$condition['fields']['goods_name']}";
		}
		
		if($condition['order']){
			$this->_sphixClient->setSortMode(SPH_SORT_EXTENDED,$condition['order']);
		}
		
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
		
		//print_r($results['matches']);
		
		if($results['matches'] && $results['status'] === 0 && $results['total_found'] > 0){
			
			$getCondition['order'] = $condition['order'];
			$getCondition['where_in'][] = array(
				
				'key' => 'goods_id',
				'value' => array_keys($results['matches'])
			);
			
			$pager = pageArrayGenerator($condition['pager'],$results['total_found']);
			$list = $this->_hpRecentModel->getList($getCondition);
			
			return array('data' => $list,'pager' => $pager['pager']);
		}else{
			return array();
		}
		
		//return $list;
		//return $results;
	}
	
	
	public function getUserLastPubKey($uid){
		return 'pubctrl_'.$uid;
	}
	
	
	/**
	 * time remain to pub
	 * 获得再次发布的 等待时间
	 */
	public function getPubTimeRemain($reqTime ,$uid,$action = 'add'){
		$lastPub = $this->getUserLastPub($uid);
		
		if($lastPub &&  ($reqTime - $lastPub['batch_id']) < 180){
			$freezenSec = $reqTime - $lastPub['batch_id'];
			return (180 - $freezenSec);
		}else{
			return 0;
		}
		
		
	}
	
	
	
	/**
	 * 添加用户控制刷新货品或者刷新货品控制数据
	 * 
	 * 
	 */
	public function addUserHpFrequentCtrl($uid,$reqTime,$rows,$action = 0, $reLookup = false){
		$ctrlData = array(
			'uid' => $uid,
			'action' => $action,
			'batch_id' => $reqTime,
			'cnt' => $rows
		);
		
		if($reLookup){
			$tableId = $this->_hpBatchHash->lookup($uid);
			$this->_hpBatchModel->setTableId($tableId);
		}
		
		$this->_hpBatchModel->_add($ctrlData);
		self::$CI->getCacheObject()->save($this->getUserLastPubKey($uid),$ctrlData,CACHE_ONE_DAY);
	}
	
	
	
	/**
	 * 添加货品
	 */
	public function addHp($insertData,$reqTime,$uid,$reLookup = false){
		$realInsert = $this->_hpRecentModel->batchInsert($insertData);
		$this->addUserHpFrequentCtrl($uid,$reqTime,$realInsert,$reLookup);
		
		return $realInsert;
	}
	
	
	/**
	 * 获得上次发布的数据信息
	 */
	public function getUserLastPub($uid){
		$ctrlKey = $this->getUserLastPubKey($uid);
		
		$lastPub = self::$CI->getCacheObject()->get($ctrlKey);
		if(empty($lastPub)){
			$tableId = $this->_hpBatchHash->lookup($uid);
			$this->_hpBatchModel->setTableId($tableId);
			
			$lastPub = $this->_hpBatchModel->getList(array(
				'where' => array(
					'uid'=> $uid
				),
				'order' => 'batch_id DESC',
				'limit' => 1 
			));
			
			if($lastPub){
				self::$CI->getCacheObject()->save($ctrlKey,$lastPub,CACHE_ONE_DAY);
			}
		}
		
		return $lastPub;
	}
	
	
	/**
	 * 重新激活用户的求货信息
	 */
	public function reactiveUserHpReq($hpid,$time, $uid){
		if(!is_array($hpid)){
			$hpid = (array)$hpid;
		}
		
		if(empty($hpid)){
			return false;
		}
		
		$condition = array(
			'where' => array(
				'uid' => $uid
			),
			'where_in' => array(
				array('key' => 'goods_id' , 'value' => $hpid)
			)
		);
		
		$affectRow = $this->_hpRecentModel->updateByCondition(array('gmt_modify' => $time),$condition);
		if($affectRow){
			//1 means 刷新
			$this->addUserHpFrequentCtrl($uid,$time,$affectRow,1,true);
		}
		
		return $affectRow;
	}
	
	
	/**
	 * 用户历史发布数据查询
	 */
	public function getPubHistory($condition,$uid){
		$tableId = $this->_hpPubHash->lookup($uid);
		$this->_hpPubModel->setTableId($tableId);
		
		$condition['where']['uid'] = $uid;
		
		return $this->_hpPubModel->getList($condition);
		
	}
	
	/**
	 * 珊瑚用户的历史货品
	 */
	public function deleteHistory($condition,$uid){
		$tableId = $this->_hpPubHash->lookup($uid);
		$this->_hpPubModel->setTableId($tableId);
		
		$condition['where']['uid'] = $uid;
		
		$this->_hpPubModel->deleteByCondition($condition);
	}
	
}
