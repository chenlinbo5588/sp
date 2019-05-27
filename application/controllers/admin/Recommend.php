<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recommend extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Cms_service','Attachment_service','Basic_data_service'));
		
		$this->assign('imageConfig',config_item('weixin_img_size'));
		
		$this->_moduleTitle = '推荐管理';
		$this->_className = strtolower(get_class());
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '新增'),
		);
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'styleList' => $this->basic_data_service->getTopChildList('推荐类型'),
			'dataformatList' => $this->basic_data_service->getTopChildList('时间格式'),
			'basicData' => $this->basic_data_service->getBasicDataList(),
		));
		
		
	}
	
	public function index(){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$list = $this->Recommend_Model->getList($condition);
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage
			
		));
		$this->display();
		
	}
	
	
	public function add(){
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_setRule();		
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$insertData = array_merge($_POST,$this->addWhoHasOperated('add'));
				$newid =$this->Recommend_Model->_add($insertData);
				
				if($newid){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
			$this->display();
		}
	}
	
	public function edit(){
		$id = $this->input->get_post('id');
		$info = $this->Recommend_Model->getFirstByKey($id);
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_setRule();		
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$updateData = array_merge($_POST,$this->addWhoHasOperated('edit'));
				$resuit = $this->Recommend_Model->update($updateData,array('id' => $id));
				
				if($resuit){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
			$this->assign('info',$info);
			$this->display($this->_className.'/add');
		}
	}
	
	
	private function _setRule(){
		$this->form_validation->set_rules('name','推荐名称','required');
		$this->form_validation->set_rules('show_number','显示条数','required|numeric');
		$this->form_validation->set_rules('cachetime','缓存时间','required|numeric');
		$this->form_validation->set_rules('style','推荐类型','required');
		$this->form_validation->set_rules('dateformat','时间格式','required');
	}
	
	
	public function detail(){
		$id = $this->input->get_post('id') ? $this->input->get_post('id') : 0;
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '详情');
		$info = $this->Recommend_Detail_Model->getList(array('where' => array('recommend_id' => $id)));
		$recommendInfo = $this->Recommend_Model->getFirstByKey($id);
		$this->assign('info',$info);
		$this->assign('recommendInfo',$recommendInfo);
		$this->display();
	}
	
	
	public function add_detail(){
		$id = $this->input->get_post('id');
		$this->_subNavs[] = array('url' => $this->_className.'/add_detail?id='.$id, 'title' => '添加文章');
		$recommendInfo = $this->Recommend_Model->getFirstByKey($id);
		if($this->isPostRequest()){			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->addWhoHasOperated('edit'));
				$info['recommend_id'] = $id;
				$info['recommend_id'] = strptime();
				$info['recommend_id'] = strptime();
				$info['recommend_id'] = strptime();
				
				$this->Recommend_Detail_Model->_add($info);
				
				$error = $this->Cms_Article_Class_Model->getError();
				
				if(QUERY_OK != $error['code']){
					$feedback = getErrorTip('保存失败,'.$error['message']);
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
			
		}else{
			$this->display();
		}
		
	}
	
	
	
	public function gettitle(){
		$searchKey = $this->input->get_post('term');
		$return = array();
		
		if($searchKey){
			
			$cmsList = $this->Cms_Article_Model->getList(array(
				'where' => array(
					'id' => $searchKey
				),
				'limit' => 10
			));
			foreach($cmsList as $item ){
				$return[] = array(
					'id' => $item['id'],
					'label' => $item['id'].' '.$item['article_title'],
					'value' => $item['id'],
					'title'=> $item['article_title'],
					'jump_url' => $item['origin_address'],
					'img_url' => $item['image_url'],
					'synopsis' => $item['content'],
					'release_time' => date('Y-m-d h:i:s',$item['publish_time']),
				);
			}
		}
		
		$this->jsonOutput2('',$return,false);
	}
	
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		$recommendDetailInfo = $this->Recommend_Detail_Model->getFirstByKey($id);
		$recommendId = $recommendDetailInfo['recommend_id'];
		$recommendInfo = $this->Recommend_Model->getFirstByKey($recommendId);
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[display]');
			if($fieldName == 'display'){
				if($newValue > $recommendInfo['show_number']){
					$this->jsonOutput('位置不能超过最大显示条数');
					break;
				}
			}
			
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				
				if($this->Recommend_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
	}
}
