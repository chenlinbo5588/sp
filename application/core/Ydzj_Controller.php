<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 运动之家 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Ydzj_Controller extends MY_Controller {
	
	public $_profile ;
	
	public function __construct(){
		parent::__construct();
		
		$this->form_validation->set_error_delimiters('<div class="form_error">','</div>');
		
		//print_r($this->session->all_userdata());
		if($this->isLogin()){
			$this->_profile = $this->session->userdata('profile');
			$this->assign('profile',$this->session->userdata('profile'));
		}
		
		if($this->isAdminLogin()){
			$this->assign('manage_profile',$this->session->userdata('manage_profile'));
		}
		
		$this->initEmail();
		
		$this->seo('运动之家','体育运动 爱好 体育场馆查询预定 个人赛事、业余联赛组织', '一个综合性体育运动爱好者聚集地，约朋友出来运动、组织对抗比赛、预约场馆，给您带来一站式体育运动服务，节省您宝贵的时间');
	}
	
	public function isLogin(){
		//print_r($this->session->userdata('memberinfo'));
		//if($this->session->userdata('admin_info') && ($this->_reqtime - $this->session->userdata('last_activity') < 86400)){
		if($this->session->userdata('profile')){
			return true;
		}
		
		return false;
	}
	
	
	
	public function isAdminLogin(){
		if($this->session->userdata('manage_profile')){
			return true;
		}
		
		return false;
		
	}
	
	
	public function getAppTemplateDir(){
		return 'ydzj';
	}
	
	
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



