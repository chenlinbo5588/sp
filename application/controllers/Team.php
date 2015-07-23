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
		$availableCity = $this->district_stat_service->getAvailableCity('篮球');
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
	
	/**
	 * 创建队伍
	 */
	public function create_team()
	{
		if($this->isLogin()){
			
			$sportsCategoryList = $this->team_service->getSportsCategory();
			$this->assign('sportsCategoryList',$sportsCategoryList);
			
			$this->seoTitle('创建我的队伍');
			
			if($this->isPostRequest()){
				$this->load->model('Sports_Category_Model');
				
				$this->form_validation->reset_validation();
				$this->form_validation->set_rules('category_id','队伍类型',array(
							'required',
							'is_natural_no_zero',
							array(
								'category_callable',
								array(
									$this->Sports_Category_Model,'avaiableCategory'
								)
							)
						),
						array(
							'category_callable' => '%s无效'
						)
					);
				
				//队伍名称允许相同
				$this->form_validation->set_rules('title','队伍名称', 'required|max_length[20]');
				
				$this->form_validation->set_rules('leader','队长设置','required|in_list[1,2]]');
				$this->form_validation->set_rules('joined_type','入队设置','required|in_list[0,1,2]]');
				
				
				if($this->form_validation->run() !== FALSE){
					/*
					$this->load->library('Register_Service');
					
					$todayRegistered = $this->register_service->getIpLimit($this->input->ip_address());
					
					if($todayRegistered < 3){
						$result = $this->register_service->createMemberByEmail(array(
							'email' => $this->input->post('email'),
							'nickname' => $this->input->post('nickname'),
							'password' => $this->input->post('psw'),
							'regip' => $this->input->ip_address()
						));
					
					
						if($result['code'] == 'success'){
							
							//@todo 发送验证邮件
							
							
							redirect('my/set_city');
						}else{
							
							$this->assign('feedback',$result['message']);
						}
					}else{
						$this->assign('feedback','很抱歉，您今日注册数量已经用完');
					}
					*/
				}
			}
			
			
			$this->display('team/create_team');
		}else{
			redirect(site_url('member/login/?returnUrl='.urlencode(site_url('team/create_team'))));
		}
	}
	
}
