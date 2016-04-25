<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Goods extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Goods_Model'));
		$this->load->library(array('Goods_service','Attachment_Service'));
	}
	
	
	
	public function index(){
		$searchMap['goods_state'] = array('-1' => '未发布','1' => '正常');
		$searchMap['goods_verify'] = array('-1' => '未通过','1' => '通过');
		
		$brandList = $this->Brand_Model->getList();
		$brandList = $this->goods_service->toEasyUseArray($brandList,'brand_id');
		$treelist = $this->goods_service->toEasyUseArray($this->goods_service->getGoodsClassTreeHTML(),'gc_id');
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'brand_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$goodsName = $this->input->get_post('search_goods_name');
		$goodsVerify = $this->input->get_post('goods_verify') ? $this->input->get_post('goods_verify') : 0;
		$goodsState = $this->input->get_post('goods_state') ? $this->input->get_post('goods_state') : 0;
		$goodsClassId = $this->input->get_post('gc_id') ? $this->input->get_post('gc_id') : 0;
		
		
		if($goodsName){
			$condition['like']['goods_name'] = $goodsName;
		}
		
		if($goodsVerify != '全部'){
			$condition['where']['goods_verify'] = $goodsVerify;
		}
		
		if($goodsState != '全部'){
			$condition['where']['goods_state'] = $goodsState;
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
		$this->form_validation->set_rules('goods_recommend','是否推荐','required|in_list[0,1]');
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
		$fileInfo = $this->attachment_service->addImageAttachment('goods_pic',array(),FROM_BACKGROUND);
		
		//print_r($fileInfo);
		$info = array(
			'goods_name' => $this->input->post('goods_name'),
			'gc_id' => $this->input->post('gc_id') ? $this->input->post('gc_id') : 0,
			'brand_id' => $this->input->post('brand_id') ? $this->input->post('brand_id') : 0,
			'goods_intro' => $this->input->post('goods_intro') ? $this->input->post('goods_intro') : '',
			'goods_recommend' => $this->input->post('goods_recommend'),
			'goods_verify' => $this->input->post('goods_verify'),
			'goods_state' => $this->input->post('goods_state'),
			
		);
		
		if($fileInfo){
			$info['goods_pic'] = $fileInfo['file_url'];
			
			$originalPic = $this->input->post('old_pic');
			
			if($originalPic){
				$this->attachment_service->deleteByFileUrl($originalPic);
			}
		}
		
		
		return $info;
	}
	
	
	public function add(){
		$feedback = '';
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		$brandList = $this->Brand_Model->getList();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareGoodsData();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				//$info['goods_public'] = $this->input->server('REQUEST_TIME');
				
				if(($newid = $this->Goods_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
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
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('goods_id');
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		$brandList = $this->Brand_Model->getList();
		
		$info = $this->Goods_Model->getFirstByKey($id,'goods_id');
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$postInfo = $this->_prepareGoodsData();
				
				if(empty($postInfo['goods_pic']) && !empty($info['goods_pic'])){
					$postInfo['goods_pic'] = $info['goods_pic'];
				}
				
				$info = $postInfo;
				$info['goods_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Goods_Model->update($info,array('goods_id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}else{
			$info = $this->Goods_Model->getFirstByKey($id,'goods_id');
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('brandList',$brandList);
		$this->assign('goodsClassList',$treelist);
		$this->display('goods/add');
	}
}
