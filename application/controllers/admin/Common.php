<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();

	}
	
	
	/**
	 * 图片上传
	 *
	 */
	public function pic_upload(){
		$this->load->library('Attachment_Service');
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
		$fileData = $this->attachment_service->addImageAttachment('_pic');
		if($fileData){
			exit(json_encode(array('status'=>1,'url'=>base_url($fileData['img_big']))));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>$this->attachment_service->getErrorMsg('',''))));
		}
	}
	
	
	/**
	 * 图片裁剪
	 *
	 */
	public function pic_cut(){
		
		$this->load->helper(array('img'));
		
		if($this->isPostRequest()){
			
		}else{
			$save_file = str_ireplace(base_url(),'',$this->input->get('url'));
			
			list($width, $height, $type, $attr) =  getimagesize(ROOTPATH.'/'.$save_file);
			
			$this->assign('image_width',$width);
			$this->assign('image_height',$height);
			$this->display('common/pic_cut');
		}
		
	}
	
}
