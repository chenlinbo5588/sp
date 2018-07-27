<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Repair extends Ydzj_Admin_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('Wuye_service','Basic_data_service','Attachment_service'));
		
		$this->_moduleTitle = '维修';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'repairType'=>RepairType::$statusName
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加')
		);
		
		$this->form_validation->set_error_delimiters('<div>','</div>');
	}
	
	public function  _searchCondition($moreSearchVal = array()){
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
		
		
		$search['name'] = $this->input->get_post('name');
		$search['mobile'] = $this->input->get_post('mobile');
		$search['repair_type'] = $this->input->get_post('repair_type');
		
		$search = array_merge($search,$moreSearchVal);
		
		if($search['name']){
			$condition['like']['yezhu_name'] = $search['name'];
		}
		
		if($search['mobile']){
			$condition['like']['mobile'] = $search['mobile'];
		}
		if($search['repair_type']){
			$condition['where']['repair_type'] = $search['repair_type'];
		}
		
		$list = $this->Repair_Model->getList($condition);
		
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		$this->assign('search',$search);

	}
	
	
	
	public function index(){
		$this->_searchCondition();
		$this->display($this->_className.'/index');
		
	}
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('address','地址','required|in_db_list['.$this->House_Model->getTableRealName().'.address]');
			$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
			$this->form_validation->set_rules('remark','备注','required');	
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData();
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				if(!empty($_POST['address']))
				{
					$houseInfo = $this->House_Model->getById(array(
							'where' => array(
							'address' => $_POST['address']
						)
					));
					$_POST['resident_id'] = $houseInfo['resident_id'];
				}
				
				if(!empty($_POST['yezhu_name']))
				{
					$yezhuInfo = $this->Yezhu_Model->getById(array(
						'where' => array(
							'name' => $_POST['yezhu_name']
						)
					));
					
					if(!empty($yezhuInfo))
					{
						$_POST['yezhu_id'] = $yezhuInfo['id'];
					}
				}
				
				$newid =$this->Repair_Model->_add(array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add')));
				$error = $this->Repair_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput('已被占用');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			
			$this->display();
		}

	}	
	
	private function _prepareData(){
		$data['displayorder'] = $this->input->post('displayorder');
		return array(
			'displayorder' => $data['displayorder'] ? $data['displayorder'] : 255
		);
	}
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Repair_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');

		$info = $this->Repair_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('address','地址','required|in_db_list['.$this->House_Model->getTableRealName().'.address]');
			$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
			$this->form_validation->set_rules('remark','备注','required');

			
			for($i = 0; $i < 1; $i++){
				$info = array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->Repair_Model->update(array_merge($info,$_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				$error = $this->Repair_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput('记录已存在');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			
			$this->assign('info',$info);
			$this->display($this->_className.'/add');
			
		}
		
	}
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		$this->form_validation->set_error_delimiters('','');
		
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[mobile,address,remark]');
			
			
			
			switch($fieldName){
				case 'mobile':
					$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
					break;
				case 'remark':
					$this->form_validation->set_rules('remark','备注','required');
					break;
				case 'address';
					$this->form_validation->set_rules('address','地址','required|in_db_list['.$this->House_Model->getTableRealName().'.address]');
				default:
					break;
			}
			
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				if($this->Yezhu_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
	}
	
	
	
	
		
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上次失败');
		
		$fileData = $this->attachment_service->addImageAttachment('fileupload',array(),FROM_BACKGROUND,'repair');

		if($fileData){
			$fileData = $this->attachment_service->resize($fileData);
			$info = array(
				'id' => $fileData['id'],
				'photos' => $fileData['file_url'],
			);					
			
		}else{
		
			//maybe run here
			
			$json['error'] = 0;
			$json['id'] = $fileData['id'];
			$json['url'] = base_url($fileData['file_url']);
			
			//尽量选择小图
			if($fileData['img_b']){
				$json['url'] = base_url($fileData['img_b']);
			}
		}
		
		exit(json_encode($json));
		
	}
	
	
	


}




?>