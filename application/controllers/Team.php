<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 队伍
 */
class Team extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Team_Service');
		$this->load->library('District_Stat_Service');
	}
	
	/**
	 * 队伍 聚合页
	 */
	public function index()
	{
		$availableCity = $this->district_stat_service->getAvailableCity(1);
		//print_r($availableCity);
		$sportsCategoryList = $this->team_service->getSportsCategory();
		$teamList = $this->team_service->getAllPagerTeam();
		
		
		
		$this->seoTitle('队伍');
		$this->assign('cities',$availableCity);
		$this->assign('sportsCategoryList',$sportsCategoryList);
		$this->assign('teamList',$teamList);
		$this->display('team/index');
	}
	
	
	
	/**
	 * 球队搜索页面
	 */
	public function search()
	{
		
		$this->display('team/search');
	}
	
	
	public function invite(){
		
		$param = $this->input->get('param');
		
		if(empty($param)){
			show_error('邀请链接参数不正确');
		}
		
		$string = $this->encrypt->decode($param,$this->config->item('encryption_key'));
		$info = explode("\t",$string);
		
		if($this->input->server('REQUEST_TIME') > $info[2]){
			show_error('邀请链接已经过期');
		}else{
			if(!$this->isLogin()){
				$this->assign('inviter',$info[1]);
				
				//表明是通过队伍邀请链接
				$this->assign('inviteFrom','teamInvite');
				$this->assign('returnUrl',site_url('team/invite/?param='.$param));
				
				$this->display('member/register');
			}else{
				//直接加入
				$this->team_service->joinTeam($info[0],$this->_profile['memberinfo']);
				
				$this->_prepareDetailData($info[0]);
				$this->display('team/detail');	
			}
		}
		
	}
	
	
	/**
	 * 
	 */
	private function _extraTeamInfo($team){
		
		if($team['basic']['joined_type'] == 1 && $this->isLogin()){
			$this->assign('inviteUrl',$this->team_service->generateInviteUrl($team['basic'],$this->_profile['memberinfo']));
		}
		
		
		$inManageMode = false;
		$canManager = $this->team_service->isTeamManager($team,$this->_profile['memberinfo']);
		
		if($canManager){
			if($this->uri->segment(4) == 'manage'){
				$inManageMode = true;
				$this->assign('inManageMode',$inManageMode);
				$this->assign('mangeText','退出管理');
				$this->assign('editUrl',site_url('team/detail/'.$team['basic']['id']));
			}else{
				$this->assign('mangeText','管理队伍');
				$this->assign('editUrl',site_url('team/detail/'.$team['basic']['id'].'/manage/'));
			}
		}
		
		if($inManageMode){
			$this->load->library('Sports_Service');
			$poistionList = $this->sports_service->getMetaByCategoryAndGroup($team['basic']['category_name'],'位置');
			$roleList = $this->sports_service->getMetaByCategoryAndGroup($team['basic']['category_name'],'职务');
			$this->assign('positionList',$poistionList);
			$this->assign('roleList',$roleList);
		}
		
		
		$this->assign('formTarget',$this->uri->uri_string());
		
		$this->assign('canManager',$canManager);
		$this->assign('team',$team);
		
		$this->seoTitle($team['basic']['title']);
	}
	
	
	private function _prepareDetailData($teamid){
		$team = $this->team_service->getTeamInfo($teamid);
		$this->_extraTeamInfo($team);
	}
	
	public function detail(){
		$ar = $this->uri->segment_array();
		if(is_numeric($ar[3])){
			$feedback = '';
			
			if($this->isPostRequest()){
				$this->assign('ispost',true);
				
				for($i = 0; $i < 1; $i++){
					if(!$this->isLogin()){
						
						$this->assign('returnUrl',site_url($this->uri->uri_string()));
						$this->display('member/login');
						
						break;
					}
					
					$team = $this->team_service->getTeamInfo(intval($ar[3]));
					$canManager = $this->team_service->isTeamManager($team,$this->_profile['memberinfo']);
					
					if(!$canManager){
						$feedback = '对不起，您不是队长不能管理';
						break;
					}
				
				
					$memberIds = array();
					foreach($team['members'] as $member){
						$memberIds[] = $member['id'];
					}
					
					foreach(array('username','num','rolename', 'position','kick') as $value){
						$data[$value] = $this->input->post($value);
					};
					
					
					$updateMemeber = array();
					
					$noticeText = $this->input->post('notice_board');
					if(!empty($noticeText)){
						$this->form_validation->set_rules('notice_board','留言','max_length[100]');
					}else{
						$noticeText = '';
					}
						
					foreach($data['username'] as $nk => $value){
						if(in_array($nk,$memberIds)){
							$updateMemeber[$nk] = array(
								'id' => $nk,
								'username' => empty($value) == true ? '' : $value,
								'num' => intval($data['num'][$nk]),
								'rolename' => empty($data['rolename'][$nk]) == true ? '' : $data['rolename'][$nk],
								'position' => empty($data['position'][$nk]) == true ? '' : $data['position'][$nk],
								'is_del' => $data['kick'][$nk] == 'yes' ? 'y' : 'n'
							);
						}
						
						if(!empty($value)){
							$this->form_validation->set_rules('username['.$nk.']','真实名称','min_length[2]|max_length[30]');
						}
						
						if(!empty($data['num'][$nk])){
							$this->form_validation->set_rules('num['.$nk.']','球衣号码','integer|greater_than_equal_to[0]|less_than[100]');
						}
						
					}
					
					//print_r($updateMemeber);
					
					if($this->form_validation->run() === false){
						$errorMsg = $this->form_validation->error_array();
						$this->assign('errorMsg',$errorMsg);
						break;
					}
					
					$flag = $this->team_service->manageTeam(array(
							'id' => $team['basic']['id'],
							'notice_board' => $noticeText
						),
						$updateMemeber
					);
					
					$feedback = "更新成功";
					$team = $this->team_service->getTeamInfo($team['basic']['id']);
				}
				
				
				if($feedback){
					$this->assign('feedback','<div class="warning">'.$feedback.'</div>');
				}
				
				$this->_extraTeamInfo($team);
				$this->display('team/detail');
				
			}else{
				$this->_prepareDetailData(intval($ar[3]));
				$this->display('team/detail');
			}
			
		}else{
			show_error('对不起，服务器无法理解你的请求');
		}
	}
	
	
	/**
	 * 创建队伍
	 */
	public function create_team()
	{
		if($this->isLogin()){
			
			$isCreateOk = false;
			
			for($i = 0; $i < 1; $i++){
				
				$sportsCategoryList = $this->team_service->getSportsCategory();
				$this->assign('sportsCategoryList',$sportsCategoryList);
				$this->seoTitle('创建我的队伍');
				
				if(!$this->_profile['memberinfo']['district_bind']){
					$this->assign('warning','<div class="warning">您尚未设置地区,暂时不能创建队伍, <a href="'.site_url('my/set_city').'">立即设置</a></div>');
					break;
				}
				
				if($this->isPostRequest()){
					$this->load->model('Sports_Category_Model');
					$this->load->model('Team_Model');
					
					$this->form_validation->reset_validation();
					$this->form_validation->set_rules('category_id','队伍类型',array(
								'required',
								'is_natural_no_zero',
								array(
									'category_callable',
									array(
										$this->Sports_Category_Model,'avaiableCategory'
									)
								),
								array(
									'user_categroy_callbale['.$this->_profile['memberinfo']['uid'].']',
									array(
										$this->Team_Model,'userCategoryTeamCount'
									)
								)
							),
							array(
								'category_callable' => '%s无效',
								'user_categroy_callbale' => '同一个分类的队伍最多可以创建两个'
							)
						);
						
					//队伍名称允许相同,因现实情况下确实有可能相同
					//用户如果设置 d4 级的话， 则校验名称重复，如果d4 级没有设置，则不校验
					if($this->_profile['memberinfo']['d4'] > 0){
						
						//获得地区名称
						$this->load->model('Common_District_Model');
						$districtName = $this->Common_District_Model->getById(array(
							'select' => 'name',
							'where' => array('id' => $this->_profile['memberinfo']['d4'])
						));
						
						$this->form_validation->set_rules('title','队伍名称', array(
								'required',
								'max_length[20]',
								array(
									'title_callable['.$this->_profile['memberinfo']['d4'].']',
									array(
										$this->Team_Model,'isTitleNotUsed'
									)
								)
							),
							array(
								'title_callable' => '%s '.$this->input->post('title').'在'.$districtName['name'].'已经存在'
							)
						);
					}else{
						$this->form_validation->set_rules('title','队伍名称', 'required|max_length[20]');
					}
					
					$this->form_validation->set_rules('leader','队长设置','required|in_list[1,2]');
					//$this->form_validation->set_rules('logo_url','队伍合影','required');
					
					$this->form_validation->set_rules('joined_type','入队设置','required|in_list[1]');
					
					
					if($this->form_validation->run() == FALSE){
						break;
					}
					
					$leader = array(
						'uid' => 0,
						'name' => ''
					);
					
					if($this->input->post('leader') == 1){
						$leader['uid'] = $this->_profile['memberinfo']['uid'];
						$leader['name'] = $this->_profile['memberinfo']['nickname'];
					}
					
					/**
					 * 添加队伍
					 */
					$this->load->library('Attachment_Service');
					$fileData = $this->attachment_service->addImageAttachment('logo_url');
					
					$addParam = array(
						'category_id' => $this->input->post('category_id'),
						'title' => $this->input->post('title'),
						'leader_uid' => $leader['uid'],
						'leader_name' => $leader['name'],
						'joined_type' => $this->input->post('joined_type'),
					);
					
					if($fileData){
						$addParam['logo_url'] = $fileData['file_url'];
					}else{
						$num = rand(1,4);
						$addParam['logo_url'] = base_url("img/avator/{$num}.jpg");
					}
					$teamid = $this->team_service->addTeam($addParam,$this->_profile['memberinfo']);
					
					if($teamid){
						$isCreateOk = true;
						$this->_prepareDetailData($teamid);
					}else{
						$this->assign('feedback','<div class="warning">对不请创建队伍失败，请刷新页面重新尝试。</div>');
					}
				}
			}
			
			if($isCreateOk){
				$this->display('team/detail');
			}else{
				$this->display('team/create_team');
			}
			
		}else{
			//创建队伍需要登陆态下
			$this->assign('returnUrl',site_url('team/create_team'));
			$this->display('member/login');
			
		}
	}
	
}
