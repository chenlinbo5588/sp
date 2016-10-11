<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_service extends Base_service {
	
	
	private $_reactiveFreezen;
	
	private $_memberSlotModel;
	private $_inventoryModel;
	private $_memberInventoryModel;
	
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Inventory_Model','Member_Slot_Model','Member_Inventory_Model'));
		$this->_inventoryModel = self::$CI->Inventory_Model;
		$this->_memberSlotModel = self::$CI->Member_Slot_Model;
		$this->_memberInventoryModel = self::$CI->Member_Inventory_Model;
		
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
	 * 
	 */
	public function getSlotDetail($slot_id,$uid,$field = '*'){
		$condition = array(
			'select' => $field,
			'where' => array(
				'uid' => $uid,
				'slot_id' => $slot_id
			)
		);
		
		return $this->_memberInventoryModel->getById($condition);
		
	}
	
	
	
	
	/**
	 * 配置 货柜 货号信息
	 */
	public function setSlotGoodsCode($slot_id,$goods_code,$uid){
		
		$inventory = $this->getUserCurrentInventory($uid);
		
		$inventory['slot_config'][$slot_id]['goods_code'] = $goods_code;
		$inventory['slot_config'] = json_encode($inventory['slot_config']);
		
		
		return $this->_memberSlotModel->_add($inventory,true);
		
	}
	
	
	/**
	 * 更新货柜 货品
	 */
	public function updateSlotGoodsInfo($data,$uid){
		
		if($data['goods_list']){
			
			$kw = array();
			$kw_price = array();
			
			foreach($data['goods_list'] as $good){
				$kw[] = str_replace('.','',str_replace(array('-','_'),'',$good['goods_code']).$good['goods_size']);
				$kw_price[] = $good['price_min'];
			}
			
			$data['kw'] = implode('|',$kw);
			$data['kw_price'] = implode('|',$kw_price);
			
			$data['goods_list'] = json_encode($data['goods_list']);
		}
		
		return $this->_memberInventoryModel->_add($data,true);
	}
	
	
	
	/**
	 * 获得 货柜货品列表
	 */
	public function getSlotGoodsList(){
		
		
		
		
		
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
