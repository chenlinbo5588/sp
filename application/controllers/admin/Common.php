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
		
		$this->load->library('Attachment_Service');
		
		$json = $this->attachment_service->pic_upload($this->_adminProfile['basic']['uid'],$uploadname,1);
		
		@unlink($_FILES[$uploadname]['tmp_name']);
		
		
		exit(json_encode($json));
	}
	
}
