<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium extends Ydzj_Admin_Controller {
	
	private $_imageSize ;
	private $_imageSizeKeys;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Stadium_service', 'Sports_service','Common_District_service','Attachment_service'));
		
		$this->_imageSize = config_item('stadium_img_size');
		$this->_imageSizeKeys = array_keys($this->_imageSize);
		
		$this->assign('imageConfig',$this->_imageSize);
	}
	
	public function index(){
		$this->load->library(array('Member_service'));
		
		$currentPage = $this->input->get('page') ? $this->input->get('page') : 1;
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => 3,
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
			
		);
		
		$ds = array();
		$dkeys = array('d1','d2','d3','d4');
		$tempK = '';
		$searchDk = '';
		foreach($dkeys as $dk){
			$tempK = $this->input->get($dk);
			if($tempK){
				$searchDk = $dk;
				$ds[$dk] = $tempK ;
			}
		}
		
		if(empty($ds)){
			$this->assign('ds',$this->common_district_service->prepareCityData());
		}else{
			$condition['where'][$searchDk] = $ds[$searchDk];
			
			$this->assign('ds',$this->common_district_service->prepareCityData($ds));
		}
		
		$search_map['create_time'] = array('id DESC' => '按时间倒序','id ASC' => '按时间顺序');
		$search['name'] = $this->input->get('name');
		
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		
		if(in_array($search['create_time'], array_keys($search_map['create_time']))){
			$condition['order'] = $search['create_time'];
		}
		
		$list = $this->Stadium_Model->getList($condition);
		
		$userList = array();
		foreach($list['data'] as $item){
			$userList[] = $item['owner_uid'];
		}
		
		if($userList){
			$this->assign('userInfo',$this->member_service->getListByCondition(array(
				'select' => 'uid,mobile,avatar_m,avatar_s',
				'where_in' => array(
					array('key' => 'uid' ,'value' => $userList )
				)
			)));
		}
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->assign('search_map',$search_map);
		
		$this->display();
	}
	
	/**
	 * 
	 */
	private function _city(){
		$ds = array();
		$dkeys = array('d1','d2','d3','d4');
		$tempK = '';
		$searchDk = '';
		foreach($dkeys as $dk){
			$tempK = $this->input->post($dk);
			if($tempK){
				$searchDk = $dk;
				$ds[$dk] = $tempK ;
			}
		}
		
		if(empty($ds)){
			$this->assign('ds',$this->common_district_service->prepareCityData());
		}else{
			$this->assign('ds',$this->common_district_service->prepareCityData($ds));
		}
	}
	
	
	
	private function _getFileList(){
		$file_ids = $this->input->post('file_id');
		$fileList = array();
		if($file_ids){
			$fileList = $this->Attachment_Model->getList(array(
				'select' => 'id,file_url,ip',
				'where_in' => array(
					array('key' => 'id', 'value' => $file_ids)
				),
				'order' => 'id DESC'
			));
		}
		
		return $fileList;
		
	}
	
	
	/**
	 * 添加场馆
	 */
	public function add(){
		
		$allMetaList = $this->stadium_service->getAllMetaGroups();
		$sportsList = $this->sports_service->getSportsCategory();
		
		$this->assign('allMetaList',$allMetaList);
		$this->assign('sportsList',$sportsList);
		
		$info = array();
		
		if($this->isPostRequest()){
			$addParam = $this->stadium_service->stadiumAddRules();
			
			for($i = 0 ; $i < 1; $i++){
				$info = $_POST;
				
				
				if($addParam['avatar']){
					$this->attachment_service->setImageSizeConfig($this->_imageSize);
					$resizeFile = $this->attachment_service->resize($addParam['avatar'],$this->_imageSizeKeys,array(), 'avatar');
					$addParam = array_merge($addParam,$resizeFile);
				}
				
				$fileList = $this->_getFileList();
				foreach($fileList as $fk => $file){
					$file['aid'] = $file['id'];
					$file = array_merge($file,getImgPathArray($file['file_url'],$this->_imageSizeKeys));
					$fileList[$fk] = $file;
				}
				
				$this->assign('fileList',$fileList);
				
				if(!$this->form_validation->run()){
					break;
				}
				
				$addParam = array_merge($addParam,$this->addWhoHasOperated('add'));
				$newid = $this->stadium_service->addStadium($addParam,$fileList);
				
				if($newid > 0){
					//蒋队伍名称 何队伍图片去除
					unset($info);
					$this->assign('feedback',getSuccessTip('添加成功'));
				}else{
					$this->assign('feedback',getErrorTip('添加失败'));
				}
			}
		}else{
			
			$info['status'] = 0;
			$info['category_name'] = array();
			$info['ground_type'] = array();
			$info['support_sports'] = array();
		}
		
		$this->assign('info',$info);
		$this->display();
		
	}
	
	
	public function addimg(){
		$json = array('error' => 1,'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addImageAttachment('fileupload',array(),FROM_BACKGROUND,'stadium');
		
		if($fileData){
			$info = array(
				'stadium_id' => $this->input->post('id') ? $this->input->post('id') : 0,
				'aid' => $fileData['id'],
				'avatar' => $fileData['file_url'],
				'uid' => $this->_adminProfile['basic']['uid'],
				'ip' => $this->input->ip_address()
			);
			
			
			if($info['avatar']){
				$this->attachment_service->setImageSizeConfig($this->_imageSize);
				$resizeFile = $this->attachment_service->resize($info['avatar'],$this->_imageSizeKeys,array(), 'avatar');
				$info = array_merge($info,$resizeFile);
			}
			
			if($info['stadium_id']){
				$imageId = $this->Stadium_Photos_Model->_add($info);
				if($imageId){
					$json['error'] = 0;
					$json['id'] = $imageId;
					$json['aid'] = $fileData['id'];
					$json['url'] = base_url($info['avatar_m']);
					$json['msg'] = '成功';
				}else{
					$json['error'] = 0;
					$json['msg'] = '系统异常';
					
					$this->attachment_service->deleteByFileUrl(array($info['avatar'],$info['avatar_h'],$info['avatar_b'],$info['avatar_m']));
				}
			}else{
				$json['error'] = 0;
				$json['aid'] = $fileData['id'];
				$json['url'] = base_url($info['avatar_m']);
				$json['msg'] = '成功';
			}
		}else{
			$json['msg'] = $this->attachment_service->getErrorMsg('','');
		}
		
		$this->jsonOutput($json['msg'],$json);
	}
	
	
	public function delimg(){
		$file_id = $this->input->get_post('file_id');
		$id = $this->input->get_post('id');
		
		if($id){
			//如果在编辑页面
			$condition = array(
				'where' => array(
					'aid' => $file_id,
					'stadium_id' => $id,
				)
			);
			
			$stadiumPhoto = $this->Stadium_Photos_Model->getById($condition);
			
			if($stadiumPhoto){
				$this->Stadium_Photos_Model->deleteByCondition($condition);
				
				$wantDeleteFiles = array($stadiumPhoto['avatar']);
				foreach($this->_imageSizeKeys as $key){
					$wantDeleteFiles[] = $stadiumPhoto['avatar_'.$key];
				}
				$this->attachment_service->deleteByFileUrl($wantDeleteFiles);
			}
			
			$this->jsonOutput('成功');
		}else{
			
			$this->jsonOutput('参数错误');
		}
		
	}
	
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$id = $this->input->get_post('id');
		
		$allMetaList = $this->stadium_service->getAllMetaGroups();
		$sportsList = $this->sports_service->getSportsCategory();
		
		$this->assign('allMetaList',$allMetaList);
		$this->assign('sportsList',$sportsList);
		
		$info = $this->Stadium_Model->getFirstByKey($id);
		
		if($this->isPostRequest()){
			$editParam = $this->stadium_service->stadiumAddRules();
			
			for($i = 0 ; $i < 1; $i++){
				//新传了的图片
				if($editParam['avatar'] && $info['aid'] != $editParam['aid']){
					$this->attachment_service->setImageSizeConfig($this->_imageSize);
					$resizeFile = $this->attachment_service->resize($editParam['avatar'],$this->_imageSizeKeys,array(), 'avatar');
					$editParam = array_merge($editParam,$resizeFile);
				}
				
				$fileList = $this->_getFileList();
				foreach($fileList as $fk => $file){
					$file['aid'] = $file['id'];
					$file = array_merge($file,getImgPathArray($file['file_url'],$this->_imageSizeKeys));
					$fileList[$fk] = $file;
				}
				
				if(!$this->form_validation->run()){
					$this->assign('feedback',$this->form_validation->error_string());
					$info = $_POST;
					break;
				}
				
				$editParam = array_merge($editParam,$this->addWhoHasOperated('edit'));
				$rows = $this->stadium_service->editStadium($id,$editParam);
				
				if($rows > 0){
					$this->assign('feedback',getSuccessTip('保存成功'));
				}else{
					$this->assign('feedback',getErrorTip('保存失败'));
				}
				
				$info = $this->Stadium_Model->getFirstByKey($id);
			}
		}else{
			
			$fileList = $this->Stadium_Photos_Model->getList(array(
				'select' => 'aid,avatar_m',
				'where' => array('stadium_id' => $id)
			));
		}
		
		
		foreach(array('category_name','ground_type','support_sports') as $grouname){
			if(!is_array($info[$grouname])){
				$info[$grouname] = explode(',',$info[$grouname]);
			}
		}
		
		$this->assign('fileList',$fileList);
		
		$this->assign('info',$info);
		$this->display('stadium/add');
	}
	
	
	
	
	/**
	 * 队伍审核
	 */
	public function audit(){
		
		$teamId = $this->input->get_post('id');
		
		$info = $this->team_service->getTeamInfo($teamId);
		
		if(!empty($teamId) && $this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_rules('status','审核状态','required|in_list[1,-1]');
				$this->form_validation->set_rules('remark','审核备注','required|min_length[2]');
				
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('保存失败',$d);
					break;
				}
				
				$updateParam = array(
					'status' => $this->input->post('status'),
					'remark' => $this->input->post('remark'),
				);
				
				$row = $this->team_service->auditTeam($teamId,$updateParam,$this->addWhoHasOperated('edit'));
				
				if($row >= 0){
					$this->jsonOutput('保存成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
		}else{
			$this->jsonOutput('请求格式不正确');
		}
	}
}
