<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 运动之家 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Ydzj_Controller extends MY_Controller {
	
	public $_profile = array();
	
	
	public function __construct(){
		parent::__construct();
		
		$this->form_validation->set_error_delimiters('<div class="form_error">','</div>');
		
		
		$this->session->set_userdata(array('lastvisit' => $this->_reqtime));
		$this->_profile = $this->session->userdata('profile');
		//print_r($this->session->all_userdata());
		if(empty($this->_profile)){
			$this->_profile = array();
		}
		
		if($this->isLogin()){
			$this->assign('profile',$this->session->userdata('profile'));
			$this->attachment_service->setUid($this->_profile['basic']['uid']);
		}
		
		//$this->initEmail();
		$this->seo('运动之家','体育运动 体育场馆 查询预定 个人赛事,业余联赛组织', '一个综合性体育运动爱好者聚集地，约朋友出来运动、组织对抗比赛、预约场馆，给您带来一站式体育运动服务，节省您宝贵的时间');
	}
	
	protected function _initLibrary(){
		parent::_initLibrary();
	}
	
	
	
	public function isLogin(){
		if($this->_profile && ($this->_reqtime - $this->session->userdata('lastvisit') < 86400)){
			return true;
		}
		
		return false;
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj';
	}
	
	
	/**
	 * @todo modify when online
	 */
	protected function initEmail(){
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = "smtp.163.com";
		$config['smtp_port'] = 25;
		$config['smtp_user'] = "tdkc_of_cixi";
		$config['smtp_pass'] = 'woaitdkc1234';
		$config['smtp_timeout'] = 10;
		$config['charset'] = config_item('charset');
		
		$this->load->library('email');
		$this->email->initialize($config);
	}
	
	/**
	 * 左快捷导航
	 */
	public function setLeftNavLink($link){
		$this->_smarty->assign('LEFT_BUTTON',$link);
	}
	
	/**
	 * 右快捷导航
	 */
	public function setRightNavLink($link){
		$this->_smarty->assign('RIGHT_BUTTON',$link);
	}
	
	
	public function setTopNavTitle($title,$css = ''){
		$this->_smarty->assign('TOP_NAV_TITLE',$title);
		$this->_smarty->assign('TOP_NAV_CSS',$css);
	}
	
	protected function _getCity(){
		
		$city_id = $this->input->cookie('city');
		if($city_id == NULL){
			if($this->_profile['basic']['district_bind'] != 0){
				$city_id = $this->_profile['basic']['d2'];
			}else{
				$city_id = 176; //默认宁波市;
			}
		}
		
		$this->input->set_cookie('city',$city_id,2592000);
		
		return $city_id;
	}
	
	
	
}


/**
 * 登陆态
 */
class MyYdzj_Controller extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->isLogin()){
			redirect('member/login');
		}
		
	}
	
}



