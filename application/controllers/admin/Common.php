<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();

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
