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
		
		/*
		$this->load->library('Common_District_service');
		
		$ds = array();
		for($i = 1; $i <= 4; $i++){
			$ds[] = $this->_profile['basic']['d'.$i];
		}
		
		$ds = array_unique($ds);
		
		$this->assign('userDs',$this->common_district_service->getDistrictByIds($ds));
		*/
		
		$this->seoTitle('个人中心');
		$this->assign('inviteUrl',site_url('member/register?inviter='.$this->_profile['basic']['uid']));
		$avatarImageSize = config_item('avatar_img_size');
		$this->assign('avatarImageSize',$avatarImageSize);
		
		$this->display();
	}
	
	
	
	private function _tradeUpload(){
		
		$this->load->library('Attachment_service');
		$this->attachment_service->setUserInfo($this->_profile['basic']);
		
		$fileData = $this->attachment_service->addImageAttachment('trade_pic',array(
			'without_db' => true
		),0,'seller_verify');
		
		$resp  = array();
		if($fileData){
			$cutImage = $this->attachment_service->resize($fileData['file_url'],
				array('m' => array('width' => 300,'height' => 450,'maintain_ratio' => true,'quality' => 100)
			));
			
			return array(
				'b' => $cutImage['file_url'],
				'm' => $cutImage['img_m']
			);
			
			/*
			$this->jsonOutput('上传成功',array(
				'id' => $fileData['id'],
				'b' => resource_url($cutImage['file_url']),
				'm' => resource_url($cutImage['img_m'])
			));
			*/
		}else{
			return array();
		}
	}
	
	public function seller_verify(){
		
		
		$this->_breadCrumbs[] = array(
			'title' => '企业认证',
			'url' => $this->uri->uri_string
		);
		
		
		$step = $this->input->get_post('step');
		
		if(empty($step)){
			$step = 1;
		}
		
		//print_r($this->_siteSetting);
		$this->load->model('Member_Seller_Model');
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				if(1 == $step){
					
					$step++;
					
				}else if(2 == $step){
					
					$back = false;
					
					//$this->form_validation->set_rules('store_url', '网店链接','required|valid_url');
					$this->form_validation->set_rules('store_url', '企业名称','required|max_length[200]');
					//$this->form_validation->set_rules('img_b','交易流水图片','required|valid_url');
					
					$store_url = $this->input->post('store_url');
					
					$img_b = $this->input->post('img_b');
					$img_m = $this->input->post('img_m');
					
					$fileData = $this->_tradeUpload();
					
					//print_r($_FILES);
					//print_r($fileData);
					
					$hasFile = false;
					if($img_b || $fileData){
						$hasFile = true;
					}
					
					if($fileData){
						$info = array(
							'store_url' => $this->input->post('store_url'),
							'source_pic' => $fileData['b'],
							'trade_pic' => $fileData['m'],
						);
						
						
						
					}else if($img_b){
						$info = array(
							'store_url' => $this->input->post('store_url'),
							'source_pic' => str_replace(base_url(),'',$img_b),
							'trade_pic' => str_replace(base_url(),'',$img_m),
						);
					}
					
					//var_dump($hasFile);
					//print_r($info);
					$this->assign('info',$info);
					$isPass = true;
					
					if(!$hasFile){
						$isPass = false;
						$step = 1;
						$this->assign('file_error','<label class="error">请上传文件</label>');
					}
					
					if(!$this->form_validation->run()){
						$isPass = false;
						$this->assign('goback',$back);
						
					}
					
					if(!$isPass){
						break;
					}
					
					if($fileData && $img_b){
						$this->attachment_service->deleteByFileUrl(array($img_b,$img_m));
					}
					
					
					
				}else if(3 == $step){
					
					$verifyRecord = $this->Member_Seller_Model->getFirstByKey($this->_loginUID,'uid');
					$updateData = array(
						'uid' => $this->_loginUID,
						'store_url' => $this->input->post('store_url',true),
						'source_pic' => $this->input->post('source_pic',true),
						'trade_pic' => $this->input->post('trade_pic',true),
						'verify_result' => 0
					);
					
					$updateData['store_url'] = trim($updateData['store_url']);
					
					//$updateData['store_url'] = preg_replace('/^(https?:\/\/)(.*)$/i',"\\2",$updateData['store_url']);
					
					
					if(empty($verifyRecord)){
						$affectRow = $this->Member_Seller_Model->_add($updateData,true);
					}else{
						
						//10 分钟开外 才能重新更新，防止用户刷页面
						if(($this->_reqtime - $verifyRecord['gmt_modify']) > 600){
							//$affectRow = $this->Member_Seller_Model->update($updateData,array('uid' => $this->_loginUID,'verify_result !=' => 0));
							$affectRow = $this->Member_Seller_Model->update($updateData,array('uid' => $this->_loginUID));
						}
					}
					
					if($affectRow > 0){
						$emailData = array(
							'email' => $this->_getSiteSetting('site_email'),
							'title' => '有新的企业认证请求',
							'content' => '用户:<a href="'.admin_site_url('member/index?search_field_name=username&search_field_value=').urlencode($this->_profile['basic']['username']).'" target="_blank">'.$this->_profile['basic']['username'].'</a> 提交了企业认证资料，请及时审核,<a href="'.admin_site_url('seller/index').'" target="_blank">马上去审核</a>'
						);
						
						$this->message_service->initEmail($this->_siteSetting);
						$flag = $this->message_service->sendEmail(
							 $emailData['email'],
							 $emailData['title'],
							 $emailData['content']
						);
						
						if(!$flag){
							//不成功则 建立一条待发记录
							$siteEmailModel = $this->message_service->getSiteEmailModel();
							$siteEmailModel->_add($emailData);
						}
					}
					
				}
			}
		}else{
			
			$retry = $this->input->get_post('retry');
			
			
			if('yes' != $retry){
				$verfiyInfo = $this->Member_Seller_Model->getFirstByKey($this->_loginUID,'uid');
				if($verfiyInfo){
					if($verfiyInfo['verify_result'] == 0){
						$step = 3;
					}else{
						$step = 4;
						$this->assign('verfiyInfo',$verfiyInfo);
					}
					
				}
			}
		}
		
		$this->assign('stepHTML',step_helper(array(
			'上传认证资料',
			'确认信息',
			'等待审核',
			'审核结果',
		),$step));
		$this->assign('step',$step);
		$this->display();
		
	}
	
	
	
	/**
	 * 
	 */
	public function change_mobile(){
		$this->load->library('Verify_service');
		
		$this->_breadCrumbs[] = array(
			'title' => '修改手机',
			'url' => $this->uri->uri_string
		);
		
		if($this->isPostRequest()){
			
			$step = $this->session->userdata('step');
			
			
			if(1 == $step){
				$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
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
				
			}else if(2 == $step){
				$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
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
			
			
		}else{
			$step = 1;
		}
		
		
		$this->assign('step',$step);
		$this->session->set_userdata('step',$step);
		$this->assign('stepHTML',step_helper(array(
			'原手机号码验证',
			'绑定新手机号码',
			'更换结果',
		),$step));
		
		$this->display();
	}
	
	
	public function change_psw(){
		
		$this->_breadCrumbs[] = array(
			'title' => '修改密码',
			'url' => $this->uri->uri_string
		);
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('old_psw','原密码','required');
			$this->form_validation->set_rules('psw','密码','required|valid_password|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('psw2','确认密码','required|matches[psw]');
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					//$feedback = $this->form_validation->error_string();
					$feedback = getErrorTip('数据校验失败');
					
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
	
	
	
	public function upload_avatar(){
		
		$this->load->library('Attachment_service');
		$this->attachment_service->setUserInfo($this->_profile['basic']);
		
		$fileData = $this->attachment_service->addImageAttachment('Filedata',array(),0,'member_avatar');
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
