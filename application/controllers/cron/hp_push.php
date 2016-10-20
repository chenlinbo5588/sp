<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 匹配货品
 */
class hp_push extends MY_Controller {


	public function __construct(){
		parent::__construct();
		
		if(!is_cli()){
			exit();
		}
	}
	
	public function index(){
		set_time_limit(0);
		
		$param = $this->input->server('argv');
		//print_r($param);
		$currentDir = pathinfo(__FILE__, PATHINFO_DIRNAME);
		
		$dir = dirname(ROOTPATH);
		//file_put_contents($dir.'/hp_push.txt',print_r($_SERVER,true));
		
		/*
		if(empty($param[3])){
			exit('table param is not given');
		}
		*/
		
		$reqTime = $this->input->server('REQUEST_TIME');
		
		/*
		$memberList = $this->Member_Model->getList(array(
			'select' => 'uid,username,email,notify_ways'
		));
		*/
		
		$this->load->model(array('Hp_Recent_Model','Member_Inventory_Model'));
		
		
		if(ENVIRONMENT == 'production'){
			$inventoryList = $this->Member_Inventory_Model->getList(array(
				'select' => 'uid,username,qq,mobile,email,kw,last_pm',
				'where' => array(
					'enable' => 1,
					'gmt_modify >=' =>  $reqTime - config_item('inventory_expired')
				)
			));
		}else{
			$inventoryList = $this->Member_Inventory_Model->getList(array(
				'select' => 'uid,username,qq,mobile,email,kw,last_pm',
			));
		}
		
		
		if($inventoryList){
			
			/*
			$searchCondition = array(
				'pager' => array(
					'page_size' => 200,
					'current_page' => 1,
					'call_js' => 'search_page',
					'form_id' => '#formSearch'
				),
				'order' => 'gmt_modify DESC',
				'fields' => array(
					'kw'=> ''
				)
			);
			
			
			$this->load->library(array('Message_service','Hp_service'));
			//$this->hp_service->setServer(0);
			*/
			
			if(ENVIRONMENT == 'production'){
				$searchCondition['where']['gmt_modify >='] = $reqTime - config_item('hp_expired');
			}
			
			
			$this->load->library(array('Message_service'));
			$userPmNeedUpdate = array();
			
			foreach($inventoryList as $inventory){
				$notifyUser = false;
				$searchCondition = array(
					'select' => 'goods_id,uid,goods_code,goods_csize,kw,price_max,gmt_modify,uid,username,qq,email,mobile',
					'order' => 'gmt_modify DESC',
					'limit' => 50
				);
			
				$searchCondition['where_in'][] = array(
					'key' => 'kw','value' => explode(',',preg_replace('/#.*?#/i','',$inventory['kw']))
				);
				
				$searchCondition['where']['uid != '] = $inventory['uid'];
				
				$results = $this->Hp_Recent_Model->getList($searchCondition);
				//print_r($searchCondition);
				//print_r($results);
				
				//$this->hp_service->setServer(0);
				//$results = $this->hp_service->automatch_query($searchCondition);
				
				//查询到相关信息
				if($results){
					
					//为了减少消息的中的匹配数据的重复值，存储6小时内的求货值，首先移除 6小时前的匹配过的ID，可有效降低消息中的货品重复度
					// 存储格式
					// 求货流水号=uid=更新时间,求货流水号=uid=更新时间,求货流水号=uid=更新时间
					// 56=1476969122,78=1474532488,123=1474534488
					
					$dealedArray = array();
					if($inventory['last_pm']){
						$historyMatchInfo = explode(',',$inventory['last_pm']);
						
						foreach($historyMatchInfo as $historyItem){
							$detail = explode('=',$historyItem);
							if(($reqTime - $detail[2]) < 21600){
								$dealedArray[] = $historyItem;
							}
						}
						
						$inventory['last_pm'] = implode(',',$dealedArray);
					}
					
					//有新的加入
					$newPushed = array();
					
					foreach($results as $goodsInfo){
						$search_key = $goodsInfo['goods_id'].'='.$goodsInfo['uid'].'='.$goodsInfo['gmt_modify'];
						
						//6 小时内已经匹配过了
						if(strpos($inventory['last_pm'],$search_key) !== false){
							continue;	
						}
						
						$matches = array();
						//找到对应的货号
						$count = preg_match('/'.$goodsInfo['kw'].'#(.*?)#/i',$inventory['kw'],$matches);
						if(!$count){
							continue;
						}
						
						if($matches[1] > $goodsInfo['price_max']){
							continue;
						}
						
						//库存最低出货价格只要不大于求货最高价格,就有交易的可能性
						$newPushed[] = "<div>流水号: {$goodsInfo['goods_id']},货号: {$goodsInfo['goods_code']},尺码:{$goodsInfo['goods_csize']},求货方:QQ:{$goodsInfo['qq']},手机:{$goodsInfo['mobile']}</div>";
						$dealedArray[] = $search_key;
					}
					
					
					if($newPushed){
						//求货方获得站内信 和邮件提醒
						//库存方暂时不提醒
						/*
						$data = array(
							'title' => '匹配到求货信息,请及时联系求货人',
							'content' => "<div>"
						)
						*/
						$this->message_service->pushEmailMessageToUser();
						$userPmNeedUpdate[] = array(
							'uid' => $inventory['uid'],
							'last_pm' => implode(',',$dealedArray)
						);
					}
					
					if(count($userPmNeedUpdate) >= 30){
						$this->Member_Inventory_Model->batchUpdate($userPmNeedUpdate,'uid');
						$userPmNeedUpdate = array();
					}
				}
				
				if($userPmNeedUpdate){
					$this->Member_Inventory_Model->batchUpdate($userPmNeedUpdate,'uid');
				}
				
				//file_put_contents("hp_match_{$inventory['uid']}.txt",print_r($inventory['kw'],true));
				//file_put_contents("hp_match_{$inventory['uid']}.txt",print_r($searchCondition,true),FILE_APPEND);
				//file_put_contents("hp_match_{$inventory['uid']}.txt",print_r($results,true),FILE_APPEND);
				
				//print_r($results);
			}
		}
	}
	
	
}
