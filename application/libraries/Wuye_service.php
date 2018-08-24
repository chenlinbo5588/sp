<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RepairType{
	//管道报修
	public static $pipeline  = 1;
	
	//电路报修
	public static $circuit = 2;
	
	//电器报修
	public static $wiring = 3;
	
	//网络报修
	public static $network = 4;
	
	//其他报修
	public static $others = 5;	
		
	public static $typeName = array(
		1 => '管道维修',
		2 => '电路报修',
		3 => '电器报修',
		4 => '网络报修',
		5 => '其他报修'
	);
}


class RepairStatus {

	
	//未受理
	public static $unReceived  = 1;
	
	//已受理
	public static $received = 2;
	
	//已派单
	public static $sendOrder = 3;
	
	//已完成
	public static $accomplish = 4;
	
	//已删除
	public static $deleted = 5;
		
	public static $statusName = array(
		1 => '未受理',
		2 => '已受理',
		3 => '已派单',
		4 => '已完成',
		5 => '已删除'
	);
}


/**
 * 物业核心服务
 */
class Wuye_service extends Base_service {
	
	private $_residentModel;
	private $_parkingModel;
	private $_buildingModel;
	private $_houseModel;
	private $_yezhuModel;
	private $_feeTypeModel;
	
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Resident_Model','Building_Model','House_Model','Yezhu_Model','Parking_Model',
			'Feetype_Model','Basic_Data_Model','Repair_Model','Repair_Images_Model'

		));
		
		$this->_residentModel = self::$CI->Resident_Model;
		$this->_parkingModel = self::$CI->Parking_Model;
		$this->_buildingModel = self::$CI->Building_Model;
		$this->_houseModel = self::$CI->House_Model;
		$this->_yezhuModel = self::$CI->Yezhu_Model;
		$this->_feeTypeModel = self::$CI->Feetype_Model;
		$this->_repairModel = self::$CI->Repair_Model; 
		
		$this->_dataModule = array(-1);
		
		$this->_objectMap = array(
			'小区' => $this->_residentModel,
			'停车位' => $this->_parkingModel,
			'建筑物' => $this->_buildingModel,
			'房屋' => $this->_houseModel,
			'业主' => $this->_yezhuModel,
			'費用类型' => $this->_feeTypeModel,
			'报修' => $this->_repairModel,
		);

	}
	
	
	
	
	
	
	
	
	/**
	 * 获得物业列表
	 */
	public function getOwnedResidentList($condition,$assocKey = ''){
		
		return $this->search('小区',$condition,$assocKey);
		
	}
	
	
	/*
	public function addFeeTypeRules(){
		
		self::$CI->form_validation->set_rules('name','费用类型名称','required|in_db_list['.self::$CI->Basic_Data_Model->getTableRealName().'.show_name]');
		
		self::$CI->form_validation->set_rules('price','每平方单价','required|is_numeric');
		self::$CI->form_validation->set_rules('resident_name','小区名称','required|in_db_list['.$this->_residentModel->getTableRealName().'.name]');
		
		self::$CI->form_validation->set_rules('fee_type','计费方式','required|in_db_list['.self::$CI->Basic_Data_Model->getTableRealName().'.show_name]');
		
	}
	*/
	
	
	/**
	 * 检查费用类型 是否存储
	 */
	public function checkFeetype($pId,$extraStr = ''){
		
		//年份.费用类型名称
		$extraInfo = explode(',',$extraStr);
		
		$flag = false;
		
		if(!empty($extraInfo[0]) && !empty($extraInfo[1])){
			
			if('物业费' == $extraInfo[1] || '能耗费' == $extraInfo[1]){
				$info = $this->_houseModel->getFirstByKey($pId,'id','resident_id');
			}else if('车位费' == $extraInfo[1]){
				$info = $this->_parkingModel->getFirstByKey($pId,'id','resident_id');
			}
			
			if($info['resident_id']){
				
				$cnt = $this->_feeTypeModel->getCount(array(
					'where' => array(
						'name' => $extraInfo[1],
						'year' => $extraInfo[0],
						'resident_id' => $info['resident_id']
					)
				));
			
				if($cnt){
					$flag = true;
				}
			}
		}
		
		if(!$flag){
			self::$CI->form_validation->set_message('feetype_callable','该物业所在小区没有配置费用信息');
			return false;
		}
		
		return $flag;
	}
	
	
	/**
	 * 获得小区 费用设置
	 */
	public function getResidentFeeSetting($pResidentId,$year,$pOrderTypename){
		$residentFee = array();
		
		$feeInfo = $this->_feeTypeModel->getList(array(
			'select' => 'year,name,price,billing_style',
			'where' => array(
				'year' => $year,
				'name' => $pOrderTypename,
				'resident_id' => $pResidentId
			),
			'limit' => 1
		));
		
		
		if($feeInfo[0]){
			$residentFee = $feeInfo[0];
		}
		
		return $residentFee;
	}
	
	
	/**
	 * @param int $year
	 * @return void
	 */
	public function setFeeTimeRules($year){
		self::$CI->form_validation->set_rules('year','缴费年份','required|is_natural_no_zero|greater_than_equal_to['.$year.']');
		self::$CI->form_validation->set_rules('end_month','缴费到期月份','required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[12]');
	}
	
	/**
	 * 计算 费用
	 * 
	 * @return float   
	 */
	public function computeFee($pComputeParam){
		
		if('车位费' != $pComputeParam['orderTypeName']){
			$info = $this->_houseModel->getFirstByKey($pComputeParam['id'],'id','resident_id,jz_area');
		}else{
			$info = $this->_parkingModel->getFirstByKey($pComputeParam['id'],'id','resident_id');
		}
		
		//获得费用设置
		$tempFeeSetting = $this->getResidentFeeSetting($info['resident_id'],$pComputeParam['year'],$pComputeParam['orderTypeName']);
		
		//基于按年缴费的方式
		$monthCnt = intval(date('m',$pComputeParam['newEndTimeStamp'])) - intval(date('m',$pComputeParam['newStartTimeStamp'])) + 1;
		
		if('按每平方' == $tempFeeSetting['billing_style']){
			
			return number_format($monthCnt * $tempFeeSetting['price'] * $info['jz_area'],2,'.',"");
			
		}else if('按每月固定值' == $tempFeeSetting['billing_style']){
			
			return number_format($monthCnt * $tempFeeSetting['price'],2,'.',"");
			
		}else{
			
			return 999999.99;
		}
		
	}
	
	
	/**
	 * 获得缴费情况
	 * 
	 */
	public function getCurrentFeeInfo($pId,$pOrderTypename,$endMonth = 0){
		
		if('车位费' != $pOrderTypename){
			$info = $this->_houseModel->getFirstByKey($pId,'id','id,resident_id,address,wuye_expire,nenghao_expire');
		}else{
			$info = $this->_parkingModel->getFirstByKey($pId,'id','id,resident_id,name,expire');
		}
		
		$temp = array(
			'id' => $pId,
			'resident_id' => $info['resident_id'],
			'goods_name' => '',
			'year' => date('Y'),
			'endMonth' => $endMonth,
			'expireTimeStamp' => 0,//原到期时间戳
			'newStartTimeStamp' => 0,//新开始时间戳
			'newEndTimeStamp' => 0,	//新的结束时间戳,
			'orderTypeName' => $pOrderTypename,
		);
		
		switch($pOrderTypename){
			case '物业费':
				$temp['expireTimeStamp'] = $info['wuye_expire'];
				$temp['goods_name'] = $info['address'];
				break;
			case '能耗费':
				$temp['expireTimeStamp'] = $info['nenghao_expire'];
				$temp['goods_name'] = $info['address'];
				break;
			case '车位费':
				$temp['expireTimeStamp'] = $info['expire'];
				$temp['goods_name'] = $info['name'];
				break;
			default:
				break;
		}
		
		if($temp['expireTimeStamp']){
			if(12 != date('m',$temp['expireTimeStamp'])){
				//如果上一个年度还没有缴费到年底 完成，则继续缴费
				$temp['year'] = date('Y',$temp['expireTimeStamp']);
				
				//上次缴费结束日期所在月的下个月的月初
				$temp['newStartTimeStamp'] = mktime(0,0,0,date('m',$temp['expireTimeStamp']) + 1,1,$temp['year']);
			}else{
				//如果上一年以及缴清至的12月份,则从当前的 1月1日开始
				$temp['newStartTimeStamp'] = mktime(0,0,0,1,1,$temp['year']);
			}
		}else{
			//从1月份开始
			$temp['newStartTimeStamp'] = mktime(0,0,0,1,1,$temp['year']);
		}
		
		
		if($endMonth){
			$temp['newEndTimeStamp'] = strtotime($temp['year'].'-'.str_pad($endMonth,2,'0',STR_PAD_LEFT).'  last day of this month');
		}
		
		return $temp;
	}
	
	
	/**
	 * 根据会话 初始话 相关数据
	 * 
	 */
	public function initUserInfoBySession($pSession,$idKey = 'openid'){
		
		$idVal = $pSession[$idKey];
		
		return self::$memberModel->getFirstByKey($idVal,$idKey);
		
	}
	/**
	 * 改变状态
	 */
	public function changeRepairStatus($pIds,$pNewValue,$pOldValue,$pFieldName,$extraData = array()){
		return $this->_repairModel->updateByCondition(array_merge(array(
			$pFieldName => $pNewValue
		),$extraData),array(
			'where' => array(
				$pFieldName => $pOldValue
			),
			'where_in' => array(
				array('key' => 'id', 'value' => $pIds)
			)
		));
	}
	
	
	
	/**
	 * 获得业主信息
	 */
	public function getYezhuInfoById($pId,$key = 'uid'){
		
		$yezhuList = $this->_yezhuModel->getList(array(
			'where' => array(
				$key => $pId
			),
			'limit' => 1
		));
		
		if($yezhuList[0]){
			//注意身份证件号码的隐藏
			$yezhuList[0]['id_no'] = mask_string($yezhuList[0]['id_no']);
			
			return $yezhuList[0];
		}else{
			return array();
		}
		
	}
	
	
	/**
	 * 获得业主物业列表
	 */
	public function getHouseListByYezhu($pYezhu){
		
		$yezhuHouseList = $this->_houseModel->getList(array(
			'select' => 'id,address,jz_area,lng,lat,wuye_expire,nenghao_expire',
			'where' => array(
				'uid' => $pYezhu['uid']
			)
		));
		
		$list = array();
		
		foreach($yezhuHouseList as $houseIndex => $houseInfo){
			if($houseInfo['wuye_expire']){
				$houseInfo['wuye_expire_date'] = date('Y-m-d',$houseInfo['wuye_expire_date']);
			}
			
			if($houseInfo['nenghao_expire']){
				$houseInfo['nenghao_expire_date'] = date('Y-m-d',$houseInfo['nenghao_expire']);
			}
			
			$list[] = $houseInfo;
		}
		
		
		return array(
			'houseCnt' => count($list),
			'houseList' => $list
		);
	}
	
	
	/**
	 * 根据业主 获得物业详情
	 */
	public function getYezhuHouseDetail($houseId,$pYezhu = array()){
		
		$condition = array(
			'select' => 'id,resident_id,address,jz_area,yezhu_name,lng,lat,wuye_expire,nenghao_expire',
			'where' => array(
				'id' => $houseId,
			)
		);
		
		if($pYezhu){
			$condition['where']['uid'] = $pYezhu['uid'];
		}
		
		$houseInfo = $this->_houseModel->getById($condition);
		
		if($houseInfo){
			if($houseInfo['wuye_expire']){
				$houseInfo['wuye_expire_date'] = date('Y-m-d',$houseInfo['wuye_expire_date']);
			}
			
			if($houseInfo['nenghao_expire']){
				$houseInfo['nenghao_expire_date'] = date('Y-m-d',$houseInfo['nenghao_expire']);
			}
			
			return $houseInfo;
		}else{
			return array();
		}
		
	}
	
	
	
	/**
	 * 获得业主物业
	 */
	public function getParkingListByYezhu($pYezhu){
		
		$parkingList = $this->_parkingModel->getList(array(
			'select' => 'id,name,jz_area,lng,lat,expire',
			'where' => array(
				'uid' => $pYezhu['uid']
			)
		));
		
		$list = array();
		
		foreach($parkingList as $index => $item){
			if($item['expire']){
				$houseInfo['expire_date'] = date('Y-m-d',$houseInfo['expire']);
			}
			
			$list[] = $item;
		}
		
		
		return array(
			'parkingCnt' => count($list),
			'parkingList' => $list
		);
	}
	
	
	/**
	 * 根据业主 获得物业
	 */
	public function getYezhuParkingDetail($pId,$pYezhu = array()){
		
		$condition = array(
			'select' => 'id,resident_id,name,jz_area,yezhu_name,lng,lat,expire',
			'where' => array(
				'id' => $pId,
			)
		);
		
		if($pYezhu){
			$condition['where']['uid'] = $pYezhu['uid'];
		}
		
		$parkingInfo = $this->_parkingModel->getById($condition);
		
		if($parkingInfo){
			if($parkingInfo['expire']){
				$parkingInfo['expire_date'] = date('Y-m-d',$parkingInfo['expire']);
			}
			
			return $parkingInfo;
		}else{
			return array();
		}
	}
	
}
