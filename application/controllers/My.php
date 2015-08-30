<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	public function index()
	{
		//$this->assign('teamCount',$this->);
		//phpinfo();
		$this->load->library('Common_District_Service');
		
		$ds = array();
		for($i = 1; $i <= 4; $i++){
			$ds[] = $this->_profile['basic']['d'.$i];
		}
		
		$ds = array_unique($ds);
		$this->assign('userDs',$this->common_district_service->getDistrictByIds($ds));
		$this->assign('inviteUrl',site_url('member/register?inviter='.$this->_profile['basic']['uid']));
		$this->display('my/index');
	}
	
	
	/**
	 * 设置用户名称
	 */
	public function set_username(){
		
		if($this->isPostRequest()){
			
			$setOk = false;
			$inviteFrom = $this->input->post('inviteFrom');
			
			$this->assign('inviteFrom',$inviteFrom);
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('username','真实姓名','required|min_length[2]|max_length[4]');
			
			
			for($i = 0; $i < 1; $i++){
				if($this->form_validation->run() == FALSE){
					break;
				}
				
				$this->load->library('Member_Service');
				$result = $this->member_service->updateUserInfo(array(
					'username' => $this->input->post('username')
				),$this->_profile['basic']['uid']);
				
				$this->member_service->refreshProfile($this->_profile['basic']['uid']);
				$setOk = true;
			}
			
			if($setOk){
				redirect('my');
			}else{
				$this->display('my/set_username');
			}
			
		}else{
			$this->assign('default_username',$this->_profile['basic']['username']);
			$this->display('my/set_username');
		}
	}
	
	/**
	 * 设置用户头像
	 */
	public function set_avatar(){
		if($this->isPostRequest()){
			
			$setOk = false;
			
			$inviteFrom = $this->input->post('inviteFrom');
			
			$this->assign('default_avatar',$this->input->post('default_avatar'));
			$this->assign('inviteFrom',$inviteFrom);
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			
			for($i = 0; $i < 1; $i++){
				$this->load->library('Attachment_Service');
				$fileData = $this->attachment_service->addImageAttachment('avatar',array(
					'min_width' => 800,
					'min_height' => 800
				));
				
				if(!empty($fileData['img_big'])){
					$newAvatar = $fileData['img_big'];
				}else{
					$newAvatar = $this->input->post('new_avatar');
				}
				
				$this->assign('new_avatar',$newAvatar);
				
				if($newAvatar == ''){
					$this->assign('avatar_error',$this->attachment_service->getErrorMsg());
					break;
				}
				
				$this->load->library('Member_Service');
				$result = $this->member_service->updateUserInfo(array(
					'avatar' => $fileData['file_url'],
					'avatar_large' => $fileData['img_large'],
					'avatar_big' => $fileData['img_big'],
					'avatar_middle' => $fileData['img_middle'],
					'avatar_small' => $fileData['img_small']
				),$this->_profile['basic']['uid']);
				
				$this->member_service->refreshProfile($this->_profile['basic']['uid']);
				$setOk = true;
			}
			
			
			if($setOk){
				if('teamInvite' == $inviteFrom){
					//不需要设置地区了
					$this->_jumpToTeam();
				}else if('my' == $inviteFrom){
					redirect('my');
				}else{
					$this->load->library('Common_District_Service');
					$this->_prepareSetCity();
					$this->seoTitle('设置您的所在地');
					$this->display('my/set_city');
				}
				
			}else{
				$this->display('my/set_avatar');
			}
			
		}else{
			
			$this->assign('inviteFrom',$this->input->get('inviteFrom'));
			$this->assign('default_avatar',$this->_profile['basic']['avatar_big']);
			$this->display('my/set_avatar');
		}
		
		
	}
	
	
	private function _jumpToTeam(){
		
		$url = $this->input->post('returnUrl');
		
		$targetUrl = ''; 
		if(empty($url) || !isLocalUrl($url)){
			$targetUrl = site_url('my/index');
			
		}else{
			preg_match('/^(.*)\?param=(.*)$/', $url, $match);
			$targetUrl = $match[1].'?param='.urlencode($match[2]);
		}
		
		redirect($targetUrl);
	}
	
	
	
	private function _prepareSetCity(){
		$ds = array(
			'd1' => $this->_profile['basic']['d1'],
			'd2' => $this->_profile['basic']['d2'],
			'd3' => $this->_profile['basic']['d3'],
			'd4' => $this->_profile['basic']['d4']
		);
		
		$this->assign('d1',$this->common_district_service->getDistrictByPid(0));
		
		if($ds['d1'] > 0){
			$this->assign('d2',$this->common_district_service->getDistrictByPid($ds['d1']));
		}
		
		if($ds['d2'] > 0){
			$this->assign('d3',$this->common_district_service->getDistrictByPid($ds['d2']));
		}
		
		if($ds['d3'] > 0){
			$this->assign('d4',$this->common_district_service->getDistrictByPid($ds['d3']));
		}
		
	}
	
	
	
	/**
	 * 设置地区
	 * 
	 * @todo 如果用户通过 加入队伍邀请进行注册的 部需要进行该步骤，直接约邀请者设置的地区直接相同
	 */
	public function set_city()
	{
		$this->load->library('Common_District_Service');
		
		if($this->isPostRequest()){
			
			$url = $this->input->post('returnUrl');
			$this->assign('returnUrl',$url);
			
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('d1','省','required|is_natural_no_zero');
			$this->form_validation->set_rules('d2','市','required|is_natural_no_zero');
			$this->form_validation->set_rules('d3','县','required|is_natural_no_zero');
			
			if($this->input->post('d4')){
				$this->form_validation->set_rules('d4','街道/镇乡','is_natural_no_zero');
			}
			
			if($this->form_validation->run() !== FALSE){
				$this->load->library('Member_Service');
				
				$addParam = array(
					'district_bind' => 1,
					'd1' => intval($this->input->post('d1')),
					'd2' => intval($this->input->post('d2')),
					'd3' => intval($this->input->post('d3')),
					'd4' => intval($this->input->post('d4'))
				);
				
				$result = $this->member_service->updateUserInfo($addParam, $this->_profile['basic']['uid']);
				
				$this->member_service->refreshProfile($this->_profile['basic']['uid']);
				
				
				$targetUrl = '';
				if(empty($url) || !isLocalUrl($url)){
					$targetUrl = site_url('my/index');
				}else{
					$targetUrl = $url;
				}
				
				$this->jsonOutput('设置成功',array(
					'url' => $targetUrl
				));
				
			}else{
				$this->jsonOutput('',array(
					'formhash' => $this->security->get_csrf_hash(),
					'errormsg' => $this->form_validation->error_array()
				));
			}
		}else{
			
			$this->assign('returnUrl',$this->input->get('returnUrl'));
			$this->_prepareSetCity();
			
			$this->seoTitle('设置您的所在地');
			$this->display('my/set_city');
		}
		
	}
	
}
