<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_service extends Base_service {

	private $_teamModel;
	private $_teamMemberModel;
	
	private $_sportsCategoryModel;
	private $_districtStatModel;


	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Team_Model');
		self::$CI->load->model('Team_Member_Model');
		
		self::$CI->load->model('Sports_Category_Model');
		self::$CI->load->model('District_Stat_Model');
		
		
		$this->_teamModel = self::$CI->Team_Model;
		$this->_teamMemberModel = self::$CI->Team_Member_Model;
		$this->_sportsCategoryModel = self::$CI->Sports_Category_Model;
		$this->_districtStatModel = self::$CI->District_Stat_Model;
	}
	
	
	/**
	 * 更新球队信息
	 */
	public function updateTeamInfo($data,$id){
		return $this->_teamModel->update($data,array('id' => $id));
	}
	
	public function getAllPagerTeam($search = array(),$page = 1, $pageSize = 10)
	{
		$search['pager'] = array(
			'current_page' => $page,
			'page_size' => $pageSize
		);
		
		$data = $this->_teamModel->getList($search);
		return $data;
	}
	
	
	
	
	/**
	 * 获得活动分类
	 */
	public function getSportsCategory($condition = array())
	{
		$condition['where']['status'] = 1;
		
		return $this->toEasyUseArray($this->_sportsCategoryModel->getList($condition));
	}
	
	/**
	 * 根据条件获得队伍
	 */
	public function getTeamListByCondition($condition = array()){
		return $this->toEasyUseArray($this->_teamModel->getList($condition));
	}
	
	/**
	 * 
	 */
	public function getTeamInfo($teamid ,$withMemeber = true){
		
		$team['basic'] = $this->_teamModel->getById(array(
			'where' => array('id' => $teamid)
		));
		
		//$team['members'] = array();
		
		if($withMemeber){
			$team['members'] = $this->_teamMemberModel->getList(array(
				'where' => array('team_id' => $teamid),
				'order' => 'displayorder ASC'
			));
		}
		
		return $team;
	}
	
	
	/**
	 * 
	 */
	public function getTeamInfoWithExtraInfo($teamid,$moreFlag = array()){
		
		$flag = array('members' => true, 'audit_log' => true, 'games' => true);
		$flag = array_merge($flag,$moreFlag);
		
		$team['basic'] = $this->_teamModel->getById(array(
			'where' => array('id' => $teamid)
		));
		
		
		if($flag['members']){
			$team['members'] = $this->_teamMemberModel->getList(array(
				'where' => array('team_id' => $teamid),
				'order' => 'displayorder ASC'
			));
		}
		
		if($flag['audit_log']){
			$team['audit_log'] = self::$auditLogModel->getList(array(
				'where' => array('ref_id' => $teamid),
				'order' => 'id DESC',
				'limit' => 6
			));
		}
		
		/*
		if($flag['games']){
			$team['games'] = self::$auditLogModel->getList(array(
				'where' => array('ref_id' => $teamid),
				'order' => 'id DESC'
			));
		}
		*/
		
		return $team;
		
	}
	
	
	/**
	 * 生成邀请链接
	 * 加密参数 队伍和用户信息到里面
	 */
	public function generateInviteUrl($teamInfo,$userInfo){
		//teamid,uid,有效期
		// 24小时内有效
		$expire = time() + 3600 * 24;
		
		//teamid + uid + timestamp
		$text = "{$teamInfo['id']}\t{$userInfo['uid']}\t{$expire}";
		$encrypted_string = self::$CI->encrypt->encode($text, config_item('encryption_key'));
		
		return site_url('team/invite/?param='.urlencode($encrypted_string));
	}
	
	/**
	 * 用户加入队伍
	 */
	public function joinTeam($teamid,$userinfo){
		
		$message = array(
			'join_in' => false,
			'new_member' => false
		);
		
		
		$notJoined = $this->_teamMemberModel->getCount(array(
			'where' => array(
				'team_id' => $teamid,
				'uid' => $userinfo['uid']
			)
		));
		
		
		if($notJoined == 0){
			$message['new_member'] = true;
			
			$id = $this->_teamMemberModel->_add(array(
				'uid' => $userinfo['uid'],
				'nickname' => $userinfo['nickname'],
				'username' => $userinfo['username'],
				'avatar_middle' => $userinfo['avatar_middle'],
				'team_id' => $teamid
			));
			
			if($id > 0){
				$this->_teamModel->increseOrDecrease(array(
					array('key' => 'current_num','value' => 'current_num+1')
				),array('id' => $teamid));
			}
		}
		
		$message['join_in'] = true;
		return $message;
		
		
	}
	
	/**
	 * 公共规则
	 */
	public function commonTeamRules(){
		$param['title'] = self::$CI->input->post('title');
		$param['leader'] = self::$CI->input->post('leader');
		$param['joined_type'] = self::$CI->input->post('joined_type');
		$param['slogan'] = self::$CI->input->post('slogan');
		$param['base_area'] = self::$CI->input->post('base_area');
		$param['notice_board'] = self::$CI->input->post('notice_board');
		$param['status'] = self::$CI->input->post('status');
		
		foreach($param as $pk => $pv){
			$param[$pk] = trim($pv) ? trim($pv) : '';
		}
		
		$param['d1'] = intval(self::$CI->input->post('d1'));
		$param['d2'] = intval(self::$CI->input->post('d2'));
		$param['d3'] = intval(self::$CI->input->post('d3'));
		$param['d4'] = intval(self::$CI->input->post('d4'));
		
		self::$form_validation->set_rules('title','球队名称', 'required|max_length[30]');
		
		if($param['leader']){
			self::$form_validation->set_rules('leader','队长设置','required|in_list[1,2]');
		}else{
			$param['leader'] = 1;
		}
		
		self::$form_validation->set_rules('joined_type','加入队伍设置','required|in_list[1]');
		
		if($param['slogan']){
			self::$form_validation->set_rules('slogan','建队口号', 'required|max_length[40]');
		}
		
		if($param['base_area']){
			self::$form_validation->set_rules('base_area','活动根据地', 'required|max_length[40]');
		}
		
		if($param['notice_board']){
			self::$form_validation->set_rules('notice_board','队伍公告', 'required|max_length[50]');
		}
		
		if($param['status']){
			self::$form_validation->set_rules('status','审核状态', 'required|in_list[1,-1]');
			self::$form_validation->set_rules('audit_remark','审核备注', 'required|min_length[2]');
			
		}else{
			unset($param['status']);
		}
		
		return $param;
	}
	
	
	/**
	 * 队伍分类规则
	 */
	public function getCategoryRule(){
		$rule = array();
		
		$rule['category_id'] = array(
			'field' => 'category_id',
			'label' => '队伍类型',
			'rules' => array(
				'required',
				array(
					'category_callable[]',
					array(
						$this->_sportsCategoryModel,'avaiableCategory'
					)
				)
			),
			'errors' => array(
				'category_callable' => '%s 无效'
			)
		);
		
		return $rule;
	}
	
	/**
	 * 队伍编辑验证规则
	 */
	public function teamEditRules(){
		$rules = $this->getCategoryRule();
		
		foreach($rules as $rule){
			self::$form_validation->set_rules($rule['field'],$rule['label'],$rule['rules'],$rule['errors']);
		}
		
		
		$param = $this->commonTeamRules();
		$param['category_id'] = self::$CI->input->post('category_id');
		
		
		return $param;
	}
	
	
	/**
	 * 增加队伍验证规则
	 *  
	 * @param array  $userInfo  登陆用户信息
	 * 
	 */
	public function teamAddRules($userInfo = array()){
		self::$form_validation->reset_validation();
		
		$this->commonTeamRules();
		$rules = $this->getCategoryRule();
		
		
		if($userInfo){
			//再加一条规则 用户建队伍数量限制
			$rules['category_id']['rules'][] = array(
					'user_categroy_callbale['.$userInfo['uid'].']',
					array(
						$this->_teamModel,'userCategoryTeamCount'
					)
				);
				
			$rules['category_id']['errors']['user_categroy_callbale'] = '对不起,同一个类型的球队最多创建三个';
		}
		
		
		foreach($rules as $rule){
			self::$form_validation->set_rules($rule['field'],$rule['label'],$rule['rules'],$rule['errors']);
		}
		
		/*
		 * 暂时不校验重复性  现实允许有重名产生
		//队伍名称允许相同,因现实情况下确实有可能相同
		//用户如果设置 d4 级的话， 则校验名称重复，如果d4 级没有设置，则不校验
		
		if($userInfo['d4'] > 0){
			//获得地区名称
			$districtName = self::$districtModel->getById(array(
				'select' => 'name',
				'where' => array('id' => $userInfo['d4'])
			));
			
			self::$form_validation->set_rules('title','球队名称', array(
					'required',
					'max_length[30]',
					array(
						'title_callable['.$userInfo['d4'].']',
						array(
							$this->_teamModel,'isTitleNotUsed'
						)
					)
				),
				array(
					'title_callable' => '%s '.self::$CI->input->post('title').'在'.$districtName['name'].'已经存在'
				)
			);
		}else{
			self::$form_validation->set_rules('title','球队名称', 'required|max_length[30]');
		}
		*/
		
	}
	
	
	public function isTeamManager($team,$userInfo){
		
		$canManager = false;
		
		$teamOwnerUid = $team['basic']['leader_uid'];
		if(!$teamOwnerUid){
			$teamOwnerUid = $team['basic']['owner_uid'];
		}
		
		if($userInfo['uid'] == $teamOwnerUid){
			$canManager = true;
		}
		
		return $canManager;
	}
	
	/**
	 * 审核队伍
	 */
	public function auditTeam($teamId , $param ,$user){
		
		$this->_teamModel->update(array_merge($param,$user),array('id' => $teamId));
		
		$addInfo = array(
			'mod' => 'team',
			'ref_id' => $teamId,
			'remark' => $param['remark'],
			'add_uid' => $user['edit_uid'],
			'add_username' => $user['edit_username'],
		);
		
		$rows = self::$auditLogModel->_add($addInfo);
	
		return true;
	}
	
	/**
	 * 添加队伍成员
	 */
	public function addMember($memberInfo){
		$member = $this->_teamMemberModel->getList(array(
			'where' => array(
				'team_id' => $memberInfo['team_id'],
				'uid' => $memberInfo['uid']
			)
		));
		
		$memberInfo2 = self::$memberModel->getFirstByKey($memberInfo['uid'],'uid');
		$memberInfo['aid'] = $memberInfo2['aid'];
		$memberInfo['avatar_m'] = $memberInfo2['avatar_m'];
		$memberInfo['avatar_s'] = $memberInfo2['avatar_s'];
		
		if($member[0]){
			//do update
			return $this->_teamMemberModel->update($memberInfo,array('id' => $member[0]['id']));
		}else{
			//do insert
			return $this->_teamMemberModel->_add($memberInfo);
		}
		
	}
	
	
	/**
	 * 
	 */
	public function removeMember($teamId,$ids){
		
		return $this->_teamMemberModel->deleteByCondition(array(
			'where' => array(
				'team_id' => $teamId
			),
			'where_in' => array(
				array('key' => 'uid' ,'value' => $ids)
			)
		));
	}
	
	
	
	/**
	 * 添加队伍
	 */
	public function addTeam($param,$creatorInfo){
		
		// 启动事务
		self::$dbInstance->trans_start();
		/*
		$cateName = $this->_sportsCategoryModel->getById(array(
			'select' => 'name',
			'where' => array('id' => $param['category_id'])
		));
		$param['category_name'] = $cateName['name'];
		*/
		
		$param['d1'] = $creatorInfo['d1'];
		$param['d2'] = $creatorInfo['d2'];
		$param['d3'] = $creatorInfo['d3'];
		$param['d4'] = $creatorInfo['d4'];
		
		$dIDs = array($param['d1'],$param['d2'],$param['d3'],$param['d4']);
		
		$dsWithName = $this->getDistrictNameByIds($dIDs);
		$param = array_merge($param,$dsWithName);
		
		$teamid = $this->_teamModel->_add($param);
		
		if (self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
		
		$memberid = $this->_teamMemberModel->_add(array(
			'uid' => $creatorInfo['uid'],
			//@todo nickname 同步问题  还没有解决 
			'nickname' => $creatorInfo['nickname'],
			'username' => $creatorInfo['username'],
			'aid' => $creatorInfo['aid'],
			'avatar_m' => $creatorInfo['avatar_m'],
			'avatar_s' => $creatorInfo['avatar_s'],
			'team_id' => $teamid,
			'rolename' => $param['leader_uid'] == 0 ? '队员' : '队长'
		));
		
		
		if (self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
		
		
		//地区统计数据
		$stat_id = md5($param['category_id'].$creatorInfo['d1'].$creatorInfo['d2'].$creatorInfo['d3'].$creatorInfo['d4']);
		$cnt = $this->_districtStatModel->getCount(array(
			'where' => array(
				'id' => $stat_id
			)
		));
		
		if($cnt > 0){
			//update
			$this->_districtStatModel->increseOrDecrease(array(
				array('key' => 'teams' ,'value' => 'teams + 1')
			),array('id' => $stat_id));
		}else{
			//add
			
			/*
			if($param['d4'] > 0){
				$district = self::$districtModel->getFirstByKey($param['d4']);
			}else{
				$district = self::$districtModel->getFirstByKey($param['d3']);
			}
			*/
			
			$this->_districtStatModel->_add(array(
				'id' => $stat_id,
				'category_id' => $param['category_id'],
				'd1' => $creatorInfo['d1'],
				'd2' => $creatorInfo['d2'],
				'd3' => $creatorInfo['d3'],
				'd4' => $creatorInfo['d4'],
				'teams' => 1
			));
		}
		
		if (self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			
			return false;
		}
		
		self::$dbInstance->trans_commit();
		self::$dbInstance->trans_off();
		
		return $teamid;
	}
	
	
	/**
	 * 更新队伍
	 */
	public function updateTeam($teamId , $updateParam){
		$dsWithName = $this->getDistrictNameByIds(array( $updateParam['d1'] ,$updateParam['d2'],$updateParam['d3'],$updateParam['d4']));
		$updateParam = array_merge($updateParam,$dsWithName);
		
		return $this->_teamModel->update($updateParam,array('id' => $teamId));
	}
	
	/**
	 * 更新队伍 和队员信息
	 */
    public function manageTeam($team,$members){
    	
    	self::$dbInstance->trans_start();
    	
    	$teamid = $team['id'];
    	
    	//unset($team['id']);
    	$this->_teamModel->update($team,array('id' => $team['id']));
    	
    	if (self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
    	
    	$this->_teamMemberModel->batchUpdate($members);
    	
    	if (self::$dbInstance->trans_status() === FALSE){
			self::$dbInstance->trans_rollback();
			return false;
		}
    	
    	self::$dbInstance->trans_commit();
		self::$dbInstance->trans_off();
    	
    	return true;
    	
    }
    
}
