<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends MyYdzj_Controller {
	
	private $_avatarImageSize ;
	private $_avatarSizeKeys;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->_avatarImageSize = config_item('avatar_img_size');
		$this->_avatarSizeKeys = array_keys($this->_avatarImageSize);
		
		$this->assign('avatarImageSize',$this->_avatarImageSize);
		
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
		
		$avatarImageSize = config_item('avatar_img_size');
		$this->assign('avatarImageSize',$avatarImageSize);
		
		//print_r($this->session->all_userdata());
		
		$this->display();
	}
	
	
	
	public function trade_upload(){
		
		$this->load->library('Attachment_service');
		$this->attachment_service->setUserInfo($this->_profile['basic']);
		
		$fileData = $this->attachment_service->addImageAttachment('Filedata',array(),0,'seller_verify');
		
		$resp  = array();
		if($fileData){
			$cutImage = $this->attachment_service->resize($fileData['file_url'],
				array('m' => array('width' => 300,'height' => 450,'maintain_ratio' => true,'quality' => 100)
			));
			
			$this->jsonOutput('上传成功',array(
				'id' => $cutImage['id'],
				'b' => resource_url($cutImage['file_url']),
				'm' => resource_url($cutImage['img_m'])
			
			));
			
		}else{
			$this->jsonOutput('上传失败');
		}
	}
	
	public function seller_verify(){
		
		$step = $this->input->get_post('step');
		
		if(empty($step)){
			$step = 1;
		}
		
		if($this->isPostRequest()){
			$uploadFile = false;
			switch($step){
				case 1:
					$this->form_validation->set_rules('store_url', '网店链接','required|valid_url');
					
					$this->load->library('Attachment_service');
					$this->attachment_service->setUserInfo($this->_profile['basic']);
					
					$fileData = $this->attachment_service->addImageAttachment('trade_pic',array(
						'min_width' => 400,
						'min_height' => 400,
					),0,'member_avatar');
					
					if($fileData){
						$cutImage = $this->attachment_service->resize($fileData['file_url'],
							array('b' => array('width' => 800,'height' => 600,'maintain_ratio' => true,'quality' => 95)
						));
						
						$uploadFile = true;
					}
					
					break;
				default:
					break;			
			}
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					break;
				}
				
				if(1 == $step && !$uploadFile){
					$feedback = getErrorTip('请上传交易流水图片');
					break;
				}
				
				$step++;
			}
			
		}
		
		
		$this->assign('step',$step);
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
		
		$this->assign('step',$step);
		$this->display();
	}
	
	
	public function change_psw(){
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('old_psw','原密码','required');
			$this->form_validation->set_rules('psw','密码','required|valid_password|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('psw2','确认密码','required|matches[psw]');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				$oldPsw = trim($this->input->post('old_psw'));
				$member = $this->Member_Model->getById(array(
					'where' => array('uid' => $this->_profile['basic']['uid'])
				));
				
				if($oldPsw != $this->encrypt->decode($member['password'])){
					$feedback = getErrorTip('原密码不正确');
					break;
				}
				
				$this->Member_Model->update(array(
					'password' => $this->encrypt->encode($this->input->post('psw'))
				),array(
					'uid' => $this->_profile['basic']['uid']
				));
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
	/**
	 * 
	 */
	public function verify_email(){
		//print_r($this->_siteSetting);
		
		if($this->isPostRequest()){
			//$this->form_validation->set_rules('email','邮箱地址','required|valid_email');
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					break;
				}
				
				$this->load->library('Message_service');
				$this->message_service->initEmail($this->_siteSetting);
				$param = $this->message_service->getEncodeParam(array(
					$this->_profile['basic']['uid'],
					$this->_profile['basic']['email']
				));
				
				$url = site_url('member/verify_email?p=').$param;
				$flag = $this->message_service->sendEmailConfirm($this->_profile['basic']['email'],$url);
				
				$this->assign('send',true);
			}
		}
		
		$mailServer = explode('@',$this->_profile['basic']['email']);
		$this->assign('emailServer','mail.'.$mailServer[1]);
				
		$this->display();
	}
	
	
	
	/**
	 * 
	 */
	public function set_email(){
		
		if($this->isPostRequest()){
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules('newemail','邮箱地址','required|valid_email');
			
			for($i = 0; $i < 1; $i++){
				if($this->form_validation->run() == FALSE){
					$this->jsonOutput($this->form_validation->error_string('',''),$this->getFormHash());
					break;
				}
				
				$newEmail = $this->input->post('newemail');
				
				$updateData = array(
					'email' => $newEmail,
					'email_status' => 0
				);
				
				$result = $this->Member_Model->update($updateData,array('uid' => $this->_profile['basic']['uid']));
				
				if($result){
					$this->_profile['basic'] = array_merge($this->_profile['basic'],$updateData);
					$this->refreshProfile();
				}
				
				$this->jsonOutput('修改成功');
			}
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
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
	
	
	
	public function upload_avatar(){
		
		$this->load->library('Attachment_service');
		$this->attachment_service->setUserInfo($this->_profile['basic']);
		
		$fileData = $this->attachment_service->addImageAttachment('imgFile',array(
			/*'min_width' => $this->_avatarImageSize['m']['width'],
			'min_height' => $this->_avatarImageSize['m']['height'],*/
			'max_width' => 800,
			'max_height' => 800,
		),0,'member_avatar');
		
		
		$resp  = array();
		
		if($fileData){
			/*
			$cutImage = $this->attachment_service->resize($fileData['file_url'],
				array('b' => array('width' => 800,'height' => 600,'maintain_ratio' => true,'quality' => 100)
			));
			print_r($cutImage);
			*/
			
			$resp = $this->uploadJSONFormat(0,$fileData);
		}else{
			$resp = $this->uploadJSONFormat(1);
		}
		
		echo json_encode($resp);
	}
	
	
	
	/**
	 * 保存头像
	 */
	public function set_avatar(){
		
		if($this->isPostRequest()){
			$src_file = str_ireplace(base_url(),'',$this->input->post('avatar'));
			
			$avatar_id = $this->input->post('avatar_id');
			$this->load->library('Attachment_service');
			
			$this->attachment_service->setImageSizeConfig($this->_avatarImageSize);
			$fileData = $this->attachment_service->resize($src_file, 
				array('m'),
				array('x_axis' => $this->input->post('x'), 'y_axis' => $this->input->post('y')));
			
			// 在中 img_m 的基础上再次裁剪 
			if($fileData['img_m']){
				$smallImg = $this->attachment_service->resize($fileData['img_m'], array('s') );
			}
			
			//删除原图
			@unlink($fileData['full_path']);
			$this->Member_Model->update(array(
				'aid' => $avatar_id,
				'avatar_m' => $fileData['file_url'],
				'avatar_s' => $smallImg['img_s'],
				'avatar_status' => 1
			),array('uid' => $this->_profile['basic']['uid']));
			
			
			$this->_profile['basic']['avatar_m'] = $fileData['img_m'];
			$this->_profile['basic']['avatar_s'] = $smallImg['img_s'];
			
			$this->refreshProfile();
			
			js_redirect('my/index');
		}
		
	}
	
}
