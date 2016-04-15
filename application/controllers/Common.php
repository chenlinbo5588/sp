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
		
		$uploadname = '_pic';
        if(0 === $_FILES['imgFile']['error']){
            $uploadname = 'imgFile';
		}
		
		$this->load->library('Attachment_Service');
		
		
		$json = $this->attachment_service->pic_upload($this->_profile['basic']['uid'],$uploadname);
		
		exit(json_encode($json));
		
	}
}
