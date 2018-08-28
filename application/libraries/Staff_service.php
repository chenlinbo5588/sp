<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class StaffStatus
{
	//草稿
	public static $draft = 1;
	
	//待审核
	public static $unverify = 2;
	
	//已审核
	public static $verify = 3;
	
	//已发布
	public static $published = 4;
	
	//回收站
	public static $recylebin = 5;
	
	
	public static $statusName = array(
		1 => '草稿',
		2 => '待审核',
		3 => '已审核',
		4 => '已发布',
		5 => '回收站',
	);
}

class BookingMeet
{
	//未碰面
	public static $unmeet = 1;
	
	//有意向
	public static $intention = 2;
	
	//预约成功
	public static $meetsuccess = 3;
	
	//预约失败
	public static $meetfail = 4;
		
	public static $statusName = array(
		1 => '未碰面',
		2 => '有意向',
		3 => '预约完成',
		4 => '预约失败',
	);
}



class Staff_service extends Base_service {
	
	
	private $_workerModel = null;
	private $_workerImagesModel = null;
	private $_workerFilesModel = null;
	private $_staffModel = null;
	private $_staffImagesModel = null;
	
	
	private $_basicDataService = null;
	
	//列表
	private $_basicData = array();
	
	//键树
	private $_basicAssocDataTree = array();
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Worker_Model','Worker_Images_Model','Worker_Files_Model','Staff_Model','Staff_Images_Model',
			'Staff_Booking_Model'
		));
		
		self::$CI->load->library(array('Basic_data_service'));
		
		$this->_basicDataService = self::$CI->basic_data_service;
		
		$this->_workerModel = self::$CI->Worker_Model;
		$this->_workerImagesModel = self::$CI->Worker_Images_Model;
		$this->_workerFilesModel = self::$CI->Worker_Files_Model;
		$this->_staffModel = self::$CI->Staff_Model;
		$this->_staffImagesModel = self::$CI->Staff_Images_Model;
		$this->_staffBookingModel = self::$CI->Staff_Booking_Model;
		
		$this->_basicData = Basic_data_service::$basicData;
		$this->_basicAssocDataTree = Basic_data_service::$basicAssocDataTree;
		
	}
	
	/**
	 * 获得属相
	 */
	public function addShuXiangField($pParam){
		$shuList = $this->_basicDataService->getTopChildList('属相');
		
		//获得属相
		foreach($shuList as $shuItem){
			if($shuItem['show_name'] == getShuXiang($pParam['birthday'])){
				$pParam['shu'] = $shuItem['id'];
				break;
			}
		}
		
		
		return $pParam;
	}
	
	
	
	/**
	 * 
	 */
	public function addWorkerRules(){
		
		self::$CI->form_validation->set_rules('name','姓名','required|max_length[60]');
		self::$CI->form_validation->set_rules('id_type','证件类型','required|is_natural_no_zero');
		self::$CI->form_validation->set_rules('birthday','出生年月','required|valid_date');
		self::$CI->form_validation->set_rules('age','年龄','required|is_natural_no_zero');
		self::$CI->form_validation->set_rules('sex','性别','required|is_natural_no_zero');
		self::$CI->form_validation->set_rules('sg','身高','required|is_natural_no_zero|less_than[255]');
		self::$CI->form_validation->set_rules('marriage','婚育状态',"required|is_natural_no_zero");
		self::$CI->form_validation->set_rules('zzmm','政治面貌',"required|is_natural_no_zero");
		self::$CI->form_validation->set_rules('job_status','职务状态',"required|is_natural_no_zero");
		self::$CI->form_validation->set_rules('jiguan','籍贯',"required|is_natural_no_zero");
		self::$CI->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		self::$CI->form_validation->set_rules('address','现居住地址','required|max_length[100]');
		
		//$this->form_validation->set_rules('shu','属相','required|is_natural_no_zero');
		self::$CI->form_validation->set_rules('degree','最高学历','required|is_natural_no_zero');
		
		
		/*
		$remark = $this->input->post('remark');
		
		if($remark){
			$this->form_validation->set_rules('remark','备注','required');
		}
		*/
		
	}
	
	
	/**
	 * 
	 */
	public function addServRule($pServTypeName){
		
		self::$CI->form_validation->set_rules('region','服务区域','required|is_natural_no_zero');
		self::$CI->form_validation->set_rules('work_month','工作月份','required|is_natural_no_zero');
		self::$CI->form_validation->set_rules('service_cnt','服务个数','required|is_natural');
		self::$CI->form_validation->set_rules('salary','薪资范围要求',"required|is_natural_no_zero");
		self::$CI->form_validation->set_rules('salary_detail','薪资描述',"required|max_length[50]");
		
		
		self::$CI->form_validation->set_rules('serv_ablity[]','服务能力',"required");
		self::$CI->form_validation->set_rules('other_id[]','已有证件',"required");
		
		if('月嫂' == $pServTypeName){
			self::$CI->form_validation->set_rules('sbt_exp','双胞胎经验',"required|in_list[0,1]");
			self::$CI->form_validation->set_rules('zcbaby_exp','早产二经验',"required|in_list[0,1]");
		}else if('保姆' == $pServTypeName){
			self::$CI->form_validation->set_rules('sub_type','保姆类型','required|is_natural_no_zero');
		}else if('护工' == $pServTypeName){
			self::$CI->form_validation->set_rules('sub_type','护工类型','required|is_natural_no_zero');
			self::$CI->form_validation->set_rules('grade','护工等级','required|is_natural_no_zero');
		}
		
	}
	
	
	
	
	/**
	 * 保存worker
	 */
	public function saveWorker($pParam,$who,$imageList = array(),$fileList = array()){
		
		$returnVal = false;
		
		$pParam = $this->addShuXiangField($pParam);
		$workerId = 0;
		
		$this->_workerModel->beginTrans();
		
		
		if($pParam['avatar']){
			$avatarImg = getImgPathArray($pParam['avatar'],array('b','m','s'));
			$pParam = array_merge($pParam,$avatarImg);
		}
		
		if(!isset($pParam['id'])){
			$returnVal = $this->_workerModel->_add(array_merge($pParam,$who));
			$workerId = $returnVal;
			
		}else{
			$returnVal = $this->_workerModel->update(array_merge($pParam,$who),array('id' => $pParam['id']));
			
			$workerId = $pParam['id'];
			//同步修改 staff 表对应的 worker_id
			
			$this->_staffModel->updateByWhere(array(
				'worker_type' => $pParam['worker_type'],
				'name' => $pParam['name'],
				'show_name' => mb_substr($pParam['name'],0,1).($pParam['sex'] == 1 ? '先生' : '阿姨'),
				'id_type' => $pParam['id_type'],
				'id_no' => $pParam['id_no'],
				'mobile' => $pParam['mobile'],
				'jiguan' => $pParam['jiguan'],
				'marriage' => $pParam['marriage'],
				'address' => $pParam['address'],
				'age' => $pParam['age'],
				'sex' => $pParam['sex'],
				'sg' => $pParam['sg'],
				'zzmm' => $pParam['zzmm'],
				'job_status' => $pParam['job_status'],
				'birthday' => $pParam['birthday'],
				'shu' => $pParam['shu']
			),array(
				'worker_id' => $workerId
			));
		}
		
		//
		if($imageList){
			$updateFileIds = array();
			foreach($imageList as $fileInfo){
				$updateFileIds[] = $fileInfo['id'];
			}
			
			$this->_workerImagesModel->updateByCondition(array(
				'worker_id' => $workerId
			),array(
				'where' => array(
					'worker_id' => 0
				),
				'where_in' => array(
					array('key' => 'id', 'value' => $updateFileIds)
				)
			));
		}
		
		//
		if($fileList){
			$updateFileIds = array();
			foreach($fileList as $fileInfo){
				$updateFileIds[] = $fileInfo['id'];
			}
			
			$this->_workerFilesModel->updateByCondition(array(
				'worker_id' => $workerId
			),array(
				'where' => array(
					'worker_id' => 0
				),
				'where_in' => array(
					array('key' => 'id', 'value' => $updateFileIds)
				)
			));
		}
		
		if($this->_workerModel->getTransStatus() === FALSE){
			$this->_workerModel->rollBackTrans();
			return false;
		}else{
			$this->_workerModel->commitTrans();
			return $returnVal;
		}
		
	}
	
	
	
	/**
	 * 获得对于 的FieldName
	 */
	public function getServerTypeFieldName($pServTypeName){
		
		$fieldName = '';
		switch($pServTypeName){
			case '月嫂':
				$fieldName = 'yuesao_id';
				break;
			case '保姆':
				$fieldName = 'baomu_id';
				break;
			case '护工':
				$fieldName = 'hugong_id';
				break;
			default:
				break;
		}
		
		return $fieldName;
	}
	
	
	
	/**
	 * 更改状态
	 */
	public function changeStaffStatus($pIds,$statusDest,$statusCurrent,$extraData = array()){
		return $this->_staffModel->updateByCondition(array_merge(array(
			'status' => $statusDest
		),$extraData),array(
			'where' => array(
				'status' => $statusCurrent
			),
			'where_in' => array(
				array('key' => 'id', 'value' => $pIds)
			)
		));
	}
	
	
	
	/**
	 * 审核
	 */
	public function staffVerify($param ,$when, $who){
		$updateData = array(
			'reason' => $param['reason']
		);
		
		//$where['status'] = 1;
		
		switch($param['op']){
			case '审核通过':
				$updateData['status'] = StaffStatus::$verify;
				$updateData = array_merge($updateData,$who);
				$updateData['verify_time'] = $when;
				break;
			case '退回':
				$updateData['status'] = StaffStatus::$draft;
				break;
			default:
				break;
		}
		
		return $this->_staffModel->updateByCondition($updateData,array(
			'where_in' => array(
				array('key' => 'id', 'value' => $param['id'])
			)
		));
	}
	
	
	
	/**
	 * 家政业务人员
	 */
	public function saveStaff($servTypeName,$pStaffParam,$who,$imgList = array()){
		
		$returnVal = false;
		$staffId = 0;
		
		$pStaffParam = $this->addShuXiangField($pStaffParam);
		
		$this->_staffModel->beginTrans();
		
		$pStaffParam['service_type'] = intval($this->_basicAssocDataTree['业务类型']['children'][$servTypeName]['id']);
		
		//print_r($pStaffParam);
		//服务能力 保存为 json
		$pStaffParam['serv_ablity'] = json_encode($pStaffParam['serv_ablity']);
		$pStaffParam['other_id'] = json_encode($pStaffParam['other_id']);
		switch($pStaffParam['sex']){
			case 1:
				$pStaffParam['show_name'] = mb_substr($pStaffParam['name'],0,1).'先生';
				break;
			case 2:
				$pStaffParam['show_name'] = mb_substr($pStaffParam['name'],0,1).'阿姨';
				break;
			default:
				break;
		}
		
		//获取薪资
		$matchCnt = preg_match('/^(\d+)/is',$pStaffParam['salary_detail'],$salaryMatch);
		if($matchCnt){
			$pStaffParam['salary_amount'] = $salaryMatch[1];
		}
		
		if($pStaffParam['avatar']){
			$avatarImg = getImgPathArray($pStaffParam['avatar'],array('b','m','s'));
			$pStaffParam = array_merge($pStaffParam,$avatarImg);
		}
		
		if($imgList){
			$pStaffParam['has_photo'] = 1;
		}else{
			$pStaffParam['has_photo'] = 0;
		}
		
		if(!isset($pStaffParam['id'])){
			//事务
			
			$returnVal = $this->_staffModel->_add(array_merge($pStaffParam,$who));
			$staffId = $returnVal;
			
			$updateFieldName = $this->getServerTypeFieldName($servTypeName);
			
			if($updateFieldName){
				$this->_workerModel->update(array($updateFieldName => $returnVal),array('id' => $pStaffParam['worker_id']));
			}
			
		}else{
			$returnVal = $this->_staffModel->update(array_merge($pStaffParam,$who),array('id' => $pStaffParam['id']));
			$staffId = $pStaffParam['id'];
			
			
			//@TODO 可能需要同步修改 worker 表 公共字段
		}
		
		if($imgList){
			$updateFileIds = array();
			foreach($imgList as $fileInfo){
				$updateFileIds[] = $fileInfo['id'];
			}
			
			$this->_staffImagesModel->updateByCondition(array(
				'staff_id' => $staffId
			),array(
				'where' => array(
					'staff_id' => 0
				),
				'where_in' => array(
					array('key' => 'id', 'value' => $updateFileIds)
				)
			));
		}
		
		if($this->_staffModel->getTransStatus() === FALSE){
			$this->_staffModel->rollBackTrans();
			return false;
		}else{
			$this->_staffModel->commitTrans();
			return $returnVal;
		}
	}
	
	
	
	/**
	 * 删除 worker,只做标记，暂时不删除， 定期清理
	 */
	public function deleteWorker($pWorkerIds,$who,&$returnMessage){
		$deleteRows = $this->_workerModel->updateByCondition(
			array_merge(array(
				'status' => StaffStatus::$recylebin
			),$who),
			array(
				'where_in' => array(
					array('key' => 'id','value' => $pWorkerIds)
			)
		));
		
		return $deleteRows;
	}
	
	
	/**
	 * 删除服务类型
	 */
	public function deleteStaff($pServTypeName,$pIds){
		
		$imageList = array();
		
		$this->_staffModel->beginTrans();
		
		$staffList = $this->_staffModel->getList(array(
			'select' => 'id,service_type,worker_id,avatar,avatar_b,avatar_m,avatar_s',
			'where_in' => array(
				array('key' => 'id','value' => $pIds)
			)
		));
		
		$staffIds = array();
		$workerIds = array();
		foreach($staffList as $staffItem){
			$staffIds[] = $staffItem['id'];
			$workerIds[] = $staffItem['worker_id'];
			
			
			$imageList[] = $staffItem['avatar'];
			$imageList[] = $staffItem['avatar_b'];
			$imageList[] = $staffItem['avatar_m'];
			$imageList[] = $staffItem['avatar_s'];
		}
		
		
		//更新  worker 关联信息
		if($workerIds){
			$fieldName = $this->getServerTypeFieldName($pServTypeName);
			
			if($fieldName){
				$this->_workerModel->updateByCondition(array(
					$fieldName => 0
				),array(
					'where_in' => array(
						array('key' => 'id', 'value' => $workerIds)
					)
				));
			}
		}
		
		
		if($staffIds){
			//清空
			$imageList = $this->_staffImagesModel->getList(array(
				'select' => 'image,image_b,image_m',
				'where_in' => array(
					array('key' => 'staff_id', 'value' => $staffIds)
				)
			));
			
			if($imageList){
				foreach($imageList as $imgItem){
					$imageList[] = $imgItem['image'];
					$imageList[] = $imgItem['image_b'];
					$imageList[] = $imgItem['image_m'];
				}
				
			}
			$updateData['status'] = StaffStatus::$recylebin;
			$imageDeleteRows = $this->_staffImagesModel->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'staff_id', 'value' => $staffIds)
				)
			));
			
			
			$staffDeleteRows = $this->_staffModel->updateByCondition($updateData,array(
				'where_in' => array(
					array('key' => 'id', 'value' => $staffIds)
				)
			));
			
		}
		
		if($this->_staffModel->getTransStatus() === FALSE){
			$this->_staffModel->rollBackTrans();
			
			return false;
		}else{
			$this->_staffModel->commitTrans();
			//清楚图片文件
			self::$CI->attachment_service->deleteByFileUrl($imageList);
			
			return true;
		}
	}
	
	
	/**
	 * 根据ID 获取名称
	 */
	public function getServerTypeNameById($pServType){
		$staffTypeList = $this->_basicAssocDataTree['业务类型']['children'];
		$servTypeName = '';
		
		foreach($staffTypeList as $typeItem){
			if($typeItem['id'] == $pServType){
				$servTypeName = $typeItem['show_name'];
				break;
			}
		}
		
		return $servTypeName;
		
	}
	
	
	/**
	 * 
	 */
	public function getStaffInfoById($pStaffId,$extra = array()){
		$info = $this->_staffModel->getFirstByKey($pStaffId);
		
		if($info){
			$info['serv_ablity'] = json_decode($info['serv_ablity'],true);
			$info['other_id'] = json_decode($info['other_id'],true);
		}
		
		if($extra['photos']){
			$photos = $this->_staffImagesModel->getList(array(
				'select' => 'image,image_b,image_m,displayorder',
				'where' => array(
					'staff_id' => $pStaffId
				)
			));
			
			if($photos){
				$info['photos'] = $photos;
			}else{
				$info['photos'] = array();
			}
		}
		
		return $info;
	}
	
	
	
	/**
	 * 获得 staff List 
	 */
	public function getStaffList($pCondition = array(),$assocKey = ''){
		$condition = array(
			'select' => 'id,service_type,sub_type,name,show_name,id_type,jiguan,marriage,address,age,sex,birthday,shu,degree,grade,avatar,avatar_b,avatar_m,avatar_s,region,work_month,service_cnt,salary_amount,salary_detail,sbt_exp,zcbaby_exp',
		);
		
		$condition = array_merge($condition,$pCondition);
		
		if($assocKey){
			return $this->_staffModel->getList($condition,$assocKey);
		}else{
			return $this->_staffModel->getList($condition);
		}
		
	}
	
	
	
	/************************************ API Method ***************************/
	
	/**
	 * 获取详情
	 */
	public function getStaffDetail($pStaffId,$extra = array()){
		
		
		$info = $this->_staffModel->getFirstByKey($pStaffId,'id',
			'id,service_type,sub_type,name,show_name,id_type,jiguan,marriage,address,age,sex,birthday,shu,degree,grade,avatar,avatar_b,avatar_m,avatar_s,region,work_month,service_cnt,salary_amount,salary_detail,other_id,serv_ablity,other_id,sbt_exp,zcbaby_exp');
		
		if($info){
			$info['serv_ablity'] = json_decode($info['serv_ablity'],true);
			$info['other_id'] = json_decode($info['other_id'],true);
			
			$servTypeName = $this->getServerTypeNameById($info['service_type']);
			$ablityList = $this->_basicAssocDataTree[$servTypeName]['children'][$servTypeName.'服务能力']['children'];
			//print_r($ablityList);
			foreach($ablityList as $ablityGroup => $ablityItem){
				if($ablityItem['children']){
					//print_r($ablityItem);
					foreach($ablityItem['children'] as $subItemName => $subItem){
						if(in_array($subItem['id'],$info['serv_ablity'])){
							$ablityList[$ablityGroup]['children'][$subItemName]['selected'] = true;
						}
					}
					
				}else{
					if(in_array($ablityItem['id'],$info['serv_ablity'])){
						$ablityList[$ablityGroup]['selected'] = true;
					}
				}
			}
			
			$info['serv_ablity_all'] = $ablityList;
			
			
			$info['id_type'] = $this->_basicData[$info['id_type']]['show_name'];
			$info['jiguan'] = $this->_basicData[$info['jiguan']]['show_name'];
			$info['marriage'] = $this->_basicData[$info['marriage']]['marriage'];
			$info['shu'] = $this->_basicData[$info['shu']]['show_name'];
			$info['degree'] = $this->_basicData[$info['degree']]['show_name'];
			$info['region'] = $this->_basicData[$info['region']]['show_name'];
			
			$info['other_id'] = $this->translateIdToName($info['other_id'],'证件证明');
			
			/*
			$matchCnt = preg_match('/^(\d+)/is',$info['salary_detail'],$salaryMatch);
			if($matchCnt){
				$info['salary_amount'] = $salaryMatch[1];
			}
			*/
		}
		
		if($extra['photos']){
			$photos = $this->_staffImagesModel->getList(array(
				'select' => 'image,image_b,image_m,displayorder',
				'where' => array(
					'staff_id' => $pStaffId
				)
			));
			
			if($photos){
				$info['photos'] = $photos;
			}else{
				$info['photos'] = array();
			}
		}
		
		return $info;
		
		
	}
	
	
	/**
	 * 获得查询条件
	 */
	public function getSearchCondition($pSearchTypeName){
		
		$searchAr = array();
		
		$searchAr[] = $this->_basicAssocDataTree['服务区域'];
		$searchAr[] = $this->_basicAssocDataTree['籍贯'];
		$searchAr[] = $this->_basicAssocDataTree['学历'];
		$searchAr[] = $this->_basicAssocDataTree['属相'];
		
		$searchAr[] = $this->_basicAssocDataTree[$pSearchTypeName]['children'][$pSearchTypeName.'薪资'];
		$searchAr[] = $this->_basicAssocDataTree[$pSearchTypeName]['children'][$pSearchTypeName.'服务数量'];
		$searchAr[] = $this->_basicAssocDataTree[$pSearchTypeName]['children'][$pSearchTypeName.'经验月份'];
		
		switch($pSearchTypeName){
			case '月嫂':
				$searchAr[] = $this->_basicAssocDataTree[$pSearchTypeName]['children']['双胞胎经验'];
				$searchAr[] = $this->_basicAssocDataTree[$pSearchTypeName]['children']['早产儿经验'];
				break;
			case '保姆':
				//放置在第一个
				array_unshift($searchAr,$this->_basicAssocDataTree[$pSearchTypeName]['children'][$pSearchTypeName.'类型']);
				break;
			case '护工':
				array_unshift($searchAr,$this->_basicAssocDataTree[$pSearchTypeName]['children'][$pSearchTypeName.'类型']);
				break;
			default:
				break;
		}
		
		$searchAr[] = $this->_basicAssocDataTree['照片信息'];
		$searchAr[] = $this->_basicAssocDataTree['视频信息'];
		
		return $searchAr;
	}
	
	/**
	 * 转换ID 到 Name
	 */
	public function translateIdToName($pIds,$pGroupName = ''){
		
		$otherIds = $this->_basicAssocDataTree[$pGroupName]['children'];
		$otherIdName = array();
		
		foreach($otherIds as $tmpKey => $otherVal){
			$otherIdName[$otherVal['id']] = $otherVal['show_name'];
		}
		
		$tempArray = array();
		
		foreach($pIds as $tempVal){
			$tempArray[] = $otherIdName[$tempVal];
		}
		
		return $tempArray;
	}
	
	
	/**
	 * 
	 */
	public function getStaffListByCondition($pServTypeName,$pCondition,$otherInfo = array()){
		
		
		$currentPage = $pCondition['page'] ? $pCondition['page'] : 1;
		$condition = array(
			'select' => 'id,service_type,name,show_name,jiguan,age,avatar_m,avatar_s,work_month,service_cnt,salary_amount,salary_detail,other_id,has_photo,has_photo,has_video,sbt_exp',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$condition = array_merge($condition,$pCondition);
		$condition['where']['service_type'] = $this->_basicAssocDataTree['业务类型']['children'][$pServTypeName]['id'];
		
		
		$data = $this->_staffModel->getList($condition);
		
		
		if($otherInfo['other_id']){
			
			
			
			$otherIds = $this->_basicAssocDataTree['证件证明']['children'];
			$otherIdName = array();
			
			foreach($otherIds as $tmpKey => $otherVal){
				$otherIdName[$otherVal['id']] = $otherVal['show_name'];
			}
			
			foreach($data['data'] as $dk => $item){
				$temp = json_decode($item['other_id'],true);
				
				$tempArray = array();
				
				if($temp){
					foreach($temp as $tempVal){
						$tempArray[] = $otherIdName[$tempVal];
					}
				}
				
				$data['data'][$dk]['other_id'] = $tempArray;
			}
		}
		
		
		return $data;
	}
	
	
	
	
}
