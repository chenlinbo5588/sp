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
		
		
		$results = $this->Bdcdj_Model->getList($condition);
		
		$this->assign('list',$results['data']);
		$this->assign('page',$results['pager']);
		
		$this->display();
	}
	
	
	
	
	
	public function add(){
		
		$feedback = '';
		$fileList = array();
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_addRules();
				
				$fileIds = $this->input->post('file_id');
				
				if($fileIds){
					$fileList = $this->attachment_service->getFileInfoByIds($fileIds);
				}
				
				
				if(!$this->form_validation->run()){
					$info = $_POST;
					//$feedback = getErrorTip($this->form_validation->error_string());
					$feedback = getErrorTip('数据校验失败');
					break;
				}
				
				$insertId = $this->budongchan_service->addBdc(array_merge($_POST,$this->addWhoHasOperated('add')),$this->_profile['basic'],$this->_reqtime);
				if(!$insertId){
					$feedback = getErrorTip('数据库错误，请联系管理员');
					break;
				}
				
				
				$info = $_POST;
				$info['id'] = $insertId;
				
				
				$this->assign('message','保存成功');
				//$feedback = getSuccessTip('保存成功');
			}
		}else{
			$info = array(
				'sex' => 1,
				'id_type' => 1
			);
		}
		
		
		$this->_breadCrumbs[] = array(
			'title' => '不动产业务登记',
			'url' => $this->uri->uri_string
		);
		
		$this->assign('fileList',$fileList);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
		
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
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_addRules();
				if(!$this->form_validation->run()){
					$info = $_POST;
					$feedback = getErrorTip('无法通过数据校验');
					break;
				}
				
				$affectRow = $this->Bdcdj_Model->update(array_merge($_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				
				if($affectRow < 0){
					$feedback = getErrorTip('数据库错误，请联系管理员');
					break;
				}
				
				$info = $_POST;
				$feedback = getSuccessTip('保存成功');
			}
		}else{
			$info = $this->Bdcdj_Model->getFirstByKey($id);
		}
		
		$this->_breadCrumbs[] = array(
			'title' => '编辑登记信息',
			'url' => $this->uri->uri_string
		);
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('budongchan/add');
		
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
	
	
	private function _importStep($step){
		$this->assign('stepHTML',step_helper(array(
			'选择要上传的文件',
			'批量处理结果',
		),$step));
	}
	
	
	
	public function addfile(){
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		$lsno = $this->input->get_post('lsno');
		
		$expiredTs = $this->_reqtime + CACHE_ONE_DAY;
		
		if($lsno){
			$hashObj = $this->attachment_service->getAttachmentHashObj();
			$tableId = $hashObj->lookup($lsno);
			$this->setAttachmentTableId($tableId);
			
			//如果已经有流水号的话，则不再自动过期
			$expiredTs = 0;
		}
		
		$fileData = $this->attachment_service->addFile('Filedata',array(
			'allowed_types' => 'jpg|jpeg',
			'expire_time' => $expiredTs
		),FROM_BACKGROUND,'budongchan');
		
		//file_put_contents('debug.txt',print_r($fileData,true));
		
		if($fileData){
			$json['id'] = $fileData['id'];
			//$json['msg'] = '上传成功';
			$json['error'] = 0;
			$json['orig_name'] = $fileData['orig_name'];
			$json['url'] = $fileData['file_url'];
			$json['size'] = byte_format($fileData['file_size']);
			
			$this->jsonOutput('上传成功',$json);
		}else{
			//$json['msg'] = $this->attachment_service->getErrorMsg('','');
			$this->jsonOutput($this->attachment_service->getErrorMsg('',''),$json);
		}
		
		//exit(json_encode($json));
	}
	
}
