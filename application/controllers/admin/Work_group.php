<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Work_group extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Basic_data_service','User_service','Yewu_service'));
		
		$this->basic_data_service->setDataModule($this->_dataModule);
		
		$this->_moduleTitle = '工作组';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'serviceArea' => $this->basic_data_service->getTopChildList('服务区域'),
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
		);
		
	}
	
	/**
	 * 
	 */
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
/*		$search['yewu_name'] = $this->input->get_post('yewu_name');
		
		if($search['yewu_name']){
			$condition['like']['yewu_name'] = $search['yewu_name'];
		}*/

		
		$list = $this->Work_Group_Model->getList($condition);
		$this->assign(array(
			'basicData' => $this->basic_data_service->getBasicData(),
			'list' => $list,
			'page' => $list['pager'],
			//'search' => $search,
			'currentPage' => $currentPage
			
		));
		
		$this->display();
		
	}
	public function edit(){
		$id = $this->input->get_post('id');
		$info = $this->Work_Group_Model->getFirstByKey($id);
		$memberList = $this->Worker_Member_Model->getList(array('where' => array('group_id' => $id)));
		$info['service_area'] = json_decode($info['service_area'],true);
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){		
/*				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}*/
				
				$updateData = array_merge($_POST,$this->addWhoHasOperated('add'));
				$updateData['service_area'] = json_encode($updateData['service_area']);
				$groupLeaderInfo = $this->User_Model->getFirstByKey($updateData['group_leader_mobile'],'mobile');
				$updateData['group_leader_name'] = $groupLeaderInfo['name'];
				$updateData['group_leaderid'] = $groupLeaderInfo['id'];
				 
				$returnVal = $this->Work_Group_Model->update($updateData,array('id' => $id));
				$groupMemberList = $this->getGroupMemberList($updateData,$id);
				$this->Worker_Member_Model->deleteByCondition(array('where' => array('group_id' => $id)));
				$error = $this->Worker_Member_Model->batchInsert($groupMemberList);
				
				if($returnVal < 0){
					$this->jsonOutput('保存失败',$this->getFormHash());
				}
				else{
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
			$this->assign('memberList',$memberList);
			$this->assign('info',$info);
			$this->assign('edit','edit');
			$this->display($this->_className.'/add');
			
		}
	}
	
	
	public function add(){
		$inPost = false;
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$inPost = true;
				$insertData = array_merge($_POST,$this->addWhoHasOperated('add'));
				$insertData['service_area'] = json_encode($insertData['service_area']);
				$groupLeaderInfo = $this->User_Model->getFirstByKey($insertData['group_leader_mobile'],'mobile');
				$insertData['group_leader_name'] = $groupLeaderInfo['name'];
				$insertData['group_leaderid'] = $groupLeaderInfo['id'];
				 
				$newid =$this->Work_Group_Model->_add($insertData);
				$groupMemberList = $this->getGroupMemberList($insertData,$newid);
				$error = $this->Worker_Member_Model->batchInsert($groupMemberList);
				if($error){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
				
			}
		}else{		
			$this->assign(array(
				'inPost' => $inPost
			));
			$this->display();
		}
		

	}
	
	private function _setRule(){
		//缺少公司用户组的验证
		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		$this->form_validation->set_rules('name','姓名','required');
		$this->form_validation->set_rules('user_type','用户类型','required');
	}
	
	public function delete(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$deleteRows = $this->Work_Group_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',array('jsReload' => true));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	private function getGroupMemberList($data,$groupId){
		foreach($data['worker_mobile'] as $key => $item){
			$userInfo = $this->User_Model->getFirstByKey($item,'mobile');
			$groupMemberInfo = array(
				'group_id' => $groupId,
				'worker_id' => $userInfo['id'],
				'worker_name' => $userInfo['name'],
				'worker_mobile' => $userInfo['mobile'],
			);
			$groupMemberList[] = array_merge($groupMemberInfo,$this->addWhoHasOperated('add'));
		}
		return $groupMemberList;
		
	}
	
	
}
