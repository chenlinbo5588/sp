<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_service extends Base_service {
	private $_memberSlotModel;
	private $_inventoryModel;
	private $_reactiveFreezen;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Inventory_Model','Member_Slot_Model'));
		$this->_inventoryModel = self::$CI->Inventory_Model;
		$this->_memberSlotModel = self::$CI->Member_Slot_Model;
		
		$this->_reactiveFreezen = config_item('inventory_freezen');
	}
	
	
	/**
	 * 激活控制
	 */
	public function getUserInventoryActiveKey($uid){
		return 'inventoryactive_'.$uid;
	}
	
	
	/**
	 * 获得再次激活 等待时间
	 */
	public function getReactiveTimeRemain($reqTime ,$uid){
		$lastPub = $this->getUserCurrentInventory($uid,'gmt_modify');
		
		if($lastPub &&  ($reqTime - $lastPub['gmt_modify']) < $this->_reactiveFreezen){
			$freezenSec = $reqTime - $lastPub['gmt_modify'];
			return ($this->_reactiveFreezen - $freezenSec);
		}else{
			return 0;
		}
	}
	
	
	/**
	 * 重新激活用户的库存 ，这样可以参与到自动匹配
	 */
	public function reactiveUserInventory($time, $uid){
		
		$condition = array(
			'where' => array(
				'uid' => $uid
			)
		);
		
		$affectRow = $this->_memberSlotModel->updateByCondition(array('gmt_modify' => $time),$condition);
		
		return $affectRow;
	}
	
	
	/**
	 * 获得用户库存
	 */
	public function getUserCurrentInventory($uid,$field = '*'){
		
		$info = $this->_memberSlotModel->getFirstByKey($uid,'uid');
		if(empty($info)){
			$initData = array(
				'uid' => $uid,
				'slot_num' => 10,
			);
			
			for($i = 1; $i <= 10; $i++){
				$initData['slot_config'][$i] = array(
					'id' => $i,
					'title' => '货柜'.$i,
					'cnt' => 0,
					'goods_code' => '',
					'max_cnt' => 50,
				);
			}
			
			$initData['slot_config'] = json_encode($initData['slot_config']);
			
			$this->_memberSlotModel->_add($initData);
			$info = $initData;
		}
		
		$info['slot_config'] = json_decode($info['slot_config'],true);
		
		return $info;
		
	}
}
