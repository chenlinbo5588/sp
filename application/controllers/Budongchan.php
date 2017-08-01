<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Budongchan extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Budongchan_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_profile['basic']);
		
		$this->assign(array(
			'id_type' => config_item('id_type'),
			'sex_type' => config_item('sex_type')
		));
		
		$this->_breadCrumbs[] = array(
			'title' => '不动产信息',
			'url' => strtolower(get_class())
		);
		
	}
	
	
	//@todo 自动加入所在村条件
	private function _prepareCondition(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$pageParam = array(
			'page_size' => config_item('page_size'),
			'page_size' => 2,
			'current_page' => $currentPage,
			'call_js' => 'search_page',
			'form_id' => '#formSearch'
		);
		
		$searchCondition = array(
			'pager' => $pageParam,
			'order' => 'id DESC',
		);
		
		return $searchCondition;
	}
	
	public function index()
	{
		$condition = $this->_prepareCondition();
		
		$where = array();
		
		$where['name'] = $this->input->get_post('name');
		$where['idNo'] = $this->input->get_post('id_no');
		
		$condition['where'] = array(
			'dept_id' => $this->_profile['basic']['dept_id']
		);
		
		if($where['name']){
			$condition['like']['name'] = $where['name'];
		}
		
		if($where['idNo']){
			$condition['like']['id_no'] = $where['idNo'];
		}
		
		
		$results = $this->Bdc_Model->getList($condition);
		
		$this->assign('list',$results['data']);
		$this->assign('page',$results['pager']);
		
		$this->display();
	}
	
	
	
	/**
	 * 添加不动产信息
	 */
	public function add(){
		$feedback = '';
		$fileList = array();
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_addRules();
				
				$fileIds = $this->input->post('file_id');
				if($fileIds){
					$fileList = $this->attachment_service->getFileInfoByIds($fileIds,$this->_profile['basic']['uid']);
				}
				
				if(!$this->form_validation->run()){
					//$info = $_POST;
					$this->jsonOutput('数据校验失败',array('errors' => $this->form_validation->error_array()));
					//$feedback = getErrorTip($this->form_validation->error_string());
					//$feedback = getErrorTip('数据校验失败');
					break;
				}
				
				$insertId = $this->budongchan_service->addBdc($_POST,$this->_profile['basic'],$this->_reqtime,$fileList);
				if(!$insertId){
					$feedback = getErrorTip('数据库错误，请联系管理员');
					break;
				}
				
				
				$info = $_POST;
				$info['id'] = $insertId;
				
				$this->jsonOutput('保存成功',array('redirectUrl' => site_url('budongchan/edit?id='.$insertId)));
				
				//$this->assign('message','保存成功');
				//$feedback = getSuccessTip('保存成功');
				
			}
		}else{
			$info = array(
				'sex' => 1,
				'id_type' => 1,
				'status_name' => '新增登记'
			);
			
			$this->_breadCrumbs[] = array(
				'title' => '不动产业务登记',
				'url' => $this->uri->uri_string
			);
			
			$bdcWorkflowConfig = $this->budongchan_service->getBdcWorkFlow();
			
			$this->assign(array(
				'info' => $info,
				'feedback' => $feedback,
				'deptList' => $this->Dept_Model->getList(array(),'id'),
				'fileList' => array($this->_profile['basic']['dept_id'] => $fileList),
				'stepHTML' => step_helper2($bdcWorkflowConfig['不动产业务'])
			));
			
			$this->display();
		}
	}
	
	
	private function _addRules(){
		
		$this->form_validation->set_rules('name','登记名称','required|max_length[200]');
		$this->form_validation->set_rules('address','地址','required|max_length[200]');
		$this->form_validation->set_rules('contactor','联系人','required|max_length[10]');
		$this->form_validation->set_rules('mobile','手机号码','required|max_length[20]');
		
		$this->form_validation->set_rules('id_type','证件类型','required|in_list['.join(',',array_keys(config_item('id_type'))).']');
		$this->form_validation->set_rules('id_no','证件号码',array(
						'required',
						'min_length[1]',
						'max_length[20]'
					)
				);
		
		$remark = $this->input->get_post('remark');
		
		if($remark){
			$this->form_validation->set_rules('remark','备注信息','required|max_length[20]');
		}
	}
	
	
	/**
	 * 下载资料
	 */
	public function getfile(){
		
		$id = $this->input->get_post('id');
		$fileId = $this->input->get_post('fid');
		
		$info = $this->budongchan_service->getBdcInfoById($id);
		
	}
	
	
	/**
	 * 修改登记信息
	 */
	public function edit(){
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$fileList = array();
		$orgList = array();
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_addRules();
				
				if(!$this->form_validation->run()){
					//$info = $_POST;
					//$feedback = getErrorTip('无法通过数据校验');
					$this->jsonOutput('数据校验失败',array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$info = $this->Bdc_Model->getFirstByKey($id);
				$affectRow = $this->budongchan_service->editBdc(array_merge($info,$_POST),$this->_profile['basic']);
				
				if(false === $affectRow){
					//$feedback = getErrorTip('数据库错误，请联系管理员');
					$this->jsonOutput('数据库错误',$this->db->get_error_info());
					break;
				}
				
				$info = $this->budongchan_service->getBdcInfoById($id);
				//$feedback = getSuccessTip('保存成功');
				//$this->assign('message','保存成功');
				$this->jsonOutput('保存成功');
			}
		}else{
			
			$this->_breadCrumbs[] = array(
				'title' => '编辑登记信息',
				'url' => $this->uri->uri_string
			);
			
			$info = $this->budongchan_service->getBdcInfoById($id);
			
			//print_r($orgList);
			$this->assign(array(
				'info' => $info,
				'fileList' => $info['fileList'],
				'feedback' => $feedback,
				'deptList' => $this->Dept_Model->getList(array(),'id')
			));
			
			$bdcWorkflowConfig = $this->budongchan_service->getBdcWorkFlow();
			
			$this->assign('stepHTML',step_helper2($bdcWorkflowConfig['不动产业务'],$info));
			
			if(($info['cur_dept_id'] == $this->_profile['basic']['dept_id']) && $info['status'] < $bdcWorkflowConfig['不动产业务']['初审']['statusValue']){
				$this->assign('showNextUrl',true);
			}
			
			
			$this->display('budongchan/add');
		}
	}
	
	
	/**
	 * 下一步
	 */
	public function nextstep(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$orgList = array();
	
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_rules('id','不动产登记ID','required');
				$this->form_validation->set_rules('to_dept','发送单位','required');
				
				if(!$this->form_validation->run()){
					//$info = $_POST;
					//$feedback = getErrorTip('无法通过数据校验');
					$this->jsonOutput('数据校验失败',array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$info = $this->Bdc_Model->getFirstByKey($id);
				$isOK = $this->budongchan_service->gonext(array_merge($info,$_POST),$this->_profile['basic']);
				
				if(!$isOK){
					$this->jsonOutput('数据库错误，请联系管理员',$this->db->get_error_info());
					break;
				}
				
				//$info = $this->budongchan_service->getBdcInfoById($id);
				//$feedback = getSuccessTip('保存成功');
				//$this->assign('message','保存成功');
				
				$this->jsonOutput('发送成功',array('redirectUrl' => site_url('budongchan/nextstep?id='.$info['id'])));
			}
		}else{
			
			$info = $this->budongchan_service->getBdcInfoById($id);
			$bdcWorkflowConfig = $this->budongchan_service->getBdcWorkFlow();
			
			$showSender = false;
			if(($info['cur_dept_id'] == $this->_profile['basic']['dept_id']) && $info['status'] < $bdcWorkflowConfig['不动产业务']['初审']['statusValue']){
				$showSender = true;
			}
			
			if($showSender){
				$orgList = $this->budongchan_service->getNextOrgList($info,$this->_profile['basic']);
			}
			
			$this->_breadCrumbs[] = array(
				'title' => '不动产业务登记',
				'url' => $this->uri->uri_string
			);
			
			$this->assign(array(
				'info' => $info,
				'fileList' => $info['fileList'],
				'feedback' => $feedback,
				'orgList' => $orgList,
				'deptList' => $this->Dept_Model->getList(array(),'id'),
				'stepHTML' => step_helper2($bdcWorkflowConfig['不动产业务'],$info)
			));
			
			
			$this->assign('showSender',$showSender);
			$this->display();
		}
	}
	
	/**
	 * 删除人员
	 */
	public function delete(){
		/*
		$personList = $this->input->post('id');
		
		if($personList && $this->isPostRequest()){
			$this->building_service->setArcgisUrl(config_item('arcgis_server'),$this->_mapConfig['编辑要素']['标准建筑点']);
			
			$personId = $personList[0];
			$returnVal = $this->building_service->deletePersonById($personId);
			
			if($returnVal > 0){
				$this->jsonOutput('删除成功');
			}else{
				$this->jsonOutput('删除出错');
			}
			
		}else{
			$this->jsonOutput('参数错误');
		}
		
		*/
		
	}
	
	public function addfile(){
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		
		$expiredTs = $this->_reqtime + CACHE_ONE_DAY;
		//$lsno = $this->input->get_post('lsno');
		$bdcId = $this->input->get_post('bdc_id');
		
		if($bdcId){
			//如果编辑页面,则上传的文件默认不过期
			$expiredTs = 0;
		}
		
		$fileData = $this->attachment_service->addFile('Filedata',array(
			'allowed_types' => 'jpg|jpeg',
			'expire_time' => $expiredTs
		),FROM_FOREGROUND,'budongchan');
		
		//file_put_contents('debug.txt',print_r($fileData,true));
		
		if($fileData){
			$json['id'] = $fileData['id'];
			$json['msg'] = '上传成功';
			$json['error'] = 0;
			$json['orig_name'] = $fileData['orig_name'];
			$json['url'] = $fileData['file_url'];
			$json['size'] = byte_format($fileData['file_size'] * 1024);
			$json['username'] = $this->_profile['basic']['username'];
			$json['gmt_create'] = date("Y-m-d H:i:s",$this->_reqtime);
			
			if($bdcId){
				$fileData['gmt_create'] = $this->_reqtime;
				$affectRow = $this->budongchan_service->addBdcFile($bdcId,$fileData,$this->_profile['basic']);
			}
			
			$this->jsonOutput('上传成功',$json);
		}else{
			//$json['msg'] = $this->attachment_service->getErrorMsg('','');
			$this->jsonOutput($this->attachment_service->getErrorMsg('',''),$json);
		}
		
		//exit(json_encode($json));
	}
	
	
	/**
	 * 删除文件
	 */
	public function deleteFile(){
		$json = array('formhash'=>$this->security->get_csrf_hash());
		
		$bdcId = $this->input->get_post('bdc_id');
		//file ids
		$id = $this->input->get_post('id');
		
		$message = '删除失败';
		if($this->isPostRequest() && $id){
			$fileId = $id[0];
			
			$checkAttachment = true;
			
			if($bdcId){
				$flag = $this->budongchan_service->deleteBdcFileById($bdcId,$fileId,$this->_profile['basic']);
				
				switch($flag){
					case 0:
						$message = "删除成功";
						$checkAttachment = false;
						break;
					case 1:
						$message = "权限不足,删除失败";
						$checkAttachment = false;
						break;
					case -1:
						break;
					default:
						break;
				}
			}
			
			if($checkAttachment){
				$fileInfo = $this->Attachment_Model->getById(array(
					'where' => array(
						'uid' => $this->_profile['basic']['uid'],
						'id' => $fileId
					)
				));
				
				if($fileInfo){
					@unlink(dirname(ROOTPATH).DIRECTORY_SEPARATOR.'filestore'.DIRECTORY_SEPARATOR.$fileInfo['file_url']);
				}
				
				$message = '删除成功';
			}
		}
		
		$this->jsonOutput($message,$json);
	}
	
}
