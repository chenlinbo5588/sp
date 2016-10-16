<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Admin_Controller {
	
	private $_avatarImageSize ;
	private $_avatarSizeKeys;
	private $_mtime;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->_avatarImageSize = config_item('avatar_img_size');
		$this->_avatarSizeKeys = array_keys($this->_avatarImageSize);
		
		$this->_mtime = array(
			'不限' => '',
			'最近1天内' => '-1 days',
			'最近3天内' => '-3 days',
			'近一周内' => '-7 days',
			'近一个月内' => '-30 days',
		);
		
		$this->assign('avatarImageSize',$this->_avatarImageSize);
	}
	
	public function index(){
		$this->load->library(array('Member_service','Common_District_service'));
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'uid DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
			
		);
		
		/*
		$ds = array();
		$dkeys = array('d1','d2','d3','d4');
		$tempK = '';
		$searchDk = '';
		foreach($dkeys as $dk){
			$tempK = $this->input->get_post($dk);
			if($tempK){
				$searchDk = $dk;
				$ds[$dk] = $tempK ;
			}
		}
		
		if(empty($ds)){
			$this->assign('ds',$this->common_district_service->prepareCityData());
		}else{
			$condition['where'][$searchDk] = $ds[$searchDk];
			
			$this->assign('ds',$this->common_district_service->prepareCityData($ds));
		}
		*/
		
		$search_map['search_field'] = array('username' => '登陆账号' ,'mobile' => '手机号码');
		
		/*
		$search_map['activity_sort'] = array(
				'-30 minutes' => '最近30分钟内活跃','-2 hours' => '最近2小时内活跃',
				'-6 hours' => '最近6小时内活跃', '-12 hours' => '最近12小时内活跃',  
				'-1 days' => '最近1天内活跃' , '-3 days' => '最近3天内活跃' , 
				'-7 days' => '最近7天内活跃' ,'-15 days' => '最近15天内活跃');
		
		
		$search_map['register_channel'] = array('1' => '后台管理中心','2' => '手机网页版' ,'3' => 'PC网页版','4' => 'iOS客户端','5' => 'Android客户端');
		$search_map['register_sort'] = array('uid DESC' => '按时间倒序','uid ASC' => '按时间顺序');
		$search_map['member_state'] = array('avatar_status@0' => '待验证头像','freeze@1' => '已禁止登录');
		*/
		
		
		$search['search_field_name'] = $this->input->get_post('search_field_name');
		$search['search_field_value'] = $this->input->get_post('search_field_value');
		//$search['activity_sort'] = $this->input->get_post('activity_sort');
		//$search['register_channel'] = $this->input->get_post('register_channel');
		$search['reg_time'] = $this->input->get_post('reg_time');
		//$search['member_state'] = $this->input->get_post('member_state');
		
		if(!empty($search['search_field_value']) && in_array($search['search_field_name'], array_keys($search_map['search_field']))){
			$condition['like'][$search['search_field_name']] = $search['search_field_value'];
		}
		
		/*
		if(in_array($search['activity_sort'], array_keys($search_map['activity_sort']))){
			$condition['where']['last_activity >='] = strtotime($search['activity_sort']);
		}
		
		if(in_array($search['register_channel'], array_keys($search_map['register_channel']))){
			$condition['where']['channel'] = $search['register_channel'];
		}
		*/
		
		if($this->_mtime[$search['reg_time']]){
			$condition['where']['gmt_create >='] = strtotime($this->_mtime[$search['reg_time']],$this->_reqtime);
		}
		
		
		$list = $this->member_service->getListByCondition($condition);
		
		//邀请人和地区名称
		$dqList = array();
		$inviterList = array();
		foreach($list['data'] as $member){
			/*
			if($member['d1']){
				$dqList[] = $member['d1'];
			}
			if($member['d2']){
				$dqList[] = $member['d2'];
			}
			
			if($member['d3']){
				$dqList[] = $member['d3'];
			}
			
			if($member['d4']){
				$dqList[] = $member['d4'];
			}
			*/
			if($member['inviter']){
				$inviterList[] = $member['inviter'];
			}
		}
		
		if($inviterList){
			$this->assign('inviterInfo',$this->member_service->getListByCondition(array(
				'select' => 'uid,username,mobile,avatar_m,avatar_s',
				'where_in' => array(
					array('key' => 'uid' ,'value' => $inviterList )
				)
			)));
		}
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		$this->assign('search_map',$search_map);
		//$this->assign('memberDs',$this->common_district_service->getDistrictByIds($dqList));
		//$this->assign('d1',$this->common_district_service->getDistrictByPid(0));
		
		$this->assign('mtime',$this->_mtime);
		$this->display();
	}
	
	
	
	/**
	 * 
	 */
	private function _setMobileRule($key){
		$this->form_validation->set_rules($key,'手机号码',array(
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
				'loginname_callable' => '%s已经被注册'
			)
		);
		
	}
	
	
	
	/**
	 * 保存头像
	 */
	public function cut_avatar(){
		
		$uid = $this->input->post('uid');
		
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
			
			//print_r($smallImg);
			/*
			$this->Member_Model->update(array(
				'aid' => $avatar_id,
				'avatar_m' => $fileData['img_m'],
				'avatar_s' => $smallImg['img_s'],
				'avatar_status' => 0,
				
			),array('uid' => $uid));
			*/
			
			$this->jsonOutput('上传成功',array(
				'm' => resource_url($fileData['img_m']),
				's' => resource_url($smallImg['img_s'])
			));
		}else{
			$this->jsonOutput('上传失败');
		}
		
	}
	
	
	/**
	 * 验证规则
	 */
	private function _addRules(){
		
		
		$param['email'] = $this->input->post('email');
		if($param['email']){
			$this->form_validation->set_rules('email','电子邮箱','valid_email');
		}
		
		$param['qq'] = $this->input->post('qq');
		
		if($param['qq']){
			$this->form_validation->set_rules('qq','QQ','regex_match[/^\d+$/]');
		}
		
		$param['weixin'] = $this->input->post('weixin');
		
		if($param['weixin']){
			$this->form_validation->set_rules('weixin','微信号','alpha_dash|min_length[6]|max_length[20]');
		}
		
		$this->form_validation->set_rules('freeze','允许登录','required|in_list[N,Y]');
	}
	
	
	/**
	 * 后台创建用户,暂时直接 die
	 */
	public function add(){
		die(0);
		$info = array();
		
		if($this->isPostRequest()){
			
			$this->_setMobileRule('mobile');
			$this->form_validation->set_rules('passwd','密码','required|valid_password|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('passwd2','密码确认','required|matches[passwd]');
			
			$this->_addRules();
			
			$aid = $this->input->post('avatar_id');
			$old_aid = $this->input->post('old_avatar_id');
			$old_avatar = $this->input->post('old_avatar');
			if($aid){
				$member_avatar = $this->input->post('avatar');
				
				$avatar = getImgPathArray($member_avatar,$this->_avatarSizeKeys);
				$avatar['aid'] = $aid;
			}
			
			for($i = 0 ; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$info = $_POST;
					if($aid){
						//校验出错，记住上传的图片
						$info = array_merge($info,$avatar);
					}
					
					//如果再次上次图片,则需要将前面那张图片删除
					if($old_aid){
						$this->load->library('Attachment_service');
						$oldsImags = getImgPathArray($old_avatar,$this->_avatarSizeKeys);
						$this->attachment_service->deleteByFileUrl($oldsImags);
					}
					
					break;
				}
				
				$addParam = array(
					'mobile' => $this->input->post('mobile'),
					'nickname' => $this->input->post('mobile'),
					'password' => $this->input->post('passwd'),
					'qq' => $this->input->post('qq'),
					'weixin' => $this->input->post('weixin'),
					'email' => $this->input->post('email'),
					'username' => $this->input->post('username'),
					'sex' => $this->input->post('sex'),
					'freeze' => $this->input->post('freeze'),
					'status' => 0,
					'channel' => 1	//1 标志直接后台增加
				);
				
				if($aid){
					$addParam = array_merge($addParam,$avatar);
				}
				
				//print_r($addParam);
				$this->load->library('Register_service');
				$result = $this->register_service->createMember($addParam);
				
				if($result['code'] == 'success'){
					$this->assign('feedback',getSuccessTip('添加成功'));
				}else{
					$this->assign('feedback',getErrorTip('添加失败'));
				}
			}
		}else{
			
			$info = array(
				//@todo 需要修改为明文
				'passwd' => random_string('numeric',6),
				'sex' => 'S',
				'allowtalk' => 'Y',
				'freeze' => 'N'
			);
			
			$info['passwd2'] = $info['passwd'];
		}
		
		
		$this->assign('info',$info);
		$this->display();
		
		
	}
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$member_id = $this->input->get_post('id');
		
		$this->load->library('Member_service');
		$info = $this->member_service->getUserInfoById($member_id);
		//print_r($info);
		
		if(!empty($member_id) && $this->isPostRequest()){
			
			$password = $this->input->post('passwd');
			$password2 =  $this->input->post('passwd2');
			
			if($password || $password2){
				$this->form_validation->set_rules('passwd','密码','alpha_dash|min_length[6]|max_length[12]');
				$this->form_validation->set_rules('passwd2','密码确认','matches[passwd]');
			}
			
			$this->_addRules();
			
			for($i = 0 ; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->assign('feedback',getErrorTip($this->form_validation->error_string('','')));
					$info = array_merge($info,$_POST);
					break;
				}
				
				$updateData = array(
					'qq' => $this->input->post('qq'),
					'weixin' => $this->input->post('weixin'),
					'email' => $this->input->post('email'),
					'sex' => $this->input->post('sex'),
					'allowtalk' => $this->input->post('allowtalk'),
					'freeze' => $this->input->post('freeze'),
				);
				
				$member_avatar = $this->input->post('avatar');
				
				
				if($member_avatar){
					$aid = $this->input->post('avatar_id');
					$avatar = getImgPathArray($member_avatar,$this->_avatarSizeKeys);
					$avatar['aid'] = $aid;
					$updateData = array_merge($updateData,$avatar);
				}
				
				if($password){
					$updateData['password'] = $password;
				}
				
				//print_r($updateData);
				$this->load->library('Member_service');
				$flag = $this->member_service->updateUserInfo($updateData,$member_id);
				
				if($flag >= 0){
					if($aid > 0 && $aid != $info['aid']){
						//上次了新的图片后 需要将旧的图片删除 释放空间
						$this->load->library('Attachment_service');
						$this->attachment_service->deleteByFileUrl(array($info['avatar'],$info['avatar_m'],$info['avatar_s']));
					}
					
					$info = $this->member_service->getUserInfoById($member_id);
					$this->assign('feedback',getSuccessTip('保存成功'));
				}else{
					$this->assign('feedback',getErrorTip('保存失败'));
				}
			}
		}
		
		$this->assign('info',$info);
		$this->display();
	}
}
