<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	
	public function index(){
		$this->load->library(array('Team_service','Member_service','Common_District_service'));
		
		$currentPage = $this->input->get('page') ? $this->input->get('page') : 1;
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
			
		);
		
		$ds = array();
		$dkeys = array('d1','d2','d3','d4');
		$tempK = '';
		$searchDk = '';
		foreach($dkeys as $dk){
			$tempK = $this->input->get($dk);
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
		
		$search_map['create_time'] = array('id DESC' => '按时间倒序','id ASC' => '按时间顺序');
		$search_map['member_state'] = array('avatar_status@0' => '待验证队伍合影');
		
		
		$search['team_name'] = $this->input->get('team_name');
		$search['member_state'] = $this->input->get('member_state');
		
		if($search['team_name']){
			$condition['like']['title'] = $search['team_name'];
		}
		
		
		if(in_array($search['create_time'], array_keys($search_map['create_time']))){
			$condition['order'] = $search['create_time'];
		}
		
		if(in_array($search['member_state'], array_keys($search_map['member_state']))){
			$statArray = explode('@',$search['member_state']);
			$condition['where'][$statArray[0]] = $statArray[1];
		}
		
		$list = $this->team_service->getTeamListByCondition($condition);
		
		//print_r($list);
		$userList = array();
		foreach($list['data'] as $item){
			$userList[] = $item['owner_uid'];
			$userList[] = $item['leader_uid'];
		}
		
		if($userList){
			$this->assign('userInfo',$this->member_service->getListByCondition(array(
				'select' => 'uid,mobile,avatar_middle,avatar_small',
				'where_in' => array(
					array('key' => 'uid' ,'value' => $userList )
				)
			)));
		}
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->assign('search_map',$search_map);
		
		$this->display('team/index');
	}
	
	
	/**
	 * 
	 */
	private function _city(){
		$ds = array();
		$dkeys = array('d1','d2','d3','d4');
		$tempK = '';
		$searchDk = '';
		foreach($dkeys as $dk){
			$tempK = $this->input->post($dk);
			if($tempK){
				$searchDk = $dk;
				$ds[$dk] = $tempK ;
			}
		}
		
		if(empty($ds)){
			$this->assign('ds',$this->common_district_service->prepareCityData());
		}else{
			$this->assign('ds',$this->common_district_service->prepareCityData($ds));
		}
	}
	
	public function add(){
		
		$this->load->library(array('Team_service','Common_District_service'));
		$sportsCategoryList = $this->team_service->getSportsCategory();
		$this->assign('sportsCategoryList',$sportsCategoryList);
		
		//$this->_city();
		
		if($this->isPostRequest()){
			
			$addParam = $this->team_service->teamAddRules();
			
			$this->form_validation->set_rules('leader_account','队长账号',array(
				'required',
				'valid_mobile',
				array(
					'mobile_callable[mobile]',
					array(
						$this->Member_Model,'checkExists'
					)
				),
				'errors' => array(
					'mobile_callable' => '%s必须为有效的登陆账号'
				)
			));
			
			/*
			$this->form_validation->set_rules('d1','一级地址','required|is_natural_no_zero');
			$this->form_validation->set_rules('d2','二级地址','required|is_natural_no_zero');
			$this->form_validation->set_rules('d3','三级地址','required|is_natural_no_zero');
			
			$d4 = $this->input->post('d4');
			if($d4){
				$this->form_validation->set_rules('d4','四级地址','required|is_natural_no_zero');
			}
			*/
			
			
			for($i = 0 ; $i < 1; $i++){
				
				$this->load->library('Attachment_service');
				$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
				
				$size = array('large','big','middle');
				
				$fileData = $this->attachment_service->addImageAttachment('team_avatar',$size,1);
				$team_logo = $this->input->post('team_logo');
				$team_logo_id = $this->input->post('team_log_id');
				
				if(!$fileData && empty($team_logo)){
					$this->assign('logo_error','<label class="form_error">请上传球队合影照片</label>');
					break;
				}
				
				if($fileData){
					$this->assign('team_log_id',$fileData['id']);
					$this->assign('team_logo',$fileData['file_url']);
					
					//重传了直接删除原先传的那张图片
					if($team_logo_id){
						$this->attachment_service->deleteFiles(array($team_logo_id) , $size, 1);
					}
				}else{
					$this->assign('team_log_id',$team_logo_id);
					$this->assign('team_logo',$team_logo);
				}
				
				if(!$this->form_validation->run()){
					break;
				}
				
				$leaderInfo = $this->Member_Model->getFirstByKey($this->input->post('leader_account'),'mobile');
				$addParam = array(
					'category_id' => $this->input->post('category_id'),
					'category_name' => $sportsCategoryList[$this->input->post('category_id')]['name'],
					'title' => $this->input->post('title'),
					'leader_uid' => $leaderInfo['uid'],
					'leader_name' => $leaderInfo['username'] ? $leaderInfo['username']  : $leaderInfo['nickname'] ,
					'joined_type' => $this->input->post('joined_type'),
					
				);
				$addParam['category_name'] = $sportsCategoryList[$this->input->post('category_id')]['name'];
				if(empty($addParam['category_name'])){
					$addParam['category_name'] = '';
				}
				
				if($fileData){
					$addParam['aid'] = $fileData['id'];
					$addParam['avatar'] = $fileData['file_url'];
				}else if($team_logo){
					$addParam['aid'] = $team_logo_id;
					$addParam['avatar'] = $team_logo;
				}
				
				//创建缩略图
				$resizeFile = $this->attachment_service->resize(array('file_url' => $addParam['avatar']) , $size);
				$addParam['avatar_large'] = $resizeFile['img_large'];
				$addParam['avatar_big'] = $resizeFile['img_big'];
				$addParam['avatar_middle'] = $resizeFile['img_middle'];
				//$addParam['avatar_small'] = $resizeFile['img_small'];
				
				
				$teamid = $this->team_service->addTeam($addParam, $leaderInfo);
				
				if($teamid > 0){
					$this->assign('feedback','<div class="tip_success">添加成功</div>');
				}else{
					$this->assign('feedback','<div class="tip_error">添加失败</div>');
				}
			}
		}
		
		$this->display('team/add');
		
		
	}
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$urlParam = $this->uri->uri_to_assoc();
		$this->assign('id',$urlParam['edit']);
		
		$this->load->library('Member_service');
		$info = $this->member_service->getUserInfoById($urlParam['edit']);
		
		//print_r($urlParam);
		
		if(!empty($urlParam['edit']) && $this->isPostRequest()){
			$this->assign('inpost',true);
			
			$this->form_validation->set_rules('member_nickname','昵称','min_length[3]|max_length[30]|is_unique_not_self['.$this->Member_Model->getTableRealName().'.nickname.uid.'.$urlParam['edit'].']');
			
			$password = $this->input->post('member_passwd');
			$password2 =  $this->input->post('member_passwd2');
			
			if($password || $password2){
				$this->form_validation->set_rules('member_passwd','密码','alpha_dash|min_length[6]|max_length[12]');
				$this->form_validation->set_rules('member_passwd2','密码确认','matches[member_passwd]');
			}
			
			
			
			$this->_addRules();
			
			for($i = 0 ; $i < 1; $i++){
				if(!$this->form_validation->run()){
					echo $this->form_validation->error_string();
					break;
				}
				
				$updateData = array(
					'nickname' => $this->input->post('member_nickname'),
					'qq' => $this->input->post('member_qq'),
					'weixin' => $this->input->post('member_weixin'),
					'email' => $this->input->post('member_email'),
					'username' => $this->input->post('member_username'),
					'sex' => $this->input->post('member_sex'),
					'allowtalk' => $this->input->post('allowtalk'),
					'freeze' => $this->input->post('memberstate'),
				);
				
				$aid = $this->input->post('avatar_id');
				
				if($aid){
					$member_avatar = $this->input->post('member_avatar');
					$avatar = getImgPathArray($member_avatar,array('middle','small'));
					$avatar['aid'] = $aid;
					$updateData = array_merge($updateData,$avatar);
				}
				
				if($password){
					$updateData['password'] = $password;
				}
				
				//print_r($updateData);
				$this->load->library('Member_service');
				$flag = $this->member_service->updateUserInfo($updateData,$urlParam['edit']);
				
				if($flag >= 0){
					if($aid && $info['aid']){
						//传新的图片
						$this->load->library('Attachment_service');
						$this->attachment_service->deleteByFileUrl(array($info['avatar_middle'],$info['avatar_small']));
					}
					
					$info = $this->member_service->getUserInfoById($urlParam['edit']);
					$this->assign('feedback','<div class="tip_success">保存成功</div>');
				}else{
					$this->assign('feedback','<div class="tip_error">保存失败</div>');
				}
			}
			
		}
		
		$this->assign('info',$info);
		$this->display('member/edit');
	}
	
	
	
}
