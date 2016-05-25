<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Goods extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Goods_service','Attachment_service'));
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
	}
	
	
	
	public function index(){
		$searchMap['goods_state'] = array('未发布' => '0','正常' => '1');
		$searchMap['goods_verify'] = array('未通过' => '0','通过' => '1');
		
		$brandList = $this->Brand_Model->getList();
		$brandList = $this->goods_service->toEasyUseArray($brandList,'brand_id');
		$treelist = $this->goods_service->toEasyUseArray($this->goods_service->getGoodsClassTreeHTML(),'gc_id');
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'goods_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$goodsName = $this->input->get_post('search_goods_name');
		$goodsVerify = $this->input->get_post('goods_verify') ? $this->input->get_post('goods_verify') : '';
		
		$goodsState = $this->input->get_post('goods_state') ? $this->input->get_post('goods_state') : '';
		$goodsClassId = $this->input->get_post('gc_id') ? $this->input->get_post('gc_id') : 0;
		
		if($goodsName){
			$condition['like']['goods_name'] = $goodsName;
		}
		
		
		if($searchMap[$goodsVerify]){
			$condition['where']['goods_verify'] = $searchMap[$goodsVerify];
		}
		
		if($searchMap[$goodsState]){
			$condition['where']['goods_state'] = $searchMap[$goodsState];
		}
		
		
		
		if($goodsClassId){
			$goodsClassIdList = $this->goods_service->getAllChildGoodsClassByPid($goodsClassId);
			$goodsClassIdList[] = $goodsClassId;
			
			$condition['where_in'][] = array('key' => 'gc_id', 'value' => $goodsClassIdList);
			
		}
		
		
		//print_r($condition);
		
		$list = $this->Goods_Model->getList($condition);
		
		$this->assign('brandList',$brandList);
		$this->assign('goodsClassList',$treelist);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		$this->assign('searchMap',$searchMap);
		
		$this->display();
	}
	
	
	
	
	private function _getRules(){
		$this->form_validation->set_rules('goods_name','商品名称','required|max_length[60]');
		$this->form_validation->set_rules('gc_id','商品分类',"required|in_db_list[{$this->Goods_Class_Model->_tableRealName}.gc_id]");
		$this->form_validation->set_rules('goods_intro','商品简介','required');
		$this->form_validation->set_rules('goods_commend','是否推荐','required|in_list[0,1]');
		$this->form_validation->set_rules('goods_verify','是否审核','required|in_list[0,1]');
		$this->form_validation->set_rules('goods_state','是否发布','required|in_list[0,1]');
	}
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Goods_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'goods_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareGoodsData(){
		
		
		$fileInfo = $this->attachment_service->addImageAttachment('goods_pic',array(),FROM_BACKGROUND,'goods');
		
		//print_r($fileInfo);
		$info = array(
			'goods_name' => $this->input->post('goods_name'),
			'gc_id' => $this->input->post('gc_id') ? $this->input->post('gc_id') : 0,
			'brand_id' => $this->input->post('brand_id') ? $this->input->post('brand_id') : 0,
			'goods_intro' => $this->input->post('goods_intro') ? $this->input->post('goods_intro') : '',
			'goods_commend' => $this->input->post('goods_commend') ? $this->input->post('goods_commend') : 0,
			'goods_verify' => $this->input->post('goods_verify'),
			'goods_state' => $this->input->post('goods_state'),
			
		);
		
		if($fileInfo){
			$info['goods_pic'] = $fileInfo['file_url'];
			$info['file_url'] = $fileInfo['file_url'];
		}
		
		
		return $info;
	}
	
	
	
	private function _getFileList(){
		$file_ids = $this->input->post('file_id');
		
		$fileList = array();
		
		if($file_ids){
			$fileList = $this->Attachment_Model->getList(array(
				'select' => 'id,file_url',
				'where_in' => array(
					array('key' => 'id', 'value' => $file_ids)
				),
				'order' => 'id DESC'
			));
			
			
		}
		
		return $fileList;
		
	}
	
	public function add(){
		$feedback = '';
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		$brandList = $this->Brand_Model->getList();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareGoodsData();
				$fileList = $this->_getFileList();
				
				$this->assign('fileList',$fileList);
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->input->post('goods_state') == 1){
					$info['public_time'] = $this->input->server('REQUEST_TIME');
				}
				
				$info['goods_code'] = $info['gc_id'] . '-' . strtolower(substr(md5($info['goods_name']),0,6));
				
				if(($newid = $this->Goods_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				
				
				if($fileList){
					$insData = array();
					
					foreach($fileList as $fileInfo){
						$insData[] = array(
							'goods_id' => $newid,
							'goods_image_aid' => $fileInfo['id'],
							'goods_image' => $fileInfo['file_url'],
							'uid' => $this->_adminProfile['basic']['uid'],
							'gmt_create' => $this->input->server('REQUEST_TIME'),
							'gmt_modify' => $this->input->server('REQUEST_TIME'),
						);
					}
					
					$this->Goods_Images_Model->batchInsert($insData);
				}
				
				$feedback = getSuccessTip('保存成功');
				
				$info = $this->Goods_Model->getFirstByKey($newid,'goods_id');
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		
		$this->assign('brandList',$brandList);
		$this->assign('goodsClassList',$treelist);
		$this->display();
	}
	
	
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上次失败');
		
		$fileData = $this->attachment_service->addImageAttachment('fileupload',array(),FROM_BACKGROUND,'goods');
		if($fileData){
			$info = array(
				'goods_id' => $this->input->post('goods_id') ? $this->input->post('goods_id') : 0,
				'goods_image' => $fileData['file_url']
			);
			
			if($info['goods_id']){
				$imageId = $this->Goods_Images_Model->_add($info);
				if($imageId){
					$json['error'] = 0;
					$json['id'] = $fileData['id'];
					$json['image_id'] = $imageId;
					$json['url'] = base_url($fileData['file_url']);
				}else{
					$json['error'] = 0;
					$json['msg'] = '系统异常';
					$this->attachment_service->deleteByFileUrl($fileData['file_url']);
				}
			}else{
				$json['error'] = 0;
				$json['id'] = $fileData['id'];
				$json['url'] = base_url($fileData['file_url']);
			}
			
		}else{
			$json['msg'] = $this->attachment_service->getErrorMsg('','');
		}
		
		exit(json_encode($json));
		
	}
	
	
	public function delimg(){
		$file_id = $this->input->get_post('file_id');
		$goods_id = $this->input->get_post('goods_id');
		
		if($goods_id){
			//如果在商品编辑页面
			$this->Goods_Images_Model->deleteByCondition(array(
				'where' => array(
					'goods_image_aid' => $file_id,
					'goods_id' => $goods_id,
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
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('goods_id');
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		$brandList = $this->Brand_Model->getList();
		
		$info = $this->Goods_Model->getFirstByKey($id,'goods_id');
		
		$fileList = array();
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$postInfo = $this->_prepareGoodsData();
				$fileList = $this->_getFileList();
				//没有新上次文件
				if(empty($postInfo['goods_pic']) && !empty($info['goods_pic'])){
					$postInfo['goods_pic'] = $info['goods_pic'];
				}
				
				$info = $postInfo;
				$info['goods_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				
				if($this->input->post('goods_state') == 1){
					$info['public_time'] = $this->input->server('REQUEST_TIME');
				}
				
				if($this->Goods_Model->update($info,array('goods_id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$originalPic = $this->input->post('old_pic');
				if($postInfo['file_url'] && $originalPic){
					//如果上传了新文件,则删除原文件
					$this->attachment_service->deleteByFileUrl($originalPic);
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}else{
			
			$currentFiles = $this->Goods_Images_Model->getList(array(
				'select' => 'goods_image_aid,goods_image',
				'where' => array('goods_id' => $id)
			));
			
			
			foreach($currentFiles as $item){
				$fileList[] = array(
					'id' => $item['goods_image_aid'],
					'file_url' => $item['goods_image']
				);
			}
		}
		
		$this->assign('fileList',$fileList);
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('brandList',$brandList);
		$this->assign('goodsClassList',$treelist);
		$this->display('goods/add');
	}
}
