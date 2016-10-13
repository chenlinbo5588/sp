<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_service extends Base_service {
	
	
	private $_reactiveFreezen;
	
	private $_memberSlotModel;
	private $_memberInventoryModel;
	
	private $_memberColorModel;
	private $_memberColorHash;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Member_Slot_Model','Member_Inventory_Model','Member_Color_Model'));
		$this->_memberSlotModel = self::$CI->Member_Slot_Model;
		$this->_memberInventoryModel = self::$CI->Member_Inventory_Model;
		$this->_memberColorModel = self::$CI->Member_Color_Model;
		
		
		$this->_memberColorHash = new Flexihash();
		$colorSplit = self::$CI->load->get_config('split_color');
		$this->_memberColorHash->addTargets($colorSplit);
		
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
	public function getReactiveTimeRemain($reqTime ,$uid){
		$lastPub = $this->getUserCurrentSlots($uid,'gmt_modify');
		
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
	public function reactiveUserSlots($time, $uid){
		$condition = array(
			'where' => array(
				'uid' => $uid
			)
		);
		
		$affectRow = $this->_memberSlotModel->updateByCondition(array('gmt_modify' => $time),$condition);
		
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
				$kw[] = str_replace('.','',str_replace(array('-','_'),'',$good['goods_code']).$good['goods_size']);
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
			$this->updateUserSlot($slots,$uid);
		}
			
		
		return $row;
	}
	
	
	/**
	 * 获得用户库存
	 */
	public function getUserCurrentSlots($uid,$field = '*'){
		
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
	
	
	/*  --------------------- 以下颜色管理 --------------------------- */
	
	
	
	
	
	public function addColor($colorName,$uid){
		$this->setColorTableByUid($uid);
		
		return $this->_memberColorModel->_add(array(
			'color_name' => $colorName,
			'uid' => $uid
		));
	}
	
	
	
	/**
	 * 设置 member color tableid
	 */
	public function setColorTableByUid($uid){
		$tableId = $this->_memberColorHash->lookup($uid);
		$this->_memberColorModel->setTableId($tableId);
	}
	
	/**
	 * 获得颜色列表
	 */
	public function getColorList($condition,$uid,$lookup = true){
		
		if($lookup){
			$this->setColorTableByUid($uid);
		}
		
		return $this->_memberColorModel->getList($condition);
		
	}
	
	
	/**
	 * 删除用户颜色
	 */
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
	
	
	/**
	 * 检查是否存在
	 */
	public function isColorExists($colorName,$uid){
		
		$this->setColorTableByUid($uid);
		
		$info = $this->_memberColorModel->getById(array(
			'select' => 'uid',
			'where' => array(
				'color_name' => $colorName
			)
		));
		
		if($info){
			return true;
		}else{
			
			return false;
		}
	}
	
}
