<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Worker extends Ydzj_Admin_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Basic_data_service','Staff_service','Attachment_service'));
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
		
		$this->_moduleTitle = '家政从业人员';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '新增'),
		);
		
		$this->assign('basicData',$this->basic_data_service->getBasicDataList());
	}
	
	public function index(){
		
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$name = $this->input->get_post('name');
		
		if($name){
			$condition['like']['name'] = $name;
		}
		
		
		$list = $this->Worker_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		$message = '';
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
				
			$returnVal = $this->staff_service->deleteWorker($ids,$this->addWhoHasOperated('edit'),$message);
			if($returnVal <= 0){
				$this->jsonOutput('删除失败',$this->getFormHash());
			}else{
				$this->jsonOutput('删除成功',$this->getFormHash());
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
	 * 图片列表
	 */
	private function _getImgList(){
		$file_ids = $this->input->post('img_file_id');
		return $this->Worker_Images_Model->getImageListByIds($file_ids);
		
	}
	
	
	/**
	 * 附件列表
	 */
	private function _getFileList(){
		$file_ids = $this->input->post('file_id');
		return $this->Worker_Files_Model->getFileListByIds($file_ids);
	}
	
	
	/**
	 * 
	 */
	private function _commonPageData(){
		$basicData = $this->basic_data_service->getAssocBasicDataTree();
		
		$this->assign(array(
			'province_idcard' => json_encode(config_item('province_idcard')),
			'idTypeList' => $this->basic_data_service->getTopChildList('证件类型'),
			'workerTypeList' => $this->basic_data_service->getTopChildList('工种类型'),
			'zzmmList' => $this->basic_data_service->getTopChildList('政治面貌'),
			'jobStatusList' => $this->basic_data_service->getTopChildList('职务状态'),
			'jiguanList' => $this->basic_data_service->getTopChildList('籍贯'),
			'xueliList' => $this->basic_data_service->getTopChildList('学历'),
			'marriageList' => $this->basic_data_service->getTopChildList('婚育状态'),
		));
		
	}
	
	
	
	public function add(){
		$feedback = '';
		
		$redirectUrl = '';
		$fileList = array();
		$imgList = array();
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('worker_type','工种类型',"required|is_natural_no_zero");
			
			$this->staff_service->addIDRules(
				$this->basic_data_service->getTopChildList('证件类型'),
				$this->input->post('id_type'), 0, true, 
				$this->Worker_Model->getTableRealName()
			);
			
			$this->staff_service->addWorkerRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData());
				$imgList = $this->_getImgList();
				$fileList = $this->_getFileList();
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip('数据校验失败');
					//$feedback = getErrorTip($this->form_validation->error_string());
					
					//校验出错时 ，记住上传的头像 避免用户重传
					if($info['avatar']){
						$info = array_merge($info,getImgPathArray($info['avatar'],array('b','m','s')));
					}
					
					break;
				}
				
				if(($newid = $this->staff_service->saveWorker($info,$this->addWhoHasOperated(),$imgList,$fileList)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$this->assign('successMessage','保存成功,3秒后自动刷新');
				$info = $this->Worker_Model->getFirstByKey($newid);
				
				$redirectUrl = admin_site_url($this->_className.'/edit?id='.$info['id']);
			}
		}
		
		$this->_commonPageData();
		$this->assign(array(
			'editable' => true,
			'fileList' => $fileList,
			'imgList' => $imgList,
			'info' => $info,
			'redirectUrl' => $redirectUrl,
			'feedback' => $feedback
		));
		
		$this->display();
	}
	
	
	
	/**
	 * 添加其他附件
	 */
	public function addfile(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addAttachment('Filedata',array('allowed_types' => 'pdf|doc|docx'));
		
		if($fileData){
			
			$info = array(
				'worker_id' => $this->input->get_post('id') ? $this->input->get_post('id') : 0,
				'title' => $fileData['orig_name'],
				'file_url' => $fileData['file_url'],
				'file_size' => $fileData['orig_size'],
				'file_ext' => $fileData['file_ext'],
				'uid' => $this->_adminProfile['basic']['uid'],
				'ip' => $fileData['ip'],
			);
			
			$fileId = $this->Worker_Files_Model->_add($info);
			if($fileId){
				$json['error'] = 0;
				$json['id'] = $fileId;
				$json['url'] = base_url($fileData['file_url']);
				$json['file_name'] = $fileData['orig_name'];
				$json['file_size'] = byte_format($fileData['orig_size']);
				$json['msg'] = '上传成功';
			}else{
				$json['msg'] = '系统异常';
				$this->attachment_service->deleteByFileUrl(array($fileData['file_url']));
			}
			
		}else{
			$json['msg'] = $this->attachment_service->getErrorMsg('','');
		}
		
		exit(json_encode($json));
		
	}
	
	/**
	 * 删除文件公共方法
	 */
	private function _delFile($modelObj,$isImg = false){
		
		$file_id = intval($this->input->get_post('file_id'));
		$worker_id = intval($this->input->get_post('id'));
		
		
		$rowsDelete = 0;
		
		$fileInfo = $modelObj->getFirstByKey($file_id);
		
		if($worker_id){
			//如果在编辑页面
			$rowsDelete = $modelObj->deleteByCondition(array(
				'where' => array(
					'id' => $file_id,
					'worker_id' => $worker_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}else{
			//在新增界面，还没有worker id
			$rowsDelete = $modelObj->deleteByCondition(array(
				'where' => array(
					'id' => $file_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}
		
		
		if($rowsDelete){
			if($isImg){
				$this->attachment_service->deleteByFileUrl(array($fileInfo['image'],$fileInfo['image_b'],$fileInfo['image_m']));
			}else{
				$this->attachment_service->deleteByFileUrl(array($fileInfo['file_url']));
			}
		}
		
		$this->jsonOutput('成功',$this->getFormHash());
	}
	
	
	/**
	 * 删除文件
	 */
	public function delfile(){
		$this->_delFile($this->Worker_Files_Model);
	}
	
	
	
	/**
	 * 添加文件
	 */
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addImageAttachment('Filedata',array('allowed_types' => 'jpg'));
		
		if($fileData){
			
			$fileData = $this->attachment_service->resize($fileData);
			
			$info = array(
				'worker_id' => $this->input->get_post('id') ? $this->input->get_post('id') : 0,
				'image' => $fileData['file_url'],
				'image_b' => !empty($fileData['img_b']) ? $fileData['img_b'] : '',
				'image_m' => !empty($fileData['img_m']) ? $fileData['img_m'] : '',
				'uid' => $this->_adminProfile['basic']['uid'],
				'ip' => $fileData['ip'],
			);
			
			$imageId = $this->Worker_Images_Model->_add($info);
			if($imageId){
				$json['error'] = 0;
				$json['id'] = $imageId;
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
	
	
	/**
	 * 删除图片文件
	 */
	public function delimg(){
		
		$this->_delFile($this->Worker_Images_Model,true);
	}
	
	
	public function detail(){
		
		
		$id = $this->input->get_post('id');
		
		$info = $this->Worker_Model->getFirstByKey($id);
		
		$fileList = array();
		$imgList = array();
		
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '详情');
		
		$imgList = $this->Worker_Images_Model->getImagesListByWorkerId($id);
		$fileList = $this->Worker_Files_Model->getFileListByWorkerId($id);
		
		
		$this->_commonPageData();
		$this->assign(array(
			'editable' => false,
			'imgList' => $imgList,
			'fileList' => $fileList,
			'info' => $info
		));
		
		$this->display($this->_className.'/add');
		
	}
	
	
	
	/**
	 * 编辑工作人员
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		
		$info = $this->Worker_Model->getFirstByKey($id);
		
		$fileList = array();
		$imgList = array();
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		$oldAvatar = $info['avatar'];
		
		if($this->isPostRequest()){
			
			$this->staff_service->addIDRules(
				$this->basic_data_service->getTopChildList('证件类型'),
				$this->input->post('id_type'), $id, true, 
				$this->Worker_Model->getTableRealName()
			);
			
			$this->form_validation->set_rules('worker_type','工种类型',"required|is_natural_no_zero");
			$this->staff_service->addWorkerRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData('edit'));
				
				$imgList = $this->_getImgList();
				$fileList = $this->_getFileList();
				
				if(!$this->form_validation->run()){
					//$feedback = getErrorTip($this->form_validation->error_string());
					$feedback = getErrorTip('数据校验失败');
					
					//校验出错时 ，记住上传的头像 避免用户重传
					if($info['avatar']){
						$info = array_merge($info,getImgPathArray($info['avatar'],array('b','m','s')));
					}
					
					break;
				}
				
				
				if($this->staff_service->saveWorker($info,$this->addWhoHasOperated('edit'),$imgList,$fileList) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$info = $this->Worker_Model->getFirstByKey($info['id']);
				
				if($oldAvatar && $oldAvatar != $info['avatar']){
					//如果上传了新文件,则删除原文件
					$oldImgsAr = getImgPathArray($oldAvatar,array('b','m','s'));
					$this->attachment_service->deleteByFileUrl($oldImgsAr);
				}
				
				
				$this->assign('successMessage','保存成功');
			}
			
		}else{
			
			$imgList = $this->Worker_Images_Model->getImagesListByWorkerId($id);
			$fileList = $this->Worker_Files_Model->getFileListByWorkerId($id);
		}
		
		
		$this->_commonPageData();
		$this->assign(array(
			'editable' => true,
			'imgList' => $imgList,
			'fileList' => $fileList,
			'info' => $info,
			'feedback' => $feedback
		));
		
		$this->display($this->_className.'/add');
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
