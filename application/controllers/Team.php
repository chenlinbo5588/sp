<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 队伍
 */
class Team extends Ydzj_Controller {
	
	
	private $_urlParam = null;
	private $_teamid = 0;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Team_Service');
		//$this->load->library('District_Stat_Service');
		
		$this->_urlParam = $this->uri->segment_array();
		
		
		/*
		if(empty($this->_urlParam[3])){
			redirect(site_url('team/index/cate/1'));
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
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button goback" href="'.site_url('team/index/cate/1').'" title="返回篮球队">篮球队</a>');
		
		
		$this->load->library('Common_District_Service');
		$ar = $this->uri->segment_array();
		
		if($this->isPostRequest()){
			$this->input->set_cookie('city',$ar[4],2592000);
			redirect('team/index/cate/1');
		}
		
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
				$mes = $this->team_service->joinTeam($info[0],$this->_profile['memberinfo']);
				if($mes['new_member']){
					$this->assign('feedback','<div class="success">欢迎 '.urlencode($this->_profile['memberinfo']['nickname']).' 加入</div>');
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
		
		$feedback = '';
		
		$this->_teamid = $this->_urlParam[3];
		
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('team/index/cate/1/').'" title="返回">返回</a>');
		//$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url($this->uri->uri_string()).'">+收藏</a>');
		
		if($this->isPostRequest()){
			$this->assign('ispost',true);
			
			for($i = 0; $i < 1; $i++){
				if(!$this->isLogin()){
					
					$this->assign('returnUrl',site_url($this->uri->uri_string()));
					$this->display('member/login');
					
					break;
				}
				
				$team = $this->team_service->getTeamInfo(intval($this->_teamid));
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
			$this->display('team/detail');
			
		}else{
			
			$this->_prepareDetailData(intval($this->_teamid));
			$this->display('team/detail');
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
				$canManager = $this->team_service->isTeamManager($team,$this->_profile['memberinfo']);
				
				if(!$canManager){
					$feedback = '对不起，您不是队长不能管理';
					break;
				}
				
				$this->load->library('Attachment_Service');
				$fileData = $this->attachment_service->addImageAttachment('avatar',array(
					'min_width' => 800,
					'min_height' => 800
				));
				
				if(!empty($fileData['img_big'])){
					$newAvatar = $fileData['img_big'];
				}else{
					$newAvatar = $this->input->post('new_avatar');
				}
				
				$this->assign('new_avatar',$newAvatar);
				
				if($newAvatar == ''){
					$this->assign('avatar_error',$this->attachment_service->getErrorMsg());
					break;
				}
				
				$this->load->library('Team_Service');
				$result = $this->team_service->updateTeamInfo(array(
					'avatar' => $fileData['file_url'],
					'avatar_large' => $fileData['img_large'],
					'avatar_big' => $fileData['img_big'],
					'avatar_middle' => $fileData['img_middle'],
					'avatar_small' => $fileData['img_small']
				),$team['basic']['id']);
				
				$setOk = true;
			}
			
			
			if($setOk){
				redirect('team/detail/'.$this->_teamid);
			}else{
				
				if($feedback){
					$this->assign('feedback','<div class="warning">'.$feedback.'</div>');
				}
				
				$this->display('team/set_teamavatar');
			}
			
		}else{
			
			$this->assign('default_avatar',$this->_profile['memberinfo']['avatar_big']);
			$this->display('team/set_teamavatar');
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
			
			
			$this->display('team/order_game');
		}
		
		
		
		
	}
	
	
	/**
	 * 创建队伍
	 */
	public function create_team()
	{
		if($this->isLogin()){
			
			$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('team/index/cate/1/').'" title="返回">返回</a>');
			
			$isCreateOk = false;
			
			for($i = 0; $i < 1; $i++){
				
				$sportsCategoryList = $this->team_service->getSportsCategory();
				$this->assign('sportsCategoryList',$sportsCategoryList);
				$this->seoTitle('创建我的队伍');
				
				if(!$this->_profile['memberinfo']['district_bind']){
					$this->assign('warning','<div class="warning">您尚未设置地区,暂时不能创建队伍, <a href="'.site_url('my/set_city').'?returnUrl='.urlencode(site_url('team/create_team')).'">立即设置</a></div>');
					break;
				}
				
				if($this->isPostRequest()){
					$this->load->model('Sports_Category_Model');
					$this->load->model('Team_Model');
					
					/**
					 * 首先处理上传图片，并记录，防止其他信息错误，不再消耗流量重传
					 */
					$this->load->library('Attachment_Service');
					$fileData = $this->attachment_service->addImageAttachment('logo_url');
					
					$team_logo = $this->input->post('team_logo');
					if(!$fileData && empty($team_logo)){
						$this->assign('logo_error',$this->attachment_service->getErrorMsg());
						break;
					}
					
					if($fileData){
						$this->assign('team_logo',$fileData['file_url']);
						$this->assign('team_logo_url',$fileData['img_big']);
					}else{
						$this->assign('team_logo',$team_logo);
						$this->assign('team_logo_url',$this->input->post('team_logo_url'));
					}
					
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
								'user_categroy_callbale' => '同一个类型的队伍最多创建三个'
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
								'max_length[4]',
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
					
					$addParam = array(
						'category_id' => $this->input->post('category_id'),
						'title' => $this->input->post('title'),
						'leader_uid' => $leader['uid'],
						'leader_name' => $leader['name'],
						'joined_type' => $this->input->post('joined_type'),
					);
					
					if($fileData){
						$addParam['avatar'] = $fileData['file_url'];
						$addParam['avatar_large'] = $fileData['img_large'];
						$addParam['avatar_big'] = $fileData['img_big'];
						$addParam['avatar_middle'] = $fileData['img_middle'];
						$addParam['avatar_small'] = $fileData['img_small'];
					}else if($team_logo){
						
						$addParam['avatar'] = $team_logo;
						$dotPos = strrpos($team_logo,'.');
						$fileName = substr($team_logo,0,$dotPos);
						$suffixName = substr($team_logo,$dotPos);
						
						$addParam['avatar_large'] = $fileName.'@large'.$suffixName;
						$addParam['avatar_big'] = $fileName.'@big'.$suffixName;
						$addParam['avatar_middle'] = $fileName.'@middle'.$suffixName;
						$addParam['avatar_small'] = $fileName.'@small'.$suffixName;
					}
					
					foreach($sportsCategoryList as $cate){
		            	if($cate['id'] == $this->input->post('category_id')){
		            		$addParam['category_name'] = $cate['name'];
		            		break;
		            	}
		            }
					
					$teamid = $this->team_service->addTeam($addParam,$this->_profile['memberinfo']);
					
					if($teamid){
						$isCreateOk = true;
						//$this->_prepareDetailData($teamid);
					}else{
						$this->assign('feedback','<div class="warning">创建队伍失败，请刷新页面重新尝试。</div>');
					}
				}
			}
			
			if($isCreateOk){
				redirect('team/detail/'.$teamid);
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
