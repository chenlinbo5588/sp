<?php
defined('BASEPATH') OR exit('No direct script access allowed');


abstract class Staff extends Ydzj_Admin_Controller {
	
	
	public $idTypeList;
	public $_moduleTitle;
	public $_className;
	
	public $statusConfig = array();
	
	public $staffClass = '';
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Staff_service','Attachment_service'));
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		$this->staffClass = strtolower(get_class());
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/unverify','title' => '待审核'),
			array('url' => $this->_className.'/verify','title' => '已审核'),
		);
		
		$this->idTypeList = $this->staff_service->getTopChildList('证件类型');
		
		$this->statusConfig = array(
			'未提交',
			'待审核',
			'已审核'
		);
		
		$this->assign(array(
			'statusConfig' => $this->statusConfig,
			'basicData' => $this->staff_service->getBasicDataList()
		));
	}
	
	
	
	/**
	 * 查询条件
	 */
	public function _searchCondition($moreSearchVal = array()){
		$search['currentPage'] = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$search['name'] = $this->input->get_post('name') ? $this->input->get_post('name') : '';
		$search['age_s'] = $this->input->get_post('age_s') ? $this->input->get_post('age_s') : '';
		$search['age_e'] = $this->input->get_post('age_e') ? $this->input->get_post('age_e') : '';
		$search['status'] = $this->input->get_post('status') ? $this->input->get_post('status') : '';
		$search['mobile'] = $this->input->get_post('mobile') ? $this->input->get_post('mobile') : '';
	
	
		$search = array_merge($search,$moreSearchVal);
		
		$condition = array(
			'select' => 'id,name,show_name,id_type,id_no,mobile,address,jiguan,age,avatar_m,avatar_s,status,work_month,service_cnt,salary_amount,salary_detail',
			'where' => array(),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $search['currentPage'],
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		if($search['name']){
			$condition['where']['name'] = $search['name'];
		}
		
		
		if($search['age_s']){
			$condition['where']['age >='] = $search['age_s'];
		}
		
		if($search['age_e']){
			$condition['where']['age <='] = $search['age_e'];
		}
		
		switch($search['status']){
			case '待审核':
				$condition['where']['status'] = 1;
				break;
			case '已审核':
				$condition['where']['status'] = 2;
				break;
			default:
				break;
		}
		
		if($search['mobile']){
			$condition['where']['mobile'] = $search['mobile'];
		}
	
		//print_r($condition);
		$list = $this->staff_service->getStaffListByCondition($this->_moduleTitle,$condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('search',$search);
		
		$this->assign('currentPage',$search['currentPage']);
		
	}
	
	
	/**
	 * 获得列表
	 */
	public function index(){
		
		$this->_searchCondition();
		$this->display($this->staffClass.'/index');
	}
	
	
	/**
	 * 未审核 列表
	 */
	public function unverify(){
		
		$this->_searchCondition(array(
			'status' => '待审核'
		));
		$this->display($this->staffClass.'/index');
		
	}
	
	
	/**
	 * 已审核列表
	 */
	public function verify(){
		
		$this->_searchCondition(array(
			'status' => '已审核'
		));
		
		
		$this->display($this->staffClass.'/index');
	}
	
	
	/**
	 * 提交审核
	 */
	public function handle_verify(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->staff_service->handleStaffVerify($ids);
			
			if($returnVal >= 0){
				$this->jsonOutput('提交审核成功',$this->getFormHash());
			}else{
				$this->jsonOutput('提交审核失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	
	/**
	 * 单个审核
	 */
	public function single_verify(){
		
		$feedback = '';
		$inPost = false;
		$id = $this->input->get_post('id');
		
		$info = array();
		
		$info = $this->staff_service->getStaffInfoById($id);
		$fileList = array();
		$workerFileList = array();
		
		
		if($info['worker_id']){
			$workerInfo = $this->Worker_Model->getFirstByKey($info['worker_id']);
			$this->assign('workerInfo',$workerInfo);
			$workerFileList = $this->Worker_Images_Model->getImagesListByWorkerId($info['worker_id']);
		}
		
		
		$this->_subNavs[] = array('url' => $this->_className.'/single_verify?id='.$id, 'title' => '审核');
		
		if($this->isPostRequest()){
			$this->_getVerifyRules();
			
			$inPost = true;
			
			for($i = 0; $i < 1; $i++){
				//$fileList = $this->_getFileList();
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$op = $this->input->get_post('op');
				
				$returnVal = $this->staff_service->staffVerify(array(
					'op' => $op,
					'id' => array($id),
					'reason' => $this->input->post('reason')
				),$this->_reqtime, $this->addWhoHasOperated('verify'));
				
				
				if($returnVal < 0){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
				
			}
		}else{
			$fileList = $this->Staff_Images_Model->getImagesListByStaffId($id);
			
			
			//print_r($info);
		
			$this->_commonPageData();
			$this->assign(array(
				'fileList' => $fileList,
				'workerFileList' => $workerFileList,
				'inPost' => $inPost,
				'info' => $info,
				'feedback' => $feedback,
			));
			
			$this->display($this->staffClass.'/single_verify');
		}
		
		
		
	}
	
	
	private function _getVerifyRules(){
		
		$this->form_validation->set_rules('id','记录ID必填','required');
		$this->form_validation->set_rules('reason','备注','required|min_length[2]|max_length[100]');
	}
	
	/**
	 * 批量审核
	 */
	public function batch_verify(){
		
		
		if($this->isPostRequest()){
			
			
			$this->_getVerifyRules();
			
			for($i = 0; $i < 1 ; $i++){
			
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$id = $this->input->post('id');
				$idAr = explode(',',$id);
				$op = $this->input->get_post('op');
				
				
				$returnVal = $this->staff_service->staffVerify(array(
					'op' => $op,
					'id' => $idAr,
					'reason' => $this->input->post('reason')
				),$this->_reqtime, $this->addWhoHasOperated('verify'));
				
				
				if($returnVal < 0){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
			}
			
		}else{
			
			$this->assign('id',implode(',',$this->input->get_post('id')));
			
			$this->display($this->staffClass.'/batch_verify');
		}
		
	}
	
	
	/**
	 * 校验规则
	 */
	private function _getRules(){
		$this->staff_service->addWorkerRules();
		$this->staff_service->addServRule($this->_moduleTitle);
	}
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			
			$returnVal = $this->staff_service->deleteStaff($this->_moduleTitle,$ids);
			
			if($returnVal){
				$this->jsonOutput('删除成功',$this->getFormHash());
			}else{
				
				$this->jsonOutput('删除失败',$this->getFormHash());
			}
			
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 准备数据
	 */
	private function _prepareData($action = 'add'){
		
		$info = array();
		
		$remark = $this->input->post('remark');
		
		if($remark){
			$info['remark'] = $remark;
		}
		
		if('add' == $action){
			// 删除老照片
			$originalPic = $this->input->post('old_pic');
			if($originalPic){
				//如果上传了新文件,则删除原文件
				$oldImgArray = getImgPathArray($originalPic,array('b','m','s'));
				$this->attachment_service->deleteByFileUrl($oldImgArray);
			}
		}
		
		return $info;
	}
	
	
	/**
	 * 获得人员的证件相关照片
	 */
	private function _getFileList(){
		$file_ids = $this->input->post('file_id');
		return $this->Staff_Images_Model->getImageListByFileIds($file_ids);
	}
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		
		$info = array();
		
		$workerId = $this->input->get_post('worker_id');
		
		if($workerId){
			$info = $this->Worker_Model->getFirstByKey($workerId);
			$info['worker_id'] = $workerId;
			$this->assign('workerInfo',$info);
			
			unset($info['id'],$info['avatar'],$info['avatar_b'],$info['avatar_m'],$info['avatar_s']);
			$workerFileList = $this->Worker_Images_Model->getImagesListByWorkerId($workerId);
		}
		
		$redirectUrl = '';
		$inPost = false;
		
		$this->_subNavs[] = array('url' => $this->_className.'/add?worker_id='.$workerId, 'title' => $this->_moduleTitle.'入驻');
		
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			$inPost = true;
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData());
				$fileList = $this->_getFileList();
				
				$this->assign('fileList',$fileList);
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					
					//校验出错时 ，记住上传的头像 避免用户重传
					if($info['avatar']){
						$info = array_merge($info,getImgPathArray($info['avatar'],array('b','m','s')));
					}
					
					break;
				}
				
				if(($newid = $this->staff_service->saveStaff($this->_moduleTitle,$info,$this->addWhoHasOperated(),$fileList)) <= 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				//$feedback = getSuccessTip('保存成功,3秒后自动刷新');
				$info = $this->staff_service->getStaffInfoById($newid);
				
				$this->assign('successMessage','保存成功,3秒后自动刷新');
				
				$redirectUrl = admin_site_url($this->_className.'/edit?id='.$info['id']);
			}
		}else{
			
			$info['serv_ablity'] = array();
		}
		
		//print_r($servAblity);
		
		
		$this->_commonPageData();
		
		$this->assign(array(
			'workerFileList' => $workerFileList,
			'inPost' => $inPost,
			'info' => $info,
			'redirectUrl' => $redirectUrl,
			'feedback' => $feedback,
		));
		
		$this->display($this->staffClass.'/add');
	}
	
	
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addImageAttachment('Filedata',array(),FROM_BACKGROUND,'worker');
		if($fileData){
			
			$fileData = $this->attachment_service->resize($fileData);
			
			$info = array(
				'staff_id' => $this->input->get_post('id') ? $this->input->get_post('id') : 0,
				'image_aid' => $fileData['id'],
				'image' => $fileData['file_url'],
				'image_b' => !empty($fileData['img_b']) ? $fileData['img_b'] : '',
				'image_m' => !empty($fileData['img_m']) ? $fileData['img_m'] : '',
				'uid' => $this->_adminProfile['basic']['uid']
			);
			
			$imageId = $this->Staff_Images_Model->_add($info);
			if($imageId){
				$json['error'] = 0;
				$json['id'] = $fileData['id'];
				$json['image_id'] = $imageId;
				$json['url'] = base_url($fileData['file_url']);
				$json['msg'] = '上传成功';
				//尽量选择小图
				if($fileData['img_b']){
					$json['img_b'] = base_url($fileData['img_b']);
				}
				
				if($fileData['img_m']){
					$json['img_m'] = base_url($fileData['img_m']);
				}
				
			}else{
				$json['error'] = 0;
				$json['msg'] = '系统异常';
				$this->attachment_service->deleteByFileUrl(array(
					$fileData['file_url'],
					$fileData['img_b'],
					$fileData['img_m'],
				));
			}
			
		}else{
			$json['msg'] = $this->attachment_service->getErrorMsg('','');
		}
		
		exit(json_encode($json));
		
	}
	
	
	public function delimg(){
		$file_id = intval($this->input->get_post('file_id'));
		$Refid = intval($this->input->get_post('staff_id'));
		
		if($Refid){
			//如果在编辑页面
			$this->Staff_Images_Model->deleteByCondition(array(
				'where' => array(
					'image_aid' => $file_id,
					'staff_id' => $Refid,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}else{
			//在新增界面，还没有worker id
			$this->Staff_Images_Model->deleteByCondition(array(
				'where' => array(
					'image_aid' => $file_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}
		
		if($file_id){
			//文件删除，数据库记录不删除
			$this->attachment_service->deleteFiles($file_id,'all',FROM_BACKGROUND);
		}
		
		$this->jsonOutput('成功',$this->getFormHash());
	}
	
	
	/**
	 * 
	 */
	private function _commonPageData(){
		$basicData = $this->staff_service->getAssocBasicDataTree();
		
		$pageData = array(
			'province_idcard' => json_encode(config_item('province_idcard')),
			'idTypeList' => $this->idTypeList,
			'jiguanList' => $this->staff_service->getTopChildList('籍贯'),
			'xueliList' => $this->staff_service->getTopChildList('学历'),
			'marriageList' => $this->staff_service->getTopChildList('婚育状态'),
			'regionList' => $this->staff_service->getTopChildList('服务区域'),
			'salaryList' => $basicData[$this->_moduleTitle]['children'][$this->_moduleTitle.'薪资']['children'],
			'ablityList' => $basicData[$this->_moduleTitle]['children'][$this->_moduleTitle.'服务能力']['children'],
		);
		
		if('保姆' == $this->_moduleTitle){
			$pageData['subTypeList'] = $basicData[$this->_moduleTitle]['children'][$this->_moduleTitle.'类型']['children'];
		}elseif('护工' == $this->_moduleTitle){
			$pageData['subTypeList'] = $basicData[$this->_moduleTitle]['children'][$this->_moduleTitle.'类型']['children'];
			$pageData['gradeList'] = $basicData[$this->_moduleTitle]['children'][$this->_moduleTitle.'等级']['children'];
		}
		
		$this->assign($pageData);
	}
	
	
	/**
	 * 编辑服务人员
	 */
	public function edit(){
		
		$feedback = '';
		$inPost = false;
		$id = $this->input->get_post('id');
		$actioName = '编辑';
		
		$info = array();
		
		
		$info = $this->staff_service->getStaffInfoById($id);
		$fileList = array();
		$workerFileList = array();
		
		if($info['worker_id']){
			$workerInfo = $this->Worker_Model->getFirstByKey($info['worker_id']);
			$this->assign('workerInfo',$workerInfo);
			$workerFileList = $this->Worker_Images_Model->getImagesListByWorkerId($info['worker_id']);
		}
		
		$oldAvatar = $info['avatar'];
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			$inPost = true;
			
			for($i = 0; $i < 1; $i++){
				$postInfo = $this->_prepareData('edit');
				$fileList = $this->_getFileList();
				
				$info = array_merge($_POST,$postInfo);
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					
					//校验出错时 ，记住上传的头像 避免用户重传
					if($info['avatar']){
						$info = array_merge($info,getImgPathArray($info['avatar'],array('b','m','s')));
					}
					
					break;
				}
				
				//print_r($info);
				
				if($this->staff_service->saveStaff($this->_moduleTitle,$info,$this->addWhoHasOperated('edit'),$fileList) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				
				$info = $this->staff_service->getStaffInfoById($id);
				if($oldAvatar && $oldAvatar != $info['avatar']){
					//如果上传了新文件,则删除原文件
					$oldImgsAr = getImgPathArray($oldAvatar,array('b','m','s'));
					$this->attachment_service->deleteByFileUrl($oldImgsAr);
				}
				
				$this->assign('successMessage','保存成功');
			}
		}else{
			$fileList = $this->Staff_Images_Model->getImagesListByStaffId($id);
		}
		
		//print_r($info);
		
		$this->_commonPageData();
		$this->assign(array(
			'fileList' => $fileList,
			'workerFileList' => $workerFileList,
			'inPost' => $inPost,
			'info' => $info,
			'feedback' => $feedback,
			'actioName' => $actioName
		));
		
		$this->display($this->staffClass.'/add');
	}
	
	
	/**
	 * 裁剪头像
	 */
	public function pic_cut(){
		if($this->isPostRequest()){
			$src_file = str_ireplace(base_url(),'',$this->input->post('url'));
			//echo $src_file;
			$fileData = $this->attachment_service->resize(array('file_url' => $src_file) , 
				array('m' => array('width' => $this->input->post('w'),'height' => $this->input->post('h'),'maintain_ratio' => false , 'quality' => 100)) , 
				array('x_axis' => $this->input->post('x1'), 'y_axis' => $this->input->post('y1')));
			
			if($fileData['img_m']){
				$smallImg = $this->attachment_service->resize(array(
					'file_url' => $fileData['img_m']
				) , array('s') );
			}
			
			//删除原图
			//@unlink($fileData['full_path']);
				
			if (empty($fileData['img_m'])){
				exit(json_encode(array(
					'status' => 0, 
					'formhash'=>$this->security->get_csrf_hash(),
					'msg'=>$this->attachment_service->getErrorMsg('','')
				)));
			}else{
				exit(json_encode(array(
					'status' => 1, 
					'formhash'=>$this->security->get_csrf_hash(),
					'url'=>base_url($fileData['img_m']),
					'picname' => $src_file
				)));
			}
			
		}else{
			$save_file = str_ireplace(base_url(),'',$this->input->get('url'));
			
			list($width, $height, $type, $attr) =  getimagesize(ROOTPATH.'/'.$save_file);
			
			$this->assign('image_width',$width);
			$this->assign('image_height',$height);
			$this->assign('formUrl', admin_site_url($this->_className.'/pic_cut'));
			$this->display('common/pic_cut');
		}
		
	}
}
