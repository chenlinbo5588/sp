<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_sender extends MyYdzj_Controller {
	
	
	
	public function __construct(){
		parent::__construct();
		
		
		$this->_breadCrumbs[] = array(
			'title' => '个人中心',
			'url' => strtolower(get_class())
		);
	}
	
	
	public function index(){
		
		
		
		$this->display();
	}
	
	
	
	public function base()
	{
		//print_r($this->session->all_userdata());
		
		/*
		$this->load->library('Common_district_service');
		
		$ds = array();
		for($i = 1; $i <= 4; $i++){
			$ds[] = $this->_profile['basic']['d'.$i];
		}
		
		$ds = array_unique($ds);
		
		$this->assign('userDs',$this->common_district_service->getDistrictByIds($ds));
		*/
		
		$this->seoTitle('个人中心');
		$this->assign('inviteUrl',site_url('member/register?inviter='.$this->_profile['basic']['uid']));
		$avatarImageSize = config_item('avatar_img_size');
		$this->assign('avatarImageSize',$avatarImageSize);
		
		$this->_breadCrumbs[] = array(
			'title' => '基本资料',
			'url' => $this->uri->uri_string
		);
		
		$this->display();
		
		
	}
	
	
	
	
	/**
	 * 发送人设置
	 */
	public function index(){
		
		
		
		$this->display();
	}
	
	
	
	
}
