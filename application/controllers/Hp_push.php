<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 匹配货品
 */
class Hp_push extends MY_Controller {
	
	private $_userPmNeedUpdate;
	private $_justUpdateMatchTime;
	
	private $_debug;
	
	public function __construct(){
		parent::__construct();
		$this->_debug = false;
		
		if(!is_cli()){
			exit();
		}
		
		$this->benchmark->mark('code_start');
		$this->initParam();
	}
	
	private function initParam(){
		
		$param = $this->input->server('argv');
		//print_r($param);
		//$currentDir = pathinfo(__FILE__, PATHINFO_DIRNAME);
		
		//$dir = dirname(ROOTPATH);
		//file_put_contents($dir.'/hp_push.txt',print_r($_SERVER,true));
		
		/*
		if(empty($param[3])){
			exit('table param is not given');
		}
		*/
		//$this-> = $this->input->server('REQUEST_TIME');
		$this->_userPmNeedUpdate = array();
		$this->_justUpdateMatchTime = array();
		
	}
	
	
	/**
	 * 数据处理
	 */
	private function result_process($inventory,$results){
		//有新匹配时 ，该数组有值
		$newPushed = array();
		
		// 为了减少消息的中的匹配数据的重复值，存储24小时内的求货历史匹配记录，首先移除24小时前的匹配过的ID，可有效降低消息中的货品重复度
		// 存储格式
		// 求货流水号=uid=更新时间,求货流水号=uid=更新时间,求货流水号=uid=更新时间
		// 56=1476969122,78=1474532488,123=1474534488
		$dealedArray = array();
		
		if($inventory['last_pm']){
			$historyMatchInfo = explode(',',$inventory['last_pm']);
			foreach($historyMatchInfo as $historyItem){
				$detail = explode('=',$historyItem);
				if(($this->_reqtime - $detail[2]) < 86400){
					$dealedArray[] = $historyItem;
				}
			}
			
			$inventory['last_pm'] = implode(',',$dealedArray);
		}
		
		$reqUserList = array();
		
		foreach($results as $goodsInfo){
			//创建时间不会被修改，这样用户求货刷新了，不会触发重复提醒
			$search_key = $goodsInfo['goods_id'].'='.$goodsInfo['uid'].'=';
			$store_key = $search_key.$goodsInfo['gmt_modify'];
			
			$matches = array();
			
			// 如果用户刷新了 
			//24  小时内已经匹配过了
			if(strpos($inventory['last_pm'],$search_key) !== false){
				continue;
			}
			
			//找到对应的货号
			$count = preg_match('/'.$goodsInfo['kw'].'#(.*?)#/i',$inventory['kw'],$matches);
			if(!$count){
				continue;
			}
			
			if($matches[1] > $goodsInfo['price_max']){
				continue;
			}
			
			//库存最低出货价格只要不大于求货最高价格,就有交易的可能性，准备消息内容
			$msgTpl = "<div>求货流水号: {$goodsInfo['goods_id']},货号: {$goodsInfo['goods_code']},尺码:{$goodsInfo['goods_csize']},{QQ},{MOBILE},{USERNAME}</div><hr/>";
			
			$newPushed[] = str_replace(
				array('{QQ}','{MOBILE}','{USERNAME}'),
				array(
					"求货方:QQ:<a target=\"_blank\" class=\"qqchat\" href=\"http://wpa.qq.com/msgrd?v=3&uin={$goodsInfo['qq']}&site=qq&menu=yes\" alt=\"点击这里给我发消息\" title=\"点击这里给我发消息\">{$goodsInfo['qq']}</a>",
					"手机:{$goodsInfo['mobile']}",
					"用户名:{$goodsInfo['username']}"
				),$msgTpl);
				
			$dealedArray[] = $store_key;
			
			//求货方  通知数据准备, 由于库存匹配已经进行了去重逻辑，因此发送消息给求货方时直接准备消息就可以了
			if(!$reqUserList[$goodsInfo['uid']]){
				$reqUserList[$goodsInfo['uid']] = array(
					'msg' => array(),
					'uid' => $goodsInfo['uid'],
					'username' => $goodsInfo['username'],
					'email' => $goodsInfo['email'],
				);
			}
			
			$reqUserList[$goodsInfo['uid']]['msg'][] = str_replace(
				array('{QQ}','{MOBILE}','{USERNAME}'),
				array(
					"库存方:QQ:<a target=\"_blank\" class=\"qqchat\" href=\"http://wpa.qq.com/msgrd?v=3&uin={$inventory['qq']}&site=qq&menu=yes\" alt=\"点击这里给我发消息\" title=\"点击这里给我发消息\">{$inventory['qq']}</a>",
					"手机:{$inventory['mobile']}",
					"用户名:{$inventory['username']}"
				),$msgTpl);
		}
		
		//print_r($reqUserList);
		
		/**
		 * 通知求货方
		 */
		if($reqUserList){
			foreach($reqUserList as $uid => $reMsg){
				$data1 = array(
					'title' => '【求货匹配】系统为您的求货匹配到了库存方联系方式，请您及时查看进行相关操作',
					'content' => implode('',$reMsg['msg'])
				);
				
				$this->message_service->pushEmailMessageToUser(
					array_merge($data1,array(
						'username'=>$reMsg['username'],
						'email' => $reMsg['email'],
						'uid' => $uid,
						)
					),$uid);
				
				$this->message_service->pushPmMessageToUser(
					array_merge($data1,array(
						'uid' => $uid,
						'msg_type' => 1,
						'from_uid' => 0,
					)),$uid);
			}
		}
		
		
		//print_r($dealedArray);
		print_r($newPushed);
		
		if($newPushed){
			//求货方获得站内信 和邮件提醒
			
			//添加库存方用户 消息提醒，邮件和站内信
			$data = array(
				'title' => '【求货匹配】系统为你匹配到求货人信息,请及时查看联系',
				'content' => implode('',$newPushed)
			);
			
			$this->message_service->pushEmailMessageToUser(
				array_merge($data,array('uid' =>$inventory['uid'],  'username'=>$inventory['username'],'email' => $inventory['email'])),$inventory['uid']);
			
			$this->message_service->pushPmMessageToUser(
				array_merge($data,array(
					'uid' => $inventory['uid'],
					'msg_type' => 1,
					'from_uid' => 0,
				)),$inventory['uid']);
		}
		
		// 表示上次匹配情况有更新了, 延迟更新 
		if($dealedArray){
			$this->_userPmNeedUpdate[] = array(
				'uid' => $inventory['uid'],
				'last_match' => $this->_reqtime,
				'gmt_modify' => $inventory['gmt_modify'],//更新时间保持不变
				'last_pm' => implode(',',$dealedArray)
			);
		}else{
			//没有新的匹配 也更新一下匹配时间，让自己有限级下级
			$this->_justUpdateMatchTime[] = array(
				'uid' => $inventory['uid'],
				'last_match' => $this->_reqtime,
				'gmt_modify' => $inventory['gmt_modify'],
			);
		}
	}
	
	
	public function index(){
		set_time_limit(0);
		$this->load->model(array('Hp_Recent_Model','Member_Inventory_Model'));
		
		//每次进行少量数据的匹配,利用 last_match 降序更新  实现自动轮转
		if(!$this->_debug){
			echo "IN Production\n";
			$inventoryList = $this->Member_Inventory_Model->getList(array(
				'select' => 'uid,username,qq,mobile,email,kw,last_pm,gmt_modify',
				'where' => array(
					'enable' => 1,
					'gmt_modify >=' =>  $this->_reqtime - config_item('inventory_expired')
				),
				'order' => 'last_match ASC',
				'limit' => 20
			));
		}else{
			$inventoryList = $this->Member_Inventory_Model->getList(array(
				'select' => 'uid,username,qq,mobile,email,kw,last_pm,gmt_modify',
				'where' => array(
					'enable' => 1
				),
				'order' => 'last_match ASC',
				'limit' => 20
			));
		}
		
		//可以考虑分用户段扫描  增加传输参数
		
		echo "Current Inventory ".count($inventoryList)."\n";
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
			
			$this->load->library(array('Message_service'));
			$userPmNeedUpdate = array();
			
			/**
			 * 扫描用户库存，去匹配用户求货
			 */
			foreach($inventoryList as $inventory){
				//$inventory 当前用户库存
				
				$searchCondition = array(
					'where' => array(
						'uid != ' => $inventory['uid']
					),
					'select' => 'goods_id,goods_code,goods_csize,kw,price_max,gmt_modify,uid,username,qq,email,mobile',
					'order' => 'gmt_modify DESC',
					'limit' => 50
				);
				
				if(!$this->_debug){
					$searchCondition['where']['gmt_modify >='] = $this->_reqtime - config_item('hp_expired');
				}
				
				$searchCondition['where_in'][] = array(
					'key' => 'kw','value' => explode(',',preg_replace('/#.*?#/i','',$inventory['kw']))
				);
				
				$results = $this->Hp_Recent_Model->getList($searchCondition);
				if($results){
					//print_r($results);
					$this->result_process($inventory,$results);
				}
				
				if(count($this->_userPmNeedUpdate) >= 10){
					$this->Member_Inventory_Model->batchUpdate($this->_userPmNeedUpdate,'uid');
					$this->_userPmNeedUpdate = array();
				}
				
				//print_r($searchCondition);
				//print_r($results);
				//$this->hp_service->setServer(0);
				//$results = $this->hp_service->automatch_query($searchCondition);
				
				//file_put_contents("hp_match_{$inventory['uid']}.txt",print_r($inventory['kw'],true));
				//file_put_contents("hp_match_{$inventory['uid']}.txt",print_r($searchCondition,true),FILE_APPEND);
				//file_put_contents("hp_match_{$inventory['uid']}.txt",print_r($results,true),FILE_APPEND);
				//print_r($results);
			}
			
			// 把剩余的更新掉
			if($this->_userPmNeedUpdate){
				$this->Member_Inventory_Model->batchUpdate($this->_userPmNeedUpdate,'uid');
			}
			
			// 刷新上次匹配时间
			if($this->_justUpdateMatchTime){
				$this->Member_Inventory_Model->batchUpdate($this->_justUpdateMatchTime,'uid');
			}
		}
		
		$this->benchmark->mark('code_end');
		echo 'Hp Match Used : '.$this->benchmark->elapsed_time('code_start', 'code_end')." seconds \n";
	}
}
