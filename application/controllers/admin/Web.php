<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends Ydzj_Admin_Controller {
	
	private $_screenpicId = 102;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Block_service','Attachment_service'));
	}
	
	
	public function screenpic(){
		
		$feedback = '';
		$currentData = $this->block_service->getDataByCodeId($this->_screenpicId);
		
		
		if($this->isPostRequest()){
			
			
		}
		
		$this->assign($currentData['var_name'],$currentData['json_array']);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	private function _getRules(){
		$this->form_validation->set_rules('title','文字标题','required');
		$this->form_validation->set_rules('url','图片跳转链接','required|valid_starthttp');
		
		$this->form_validation->set_rules('color','图片背景颜色','required');
		
		
		if($this->input->post('displayorder')){
			$this->form_validation->set_rules('displayorder','排序',"is_natural|less_than[256]");
		}
	}
	
	
	
	public function screenpic_delete(){
		
		$ids = $this->input->get_post('id');
		
		if(is_array($ids)){
			$id = $ids[0];
		}else{
			$id = $ids;	
		}
		
		$realid = intval($id) - 1;
		$currentData = $this->block_service->getDataByCodeId($this->_screenpicId);
		
		if($this->isPostRequest() && !empty($id)){
			
			unset($currentData['json_array'][$realid]);
			
			//小技巧 让数组下标重新排列
			$currentData['json_array'] = array_merge($currentData['json_array'],array());
			
			if($this->Block_Data_Model->update(array(
				'data' => json_encode($currentData['json_array'])
			),array('code_id' => $this->_screenpicId)) < 0){
				$feedback = getErrorTip('保存失败');
				break;
			}
			
			$feedback = getSuccessTip('保存成功');
			$currentData = $this->block_service->getDataByCodeId($this->_screenpicId);
			
			
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareData($action = 'add'){
		$fileInfo = $this->attachment_service->addImageAttachment('pic',array(),FROM_BACKGROUND);
		
		//print_r($fileInfo);
		$info = array(
			'title' => $this->input->post('title'),
			'url' => $this->input->post('url') ? $this->input->post('url') : '',
			'pic_url' => $this->input->post('pic_url') ? $this->input->post('pic_url') : '',
			'color' => $this->input->post('color') ? $this->input->post('color') : '',
			'displayorder' => $this->input->post('displayorder') ? $this->input->post('displayorder') : '255'
		);
		
		
		if($fileInfo){
			$info['pic_url'] = $fileInfo['file_url'];
		}
		
		return $info;
	}
	
	
	public function screenpic_add(){
		$feedback = '';
		
		//print_r($_FILES);
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData();
				
				if(empty($info['pic_url'])){
					$feedback = getErrorTip($this->attachment_service->getErrorMsg());
					break;
				}
				
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$currentData = $this->block_service->getDataByCodeId($this->_screenpicId);
				$currentData['json_array'][] = $info;
				
				
				
				if($this->Block_Data_Model->update(array(
					'data' => json_encode($currentData['json_array'])
				),array('code_id' => $this->_screenpicId)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$currentData = $this->block_service->getDataByCodeId($this->_screenpicId);
				
				
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	public function screenpic_edit(){
		
		$feedback = '';
		
		$id = $this->input->get_post('screen_id');
		$realid = intval($id) - 1;
		
		$currentData = $this->block_service->getDataByCodeId($this->_screenpicId);
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData('edit');
				
				if(empty($info['pic_url'])){
					$feedback = getErrorTip($this->attachment_service->getErrorMsg());
					break;
				}
					
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$currentData['json_array'][$realid] = $info;
				
				if($this->Block_Data_Model->update(array(
					'data' => json_encode($currentData['json_array'])
				),array('code_id' => $this->_screenpicId)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$currentData = $this->block_service->getDataByCodeId($this->_screenpicId);
			}
		}
		
		$info = $currentData['json_array'][$realid];
		$info['screen_id'] = $id;
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('web/screenpic_add');
	}
	
	
	
}
