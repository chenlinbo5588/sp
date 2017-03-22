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
        if(0 === $_FILES['Filedata']['error']){
            $uploadname = 'Filedata';
		}
		
		$mod = $this->input->get_post('mod');
		if(empty($mod)){
			$mod = '';
		}
		
		$this->load->library('Attachment_service');
		
		$json = $this->attachment_service->pic_upload($this->_profile['basic']['uid'],$uploadname,0,$mod);
		$json['message'] = $json['msg'];
		
		@unlink($_FILES[$uploadname]['tmp_name']);
		
		exit(json_encode($json));
		
	}
	
	/**
	 * 验证账户重复性
	 */
	public function member_check(){
		
		$this->load->model('Member_Model');
		
		$flag = "false";
		
		$keyword = $this->input->get('keyword');
		$value = $this->input->get('value');
		
		switch($keyword){
			case 'mobile':
				$info = $this->Member_Model->getFirstByKey($value,$keyword);
				
				if(empty($info)){
					$flag = "true";
				}
				break;
			case 'nickname':
				$info = $this->Member_Model->getFirstByKey($value,$keyword);
				$id = $this->input->get('member_id');
				
				if($info){
					if($id == $info['uid']){
						//自己
						$flag = "true";
					}
				}else{
					$flag = "true";
				}
				
				break;
			default:
				break;
		}
		
		echo $flag;
	}
	
	/**
	 * role_check
	 */
	public function role_check(){
		
		$this->load->model('Role_Model');
		
		$flag = "false";
		
		$keyword = $this->input->get('keyword');
		$value = $this->input->get('value');
		
		switch($keyword){
			case 'name':
				$info = $this->Role_Model->getFirstByKey($value,$keyword);
				if(empty($info)){
					$flag = "true";
				}
				break;
			default:
				break;
		}
		
		echo $flag;
	}
}
