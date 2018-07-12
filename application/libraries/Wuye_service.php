<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
			'Feetype_Model'
		));
		
		$this->_residengtModel = self::$CI->Resident_Model;
		$this->_buildingModel = self::$CI->Building_Model;
		$this->_houseModel = self::$CI->House_Model;
		$this->_yezhuModel = self::$CI->Yezhu_Model;
		$this->_feeTypeModel = self::$CI->Feetype_Model;
		
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
			$returnVal = $this->_residengtModel->_add(array_merge($pResidentParam,$who));
		}else{
			
			$this->_residengtModel->beginTrans();
			$returnVal = $this->_residengtModel->update(array_merge($pResidentParam,$who),array('id' => $pResidentParam['id']));
			
			
			//修改小区名称，则自动更新对象的幢以及
			
			
			if($this->_residengtModel->getTransStatus() === FALSE){
				$this->_residengtModel->rollBackTrans();
				return false;
			}else{
				$this->_residengtModel->commitTrans();
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
	
	
	
	/**
	 * 根据会话 初始话 相关数据
	 * 
	 */
	public function initUserInfoBySession($pSession,$idKey = 'openid'){
		
		$idVal = $pSession[$idKey];
		
		//$info = array();
		
		$memberInfo = self::$memberModel->getFirstByKey($idVal,$idKey);
		
		/*
		if($memberInfo){
			$info['member'] = $memberInfo;
		}
		*/
		
		
		return $memberInfo;
		
	}
	
	/**
	 * 获得业主信息
	 */
	public function getYezhuInfoByMobile($pId,$key = 'mobile'){
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
		
		return array(
			'houseCnt' => count($yezhuHouseList),
			'houseList' => $yezhuHouseList
		);
	}
	
	
	/**
	 * 根据业主 获得物业
	 */
	public function getYezhuHouseDetail($houseId,$pYezhu){
		
		$houseInfo = $this->_houseModel->getById(array(
			'select' => 'id,address,jz_area,lng,lat,wuye_expire,nenghao_expire',
			'where' => array(
				'id' => $houseId,
				'yezhu_id' => $pYezhu['id']
			)
		));
		
		
		if($houseInfo){
			return $houseInfo;
		}else{
			return array();
		}
		
	}
	
	
}
