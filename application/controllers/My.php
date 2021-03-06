<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends MyYdzj_Controller {
	
	private $_avatarImageSize ;
	private $_avatarSizeKeys;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->_avatarImageSize = config_item('avatar_img_size');
		$this->_avatarSizeKeys = array_keys($this->_avatarImageSize);
	}
	
	
	public function index()
	{
		//$this->assign('teamCount',$this->);
		//phpinfo();
		$this->load->library('Common_District_service');
		
		$ds = array();
		for($i = 1; $i <= 4; $i++){
			$ds[] = $this->_profile['basic']['d'.$i];
		}
		
		$ds = array_unique($ds);
		$this->seoTitle('个人中心');
		$this->assign('userDs',$this->common_district_service->getDistrictByIds($ds));
		$this->assign('inviteUrl',site_url('member/register?inviter='.$this->_profile['basic']['uid']));
		$this->display('my/index');
	}
	
	
	/**
	 * 申请裁判员
	 */
	public function apply_judge(){
		
		
		
	}
	
	/**
	 * 
	 */
	public function set_email(){
		
		$this->setTopNavTitle('绑定邮箱');
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('my').'" title="返回">返回</a>');
		if($this->isPostRequest()){
			
			$setOk = false;
			$inviteFrom = $this->input->post('inviteFrom');
			
			$this->assign('inviteFrom',$inviteFrom);
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('email','真实姓名','required|valid_email');
			
			
			for($i = 0; $i < 1; $i++){
				if($this->form_validation->run() == FALSE){
					break;
				}
				
				$this->load->library('Member_service');
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
	 * 设置用户名称
	 */
	public function set_username(){
		
		$this->setTopNavTitle('设置真实名称');
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('my').'" title="返回">返回</a>');
		if($this->isPostRequest()){
			
			$setOk = false;
			
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('username','真实姓名','required|min_length[2]|max_length[4]');
			
			
			for($i = 0; $i < 1; $i++){
				if($this->form_validation->run() == FALSE){
					break;
				}
				
				$this->load->library('Member_service');
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
		
		$this->setTopNavTitle('修改头像');
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('my').'" title="返回">返回</a>');
		
		if($this->isPostRequest()){
			$setOk = false;
			
			$inviteFrom = $this->input->post('inviteFrom');
			//$this->assign('default_avatar',$this->input->post('default_avatar'));
			$this->assign('inviteFrom',$inviteFrom);
			$this->assign('returnUrl',$this->input->post('returnUrl'));
			
			for($i = 0; $i < 1; $i++){
				$avatar_id = $this->input->post('avatar_id');
				$newAvatar = $this->input->post('new_avatar');
				$newAvatar = str_replace(base_url(),'',$newAvatar);
				
				$this->load->library('Attachment_service');
				$this->attachment_service->setImageSizeConfig($this->_avatarImageSize);
				$fileData = $this->attachment_service->resize(
					$newAvatar,
					array('m') , 
					array('x_axis' => $this->input->post('x1'), 'y_axis' => $this->input->post('y1'))
				);
				
				if($fileData['img_m']){
					$smallImg = $this->attachment_service->resize($fileData['img_m'],array('s'));
				}
				
				//删除原图
				//unlink($fileData['full_path']);
				
				$this->load->library('Member_service');
				$result = $this->member_service->updateUserInfo(array(
					'status' => 0,
					'avatar_status' => 0,
					'aid' => $avatar_id,
					'avatar_m' => $fileData['img_m'],
					'avatar_s' => $smallImg['img_s']
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
					$this->load->library('Common_District_service');
					$this->_prepareSetCity();
					$this->seoTitle('设置您的所在地');
					$this->setTopNavTitle('设置您的所在地');
					$this->display('my/set_city');
				}
				
			}else{
				$this->display('my/set_avatar');
			}
			
		}else{
			
			$this->assign('inviteFrom',$this->input->get('inviteFrom'));
			$this->assign('default_avatar',$this->_profile['basic']['avatar_m']);
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
		$d = array(
			'd1' => $this->_profile['basic']['d1'],
			'd2' => $this->_profile['basic']['d2'],
			'd3' => $this->_profile['basic']['d3'],
			'd4' => $this->_profile['basic']['d4']
		);
		
		$rt = $this->common_district_service->prepareCityData($d);
		
		$this->assign('ds',$rt);
	}
	
	
	
	/**
	 * 设置地区
	 * 
	 * 如果用户通过 加入队伍邀请进行注册的 不需要进行该步骤，直接约邀请者设置的地区直接相同
	 */
	public function set_city()
	{
		$this->load->library('Common_District_service');
		
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
				$this->load->library('Member_service');
				
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
