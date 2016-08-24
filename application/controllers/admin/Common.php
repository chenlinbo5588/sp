<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();

	}
	
	
	public function upload_excel(){
		$uploadname = '_pic';
        if(0 === $_FILES['imgFile']['error']){
            $uploadname = 'imgFile';
		}
		
		$mod = $this->input->get_post('mod');
		if(empty($mod)){
			$mod = '';
		}
		
		$this->load->library('Attachment_service');
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
		
		$options = array(
			'allowed_types' => 'xls|xlsx'
		);
		
		$fileData = $this->attachment_service->addAttachment($uploadname,$options,FROM_BACKGROUND,$mod);
		
		if($fileData){
			$json = array('error' => 0, config_item('csrf_token_name') => $this->security->get_csrf_hash(),'id' => $fileData['id'], 'url'=>$fileData['file_url'],'title' => htmlspecialchars($fileData['orig_name']));
		}else{
			$json = array('error' => 1, config_item('csrf_token_name') => $this->security->get_csrf_hash(),'msg'=>$this->getErrorMsg('',''));
		}
		
		@unlink($_FILES[$uploadname]['tmp_name']);
		
		exit(json_encode($json));
	}
	
	
	/**
	 * 
	 */
	public function pic_upload(){
		
		$uploadname = '_pic';
        if(0 === $_FILES['imgFile']['error']){
            $uploadname = 'imgFile';
		}
		
		$mod = $this->input->get_post('mod');
		if(empty($mod)){
			$mod = '';
		}
		
		$this->load->library('Attachment_service');
		$this->attachment_service->setUserInfo($this->_adminProfile['basic']);
		
		$options = array();
		
		if($this->input->get_post('min_width')){
			$options['min_width'] = intval($this->input->get_post('min_width'));
		}
		
		if($this->input->get_post('min_height')){
			$options['min_height'] = intval($this->input->get_post('min_height'));
		}
		
		
		$json = $this->attachment_service->pic_upload($uploadname,$options,FROM_BACKGROUND,$mod);
		
		@unlink($_FILES[$uploadname]['tmp_name']);
		
		
		exit(json_encode($json));
	}
	
}
