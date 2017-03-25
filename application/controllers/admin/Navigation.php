<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Navigation extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Navigation_service'));
	}
	
	public function category(){
		
		$id = $this->input->get_post('pid') ? $this->input->get_post('pid') : 0;
		
		$treelist = $this->navigation_service->getClassTreeHTML();
		$deep = 0;
		
		
		//print_r($list);
		$parentId = 0;
		foreach($treelist as $item){
			if($id == $item['id']){
				$deep = $item['level'];
				$parentId = $item['pid'];
			}
			
		}
		
		//echo $deep;
		
		$list = $this->navigation_service->getClassByParentId($id);
		$this->assign('list',$list);
		$this->assign('parentId',$parentId);
		$this->assign('deep',$deep + 1);
		$this->assign('id',$id);
		$this->assign('idReplacement','{ID}');
		
		$this->display();
	}
	
	
	public function delete(){
		
		$delId = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			
			if(is_array($delId)){
				$delId = $delId[0];
			}
			
			$this->navigation_service->deleteClass($delId);
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	public function add(){
		
		$feedback = '';
		$treelist = $this->navigation_service->getClassTreeHTML();
		
		$this->load->library(array('Cms_service','Article_service','Goods_service'));
		
		$ac_parent_id = $this->input->get_post('pid');
		
		
		if($ac_parent_id){
			$info['pid'] = $ac_parent_id;
		}
		
		if($this->isPostRequest()){
			$this->_getRules('add');
			
			for($i = 0; $i < 1; $i++){
				
				$info = array(
					'name_cn' => $this->input->post('name_cn'),
					'name_en' => $this->input->post('name_en'),
					'url_cn' => $this->input->post('url_cn'),
					'url_en' => $this->input->post('url_en'),
					'nav_type' => $this->input->post('nav_type'),
					'jump_type' => $this->input->post('jump_type'),
					'pid' => $this->input->post('pid') ? $this->input->post('pid') : 0,
					'displayorder' => $this->input->post('displayorder') ? $this->input->post('displayorder') : 255
				);
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				
				if(($newid = $this->Navigation_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Navigation_Model->getFirstByKey($newid,'id');
			}
		}
		
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
			'goodsClass' => $this->goods_service->getGoodsClassTreeHTML(),
			'articleClass' => $this->article_service->getArticleClassTreeHTML(),
			'cmsArticleClass' => $this->cms_service->getArticleClassTreeHTML(),
			'list' => $treelist
		));

		$this->display();
	}
	
	
	public function checkpid($pid, $extra = ''){
		//不能是自己，也不能是其下级分类
		$currentGcId = $this->input->post('id');
		
		$deep = $this->navigation_service->getClassDeepById($pid);
		
		if($deep >=2){
			$this->form_validation->set_message('checkpid','父级只能是一级导航');
			return false;
		}
		
		if($extra == 'add'){
			//如果是增加的不需要再网后面继续执行了
			return true;
		}
		
		
		$list = $this->Navigation_Model->getList(array(
			'where' => array('pid' => $currentGcId)
		));
		
		$subIds = array($currentGcId);
		$hasData = true;
		
		while($list && $hasData){
			
			$ids = array();
			foreach($list as $item){
				$subIds[] = $item['id'];
				$ids[] = $item['id'];
			}
			
			if($ids){
				$hasData = false;
			}else{
				$list = $this->Navigation_Model->getList(array(
					'where_in' => array(
						array('key' => 'pid', 'value' => $ids)
					)
				));
			}
		}
		
		//print_r($subIds);
		if(in_array($pid,$subIds)){
			$this->form_validation->set_message('checkpid','父级不能选择自己和自己的下级导航');
			return false;
		}else{
			
			return true;
		}
		
	}
	
	
	private function _getRules($action = 'add'){
		
		$this->form_validation->set_rules('nav_type','导航类型',"required|in_list[0,1,2,3,4,5]");
		
		$this->form_validation->set_rules('name_cn','导航中文名称',"required|min_length[1]|max_length[50]");
		$this->form_validation->set_rules('name_en','导航英文名称',"required|min_length[1]|max_length[50]");
		
		
		if($this->input->post('pid')){
			$this->form_validation->set_rules('pid','上级分类', "in_db_list[{$this->Navigation_Model->_tableRealName}.id]|callback_checkpid[{$action}]");
		}
		
		$this->form_validation->set_rules('url_cn','导航中文链接',"required|valid_url");
		$this->form_validation->set_rules('url_en','导航英文链接',"required|valid_url");
		
		$this->form_validation->set_rules('jump_type','跳转方式',"required|in_list[0,1]");
		
		if($this->input->post('displayorder')){
			$this->form_validation->set_rules('displayorder','排序',"is_natural|less_than[256]");
		}
		
	}
	
	
	public function edit(){
		
		$feedback = '';
		$treelist = $this->navigation_service->getClassTreeHTML();
		$id = $this->input->get_post('id');
		
		$this->load->library(array('Cms_service','Article_service','Goods_service'));
		
		$info = $this->Navigation_Model->getFirstByKey($id,'id');
		
		if($this->isPostRequest()){
			$this->_getRules('edit');
			for($i = 0; $i < 1; $i++){
				
				$info = array(
					'id' => $id,
					'name_cn' => $this->input->post('name_cn'),
					'name_en' => $this->input->post('name_en'),
					'url_cn' => $this->input->post('url_cn'),
					'url_en' => $this->input->post('url_en'),
					'jump_type' => $this->input->post('jump_type'),
					'pid' => $this->input->post('pid') ? $this->input->post('pid') : 0,
					'displayorder' => $this->input->post('displayorder') ? $this->input->post('displayorder') : 255
				);
				
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$info = array_merge($info,$this->addWhoHasOperated('edit'));
				
				if($this->Navigation_Model->update($info,array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
			'goodsClass' => $this->goods_service->getGoodsClassTreeHTML(),
			'articleClass' => $this->article_service->getArticleClassTreeHTML(),
			'cmsArticleClass' => $this->cms_service->getArticleClassTreeHTML(),
			'list' => $treelist
		));
		
		
		$this->display('navigation/add');
	}
	
}
