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
		$this->load->library(array('Team_service','Member_service','Common_District_service'));
		
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
				$fileData = $this->attachment_service->addImageAttachment('team_avatar',array(),FROM_BACKGROUND,'team');
				
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
					$resizeFile = $this->attachment_service->resize(array('file_url' => $addParam['avatar']) , $this->_teamSizeKeys);
					foreach($this->_teamSizeKeys as $sizeKey){
						$addParam['avatar_'.$sizeKey] = $resizeFile['img_'.$sizeKey];
					}
				}
				
				//$addParam['avatar_small'] = $resizeFile['img_small'];
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
		
		$info = $this->team_service->getTeamInfo($teamId);
		
		//print_r($urlParam);
		if(!empty($teamId) && $this->isPostRequest()){
			
		}
		
		
		$this->assign('teamImageConfig',config_item('team_img_size'));
		
		$this->assign('formUrl',admin_site_url('team/edit?id=').$teamId);
		$this->assign('info',$info);
		$this->display();
	}
	
	
	
}
