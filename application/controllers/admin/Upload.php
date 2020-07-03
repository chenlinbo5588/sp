<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Attachment_service');
		
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
		
		$this->_moduleTitle = '上传设置';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/param','title' => '上传参数'),
			array('url' => $this->_className.'/default_image','title' => '默认图片'),
		);
		
		
	}
	
	
	public function imagetype_check($str){
		$sysList = explode(',', 'gif,jpg,jpeg,bmp,png,swf');
		$userList = explode(',',trim($str));
		//print_r($userList);
		$error = false;
		
		foreach($userList as $value){
			if(!in_array(trim($value), $sysList)){
				$error = true;
				break;
			}
		}
		
		if ($error)
        {
        	$this->form_validation->set_message('imagetype_check', ' {field} 请输入 gif,jpg,jpeg,bmp,png,swf以上这些合法的值');
        	return FALSE;
        }
        else
        {
        	return TRUE;
        }
        
	}
	
	
	public function param(){
		
		$feedback = '';
		
		$settingKey = array(
			'image_max_filesize',
			'forground_image_allow_ext',
			'background_image_allow_ext',
		);
		
		$currentSetting = $this->base_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name' , 'value' => $settingKey)
			)
		));
		
		//print_r(config_item('image_max_filesize'));
		if($this->isPostRequest()){
			$this->form_validation->set_rules('image_max_filesize','图片文件大小', 'required|is_natural_no_zero|less_than_equal_to['.config_item('image_max_filesize').']');
			$this->form_validation->set_rules('forground_image_allow_ext','前台图片扩展名','required|callback_imagetype_check');
			$this->form_validation->set_rules('background_image_allow_ext','后台图片扩展名','required|callback_imagetype_check');
			
			
			if($this->form_validation->run()){
				$data = array();
				foreach($settingKey as $oneKey){
					$data[] = array(
						'name' => $oneKey,
						'value' => $this->input->post($oneKey)
					);
				}
				
				if($this->base_service->updateSetting($data) >= 0){
					$feedback = getSuccessTip('保存成功');
					
					
					
					$currentSetting = $this->base_service->getSettingList(array(
						'where_in' => array(
							array('key' => 'name' , 'value' => $settingKey)
						)
					));
					
				}else{
					$feedback = getErrorTip('保存失败');
				}
			}else{
				
				$feedback = getErrorTip($this->form_validation->error_string());
				
			}
		}
		
		//print_r($currentSetting);
		
		$this->assign('currentUploadSize',ini_get('upload_max_filesize'));
		$this->assign('currentSetting',$currentSetting);
		$this->assign('feedback',$feedback);
		
		$this->display();
	}
	
	public function default_image(){
		
		
		$feedback = '';
		
		$settingKey = array(
			'default_goods_image',
			'default_store_logo',
			'default_user_portrait',
			'default_group_portrait'
		);
		
		$currentSetting = $this->base_service->getSettingList(array(
			'where_in' => array(
				array('key' => 'name' , 'value' => $settingKey)
			)
		));
		
		
		if($this->isPostRequest()){
			
			$data = array();
			foreach($settingKey as $oneKey){
				$fileData = $this->attachment_service->addImageAttachment($oneKey,array(),FROM_BACKGROUND);
				if($fileData){
					$data[] = array('name' => $oneKey, 'value' => $fileData['file_url']);
				}
			}
			
			if($data){
				if($this->base_service->updateSetting($data) >= 0){
					$feedback = getSuccessTip('保存成功');
					
					foreach($data as $fileInfo){
						$this->attachment_service->deleteByFileUrl($currentSetting[$fileInfo['name']]);
					}
					
					$currentSetting = $this->base_service->getSettingList(array(
						'where_in' => array(
							array('key' => 'name' , 'value' => $settingKey)
						)
					));
				}else{
					$feedback = getErrorTip('保存失败');
				}
				
			}else{
				$feedback = getSuccessTip('保存成功');
				
			}
			
		}
		
		//print_r($currentSetting);
		$this->assign('currentSetting',$currentSetting);
		$this->assign('feedback',$feedback);
		
		
		$this->display();
	}
	
}
