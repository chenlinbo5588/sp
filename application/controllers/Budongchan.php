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
			
		);
		
		return $searchCondition;
	}
	
	public function index()
	{
		$condition = $this->_prepareCondition();
		$condition['order'] = 'bdc_id DESC';
		
		
		$where = array();
		
		$where['name'] = $this->input->get_post('name');
		
		//$where['idNo'] = $this->input->get_post('id_no');
		
		$condition['where'] = array(
			'dept_id' => $this->_profile['basic']['dept_id']
		);
		
		if($where['name']){
			$condition['like']['name'] = $where['name'];
		}
		/*
		if($where['idNo']){
			$condition['like']['id_no'] = $where['idNo'];
		}
		*/
		
		$results = $this->Dept_Bdc_Model->getList($condition,'bdc_id');
		//$results = $this->Bdc_Model->getList($condition);
		
		$bdcIds = array_keys($results['data']);
		//print_r($bdcIds);
		$currentPageBdcList = array();
		if($bdcIds){
			$currentPageBdcList = $this->Bdc_Model->getList(array(
				'where_in' => array(
					array('key' => 'id','value' => $bdcIds)
				)
			),'id');
		}
		
		
		//print_r($currentPageBdcList);
		$this->assign(array(
			'list' => $results['data'],
			'page' => $results['pager'],
			'currentBdcList' => $currentPageBdcList
		));
		
		
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
				'uploadEnable' => true,
				'info' => $info,
				'showSaveButton' => true,
				'feedback' => $feedback,
				'deptList' => $this->Dept_Model->getList(array(),'id'),
				'fileList' => array($this->_profile['basic']['dept_id'] => $fileList),
				'stepHTML' => step_helper2($bdcWorkflowConfig)
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
						'alpha_dash',
						'min_length[1]',
						'max_length[20]'
					)
				);
		
		$remark = $this->input->get_post('bz');
		
		if($remark){
			$this->form_validation->set_rules('bz','备注信息','required');
		}
	}
	
	
	/**
	 * 下载资料
	 */
	public function getfile(){
		
		$id = $this->input->get_post('id');
		$fileId = $this->input->get_post('fid');
		
		$isVisible = $this->budongchan_service->getBdcVisibleByDept($id,$this->_profile['basic']['dept_id']);
		
		if(!$isVisible){
			$this->display('common/not_found');
		}else{
			
			$file = $this->Bdc_File_Model->getFirstByKey($id,'bdc_id','fileinfo');
			$fileList = json_decode($file['fileinfo'],true);
			
			$fileAssocList = array();
			
			foreach($fileList as $deptId => $deptFiles){
				foreach($deptFiles as $fid => $fileItem){
					$fileAssocList[$fid] = $fileItem;
				}
			}
			
			
			
			
			for($i = 0; $i < 1; $i++){
				if(!in_array($fileId, array_keys($fileAssocList))){
					$this->display('common/not_found');
					break;
				}
				
				$this->load->helper('download');
				$fileRealPath = dirname(ROOTPATH) . '/filestore/'.$fileAssocList[$fileId]['file_url'];
				
				if(!file_exists($fileRealPath)){
					$this->display('common/not_found');
					break;
				}
				
				//file_put_contents('filelist.txt',print_r($fileAssocList[$fileId],true));
				
				force_download($fileAssocList[$fileId]['orig_name'],file_get_contents($fileRealPath));
			}
		}
		
		
	}
	
	
	/**
	 * 修改登记信息
	 */
	public function edit(){
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$fileList = array();
		$orgList = array();
		
		$isVisible = $this->budongchan_service->getBdcVisibleByDept($id,$this->_profile['basic']['dept_id']);
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				if(!$isVisible){
					$this->jsonOutput('权限不足');
					break;
				}
				
				$opName = $this->input->get_post('op');
				
				$bdcWorkflowConfig = $this->budongchan_service->getBdcWorkFlow();
				$info = $this->Bdc_Model->getFirstByKey($id);
				
				$isOk = false;
				$redirectInfo = array();
				
				
				if('保存' == $opName){
					if($info['status'] >= $bdcWorkflowConfig['初审']['statusValue']){
						$this->jsonOutput('已在初复审的不动产资料不能再次编辑保存');
						break;	
					}
					
					$this->_addRules();
					
					if(!$this->form_validation->run()){
						//$info = $_POST;
						//$feedback = getErrorTip('无法通过数据校验');
						$this->jsonOutput('数据校验失败,请填入必要内容',array('errors' => $this->form_validation->error_array()));
						break;
					}
					
					$affectRow = $this->budongchan_service->editBdc(array_merge($info,$_POST),$this->_profile['basic']);
					if($affectRow >= 0){
						$isOk = true;
					}
					
				}else if('撤销提交' == $opName){
					//撤销
					
					$lastStatusInfo = array();
					$isOk = $this->budongchan_service->revert($info,$this->_profile['basic'],$lastStatusInfo);
					
					$redirectInfo = array('redirectUrl' => site_url($this->uri->uri_string.'?id='.$id));
					switch($lastStatusInfo['status_name']){
						case '初审':
							$redirectInfo = array('redirectUrl' => site_url('budongchan/cs?id='.$id));
							break;
						case '复审':
							$redirectInfo = array('redirectUrl' => site_url('budongchan/fs?id='.$id));
							break;
						default:
							break;
					}
					
				}else if('受理' == $opName){
					
					$isOk = $this->budongchan_service->shouli($id,$info['status'],$this->_profile['basic']);
					if(!$isOk){
						$this->jsonOutput('受理失败，可能已被受理');
						break;
					}
					
					$redirectInfo = array('redirectUrl' => site_url($this->uri->uri_string.'?id='.$id));
					
					switch($info['status_name']){
						case '初审':
							$redirectInfo = array('redirectUrl' => site_url('budongchan/cs?id='.$id));
							break;
						case '复审':
							$redirectInfo = array('redirectUrl' => site_url('budongchan/fs?id='.$id));
							break;
						default:
							break;
					}
					
				}else if('发送' == $opName){
					
					$this->form_validation->set_rules('id','不动产登记ID','required');
					$this->form_validation->set_rules('to_dept','发送单位','required');
					
					if(!$this->form_validation->run()){
						$this->jsonOutput('数据校验失败',array('errors' => $this->form_validation->error_array()));
						break;
					}
					
					$isOk = $this->budongchan_service->gonext($id,$_POST,$this->_profile['basic']);
					
					if(!$isOk){
						$this->jsonOutput('数据库错误,请联系管理员');
						break;
					}
					
					$redirectInfo = array('redirectUrl' => site_url($this->uri->uri_string.'?id='.$id));
				}
				
				if(false == $isOk){
					$this->jsonOutput('系统错误,请稍后尝试!');
					break;
				}
				
				//$info = $this->budongchan_service->getBdcInfoById($id);
				//$feedback = getSuccessTip('保存成功');
				//$this->assign('message','保存成功');
				$this->jsonOutput($opName.'成功',$redirectInfo);
			}
		}else{
			
			//$isVisible = false;
			if($isVisible){
				$info = $this->budongchan_service->getBdcInfoById($id);
				$this->_breadCrumbs[] = array(
					'title' => '编辑不动产业务',
					'url' => $this->uri->uri_string.'?id='.$info['id']
				);
			
			
				if(empty($info['fileList'][$this->_profile['basic']['dept_id']])){
					$info['fileList'][$this->_profile['basic']['dept_id']] = array();
				}
				
				$bdcWorkflowConfig = $this->budongchan_service->getBdcWorkFlow();
				
				$showRevertButton = false;
				$showShouliButton = false;
				$showSaveButton = false;
				$orgList = array();
				
				if($info['cur_dept_id'] == $this->_profile['basic']['dept_id'] && $info['cur_uid'] && $bdcWorkflowConfig[$info['status_name']]['nextStep']){
					$orgList = $this->budongchan_service->getNextOrgList($info,$this->_profile['basic']);
				}
				
				//撤销提交初审按钮
				$preStatus = $bdcWorkflowConfig[$info['status_name']]['preStep'];
				
				//如果上一个状态为当前部门处理 且未受理, 则可以作撤销
				if($preStatus && $info['statusLog'][$preStatus] && $info['statusLog'][$preStatus]['dept_id'] == $this->_profile['basic']['dept_id'] && $info['cur_uid'] == 0){
					$showRevertButton = true;
				}
				
				if($info['cur_dept_id'] == $this->_profile['basic']['dept_id']){
					$showSaveButton = true;
					if(0 == $info['cur_uid']){
						$showShouliButton = true;
					}
				}
				
				//print_r($info);
				
				//print_r($orgList);
				$this->assign(array(
					'info' => $info,
					'uploadEnable' => $showSaveButton,
					'showSaveButton' => $showSaveButton,
					'fileList' => $info['fileList'],
					'deptList' => $this->Dept_Model->getList(array(),'id'),
					'orgList' => $orgList,
					'showRevertButton' => $showRevertButton,
					'showShouliButton' => $showShouliButton,
					'stepHTML' => step_helper2($bdcWorkflowConfig,$info)
				));
				
				
				$this->display('budongchan/add');
			}else{
				
				$this->display('common/not_found');
			}
		}
	}
	
	
	/**
	 * 删除不动产信息
	 */
	public function delete(){
		
		
		
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
			'allowed_types' => 'jpg|jpeg|dwg|xls|xlsx|doc|docx|txt',
			'max_size' => 5120,
			'expire_time' => $expiredTs
		),FROM_FOREGROUND,'budongchan');
		
		//file_put_contents('debug.txt',print_r($_FILES,true));
		//file_put_contents('debug.txt',print_r($fileData,true),FILE_APPEND);
		
		if($fileData){
			$json['id'] = $fileData['id'];
			$json['msg'] = '上传成功';
			$json['error'] = 0;
			$json['orig_name'] = $fileData['orig_name'];
			$json['url'] = $fileData['file_url'];
			
			//还原
			$json['file_size'] = byte_format($fileData['file_size'] * 1024);
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
	 * 初审
	 */
	public function cs(){
		$this->_shOp('初审',$this->uri->uri_string);
	}
	
	
	/**
	 * 复审
	 */
	public function fs(){
		$this->_shOp('复审',$this->uri->uri_string);
	}
	
	
	/**
	 * 审核操作
	 */
	private function _shOp($shName = '初审',$shUrl = 'budongchan/cs'){
		$id = $this->input->get_post('id');
		$bdcWorkflowConfig = $this->budongchan_service->getBdcWorkFlow();
		
		
		if($this->isPostRequest()){
			
			//开始审核
			for($i = 0; $i < 1; $i++){
				$isVisible = $this->budongchan_service->getBdcVisibleByDept($id,$this->_profile['basic']['dept_id']);
				if(!$isVisible){
					$this->jsonOutput('权限不足');
					break;
				}
				
				$opName = $this->input->get_post('op');
				
				$isOk = false;
				$redirectInfo = array();
				$erroMsg = '';
				
				if('受理' == $opName){
					$isOk = $this->budongchan_service->shouli($id,$bdcWorkflowConfig[$shName]['statusValue'],$this->_profile['basic']);
					
					$redirectInfo = array('redirectUrl' => site_url($shUrl.'?id='.$id));
					
					if(!$isOk){
						$this->jsonOutput($opName.'失败，可能已被受理',$redirectInfo);
						break;
					}
					
				}else if('通过'.$shName == $opName){
					
					$this->form_validation->set_rules('id','不动产登记ID','required');
					
					if($bdcWorkflowConfig[$shName]['nextStep']){
						$this->form_validation->set_rules('to_dept','发送单位','required');
					}
					
					if($bdcWorkflowConfig[$shName]['moreRules']){
						foreach($bdcWorkflowConfig[$shName]['moreRules'] as $keyName => $ruleConfig){
							$this->form_validation->set_rules($keyName,$ruleConfig['title'],$ruleConfig['rules']);
						}
					}
					
					$this->form_validation->set_rules('remark',$shName.'备注','required');
					
					if(!$this->form_validation->run()){
						$this->jsonOutput('数据校验失败,请填入必要内容',array('errors' => $this->form_validation->error_array()));
						break;
					}
					
					$isOK = $this->budongchan_service->gonext($id,$_POST,$this->_profile['basic']);
					
					if(!$isOK){
						$this->jsonOutput($opName.'操作失败,可能发生系统错误');
						break;
					}
					
					if(!$bdcWorkflowConfig[$shName]['nextStep']){
						$this->budongchan_service->storeBdcNo($id,array('bdc_no' => $this->input->post('bdc_no'), 'is_done' => 1));
					}
					
					$this->budongchan_service->notifyAll($id,site_url('budongchan/edit?id='.$id),$this->_profile['basic']);
					$redirectInfo = array('redirectUrl' => site_url($shUrl.'?id='.$id));
				
				}else if('退回' == $opName){
					
					$this->form_validation->set_rules('id','不动产登记ID','required');
					$this->form_validation->set_rules('reason','退回原因','required');
					
					if(!$this->form_validation->run()){
						$this->jsonOutput('数据校验失败',array('errors' => $this->form_validation->error_array()));
						break;
					}
					
					$lastStatusInfo = array();
					
					$isOk = $this->budongchan_service->sendback($id,$_POST,$this->_profile['basic'],$lastStatusInfo);
					
					if(!$isOk){
						$this->jsonOutput($opName.'操作失败,可能发生系统错误');
						break;
					}
					
					//发送一个私信,给对方
					$this->budongchan_service->notifyLastUser($id,site_url('budongchan/edit?id='.$id), $this->_profile['basic'],$lastStatusInfo ,'<div>退回原因:<strong class="error">'.$this->input->post('reason').'</strong></div>');
					
					$redirectInfo = array('redirectUrl' => site_url($shUrl.'?id='.$id));
					
				}else if('撤销提交' == $opName){
					//撤销
					$info = $this->Bdc_Model->getFirstByKey($id);
					$isOk = $this->budongchan_service->revert($info,$this->_profile['basic']);
					
					$redirectInfo = array('redirectUrl' => site_url($shUrl.'?id='.$id));
					
					if(!$isOk){
						$this->jsonOutput($opName.'操作失败,页面将自动刷新,请刷新后再次尝试',$redirectInfo);
						break;
					}
				}
				
				
				$this->jsonOutput($opName.'成功',$redirectInfo);
			}
			
		}else{
			
			if($id){
				
				$this->_breadCrumbs[] = array(
					'title' => $shName,
					'url' => $shUrl
				);
				
				
				//某一个初审详情
				$isVisible = $this->budongchan_service->getBdcVisibleByDept($id,$this->_profile['basic']['dept_id']);
				
				//$isVisible = false;
				if($isVisible){
					$info = $this->budongchan_service->getBdcInfoById($id);
					
					$this->_breadCrumbs[] = array(
						'title' => $shName,
						'url' => $shUrl.'?id=' .$id
					);
				
					if(empty($info['fileList'][$this->_profile['basic']['dept_id']])){
						$info['fileList'][$this->_profile['basic']['dept_id']] = array();
					}
					
					$this->assign(array(
						'info' => $info,
						'shName' => $shName,
						'shUrl' => $shUrl,
						'fileList' => $info['fileList'],
						'deptList' => $this->Dept_Model->getList(array(),'id'),
						'stepHTML' => step_helper2($bdcWorkflowConfig,$info)
					));
					
					
					if($info['cur_uid']){
						$orgList = array();
						$showPassButton = true;
						$uploadEnable = false;
						
						if($info['cur_dept_id'] == $this->_profile['basic']['dept_id'] && $info['cur_uid'] && $bdcWorkflowConfig[$info['status_name']]['nextStep']){
							$orgList = $this->budongchan_service->getNextOrgList($info,$this->_profile['basic']);
						}
						
						if(!$info['statusLog'][$shName]['is_complete']){
							$uploadEnable = true;
						}
						
						if($info['statusLog'][$shName]['is_complete'] && !$bdcWorkflowConfig[$shName]['nextStep']){
							//表示没有下一步了，以及是最后一个步骤
							$showPassButton = false;
						}
						
						$this->assign(array(
							'uploadEnable' => $uploadEnable,
							'orgList' => $orgList,
							'showPassButton' => $showPassButton
						));
					}
					
					$this->display('budongchan/sh_id');
				}else{
					$this->display('common/not_found');
				}
				
			}else{
				
				$this->_breadCrumbs[] = array(
					'title' => '待'.$shName,
					'url' => $this->uri->uri_string
				);
			
				$bdcWorkflowConfig = $this->budongchan_service->getBdcWorkFlow();
				$condition = $this->_prepareCondition();
				
				$condition['where'] = array(
					'cur_dept_id' => $this->_profile['basic']['dept_id'],
					'status' => $bdcWorkflowConfig[$shName]['statusValue'],
				);
				
				$searchAssoc['name'] = $this->input->get_post('name');
				$searchAssoc['lsno'] = $this->input->get_post('lsno');
				
				if($searchAssoc['name']){
					$condition['like']['name'] = $searchAssoc['name'];
				}
				
				if($searchAssoc['lsno']){
					$condition['like']['lsno'] = $searchAssoc['lsno'];
				}
				
				//print_r($condition);
				
				$condition['order'] = 'id DESC';
				
				$list = $this->Bdc_Model->getList($condition);
				
				$this->assign(array(
					'list' => $list['data'],
					'page' => $list['pager'],
					'shUrl' => $shUrl,
				));
				
				$this->display('budongchan/sh');
			}
		}
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
				
				$bdcWorkflowConfig = $this->budongchan_service->getBdcWorkFlow();
				$info = $this->Bdc_Model->getFirstByKey($bdcId);
				
				if($info['status'] <= $bdcWorkflowConfig['测量']['statusValue']){
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
				}else{
					
					$checkAttachment = false;
					$message = "已提交初复审的资料附件不可删除";
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
