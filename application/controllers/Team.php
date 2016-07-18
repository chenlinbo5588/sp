<?php
defined('BASEPATH') OR exit('No direct script access allowed');


define('BASKET_BALL','team/index/cate/1');
/**
 * 队伍
 */
class Team extends Ydzj_Controller {
	
	private $_urlParam = null;
	private $_teamid = 0;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Team_service');
		//$this->load->library('District_Stat_service');
		
		$this->_urlParam = $this->uri->segment_array();
		
		
		/*
		if(empty($this->_urlParam[3])){
			redirect(site_url(BASKET_BALL));
		}else{
			$this->_teamid = $this->_urlParam[3];
		}
		*/
	}
	
	
	/**
	 * 队伍 聚合页
	 */
	public function index()
	{
		//$availableCity = $this->district_stat_service->getAvailableCity(1);
		//print_r($availableCity);
		//$sportsCategoryList = $this->team_service->getSportsCategory();
		
		$city_id = $this->_getCity();
		
		if($city_id){
			$cityInfo = $this->team_service->getCityById($city_id);
		}
		
		//print_r($cityInfo);
		
		$title = '篮球队';
		$this->setTopNavTitle($title);
		$this->seoTitle($title);
		
		
		$searchKey = $this->input->get('search_key');
		$condition['order'] = 'id DESC';
		
		if($searchKey){
			$condition['like']['title'] = $searchKey;
		}
		
		if($city_id){
			$condition['where'] = array('d'.$cityInfo['level'] => $city_id);
		}else{
			$cityInfo['id'] = 0;
			$cityInfo['level'] = 0;
			$cityInfo['name'] = '全国';
		}
		
		$teamList = $this->team_service->getAllPagerTeam($condition);
		
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('team/switch_city/upid/'.$city_id).'" title="点击切换城市">'.$cityInfo['name'].'</a>');
		$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url('team/create_team').'">+创建球队</a>');
		
		//$this->assign('sportsCategoryList',$sportsCategoryList);
		if($cityInfo['level'] == 4){
			$this->assign('cityLevel','dname4');
		}else{
			$this->assign('cityLevel','dname'.($cityInfo['level'] + 1));
		}
		
		$this->assign('teamList',$teamList);
		$this->display('team/index');
	}
	
	
	/**
	 * 切换城市
	 */
	public function switch_city(){
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button goback" href="'.site_url(BASKET_BALL).'" title="返回篮球队">篮球队</a>');
		
		$ar = $this->uri->segment_array();
		
		if($this->isPostRequest()){
			$this->input->set_cookie('city',$ar[4],2592000);
			redirect(BASKET_BALL);
		}
		
		$this->load->library('Common_District_service');
		if($ar[4]){
			$city = $ar[4];
			$cityInfo = $this->common_district_service->getDistrictInfoById($city);
		}else{
			$city = $ar[4];
			
			$cityInfo['id'] = $city;
			$cityInfo['name'] = '全国';
		}
		
		$childCity = $this->common_district_service->getDistrictByPid($city);
		
		$title = $cityInfo['name'];
		$this->setTopNavTitle($title);
		$this->seoTitle($title);
		
		$this->assign('cityUrl','team/switch_city/upid/');
		$this->assign('formUrl',site_url('team/switch_city/upid/'.$cityInfo['id']));
		$this->assign('currentCity',$cityInfo);
		$this->assign('cityList',$childCity);
		$this->display('common/switch_city');
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
				$this->assign('param',urlencode($param));
				
				$this->display('member/register');
			}else{
				//直接加入
				$mes = $this->team_service->joinTeam($info[0],$this->_profile['basic']);
				if($mes['new_member']){
					$this->assign('feedback','<div class="success">欢迎 '.urlencode($this->_profile['basic']['nickname']).' 加入</div>');
				}
				
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
			$this->assign('inviteUrl',$this->team_service->generateInviteUrl($team['basic'],$this->_profile['basic']));
		}
		
		
		$inManageMode = false;
		$canManager = $this->team_service->isTeamManager($team,$this->_profile['basic']);
		
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
			$this->load->library('Sports_service');
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
		
		$feedback = '';
		
		$this->_teamid = $this->_urlParam[3];
		
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url(BASKET_BALL).'" title="返回">返回</a>');
		$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url($this->uri->uri_string()).'">刷新</a>');
		
		if($this->isPostRequest()){
			$this->assign('ispost',true);
			
			for($i = 0; $i < 1; $i++){
				if(!$this->isLogin()){
					
					$this->assign('returnUrl',site_url($this->uri->uri_string()));
					$this->display('member/login');
					
					break;
				}
				
				$team = $this->team_service->getTeamInfo(intval($this->_teamid));
				$canManager = $this->team_service->isTeamManager($team,$this->_profile['basic']);
				
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
				$sloganText = $this->input->post('slogan');
				$baseArea = $this->input->post('base_area');
				
				if(!empty($noticeText)){
					$this->form_validation->set_rules('notice_board','队长留言','max_length[100]');
				}else{
					$noticeText = '';
				}
				
				if(!empty($sloganText)){
					$this->form_validation->set_rules('slogan','球队口号','max_length[80]');
				}else{
					$sloganText = '';
				}
				
				if(!empty($baseArea)){
					$this->form_validation->set_rules('base_area','主场场地','max_length[80]');
				}else{
					$baseArea = '';
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
						'slogan' => $sloganText,
						'base_area' => $baseArea,
						'notice_board' => $noticeText,
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
			$this->display();
			
		}else{
			
			$this->_prepareDetailData(intval($this->_teamid));
			$this->display();
		}
		
	}
	
	
	/**
	 * 球队合影
	 */
	public function set_teamavatar(){
		
		$this->_teamid = $this->_urlParam[3];
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('team/detail/'.$this->_teamid).'" title="返回">返回</a>');
		
		$this->setTopNavTitle('修改球队合影');
		
		$feedback = '';
		$this->assign('teamid',$this->_teamid);
		
		if($this->isPostRequest()){
			$setOk = false;
			
			$this->assign('default_avatar',$this->input->post('default_avatar'));
			
			for($i = 0; $i < 1; $i++){
				
				$team = $this->team_service->getTeamInfo(intval($this->_teamid));
				$canManager = $this->team_service->isTeamManager($team,$this->_profile['basic']);
				
				if(!$canManager){
					$feedback = '对不起，您不是队长不能管理';
					break;
				}
				
				$this->load->library('Attachment_service');
				$this->attachment_service->setUid($this->_profile['basic']['uid']);
				
				$fileData = $this->attachment_service->addImageAttachment('avatar');
				
				if(empty($fileData)){
					$this->assign('avatar_error',$this->attachment_service->getErrorMsg());
					break;
				}
				
				//创建缩略图
				$resizeFile = $this->attachment_service->resize($fileData , array('large','big','middle'));
				
				$this->load->library('Team_service');
				$result = $this->team_service->updateTeamInfo(array(
					'aid' => $fileData['id'],
					'avatar_large' => $resizeFile['img_large'],
					'avatar_big' => $resizeFile['img_big'],
					'avatar_middle' => $resizeFile['img_middle']
				),$team['basic']['id']);
				
				$this->attachment_service->deleteByFileUrl($fileData['file_url']);
				if($team['basic']['aid']){
					$this->attachment_service->deleteFiles(array($team['basic']['aid']) ,'all');
				}
				
				$setOk = true;
			}
			
			if($setOk){
				redirect('team/detail/'.$this->_teamid);
			}else{
				
				if($feedback){
					$this->assign('feedback','<div class="warning">'.$feedback.'</div>');
				}
				
				$this->display();
			}
			
		}else{
			$team = $this->team_service->getTeamInfo(intval($this->_teamid),false);
			
			$this->assign('default_avatar',$team['basic']['avatar_big']);
			$this->display();
		}
		
	}
	
	
	
	
	
	/**
	 * 预定比赛
	 */
	public function order_game(){
		if(!$this->isLogin()){
			
			
			
			$this->assign('returnUrl',site_url($this->uri->uri_string()));
			$this->display('member/login');
			
		}else{
			
			/**
			 * 当前用户使用创建篮球队或者是管理者
			 */
			
			$this->display();
		}
		
		
		
		
	}
	
	
	/**
	 * 创建队伍
	 */
	public function create_team()
	{
		if($this->isLogin()){
			
			$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url(BASKET_BALL).'" title="返回">返回</a>');
			
			$isCreateOk = false;
			
			for($i = 0; $i < 1; $i++){
				
				$sportsCategoryList = $this->team_service->getSportsCategory();
				$this->assign('sportsCategoryList',$sportsCategoryList);
				$this->seoTitle('创建我的球队');
				
				if(!$this->_profile['basic']['district_bind']){
					$this->assign('warning','<div class="warning">您尚未设置地区,暂时不能创建球队, <a href="'.site_url('my/set_city').'?returnUrl='.urlencode(site_url('team/create_team')).'">立即设置</a></div>');
					break;
				}
				
				if($this->isPostRequest()){
					//$this->load->model('Sports_Category_Model');
					//$this->load->model('Team_Model');
					
					/**
					 * 首先处理上传图片，并记录，防止其他信息错误，不再消耗流量重传
					 */
					$this->load->library('Attachment_service');
					$this->attachment_service->setUid($this->_profile['basic']['uid']);
					$fileData = $this->attachment_service->addImageAttachment('logo_url');
					
					$team_logo = $this->input->post('team_logo');
					$team_logo_id = $this->input->post('team_log_id');
					if(!$fileData && empty($team_logo)){
						$this->assign('logo_error','<div class="form_error">请上传球队合影照片</div>');
						break;
					}
					
					if($fileData){
						$this->assign('team_log_id',$fileData['id']);
						$this->assign('team_logo',$fileData['file_url']);
						
						//重传了直接删除原先传的那张图片
						if($team_logo_id){
							$this->attachment_service->deleteFiles(array($team_logo_id));
						}
					}else{
						$this->assign('team_log_id',$team_logo_id);
						$this->assign('team_logo',$team_logo);
					}
					
					$this->team_service->teamAddRules($this->_profile['basic']);
					
					if($this->form_validation->run() == FALSE){
						break;
					}
					
					$leader = array(
						'uid' => 0,
						'name' => ''
					);
					
					if($this->input->post('leader') == 1){
						$leader['uid'] = $this->_profile['basic']['uid'];
						$leader['name'] = $this->_profile['basic']['nickname'];
					}
					
					$addParam = array(
						'category_id' => $this->input->post('category_id'),
						'title' => $this->input->post('title'),
						'leader_uid' => $leader['uid'],
						'leader_name' => $leader['name'],
						'joined_type' => $this->input->post('joined_type'),
					);
					
					if($fileData){
						$addParam['aid'] = $fileData['id'];
						$addParam['avatar'] = $fileData['file_url'];
					}else if($team_logo){
						$addParam['aid'] = $team_logo_id;
						$addParam['avatar'] = $team_logo;
					}
					
					//创建缩略图
					$resizeFile = $this->attachment_service->resize(array('file_url' => $addParam['avatar']) , array('large','big','middle'));
					$addParam['avatar_large'] = $resizeFile['img_large'];
					$addParam['avatar_big'] = $resizeFile['img_big'];
					$addParam['avatar_middle'] = $resizeFile['img_middle'];
					//$addParam['avatar_small'] = $resizeFile['img_small'];
					
					$addParam['category_name'] = $sportsCategoryList[$this->input->post('category_id')]['name'];
					if(empty($addParam['category_name'])){
						$addParam['category_name'] = '';
					}
					
					
					$teamid = $this->team_service->addTeam($addParam,$this->_profile['basic']);
					$this->attachment_service->deleteByFileUrl($addParam['avatar']);
					if($teamid){
						$isCreateOk = true;
					}else{
						$this->assign('feedback','<div class="warning">创建球队失败，请刷新页面重新尝试。</div>');
					}
				}
			}
			
			if($isCreateOk){
				redirect('team/detail/'.$teamid);
			}else{
				$this->display();
			}
			
		}else{
			//创建球队需要登陆态下
			$this->assign('returnUrl',site_url('team/create_team'));
			
			$this->seoTitle('登陆');
			$this->display('member/login');
			
		}
	}
	
}
