<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends Ydzj_Admin_Controller {
	
	private $_teamImageSize ;
	private $_teamSizeKeys;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Team_service','Common_District_service'));
		
		$this->_teamImageSize = config_item('team_img_size');
		$this->_teamSizeKeys = array_keys($this->_teamImageSize);
	}
	
	public function index(){
		$this->load->library(array('Member_service'));
		
		$currentPage = $this->input->get('page') ? $this->input->get('page') : 1;
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => 3,
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
				'select' => 'uid,mobile,avatar_m,avatar_s',
				'where_in' => array(
					array('key' => 'uid' ,'value' => $userList )
				)
			)));
		}
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->assign('search_map',$search_map);
		
		$this->display();
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
		
		$sportsCategoryList = $this->team_service->getSportsCategory();
		$this->assign('sportsCategoryList',$sportsCategoryList);
		
		//$this->_city();
		$info = array();
		
		if($this->isPostRequest()){
			$addParam = $this->team_service->teamAddRules();
			$this->form_validation->set_rules('leader_account','手机号码',"required|valid_mobile|in_db_list[{$this->Member_Model->_tableRealName}.mobile]");
			
			/*
			$this->form_validation->set_rules('leader_account','手机号码',array(
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
					'loginname_callable' => '%s必须为有效的登陆账号'
				)
			);
		
			
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
				$fileData = $this->attachment_service->addImageAttachment('team_avatar',array(),FROM_BACKGROUND,'team_avatar');
				
				// 看有没有传成功过 
				$avatar = $this->input->post('avatar');
				$aid = $this->input->post('aid');
				$info = $_POST;
				
				/**
				 * 可以后面再传图片
				if(!$fileData && empty($aid)){
					$this->assign('logo_error','<label class="form_error">请上传球队合影照片</label>');
					break;
				}
				*/
				
				if($fileData){
					$info['aid'] = $fileData['id'];
					$info['avatar'] = $fileData['file_url'];
					//重传了直接删除原先传的那张图片
					if($aid){
						$oldsImags = getImgPathArray($avatar,$this->_teamSizeKeys);
						$this->attachment_service->deleteByFileUrl($oldsImags);
					}
				}else{
					$info['aid'] = $aid;
					$info['avatar'] = $avatar;
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
				}else if($avatar){
					$addParam['aid'] = $aid ? $aid : 0;
					$addParam['avatar'] = empty($avatar) != true ? $avatar : '';
				}
				
				//创建缩略图
				if($addParam['avatar']){
					$this->attachment_service->setImageSizeConfig($this->_teamImageSize);
					$resizeFile = $this->attachment_service->resize($addParam['avatar'],$this->_teamSizeKeys,array(), 'avatar');
					$addParam = array_merge($addParam,$resizeFile);
				}
				
				$addParam = array_merge($addParam,$this->addWhoHasOperated('add'));
				$teamid = $this->team_service->addTeam($addParam, $leaderInfo);
				
				if($teamid > 0){
					//蒋队伍名称 何队伍图片去除
					unset($info);
					$this->assign('feedback',getSuccessTip('添加成功'));
				}else{
					$this->assign('feedback',getErrorTip('添加失败'));
				}
			}
		}
		
		$this->assign('formUrl',admin_site_url('team/add'));
		$this->assign('info',$info);
		$this->display();
		
	}
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$teamId = $this->input->get_post('id');
		
		$sportsCategoryList = $this->team_service->getSportsCategory();
		$this->assign('sportsCategoryList',$sportsCategoryList);
		
		$info = $this->team_service->getTeamInfoWithExtraInfo($teamId);
		
		if(!empty($teamId) && $this->isPostRequest()){
			// 队伍未审核通过时，可以修改队伍类型，审核通过后不可再次修改
			for($i = 0; $i < 1; $i++){
				$updateParam = $this->team_service->teamEditRules();
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('保存失败',$d);
					break;
				}
				
				$avatar = trim($this->input->post('avatar'));
				$avatar_id = trim($this->input->post('avatar_id'));
				
				$this->load->library('Attachment_service');
				
				if($avatar){
					$this->attachment_service->setImageSizeConfig($this->_teamImageSize);
					$resizeFile = $this->attachment_service->resize($avatar,$this->_teamSizeKeys,array(), 'avatar');
					$updateParam = array_merge($updateParam,$resizeFile);
				}
				
				$updateParam = array_merge($updateParam,$this->addWhoHasOperated('edit'));
				
				$row = $this->team_service->updateTeam($teamId,$updateParam);
				
				if($row >= 0){
					$this->jsonOutput('保存成功');
					
					if($row > 0 && $info['basic']['aid'] && $avatar_id && $avatar_id != $info['basic']['aid']){
						$wantDeleteFiles = getImgPathArray($info['basic']['avatar_h'],$this->_teamSizeKeys);
						$this->attachment_service->deleteByFileUrl($wantDeleteFiles);
					}
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
		}else{
			$this->assign('ds',$this->common_district_service->prepareCityData(array(
				'd1' => $info['basic']['d1'],
				'd2' => $info['basic']['d2'],
				'd3' => $info['basic']['d3'],
				'd4' => $info['basic']['d4']
			)));
			
			$this->assign('teamImageConfig',config_item('team_img_size'));
			
			$this->load->library('Sports_service');
			$positionList = $this->sports_service->getMetaByCategoryAndGroup($info['basic']['category_name'],'位置');
			$roleNameList = $this->sports_service->getMetaByCategoryAndGroup($info['basic']['category_name'],'职务');
			
			$this->assign('positionList',$positionList);
			$this->assign('roleNameList',$roleNameList);
		
			$this->assign('info',$info);
			$this->display();
		
		}
	}
	
	
	/**
	 * 
	 */
	public function saveTeamMember(){
		$teamId = $this->input->get_post('id');
		
		if(!empty($teamId) && $this->isPostRequest()){
			$this->form_validation->set_rules('uid','用户ID','required|is_natural_no_zero');
			$this->form_validation->set_rules('nickname','用户昵称','required');
			$this->form_validation->set_rules('displayorder','排序','is_natural|less_than[256]');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('保存失败,请检查数据合法性',array('error'=> $this->form_validation->error_array()));
					break;
				}
				
				$data = array(
					'team_id' => $teamId,
					'uid' => $this->input->post('uid'),
					'nickname' => $this->input->post('nickname') ? $this->input->post('nickname') : '',
					'username' => $this->input->post('username') ? $this->input->post('username') : $this->input->post('nickname'),
					'position' => $this->input->post('position') ? $this->input->post('position') : '',
					'rolename' => $this->input->post('rolename') ? $this->input->post('rolename') : '',
					'num' => $this->input->post('num') ? intval($this->input->post('num')) : 0,
					'displayorder' => $this->input->post('displayorder') ? intval($this->input->post('displayorder')) : 0,
				);
				
				$result = $this->team_service->addMember($data);
				if($result >= 0){
					$this->jsonOutput('保存成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
			
		}else{
			$this->jsonOutput('请求格式不正确');
		}
		
	}
	
	
	/**
	 * 删除队伍成员
	 */
	public function removeTeamMember(){
		$teamId = $this->input->get_post('id');
		
		if(!empty($teamId) && $this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				
				/*
				if(!$this->form_validation->run()){
					break;
				}*/
				
				$sel = $this->input->post('sel');
				if(!is_array($this->input->post('sel'))){
					$sel = (array)$sel;
				}
				
				$result = $this->team_service->removeMember($teamId,$sel);
				if($result >= 0){
					$this->jsonOutput('删除成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
			
			
		}else{
			$this->jsonOutput('请求格式不正确');
		}
		
		
	}
	
	
	
	/**
	 * 队伍审核
	 */
	public function audit(){
		
		$teamId = $this->input->get_post('id');
		
		$info = $this->team_service->getTeamInfo($teamId);
		
		if(!empty($teamId) && $this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$this->form_validation->set_rules('status','审核状态','required|in_list[1,-1]');
				$this->form_validation->set_rules('remark','审核备注','required|min_length[2]');
				
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('保存失败',$d);
					break;
				}
				
				$updateParam = array(
					'status' => $this->input->post('status'),
					'remark' => $this->input->post('remark'),
				);
				
				$row = $this->team_service->auditTeam($teamId,$updateParam,$this->addWhoHasOperated('edit'));
				
				if($row >= 0){
					$this->jsonOutput('保存成功');
				}else{
					$this->jsonOutput($this->db->get_error_info());
				}
			}
		}else{
			$this->jsonOutput('请求格式不正确');
		}
	}
}
