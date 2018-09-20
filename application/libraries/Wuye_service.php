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
			'Feetype_Model','Basic_Data_Model','Repair_Model','Repair_Images_Model','Plan_Detail_Model',
			'Plan_Model','Plan_History_Model','House_Yezhu_Model','Payment_Report_Date_Model','Payment_Report_Week_Model',
			'Payment_Report_Month_Model','Payment_Report_Year_Model','Order_Model'
		));
		
		$this->_residentModel = self::$CI->Resident_Model;
		$this->_parkingModel = self::$CI->Parking_Model;
		$this->_buildingModel = self::$CI->Building_Model;
		$this->_houseModel = self::$CI->House_Model;
		$this->_yezhuModel = self::$CI->Yezhu_Model;
		$this->_feeTypeModel = self::$CI->Feetype_Model;
		$this->_repairModel = self::$CI->Repair_Model;
		$this->_planDetailModel = self::$CI->Plan_Detail_Model;
		$this->_planModel = self::$CI->Plan_Model;
		$this->_planHistoryModel = self::$CI->Plan_History_Model;   
		$this->_hosueYezhuModel = self::$CI->House_Yezhu_Model;   
		$this->_dataModule = array(-1);
		
		$this->_objectMap = array(
			'小区' => $this->_residentModel,
			'停车位' => $this->_parkingModel,
			'建筑物' => $this->_buildingModel,
			'房屋' => $this->_houseModel,
			'业主' => $this->_yezhuModel,
			'費用类型' => $this->_feeTypeModel,
			'报修' => $this->_repairModel,
			'收费计划' => $this->_planModel,
			'收费计划详情' => $this->_planDetailModel,
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
	public function getResidentFeeSetting($pResidentId,$year,$pOrderTypename,$wuyeType = null){
		$residentFee = array();
		if('物业费' == $pOrderTypename)
		{
			$feeInfo = $this->_feeTypeModel->getList(array(
				'select' => 'year,name,price,billing_style',
				'where' => array(
					'year' => $year,
					'name' => $pOrderTypename,
					'resident_id' => $pResidentId,
					'wuye_type' => $wuyeType
				),
				'limit' => 1
			));
			$feeParkInfo = $this->_feeTypeModel->getList(array(
				'select' => 'year,name,price,billing_style',
				'where' => array(
					'year' => $year,
					'name' => '车位费',
					'resident_id' => $pResidentId,
				),
			));
		}else{
			$feeInfo = $this->_feeTypeModel->getList(array(
				'select' => 'year,name,price,billing_style',
				'where' => array(
					'year' => $year,
					'name' => $pOrderTypename,
					'resident_id' => $pResidentId
				),
				'limit' => 1
			));
		}
		
		if($feeInfo[0]){
			$residentFee[0] = $feeInfo[0];
		}
		if($feeParkInfo[0]){
			$residentFee[1] = $feeParkInfo[0];
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
		
		$info = $this->_houseModel->getFirstByKey($pComputeParam['id'],'id','resident_id');
		//基于按年缴费的方式
		$monthCnt = intval(date('m',$pComputeParam['newEndTimeStamp'])) - intval(date('m',$pComputeParam['newStartTimeStamp'])) + 1;
		
		//获得费用设置		
		if('物业费' == $pComputeParam['orderTypeName']){
			$this->_planDetailModel->setTableId(date('Y'));
			$feeList = $this->_planDetailModel->getList(array(
				'select' => 'feetype_name,parking_name,jz_area,price,billing_style,amount_real',
				'where' => array(
					'address' => $pComputeParam['goods_name'],
				)
			));
			$parkingMoney = 0;
			$amount = 0;
			$amountList = array();
			foreach($feeList as $key => $item){
			if('能耗费' != $item['feetype_name']){
				if('车位费' == $item['feetype_name']){
						$parkingMoney += $item['amount_real'];
						$amount += $item['amount_real'];
						$amountList[] = $item;
					}else if('物业费' ==$item['feetype_name']){
						$wuyeMoney += $item['amount_real'];
						$amount += $item['amount_real'];
						$amountList[] = $item;
					}
				}
			}
			$amountList['车位费'] = $parkingMoney;
			$amountList['物业费'] = $wuyeMoney;
			$amountList['总费用'] = $amount;
			return $amountList;
			

		}else{
			$tempFeeSetting = $this->getResidentFeeSetting($info['resident_id'],$pComputeParam['year'],$pComputeParam['orderTypeName']);
		}


		$amount = 0;
		foreach($tempFeeSetting as $key => $item){
			if(false !== strpos($item['billing_style'],'按每平方')){	
				$amount += number_format($item['price'] * $info['jz_area'] * $monthCnt,2,'.',"");
				
			}else if('按每月固定值' == $item['billing_style']){
				
				$amount += number_format($monthCnt * $item['price'],2,'.',"");
				
			}else{
				$amount = 99999.99;
			}
		}
		return $amount;
	}
	
	
	/**
	 * 获得缴费情况
	 * 
	 */
	public function getCurrentFeeInfo($pId,$pOrderTypename,$endMonth = 12){
		$info = $this->_houseModel->getFirstByKey($pId,'id','id,resident_id,address,wuye_expire,nenghao_expire');
/*		if('车位费' != $pOrderTypename){
			$info = $this->_houseModel->getFirstByKey($pId,'id','id,resident_id,address,wuye_expire,nenghao_expire');
		}else{
			$info = $this->_parkingModel->getFirstByKey($pId,'id','id,resident_id,name,expire');
		}*/
		
		$residentInfo = $this->_residentModel->getFirstByKey($info['resident_id'],'id','name');
		
		$temp = array(
			'id' => $pId,
			'resident_id' => $info['resident_id'],
			'resident_name' => $residentInfo['name'],
			'goods_name' => '',
			'year' => date('Y'),
			'end_month' => $endMonth,
			//缴费月数
			'fee_month' => 0,
			'expireTimeStamp' => 0,//原到期时间戳
			'newStartTimeStamp' => 0,//新开始时间戳
			'newEndTimeStamp' => 0,	//新的结束时间戳,
			'orderTypeName' => $pOrderTypename,
			'address' => $info['address'],
		);
		
		switch($pOrderTypename){
			case '车位费':
			case '物业费':
				$temp['expireTimeStamp'] = $info['wuye_expire'];
				$temp['goods_name'] = $info['address'];
				break;
			case '能耗费':
				$temp['expireTimeStamp'] = $info['nenghao_expire'];
				$temp['goods_name'] = $info['address'];
				break;
/*			case '车位费':
				$temp['expireTimeStamp'] = $info['expire'];
				$temp['goods_name'] = $info['name'];
				break;*/
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
			$temp['newEndTimeStamp'] = strtotime($temp['year'].'-'.str_pad($endMonth,2,'0',STR_PAD_LEFT).' last day of this month');
			$temp['fee_month'] = $endMonth - date('n',$temp['newStartTimeStamp']) + 1;
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
		$houseIdList = $this->_hosueYezhuModel->getList(array('where' => array('uid' => $pYezhu['uid'])));
		foreach($houseIdList as $key => $value){
			$houseId[] = $value['house_id'];
		}
		$yezhuHouseList = $this->_houseModel->getList(array(
			'select' => 'id,address,jz_area,lng,lat,wuye_expire,nenghao_expire',
			'where_in' => array(
				array('key' => 'id', 'value' => $houseId)
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
			'select' => 'id,resident_id,address,jz_area,yezhu_name,lng,lat,wuye_expire,nenghao_expire,wuye_type',
			'where' => array(
				'id' => $houseId,
			)
		);
		
/*		if($pYezhu){
			$condition['where']['uid'] = $pYezhu['uid'];
		}*/
		
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
	  *  根据地址 获得物业
	 */
	public function getHouseByAddress($address,$pYezhu = array()){
		
		$condition = array(
			'select' => 'id,address,jz_area,lng,lat,wuye_expire,nenghao_expire',
			'where' => array(
				'address' => $address,
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
	  *  获得社区列表
	 */
	public function getResidentById(){
		
		$condition = array(
			'select' => '*',
		);	
		$houseInfo = $this->_residentModel->getList($condition);
		
		if($houseInfo){
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
	
	
	/**
	 * 生成费用计划
	 */
	public function generationPlan($residentId,$who,$moreCondition = array(),$year = 0){
		$result = array(
			'commitCnt' => 0,
			'successCnt' => 0,
			'failedCnt' => 0,
			'failedInfo' => array(),
		);
		
		if(empty($year)){
			$year = date('Y');
		}
		$condition = array(
			'where' => array(
				'resident_id' => $residentId,
			)
		);
		$condition = array_merge_recursive($condition,$moreCondition);
		$feetypeList = $this->search('費用类型',array(
			'where' => array(
				'resident_id' => $residentId,
				'year' => $year,
			)
		),'name_detail');
		
		if(empty($feetypeList)){
			return ;
		}
		
		$residengList = $this->search('小区',array(
				'order' => 'displayorder DESC'
			),'id');

		$houseList = $this->_houseModel->getList($condition);
		
		$count = 0;
		
		$this->_planDetailModel->setTableId($year);
		$this->_planModel->setTableId($year);
		
		//汇总
		
		$wuyeTotalTemporary = array();
		$wuyeTotalItem = array();
		

		$wuyeDetailItem = array();
		
		foreach($houseList as $key => $houseItem){
			//明细
			$wuyeDetailTemp = array();
			$wuyeTotalTemporary = array();
			
			//汇总
			$wuyeTotalTemp = array();
			$residentName = $residengList[$residentId]['name'];
			
			//物业费
			foreach($feetypeList as $key => $feeTypeInfo){
				if('物业费' == $feeTypeInfo['name']){
					$detailInsert = array(
						'address' => $houseItem['address'],
						'parking_name' => null,
						'feetype_name' => $feeTypeInfo['name'],
						'resident_id' => $houseItem['resident_id'],
						'resident_name' => $feeTypeInfo['resident_name'],
						'year' => $year,
						'jz_area' => $houseItem['jz_area'],
						'price' =>  $feeTypeInfo['price'],
						'wuye_type' => $feeTypeInfo['wuye_type'],
						'billing_style' => $feeTypeInfo['billing_style'],
						'add_uid' => $who['add_uid'],
						'add_username' => $who['add_username'],	
					);
				}else if('车位费' == $feeTypeInfo['name']){
					$parkingList = $this->search('停车位',array(
						'where' => array(
							'address' => $houseItem['address'],
						)
					),'id');
					foreach($parkingList as $key => $parkingItem){
						$parkingInsert = array(
							'address' => $parkingItem['address'],
							'parking_name' => $parkingItem['name'],
							'feetype_name' => $feeTypeInfo['name'],
							'resident_id' => $parkingItem['resident_id'],
							'resident_name' => $feeTypeInfo['resident_name'],
							'year' => $year,
							'jz_area' => $parkingItem['jz_area'],
							'price' =>  $feeTypeInfo['price'],
							'wuye_type' => $feeTypeInfo['wuye_type'],
							'billing_style' => $feeTypeInfo['billing_style'],
							'add_uid' => $who['add_uid'],
							'add_username' => $who['add_username'],	
						);
						if(false !== strpos($feeTypeInfo['billing_style'],'按每平方')){						
							$parkingInsert['amount_plan'] = $parkingInsert['price'] * $parkingInsert['jz_area'] * 12;
						}else if('按每月固定值' == $feeTypeInfo['billing_style']){
							$parkingInsert['amount_plan'] = $parkingInsert['price'] * 12;
						}
						$parkingInsert['amount_real'] = $parkingInsert['amount_plan'];
						$wuyeDetailTemp[] = $parkingInsert; 
					}
				}
				
				$basicInfo = array(
					'address' => $houseItem['address'],
					'resident_id' => $houseItem['resident_id'],
					'resident_name' => $feeTypeInfo['resident_name'],
					'year' => $year,
					'add_uid' => $who['add_uid'],
					'add_username' => $who['add_username'],				
				);
				
				if('能耗费' == $feeTypeInfo['name'] ){			
					$basicInfo['amount_plan'] = $detailInsert['price'] * 12;
					$basicInfo['amount_real'] = $basicInfo['amount_plan'];
					$basicInfo['feetype_name'] = '能耗费';
					$basicInfo['wuye_type'] = $feeTypeInfo['wuye_type'];
					$wuyeTotalItem[] = $basicInfo;	
				}else if($houseItem['wuye_type'] == $feeTypeInfo['wuye_type']){
					$feeTypeNameRule = $residentName.$feeTypeInfo['wuye_type'].$year.$feeTypeInfo['name'];		
					if(false !== strpos($feetypeList[$feeTypeNameRule]['billing_style'],'按每平方')){						
						$detailInsert['amount_plan'] = $feetypeList[$feeTypeNameRule]['price'] * $detailInsert['jz_area'] * 12;
					}else if('按每月固定值' == $feetypeList[$feeTypeNameRule]['billing_style']){
						$detailInsert['amount_plan'] = $detailInsert['price'] * 12;
					}

					$detailInsert['amount_real'] = $detailInsert['amount_plan'];
					
					$wuyeDetailTemp[] = $detailInsert;
				}
			}
			
			//物业费金额			
			$wuyeAmountInfo = array('amount_plan' => 0 ,'amount_real' => 0,'feetype_name' => '物业费');
			foreach($wuyeDetailTemp as $key => $item){
				$wuyeAmountInfo['amount_plan'] += $item['amount_plan'];
				$wuyeAmountInfo['amount_real'] = $wuyeAmountInfo['amount_plan'];
				$wuyeAmountInfo['wuye_type'] = $item['wuye_type'];
				$wuyeDetailItem[] = $item;
			}
			//物业费
			$wuyeTotalItem[] = array_merge($basicInfo,$wuyeAmountInfo);
				
			if(count($wuyeTotalItem) > 100){
				$this->_planModel->beginTrans();
				$result['commitCnt'] += 100;
				$this->_planDetailModel->batchInsert($wuyeDetailItem);
				$this->_planModel->batchInsert($wuyeTotalItem);
				
				if($this->_planModel->getTransStatus() === FALSE){
					$this->_planModel->rollBackTrans();
					$result['failedCnt'] += 100;
					log_message('error','批量出错' );
					
				}else{
					$this->_planModel->commitTrans();
				}
				$result['successCnt'] += 100;
				$wuyeDetailItem = array();
				$wuyeTotalItem = array();
			}		
		}
		
		if(count($wuyeTotalItem) > 0){
			$this->_planModel->beginTrans();
			$result['commitCnt'] += count($wuyeTotalItem);
			$this->_planDetailModel->batchInsert($wuyeDetailItem);
			$this->_planModel->batchInsert($wuyeTotalItem);
			
			//$this->_planHistoryModel->batchInsert($wuyeTotalItem);
			
			if($this->_planModel->getTransStatus() === FALSE){
				$this->_planModel->rollBackTrans();
				$result['failedCnt'] += count($wuyeTotalItem);
				log_message('error','批量出错');
				
			}else{
				$this->_planModel->commitTrans();
				$result['successCnt'] += count($wuyeTotalItem);
			}
			
			$wuyeDetailItem = array();
			$wuyeTotalItem = array();
		}
		
		return $result;
	}

}
