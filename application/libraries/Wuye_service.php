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
		
	public static $statusName = array(
		1 => '管道维修',
		2 => '电路报修',
		3 => '电器报修',
		4 => '网络报修',
		5 => '其他报修'
	);
}


/**
 * 物业核心服务
 */
class Wuye_service extends Base_service {
	
	private $_residentModel;
	private $_buildingModel;
	private $_houseModel;
	private $_yezhuModel;
	private $_feeTypeModel;
	
	
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Resident_Model','Building_Model','House_Model','Yezhu_Model',
			'Feetype_Model','Basic_Data_Model','Repair_Model','Repair_Images_Model'

		));
		
		$this->_residentModel = self::$CI->Resident_Model;
		$this->_buildingModel = self::$CI->Building_Model;
		$this->_houseModel = self::$CI->House_Model;
		$this->_yezhuModel = self::$CI->Yezhu_Model;
		$this->_feeTypeModel = self::$CI->Feetype_Model;
		$this->_repairModel = self::$CI->Repair_Model; 

		
	}
	

	// 小区管理
	public function deleteResident($pId){
		//只有悬挂的小区才能被删除
		
	}
	
	/*
	public function saveResident($pResidentParam,$who){
		$returnVal = false;
		if(!isset($pResidentParam['id'])){
			//事务
			$returnVal = $this->_residentModel->_add(array_merge($pResidentParam,$who));
		}else{
			
			$this->_residentModel->beginTrans();
			$returnVal = $this->_residentModel->update(array_merge($pResidentParam,$who),array('id' => $pResidentParam['id']));
			
			
			//修改小区名称，则自动更新对象的幢以及
			
			
			if($this->_residentModel->getTransStatus() === FALSE){
				$this->_residentModel->rollBackTrans();
				return false;
			}else{
				$this->_residentModel->commitTrans();
				return $returnVal;
			}
		}
	}
	*/
	
	
	
	
	/**
	 * 
	 */
	public function addYezhuRules($idTypeList,$pIdType,$id = 0){
		
		self::$CI->form_validation->set_rules('name','姓名','required|max_length[50]');
		
		$idRules = array('required');
		
		if(ENVIRONMENT == 'production'){
			$idName = '';
			
			foreach($idTypeList as $idTypeItem){
				if($idTypeItem['id'] == $pIdType || $idTypeItem['show_name'] == $pIdType){
					$idName = $idTypeItem['show_name'];
				}
			}
			
			if('身份证' == $idName || '驾驶证' == $idName){
				$idRules[] = 'valid_idcard';
			}else{
				$idRules[] = 'max_length[50]';
			}
		}else{
			$idRules[] = 'max_length[50]';
		}
		
		if($id){
			$idRules[] = 'is_unique_not_self['.$this->_yezhuModel->getTableRealName().".id_no.id.{$id}]";
		}else{
			$idRules[] = 'is_unique['.$this->_yezhuModel->getTableRealName().".id_no]";
		}
		
		self::$CI->form_validation->set_rules('id_no','证件号码',implode('|',$idRules));
		
		self::$CI->form_validation->set_rules('birthday','出生年月','required|valid_date');
		self::$CI->form_validation->set_rules('age','年龄','required|is_natural_no_zero');
		self::$CI->form_validation->set_rules('sex','性别','required|in_list[1,2]');
		
		self::$CI->form_validation->set_rules('jiguan','籍贯',"required|is_natural_no_zero");
		
		
		if($id){
			self::$CI->form_validation->set_rules('mobile','手机号码','required|valid_mobile|is_unique_not_self['.$this->_yezhuModel->getTableRealName().".mobile.id.{$id}]");
		}else{
			self::$CI->form_validation->set_rules('mobile','手机号码','required|valid_mobile|is_unique['.$this->_yezhuModel->getTableRealName().'.mobile]');
		}
		
		
	}
	
	
	public function addFeeTypeRules(){
		
		self::$CI->form_validation->set_rules('name','费用类型名称','required|in_db_list['.self::$CI->Basic_Data_Model->getTableRealName().'.show_name]');
		
		self::$CI->form_validation->set_rules('price','每平方单价','required|is_numeric');
		self::$CI->form_validation->set_rules('resident_name','小区名称','required|in_db_list['.$this->_residentModel->getTableRealName().'.name]');
		
	}
	
	
	/**
	 * 检查费用类型 是否存储
	 */
	public function checkFeetype($pHouseId,$extraStr = ''){
		$extraInfo = explode(',',$extraStr);
		
		$flag = false;
		
		if(!empty($extraInfo[0]) && !empty($extraInfo[1])){
			$houseInfo = $this->_houseModel->getFirstByKey($pHouseId);
			if($houseInfo['resident_id']){
				
				$cnt = $this->_feeTypeModel->getCount(array(
					'where' => array(
						'name' => $extraInfo[1],
						'year' => $extraInfo[0],
						'resident_id' => $houseInfo['resident_id']
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
			'select' => 'year,name,price',
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
		//self::$CI->form_validation->set_rules('start_month','缴费起始月份','required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[12]');
		self::$CI->form_validation->set_rules('end_month','缴费到期月份','required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[12]');
	}
	
	/**
	 * 计算 费用
	 * 
	 * @return float   
	 */
	public function computeHouseFee($pComputeParam){
		
		$houseInfo = $this->_houseModel->getFirstByKey($pComputeParam['id']);
		//获得费用设置
		$tempFeeSetting = $this->getResidentFeeSetting($houseInfo['resident_id'],$pComputeParam['year'],$pComputeParam['orderTypeName']);
		
		//基于按年缴费的方式
		$monthCnt = intval(date('m',$pComputeParam['newEndTimeStamp'])) - intval(date('m',$pComputeParam['newStartTimeStamp'])) + 1;
		
		return number_format($monthCnt * $tempFeeSetting['price'] * $houseInfo['jz_area'],2,'.',"");
	}
	
	
	/**
	 * 获得缴费情况
	 * 
	 */
	public function getCurrentHouseFeeInfo($pHouseId,$pOrderTypename,$endMonth = 0){
		
		$houseInfo = $this->_houseModel->getFirstByKey($pHouseId);
		
		$temp = array(
			'id' => $pHouseId,
			'year' => date('Y'),
			'expireTimeStamp' => 0,//原到期时间戳
			'newStartTimeStamp' => 0,//新开始时间戳
			'newEndTimeStamp' => 0,	//新的结束时间戳,
			'orderTypeName' => $pOrderTypename,
		);
		
		switch($pOrderTypename){
			case '物业费':
				$temp['expireTimeStamp'] = $houseInfo['wuye_expire'];
				break;
			case '能耗费':
				$temp['expireTimeStamp'] = $houseInfo['nenghao_expire'];
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
	 * 获得业主信息
	 */
	public function getYezhuInfoById($pId,$key = 'uid'){
		$yezhuInfo = $this->_yezhuModel->getFirstByKey($pId,$key);
		
		//注意身份证件号码的隐藏
		
		if($yezhuInfo){
			$yezhuInfo['id_no'] = mask_string($yezhuInfo['id_no']);
			
			return $yezhuInfo;
		}else{
			return array();
		}
		
	}
	
	
	/**
	 * 获得业主物业类别
	 */
	public function getYezhuHouseListByYezhu($pYezhu){
		
		$yezhuHouseList = $this->_houseModel->getList(array(
			'select' => 'id,address,jz_area,lng,lat,wuye_expire,nenghao_expire',
			'where' => array(
				'yezhu_id' => $pYezhu['id']
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
	 * 根据业主 获得物业
	 */
	public function getYezhuHouseDetail($houseId,$pYezhu = array()){
		
		$condition = array(
			'select' => 'id,resident_id,address,jz_area,lng,lat,wuye_expire,nenghao_expire',
			'where' => array(
				'id' => $houseId,
			)
		);
		
		if($pYezhu){
			$condition['where']['yezhu_id'] = $pYezhu['id'];
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
	
	
}
