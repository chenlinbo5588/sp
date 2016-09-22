<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends MyYdzj_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->_breadCrumbs[] = array('title' => '个人中心','url' => 'my/index');
	}
	
	
	public function index()
	{
		//print_r($this->session->all_userdata());
		$this->load->library('Common_District_service');
		
		$ds = array();
		for($i = 1; $i <= 4; $i++){
			$ds[] = $this->_profile['basic']['d'.$i];
		}
		
		$ds = array_unique($ds);
		$this->seoTitle('个人中心');
		$this->assign('userDs',$this->common_district_service->getDistrictByIds($ds));
		$this->assign('inviteUrl',site_url('member/register?inviter='.$this->_profile['basic']['uid']));
		
		$this->_breadCrumbs[] = array('title' => '基本资料' ,'url' => 'my/index');
		
		$this->display();
	}
	
	/**
	 * 
	 */
	public function edit_base(){
		$this->_breadCrumbs[] = array('title' => '修改基本资料' ,'url' => $this->uri->uri_string);
		$this->display();
	}
	
	
	
	
	
	
	/**
	 * 
	 */
	public function change_mobile(){
		$step = $this->input->get_post('step');
		
		
		$this->load->library('Verify_service');
		
		if(empty($step)){
			$step = 1;
		}
		
		if($step == 1){
			$this->form_validation->set_rules('mobile_auth_code','手机验证码', array(
					'required',
					array(
						'authcode_callable['.$this->input->post('mobile').']',
						array(
							$this->verify_service,'validateAuthCode'
						)
					)
				),
				array(
					'authcode_callable' => '手机验证码不正确'
				)
			);
			
		}else if($step == 2){
			
			$this->form_validation->set_rules('newmobile','新手机号',array(
					'required',
					'valid_mobile',
					array(
						'loginname_callable[mobile]',
						array(
							$this->Member_Model,'isUnqiueByKey'
						)
					)
				),
				array(
					'loginname_callable' => '%s已经被绑定'
				)
			);
			
			$this->form_validation->set_rules('mobile_auth_code','手机验证码', array(
					'required',
					array(
						'authcode_callable['.$this->input->post('newmobile').']',
						array(
							$this->verify_service,'validateAuthCode'
						)
					)
				),
				array(
					'authcode_callable' => '手机验证码不正确'
				)
			);
		}
		
		for($i = 0; $i < 1; $i++){
			if(!$this->form_validation->run()){
				break;
			}
			
			if($step == 2){
				//$this->form_validation->set_ruls('mobile','require|valid_mobile');
				
				$rows = $this->Member_Model->update(array(
					'mobile' => $this->input->post('newmobile')
				),array(
					'uid' => $this->_profile['basic']['uid']
				));
				
				if($rows > 0){
					$this->_profile['basic']['mobile'] = $this->input->post('newmobile');
					$this->session->set_userdata(array(
						$this->_profileKey => $this->_profile
					));
				}
			}
			
			if($step <= 2){
				$step++;
			}
		}
		
		$this->_breadCrumbs[] = array('title' => '更改手机' ,'url' => $this->uri->uri_string);
		$this->assign('step',$step);
		$this->display();
	}
	
	
	public function change_psw(){
		$this->_breadCrumbs[] = array('title' => '修改密码' ,'url' => $this->uri->uri_string);
		$this->display();
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
				js_redirect('my');
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
				
				
				$avatarImageSize = config_item('avatar_img_size');
				$avatarSizeKeys = array_keys($avatarImageSize);
		
				$this->load->library('Attachment_service');
				$this->attachment_service->setImageSizeConfig($avatarImageSize);
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
					js_redirect('my');
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
			$this->display();
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
		
		js_redirect($targetUrl);
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
			$this->display();
		}
		
	}
	
}
