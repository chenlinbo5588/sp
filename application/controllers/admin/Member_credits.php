<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_credits extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
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
		
		$search_map['search_field'] = array('mobile' => '账号','email' => '电子邮箱','username' => '真实姓名');
		
		/*
		$search_map['activity_sort'] = array(
				'-30 minutes' => '最近30分钟内活跃','-2 hours' => '最近2小时内活跃',
				'-6 hours' => '最近6小时内活跃', '-12 hours' => '最近12小时内活跃',  
				'-1 days' => '最近1天内活跃' , '-3 days' => '最近3天内活跃' , 
				'-7 days' => '最近7天内活跃' ,'-15 days' => '最近15天内活跃');
		*/
		
		$search_map['register_channel'] = array('1' => '后台管理中心','2' => '手机网页版' ,'3' => 'PC网页版','4' => 'iOS客户端','5' => 'Android客户端');
		$search_map['register_sort'] = array('uid DESC' => '按时间倒序','uid ASC' => '按时间顺序');
		$search_map['member_state'] = array('avatar_status@0' => '待验证头像','district_bind@0' => '未设置地区','freeze@1' => '已禁止登录');
		
		
		$search['search_field_name'] = $this->input->get_post('search_field_name');
		$search['search_field_value'] = $this->input->get_post('search_field_value');
		//$search['activity_sort'] = $this->input->get_post('activity_sort');
		$search['register_channel'] = $this->input->get_post('register_channel');
		$search['register_sort'] = $this->input->get_post('register_sort');
		$search['member_state'] = $this->input->get_post('member_state');
		
		if(!empty($search['search_field_value']) && in_array($search['search_field_name'], array_keys($search_map['search_field']))){
			$condition['like'][$search['search_field_name']] = $search['search_field_value'];
		}
		
		/*
		if(in_array($search['activity_sort'], array_keys($search_map['activity_sort']))){
			$condition['where']['last_activity >='] = strtotime($search['activity_sort']);
		}
		*/
		if(in_array($search['register_channel'], array_keys($search_map['register_channel']))){
			$condition['where']['channel'] = $search['register_channel'];
		}
		
		if(in_array($search['register_sort'], array_keys($search_map['register_sort']))){
			$condition['order'] = $search['register_sort'];
		}
		
		if(in_array($search['member_state'], array_keys($search_map['member_state']))){
			$statArray = explode('@',$search['member_state']);
			$condition['where'][$statArray[0]] = $statArray[1];
		}
		
		$list = $this->member_service->getListByCondition($condition);
		
		//邀请人和地区名称
		$dqList = array();
		$inviterList = array();
		foreach($list['data'] as $member){
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
			
			if($member['inviter']){
				$inviterList[] = $member['inviter'];
			}
		}
		
		if($inviterList){
			$this->assign('inviterInfo',$this->member_service->getListByCondition(array(
				'select' => 'uid,mobile,avatar_m,avatar_s',
				'where_in' => array(
					array('key' => 'uid' ,'value' => $inviterList )
				)
			)));
		}
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		$this->assign('search_map',$search_map);
		$this->assign('memberDs',$this->common_district_service->getDistrictByIds($dqList));
		//$this->assign('d1',$this->common_district_service->getDistrictByPid(0));
		
		$this->display('member/index');
	}
	
}
