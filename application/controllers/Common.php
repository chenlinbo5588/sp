<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 图片上传
	 *
	 */
	public function pic_upload(){
		
		$uploadname = 'Filedata';
		
		//print_r($_FILES);
        if(0 === $_FILES['imgFile']['error']){
            $uploadname = 'imgFile';
		}
		
		$mod = $this->input->get_post('mod');
		$this->load->library('Attachment_service');
		
		$this->attachment_service->setUserInfo($this->_profile['basic']);
		
		$options = array();
		
		if($this->input->get_post('min_width')){
			$options['min_width'] = intval($this->input->get_post('min_width'));
		}
		
		if($this->input->get_post('min_height')){
			$options['min_height'] = intval($this->input->get_post('min_height'));
		}
		
		$json = $this->attachment_service->pic_upload($uploadname, $options, 0,$mod ? $mod : '');
		
		exit(json_encode($json));
	}
	
}
