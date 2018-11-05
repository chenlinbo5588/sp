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
			'Plan_Model','House_Yezhu_Model','Order_Model','Member_Model'
		));
		self::$CI->load->library(array('constant/OrderStatus'));
		
		$this->_residentModel = self::$CI->Resident_Model;
		$this->_parkingModel = self::$CI->Parking_Model;
		$this->_buildingModel = self::$CI->Building_Model;
		$this->_houseModel = self::$CI->House_Model;
		$this->_yezhuModel = self::$CI->Yezhu_Model;
		$this->_feeTypeModel = self::$CI->Feetype_Model;
		$this->_repairModel = self::$CI->Repair_Model;
		$this->_planDetailModel = self::$CI->Plan_Detail_Model;
		$this->_planModel = self::$CI->Plan_Model;
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
			'房屋业主' => $this->_hosueYezhuModel
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
			
			$info = $this->_houseModel->getFirstByKey($pId,'id','resident_id');
			
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
		
		$feetypeList = $this->_feeTypeModel->getList(array(
			'where' => array(
				'resident_id' => $pResidentId,
				'year' => $year,
				'name' => $pOrderTypename,
			)
		));
		if($feetypeList){
			$feeRule = json_decode($feetypeList[0]['fee_rule'],true);
		
			foreach($feeRule as $key => $item){
				//获得该物业所在小区与当前物业匹配的费用配置列表
				if($item['feeName'] ==$pOrderTypename && $item['wuyeType'] == $wuyeType){
					$residentFee[] = $item;
				}
			}
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
		
		$feeList = array(
			'amount' => 0,
			'amountDetail' => array()
		);
		
		//获得费用设置		
		if('能耗费' == $pComputeParam['orderTypeName']){
			
			$feetypeList = $this->getResidentFeeSetting($pComputeParam['resident_id'],$pComputeParam['year'],$pComputeParam['orderTypeName'],$pComputeParam['houseInfo']['wuye_type']);
			
			if('按面积' == $feetypeList[0]['billingStyle']){
				$feeList['amount'] = $feetypeList[0]['price'] * $monthCnt * $pComputeParam['houseInfo']['jz_area'];
			}else if('按每月固定值' == $feetypeList[0]['billingStyle']){
				$feeList['amount'] = $feetypeList[0]['price'] * $monthCnt;
			}else{
				$feeList['amount'] = 999999999;
			}
			
		}else{
			
			$this->_planDetailModel->setTableId($pComputeParam['year']);
			$this->_planModel->setTableId($pComputeParam['year']);
			
			$feeList['amountDetail'] = $this->_planDetailModel->getList(array(
				'select' => 'feetype_name,parking_name,jz_area,price,billing_style,amount_real,order_status',
				'where' => array(
					'house_id' => $pComputeParam['id'],
					'fee_gname' => $pComputeParam['orderTypeName']
				)
			));
			
			$amountInfo = $this->_planModel->getById(array(
				'select' => 'amount_real',
				'where' => array(
					'house_id' => $pComputeParam['id'],
					'feetype_name' => $pComputeParam['orderTypeName']
				)
			));
			foreach($feeList['amountDetail'] as $key =>$item){
				if($item['order_status'] == OrderStatus::$unPayed){
					$feeList['amountDetail'][$key]['order_status'] = OrderStatus::$statusName[$item['order_status']];
					$feeList['amout_unpayed'] += $item['amount_real'];
				}else if($item['order_status'] == OrderStatus::$payed){
					$feeList['amountDetail'][$key]['order_status'] = OrderStatus::$statusName[$item['order_status']];
					$feeList['amout_payed'] += $item['amount_real'];
				}
			}
			$feeList['amount'] = $amountInfo['amount_real'];
			
		}
		
		return $feeList;
	}
	
	
	/**
	 * 获得缴费情况
	 * 
	 */
	public function getCurrentFeeInfo($pId,$pOrderTypename,$endMonth = 12){
		$info = $this->_houseModel->getFirstByKey($pId,'id');
		
		$residentInfo = $this->_residentModel->getFirstByKey($info['resident_id'],'id','name');
		
		$temp = array(
			'id' => $pId,
			'resident_id' => $info['resident_id'],
			'resident_name' => $residentInfo['name'],
			'year' => date('Y'),
			'end_month' => $endMonth,
			//缴费月数
			'fee_month' => 0,
			'expireTimeStamp' => 0,//原到期时间戳
			'newStartTimeStamp' => 0,//新开始时间戳
			'newEndTimeStamp' => 0,	//新的结束时间戳,
			'orderTypeName' => $pOrderTypename,
			'address' => $info['address'],
			'houseInfo' => $info
		);
		
		switch($pOrderTypename){
			case '车位费':
			case '物业费':
				$temp['expireTimeStamp'] = $info['wuye_expire'];
				break;
			case '能耗费':
				$temp['expireTimeStamp'] = $info['nenghao_expire'];
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
		$houseIdList = $this->_hosueYezhuModel->getList(array(
			'select' => 'house_id',
			'where' => array('yezhu_id' => $pYezhu['id'])
		));

		
		$houseId = array();
		$yezhuHouseList = array();
		$list = array();
		
		foreach($houseIdList as $key => $value){
			$houseId[] = $value['house_id'];
		}
		
		if($houseId){
			$yezhuHouseList = $this->_houseModel->getList(array(
				'select' => 'id,address,jz_area,lng,lat,wuye_expire,nenghao_expire',
				'where_in' => array(
					array('key' => 'id', 'value' => $houseId)
				)
			));
			
			
			foreach($yezhuHouseList as $houseIndex => $houseInfo){
				if($houseInfo['wuye_expire']){
					$houseInfo['wuye_expire_date'] = date('Y-m-d',$houseInfo['wuye_expire_date']);
				}
				
				if($houseInfo['nenghao_expire']){
					$houseInfo['nenghao_expire_date'] = date('Y-m-d',$houseInfo['nenghao_expire']);
				}
				
				$list[] = $houseInfo;
			}
			
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
		return $this->_dealWithHouseInfo('id',$houseId,$pYezhu = array());
	}
	
	/**
	 * 
	 */
	private function _dealWithHouseInfo($key,$value,$pYezhu = array()){
		
		$condition = array(
			'select' => 'id,resident_id,address,jz_area,yezhu_name,lng,lat,wuye_expire,nenghao_expire,wuye_type',
			'where' => array(
				$key => $value,
			)
		);
		
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
		
		return $this->_dealWithHouseInfo('address',$address,$pYezhu = array());
		
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
	public function generationPlan($residentId,$who,$year = 0,$month = 0,$houseInfo=array(),$moreCondition = array()){
		$result = array(
			'commitCnt' => 0,
			'successCnt' => 0,
			'failedCnt' => 0,
			'failedInfo' => array(),
		);
	
		if(empty($year)){
			$year = date('Y');
		}
		
		$feetypeList = $this->search('費用类型',array(
			'where' => array(
				'resident_id' => $residentId,
				'year' => $year,
			)
		),'name');
		
		if(empty($feetypeList)){
			return ;
		}
		
		$residengList = $this->search('小区',array(
				'order' => 'displayorder DESC'
			),'id');

		
		$condition = array(
			'where' => array(
				'resident_id' => $residentId,
			)
		);
		
		$condition = array_merge_recursive($condition,$moreCondition);
		
		$houseList = $this->_houseModel->getList($condition);
		
		$count = 0;
		
		$this->_planDetailModel->setTableId($year);
		$this->_planModel->setTableId($year);
		
		//汇总
		
		$wuyeTotalItem = array();
		

		$wuyeDetailItem = array();
		if(!$houseInfo){
			foreach($houseList as $key => $houseItem){
			//明细
				$this->greatOnePlan($feetypeList,$houseItem,$year,$who,$wuyeDetailItem,$wuyeTotalItem);
					
				if(count($wuyeTotalItem) > 100){
					$this->_planModel->beginTrans();
					$result['commitCnt'] += count($wuyeTotalItem);
					$this->_planDetailModel->batchInsert($wuyeDetailItem);
					$this->_planModel->batchInsert($wuyeTotalItem);
					
					if($this->_planModel->getTransStatus() === FALSE){
						$this->_planModel->rollBackTrans();
						$result['failedCnt'] += count($wuyeTotalItem);
						log_message('error','批量出错' );
						
					}else{
						$this->_planModel->commitTrans();
						$result['successCnt'] += count($wuyeTotalItem);
					}
					
					
					$wuyeDetailItem = array();
					$wuyeTotalItem = array();
				}		
			}
		}else{
			$this->greatOnePlan($feetypeList,$houseInfo,date('Y'),$who,$wuyeDetailItem,$wuyeTotalItem,$month);
		}
		
		
	
		if(count($wuyeTotalItem) > 0){
			$this->_planModel->beginTrans();
			$result['commitCnt'] += count($wuyeTotalItem);
			$this->_planDetailModel->batchInsert($wuyeDetailItem);
			$this->_planModel->batchInsert($wuyeTotalItem);
			
			
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
	
	
	public function combinationFeeRule($feedetail){
		foreach($feedetail['feeName'] as $key => $item){
			$feeRule[$key]['feeName'] = $feedetail['feeName'][$key];
			$feeRule[$key]['price'] = $feedetail['price'][$key];
			$feeRule[$key]['wuyeType'] = $feedetail['wuyeType'][$key];
			$feeRule[$key]['billingStyle'] = $feedetail['billingStyle'][$key];
			$feeRule[$key]['cale'] = $feedetail['cale'][$key];
		}	
		return $feeRule;	
	}
	
	public function greatOnePlan($feetypeList,$houseItem,$year,$who,&$wuyeDetailItem,&$wuyeTotalItem,$month = 0){
		//物业费
		foreach($feetypeList as $key => $feeTypeInfo){
			$feeTypeInfo['fee_rule'] = (json_decode($feeTypeInfo['fee_rule'],true));
			foreach($feeTypeInfo['fee_rule'] as $key => $feeTypeRule){
				$detailInsert = array(
					'house_id' => $houseItem['id'],
					'uid' => $houseItem['uid'],
					'address' => $houseItem['address'],
					'parking_name' => null,
					'parking_id' => 0,
					'fee_gname' =>  $feeTypeInfo['name'],//组名
					'feetype_name' => $feeTypeRule['feeName'],//明细名
					'resident_id' => $houseItem['resident_id'],
					'resident_name' => $feeTypeInfo['resident_name'],
					'year' => $year,
					'jz_area' => $houseItem['jz_area'],
					'price' =>  $feeTypeRule['price'],
					'wuye_type' => $feeTypeRule['wuyeType'],
					'billing_style' => $feeTypeRule['billingStyle'],
					'add_uid' => $who['add_uid'],
					'add_username' => $who['add_username'],	
					'amount_plan' => 0,
					'amount_real' => 0,
					'month' => 12-$month,
					'stat_date' => strtotime($year.'-'.($month+1).'-01'),
					'end_date' => strtotime($year.'-12-31'),
				);
				if('车位费' == $feeTypeRule['feeName']){
					$parkingList = $this->search('停车位',array(
						'where' => array(
							'house_id' => $houseItem['id'],
						)
					),'id');
					if($parkingList){
						foreach($parkingList as $key => $parkingItem){
							$detailInsert = array(
								'house_id' => $houseItem['id'],
								'uid' => $houseItem['uid'],
								'address' => $parkingItem['address'],
								'parking_name' => $parkingItem['name'],
								'parking_id' => $parkingItem['id'],
								'fee_gname' =>  $feeTypeInfo['name'],
								'feetype_name' => $feeTypeRule['feeName'],
								'resident_id' => $parkingItem['resident_id'],
								'resident_name' => $feeTypeInfo['resident_name'],
								'year' => $year,
								'jz_area' => $parkingItem['jz_area'],
								'price' =>  $feeTypeRule['price'],
								'wuye_type' => $feeTypeRule['wuyeType'],
								'billing_style' => $feeTypeRule['billingStyle'],
								'add_uid' => $who['add_uid'],
								'add_username' => $who['add_username'],
								'amount_plan' => 0,
								'amount_real' => 0,
								'month' => 12-$month,
								'stat_date' => strtotime($year.'-'.($month+1).'-01'),
								'end_date' => strtotime($year.'-12-31'),
							);
							if(false !== strpos($detailInsert['billing_style'],'按面积')){						
								$detailInsert['amount_plan'] = $detailInsert['price'] * $detailInsert['jz_area'] * (12 -$month);
							}else if('按每月固定值' == $detailInsert['billing_style']){
								$detailInsert['amount_plan'] = $detailInsert['price'] * (12 -$month);
							}
							$detailInsert['amount_real'] = $detailInsert['amount_plan'];
						 	
						 	$basicInfo['amount_plan'] += $detailInsert['amount_plan'];
						 	$wuyeDetailItem[] = $detailInsert;
						}
					}
					
				}else if($houseItem['wuye_type'] == $feeTypeRule['wuyeType'] ){
					if(false !== strpos($detailInsert['billing_style'],'按面积')){						
						$detailInsert['amount_plan'] = $detailInsert['price'] * $detailInsert['jz_area'] * (12 -$month);
					}else if('按每月固定值' == $detailInsert['billing_style']){
						$detailInsert['amount_plan'] = $detailInsert['price'] * (12 -$month);
					}
					$detailInsert['amount_real'] = $detailInsert['amount_plan'];
				 	$basicInfo['amount_plan'] += $detailInsert['amount_plan'];
				 	if(1 == $feeTypeInfo['generate_deatil']){
				 		$wuyeDetailItem[] = $detailInsert;
				 	}
				}
				
			}
			if(empty($basicInfo['amount_plan'])){
				 $basicInfo['amount_plan'] = 0;
			}
			
			$basicInfo = array(
				'house_id' => $houseItem['id'],
				'uid' => $houseItem['uid'],
				'address' => $houseItem['address'],
				'resident_id' => $houseItem['resident_id'],
				'resident_name' => $feeTypeInfo['resident_name'],
				'year' => $year,
				'add_uid' => $who['add_uid'],
				'add_username' => $who['add_username'],
				'feetype_name' => $feeTypeInfo['name'],
				'amount_plan' => $basicInfo['amount_plan'],
				'amount_real' => $basicInfo['amount_plan'],	
			);
			
			
			$wuyeTotalItem[] = $basicInfo;
			$basicInfo['amount_plan'] = 0;
		}
	}
	
	
	/**
	 * 创建一条车位收费记录
	 */
	 public function creatParkingPlan($residentId,$insertData,$parkingId,$month,$who){
		$year = date('Y');
		$feetypeList = $this->search('費用类型',array(
			'where' => array(
				'resident_id' => $residentId,
				'year' => $year,
				'name' => '物业费'
			)
		));
		
		$feetypeList[0]['fee_rule'] = (json_decode($feetypeList[0]['fee_rule'],true));
		foreach($feetypeList[0]['fee_rule'] as $key => $feeTypeRule){
			if('车位费' == $feeTypeRule['feeName']){
				$detailInsert = array(
					'house_id' => $insertData['house_id'],
					'uid' => $insertData['uid'],
					'address' => $insertData['address'],
					'parking_name' => $insertData['name'],
					'parking_id' => $parkingId,
					'fee_gname' =>  $feetypeList[0]['name'],
					'feetype_name' => $feeTypeRule['feeName'],
					'resident_id' => $insertData['resident_id'],
					'resident_name' => $feetypeList[0]['resident_name'],
					'year' => $year,
					'jz_area' => $insertData['jz_area'],
					'price' =>  $feeTypeRule['price'],
					'wuye_type' => $feeTypeRule['wuyeType'],
					'billing_style' => $feeTypeRule['billingStyle'],
					'add_uid' => $who['add_uid'],
					'add_username' => $who['add_username'],
					'amount_plan' => 0,
					'month' => 12-$month,
					'amount_real' => 0,
					'stat_date' => strtotime($year.'-'.($month+1).'-01'),
					'end_date' => strtotime($year.'-12-31'),
				);
				if(false !== strpos($detailInsert['billing_style'],'按面积')){						
					$detailInsert['amount_plan'] = $detailInsert['price'] * $detailInsert['jz_area'] * (12 -$month);
				}else if('按每月固定值' == $detailInsert['billing_style']){
					$detailInsert['amount_plan'] = $detailInsert['price'] * (12 -$month);
				}
				$detailInsert['amount_real'] = $detailInsert['amount_plan'];
			 	$wuyeDetailItem = $detailInsert;
			}
		}
		$this->_planDetailModel->setTableId($year);
		$this->_planModel->setTableId($year);
		$this->_planDetailModel->_add($wuyeDetailItem);
		$planList = $this->_planModel->getById(array('where' => array('house_id' => $insertData['house_id'])));
		$updateInfo  = array(
			'amount_real' => $planList['amount_real'] + $detailInsert['amount_real'],
			'amount_plan' => $planList['amount_plan'] + $detailInsert['amount_plan'],
		);
		$this->_planModel->update($updateInfo,array('house_id' => $planList['house_id']));

	 }
	 
	 public function transferAssets($oldHouseId,$newHouseId,$updateInfo,$parkingId){
	 	$year = date('Y');		
		$this->_planDetailModel->setTableId($year);
		$this->_planModel->setTableId($year);
		if($oldHouseId != $newHouseId){
			$parkingPlan = $this->_planDetailModel->getFirstByKey($parkingId,'parking_id');
			$oldHosuePlan =  $this->_planModel->getFirstByKey($oldHouseId,'house_id');
			$newHosuePlan =  $this->_planModel->getFirstByKey($newHouseId,'house_id');

			$this->_planModel->update(array(
				'amount_plan' => $oldHosuePlan['amount_plan'] - $parkingPlan['amount_plan'],
				'amount_real' => $oldHosuePlan['amount_real'] - $parkingPlan['amount_real'],
				'amount_payed' => $oldHosuePlan['amount_payed'] - $parkingPlan['amount_payed'],
			),array('house_id' => $oldHouseId , 'feetype_name' => '物业费'));
			$this->_planModel->update(array(
				'amount_plan' => $newHosuePlan['amount_plan'] + $parkingPlan['amount_plan'],
				'amount_real' => $newHosuePlan['amount_real'] + $parkingPlan['amount_real'],
				'amount_payed' => $newHosuePlan['amount_payed'] + $parkingPlan['amount_payed'],
			),array('house_id' => $newHouseId , 'feetype_name' => '物业费'));
		}
		$this->_planDetailModel->update(array(
			'parking_name' => $updateInfo['name'],
			'house_id' => $updateInfo['house_id'],
			'address' => $updateInfo['address'],
			'jz_area' => $updateInfo['jz_area'],
		),array('parking_id' => $parkingId));
	 }
	
}
