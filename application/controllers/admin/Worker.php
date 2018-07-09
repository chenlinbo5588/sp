<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Worker extends Ydzj_Admin_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Staff_service','Attachment_service'));
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
		
		$this->assign('basicData',$this->staff_service->getBasicDataList());
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
		$fileInfo = $this->attachment_service->addImageAttachment('worker_pic',array('widthout_db' => true),FROM_BACKGROUND,'worker');
		
		$info = array();
		
		if($fileInfo){
			$oldFile = $this->input->post('old_pic');
			if('add' == $action){
				//添加页面 删除上一次上传的图片
				if($oldFile){
					$oldFiles = getImgPathArray($oldFile,array('b','m','s'));
					$this->attachment_service->deleteByFileUrl($oldFiles);
				}
				
				//设置新上传的图片
				$info['avatar'] = $fileInfo['file_url'];
				
				//设置上一张图片
				$info['old_pic'] = $info['avatar'];
			}else{
				//编辑页面 
				
				$info['old_pic'] = $oldFile;
				$info['avatar'] = $fileInfo['file_url'];
			}
			
			$fileInfo = $this->attachment_service->resize($fileInfo,array('b','m','s'));
			
			if($fileInfo['img_b']){
				//如果裁剪了大图用大图,防止上传超级大图后，前台页面显示好几兆的图片
				$info['avatar_b'] = $fileInfo['img_b'];
			}
			
			if($fileInfo['img_m']){
				//如果裁剪了大图用大图,防止上传超级大图后，前台页面显示好几兆的图片
				$info['avatar_m'] = $fileInfo['img_m'];
			}
			
			if($fileInfo['img_s']){
				//如果裁剪了大图用大图,防止上传超级大图后，前台页面显示好几兆的图片
				$info['avatar_s'] = $fileInfo['img_s'];
			}
			
			// 标记上传了新文件,用于删除旧文件用
			$info['file_url'] = $fileInfo['file_url'];
		}else{
			
			$pic = $this->input->post('old_pic');
			if($pic){
				//还是记住上一张
				$info['old_pic'] = $pic;
				
				$info['avatar'] = $pic;
				$imgs = getImgPathArray($info['avatar'],array('b','m','s'));
				$info = array_merge($info,$imgs);
			}
		}
		
		$remark = $this->input->post('remark');
		
		if($remark){
			$info['remark'] = $remark;
		}
		
		return $info;
	}
	
	
	
	private function _getFileList(){
		$file_ids = $this->input->post('file_id');
		return $this->Worker_Images_Model->getImageListByFileIds($file_ids);
		
	}
	
	
	
	
	/**
	 * 
	 */
	private function _commonPageData(){
		$basicData = $this->staff_service->getAssocBasicDataTree();
		
		$this->assign(array(
			'province_idcard' => json_encode(config_item('province_idcard')),
			'idTypeList' => $this->staff_service->getTopChildList('证件类型'),
			'jiguanList' => $this->staff_service->getTopChildList('籍贯'),
			'xueliList' => $this->staff_service->getTopChildList('学历'),
			'marriageList' => $this->staff_service->getTopChildList('婚育状态'),
		));
		
	}
	
	
	
	public function add(){
		$feedback = '';
		
		$redirectUrl = '';
		$fileList = array();
		
		if($this->isPostRequest()){
			
			$this->staff_service->addWorkerRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData());
				$fileList = $this->_getFileList();
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				if(($newid = $this->staff_service->saveWorker($info,$this->addWhoHasOperated(),$fileList)) < 0){
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
			'fileList' => $fileList,
			'info' => $info,
			'redirectUrl' => $redirectUrl,
			'feedback' => $feedback
		));
		
		$this->display();
	}
	
	
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addImageAttachment('Filedata',array(),FROM_BACKGROUND,'worker');
		if($fileData){
			
			$fileData = $this->attachment_service->resize($fileData);
			
			$info = array(
				'worker_id' => $this->input->get_post('id') ? $this->input->get_post('id') : 0,
				'image_aid' => $fileData['id'],
				'image' => $fileData['file_url'],
				'image_b' => !empty($fileData['img_b']) ? $fileData['img_b'] : '',
				'image_m' => !empty($fileData['img_m']) ? $fileData['img_m'] : '',
				'uid' => $this->_adminProfile['basic']['uid']
			);
			
			$imageId = $this->Worker_Images_Model->_add($info);
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
		$worker_id = intval($this->input->get_post('worker_id'));
		
		if($worker_id){
			//如果在编辑页面
			$this->Worker_Images_Model->deleteByCondition(array(
				'where' => array(
					'image_aid' => $file_id,
					'worker_id' => $worker_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}else{
			//在新增界面，还没有worker id
			$this->Worker_Images_Model->deleteByCondition(array(
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
	 * 编辑工作人员
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		
		$info = $this->Worker_Model->getFirstByKey($id);
		$fileList = array();
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->staff_service->addWorkerRules();
			
			for($i = 0; $i < 1; $i++){
				
				$postInfo = array_merge($_POST,$this->_prepareData('edit'));
				$fileList = $this->_getFileList();
				
				$info = $postInfo;
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				if($this->staff_service->saveWorker($info,$this->addWhoHasOperated('edit'),$fileList) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$originalPic = $this->input->post('old_pic');
				if($postInfo['file_url'] && $originalPic){
					//如果上传了新文件,则删除原文件
					$this->attachment_service->deleteByFileUrl($originalPic);
				}
				
				$this->assign('successMessage','保存成功');
			}
			
		}else{
			$fileList = $this->Worker_Images_Model->getList(array(
				'where' => array('worker_id' => $id)
			));
		}
		
		
		
		$this->_commonPageData();
		$this->assign(array(
			'fileList' => $fileList,
			'info' => $info,
			'feedback' => $feedback
		));
		
		$this->display($this->_className.'/add');
	}
}
