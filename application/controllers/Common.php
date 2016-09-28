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
