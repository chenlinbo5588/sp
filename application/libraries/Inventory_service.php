<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_service extends Base_service {
	
	
	private $_reactiveFreezen;
	
	//private $_memberSlotModel;
	private $_memberInventoryModel;
	
	//private $_memberColorModel;
	//private $_memberColorHash;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Member_Inventory_Model'));
		$this->_memberInventoryModel = self::$CI->Member_Inventory_Model;
		//$this->_memberColorModel = self::$CI->Member_Color_Model;
		/*
		$this->_memberColorHash = new Flexihash();
		$colorSplit = self::$CI->load->get_config('split_color');
		$this->_memberColorHash->addTargets($colorSplit);
		*/
		
		$this->_reactiveFreezen = config_item('inventory_freezen');
	}
	
	
	/**
	 * 获得上次更新时间
	 */
	public function getLastUpdate($uid){
		$inventoryLastModify = $this->_memberInventoryModel->getFirstByKey($uid,'uid','gmt_modify');
		return $inventoryLastModify['gmt_modify'];
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
	 * 获得用户当前库存
	 */
	public function getUserCurrentInventory($uid,$field = '*'){
		$inventory = $this->_memberInventoryModel->getFirstByKey($uid,'uid');
		
		if($inventory){
			$inventory['goods_list'] = json_decode($inventory['goods_list'],true);
		}
		
		return $inventory;
	}
	
	
	public function reactiveUserInventory($ts,$uid){
		return $this->_memberInventoryModel->update(array(
			'gmt_modify' => $ts
		),array('uid' => $uid));
		
	}
	
	
	/**
	 * 初始化用户库存
	 */
	public function initUserInventory($uid){
		$data['goods_list'] = json_encode(array());
		$data['enable'] = 0;
		$data['kw'] = '';
		$data['last_pm'] = '';
		$data['uid'] = $uid;
		
		return $this->_memberInventoryModel->_add($data,true);
	}
	
	
    /**
	 * 更新用户 某一个货号的 货品列表 
	 */
	public function updateUserInventory($data,$uid){
		$data['hp_cnt'] = count($data['goods_list']);
		$data['goods_list'] = json_encode($data['goods_list']);
		$data['enable'] = 1;
		
		if($data['hp_cnt'] == 0){
			$data['enable'] = 0;
		}
		
		return $this->_memberInventoryModel->update($data,array('uid' => $uid));
		
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
