<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_service extends Base_service {
	
	
	private $_reactiveFreezen;
	
	private $_memberSlotModel;
	private $_memberInventoryModel;
	
	//private $_memberColorModel;
	//private $_memberColorHash;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Member_Slot_Model','Member_Inventory_Model'));
		$this->_memberSlotModel = self::$CI->Member_Slot_Model;
		$this->_memberInventoryModel = self::$CI->Member_Inventory_Model;
		$this->_memberColorModel = self::$CI->Member_Color_Model;
		
		/*
		$this->_memberColorHash = new Flexihash();
		$colorSplit = self::$CI->load->get_config('split_color');
		$this->_memberColorHash->addTargets($colorSplit);
		*/
		
		$this->_reactiveFreezen = config_item('inventory_freezen');
	}
	
	
	/**
	 * 激活控制 所有货柜 做为一个整体 计算刷新时间
	 */
	public function getUserSlotsActiveKey($uid){
		return 'slotsactive_'.$uid;
	}
	
	
	/**
	 * 获得再次激活 等待时间
	 */
	public function getReactiveTimeRemain($reqTime ,$uid, $userSlots = array()){
		
		if(empty($userSlots)){
			$userSlots = $this->getUserCurrentSlots($uid,'active_time');
		}
		
		if($userSlots &&  ($reqTime - $userSlots['active_time']) < $this->_reactiveFreezen){
			$freezenSec = $reqTime - $userSlots['active_time'];
			return ($this->_reactiveFreezen - $freezenSec);
		}else{
			return 0;
		}
	}
	
	
	/**
	 * 获得用户所有的得库存 ,最多 50 条记录 即50个格子
	 */
	public function getUserAllInventory($uid){
		$inventoryList = $this->_memberInventoryModel->getList(array(
			'where' => array(
				'uid' => $uid,
			)
		));
		
		if($inventoryList){
			foreach($inventoryList as $key => $slot){
				$inventoryList[$key]['goods_list'] = json_decode($inventoryList[$key]['goods_list']);
			}
		}
		
		
		return $inventoryList;
		
	}
	
	/**
	 * 重新激活用户的库存 ，这样可以参与到自动匹配
	 */
	public function reactiveUserSlots($time, $uid){
		$affectRow = 0;
		
		$slotList = $this->_memberInventoryModel->getList(array(
			'where' => array(
				'uid' => $uid,
			)
		));
		
		$condition = array(
			'where' => array(
				'uid' => $uid
			)
		);
			
		
		if($slotList){
			$kwList = array();
			$kwPriceList = array();
			foreach($slotList as $slot){
				$slot['kw'] = trim($slot['kw']);
				$slot['kw_price'] = trim($slot['kw_price']);
				
				if($slot['kw']){
					$kwList[] = $slot['kw'];
				}
				
				if($slot['kw_price']){
					$kwPriceList[] = $slot['kw_price'];
				}
			}
			
			$updateData = array(
				'active_time' => $time, 
				'kw' => implode('|',$kwList),
				'kw_price' => implode('|',$kwPriceList)
			);
			
			
			$affectRow = $this->_memberSlotModel->updateByCondition($updateData,$condition);
			
		}else{
			
			//所以货柜都空
			$updateData = array(
				'active_time' => $time, 
				'kw' => '',
				'kw_price' => '',
			);
			
			$affectRow = $this->_memberSlotModel->updateByCondition($updateData,$condition);
			
		}
		
		return $affectRow;
	}
	
	
	
	/**
	 * 获得某一个货柜的 详情
	 */
	public function getSlotDetail($slot_id,$uid,$field = '*'){
		$condition = array(
			'select' => $field,
			'where' => array(
				'uid' => $uid,
				'slot_id' => $slot_id
			)
		);
		
		$slot =  $this->_memberInventoryModel->getById($condition);
		$slot['goods_list'] = json_decode($slot['goods_list'],true);
		
		return $slot;
	}
	
	
	/**
	 * 转换成 POST data 形式
	 */
	public function formatGoodsListAsPostStyle($slotInfo){
		$postData = array();
		foreach($slotInfo['goods_list'] as $goods){
			$postData['goods_color'][] = $goods['goods_color'];
			$postData['goods_size'][] = $goods['goods_size'];
			$postData['quantity'][] = $goods['quantity'];
			$postData['sex'][] = $goods['sex'];
			$postData['price_min'][] = $goods['price_min'];
		}
		
		return $postData;
	}
	
	
	
	/**
	 * 更新用户 某一个货号的 货品列表 
	 */
	public function updateUserInventory($data,$uid){
		$data['hp_cnt'] = count($data['goods_list']);
		$data['goods_list'] = json_encode($data['goods_list']);
		$data['uid'] = $uid;
		
		return $this->_memberInventoryModel->_add($data,true);
		
		
	}
	
	
	
	/**
	 * 更新用户 货柜信息表
	 */
	public function updateUserSlot($slot,$uid){
		$slot['slot_config'] = json_encode($slot['slot_config']);
		
		return $this->_memberSlotModel->update($slot,array('uid' => $uid));
	}
	
	
	
	/**
	 * 配置 货柜 货柜标题
	 */
	public function setSlotTitle($slot_id,$title,$uid){
		$slots = $this->getUserCurrentSlots($uid);
		$slots['slot_config'][$slot_id]['title'] = $title;
		return $this->updateUserSlot($slots,$uid);
	}
	
	
	/**
	 * 配置 货柜 货号信息
	 */
	public function setSlotGoodsCode($slot_id,$goods_code,$uid){
		$slots = $this->getUserCurrentSlots($uid);
		
		if($slots['slot_config'][$slot_id]['cnt'] > 0){
			//还有货品不允许更改
			return 0;
		}
		
		$slots['slot_config'][$slot_id]['goods_code'] = $goods_code;
		return $this->updateUserSlot($slots,$uid);
	}
	
	
	/**
	 * 更新货柜 货品
	 */
	public function updateSlotGoodsInfo($slots,$data,$uid){
		
		$newCount = 0;
		if($data['goods_list']){
			$kw = array();
			$kw_price = array();
			
			foreach($data['goods_list'] as $good){
				$kw[] = str_replace('.','',str_replace(array('-','_'),'',$good['goods_code']).'S'.$good['goods_size']);
				$kw_price[] = $good['price_min'];
			}
			
			
			$data['kw'] = implode('|',$kw);
			$data['kw_price'] = implode('|',$kw_price);
			
			$newCount = count($data['goods_list']);
			
			$data['goods_list'] = json_encode($data['goods_list']);
		}else{
			$data['goods_list'] = json_encode(array());
			$data['kw'] = '';
			$data['kw_price'] = '';
		}
		
		
		$row = $this->_memberInventoryModel->_add($data,true,'update');
		
		$hpCount = 0;
		
		if($row){
			/* 更新库存表  */
			foreach($slots['slot_config'] as $slot_id => $slot){
				if($slot_id == $data['slot_id']){
					// 为0 表示已被清空，可以不计入统计
					$slots['slot_config'][$slot_id]['cnt'] = $newCount;
					$hpCount += $newCount;
					
				}else{
					$hpCount += $slot['cnt'];
				}
			}
			
			$slots['hp_cnt'] = $hpCount;
			
			//让系统自动更新时间
			unset($slots['gmt_modify']);
			$this->updateUserSlot($slots,$uid);
		}
			
		
		return $row;
	}
	
	
	/**
	 * 获得用户当前库存
	 */
	public function getUserCurrentInventory($uid,$field = '*'){
		$inventory = $this->_memberInventoryModel->getFirstByKey($uid,'uid');
		
		if($inventory){
			$inventory['goods_list'] = json_decode($inventory['goods_list'],true);
		}
		
		return $inventory;
	}
	
	/**
	 * 获得用户库存
	 */
	public function getUserCurrentSlots($uid,$field = '*'){
		$info = $this->_memberSlotModel->getFirstByKey($uid,'uid');
		if(empty($info)){
			$initSize = 50;
			
			$initData = array(
				'uid' => $uid,
				'slot_num' => $initSize,
				'kw' => '',
				'kw_price' => ''
			);
			
			for($i = 1; $i <= $initSize; $i++){
				$initData['slot_config'][$i] = array(
					'id' => $i,
					'title' => '货柜'.$i,
					'cnt' => 0,
					'goods_code' => '',
					'max_cnt' => 30,
				);
			}
			
			$initData['slot_config'] = json_encode($initData['slot_config']);
			
			$this->_memberSlotModel->_add($initData);
			$info = $initData;
		}
		
		$info['slot_config'] = json_decode($info['slot_config'],true);
		return $info;
		
	}
	
	
	/*  --------------------- 以下颜色管理 已废弃，不需要做管理--------------------------- */
	
	/*
	public function addColor($colorName,$uid){
		$this->setColorTableByUid($uid);
		
		return $this->_memberColorModel->_add(array(
			'color_name' => $colorName,
			'uid' => $uid
		));
	}
	
	public function editColor($colorName,$id,$uid){
		$this->setColorTableByUid($uid);
		return $this->_memberColorModel->update(array(
			'color_name' => $colorName,
		),array(
			'id' => $id
		));
	}
	*/
	
	
	/**
	 * 设置 member color tableid
	 */
	
	/*
	public function setColorTableByUid($uid){
		$tableId = $this->_memberColorHash->lookup($uid);
		$this->_memberColorModel->setTableId($tableId);
	}
	*/
	
	/**
	 * 获得颜色列表
	 */
	 
	 /*
	public function getColorList($condition,$uid,$lookup = true){
		
		if($lookup){
			$this->setColorTableByUid($uid);
		}
		
		return $this->_memberColorModel->getList($condition);
		
	}
	*/
	
	/**
	 * 删除用户颜色
	 */
	
	/*
	public function deleteUserColor($ids, $uid){
		if(!is_array($ids)){
			$ids = (array)$ids;
		}
		
		if(empty($ids)){
			return false;
		}
		
		$condition = array(
			'where' => array(
				'uid' => $uid
			),
			'where_in' => array(
				array('key' => 'id' , 'value' => $ids)
			)
		);
		
		$this->setColorTableByUid($uid);
		
		return $this->_memberColorModel->deleteByCondition($condition);
	}
	
	*/
	
	
	/**
	 * 根据颜色名称获得颜色信息
	 */
	 
	/*
	public function getColorByName($colorName,$uid,$field = '*'){
		$this->setColorTableByUid($uid);
		
		$info = $this->_memberColorModel->getById(array(
			'select' => $field,
			'where' => array(
				'color_name' => $colorName,
				'uid' => $uid
			)
		));
		return $info;
	}
	*/
}
