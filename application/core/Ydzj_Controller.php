<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 控制器
 * 
 * 登陆态 和 非登陆态 都可继承
 */
class Ydzj_Controller extends MY_Controller {
	
	public $_profile = array();
	public $_profileKey = '';
	
	
	public function __construct(){
		parent::__construct();
		
		$this->_profileKey = 'profile';
		$this->form_validation->set_error_delimiters('<label class="form_error">','</label>');
		
		$this->_initLogin();
	}
	
	
	protected function _initLibrary(){
		parent::_initLibrary();
		$this->load->config('site');
	}
	
	private function _initLogin(){
		//print_r($this->session->all_userdata());
		$this->_profile = $this->session->userdata($this->_profileKey);
		if(empty($this->_profile)){
			$this->_profile = array();
		}
		
		$lastVisit = $this->session->userdata($this->_lastVisitKey);
		
		if(empty($lastVisit)){
			$this->_lastVisit = $this->_reqtime;
			$this->session->set_userdata(array($this->_lastVisitKey => $this->_lastVisit));
		}else{
			$this->_lastVisit = $lastVisit;
		}
		
		if($this->isLogin()){
			$this->assign($this->_profileKey,$this->session->userdata($this->_profileKey));
		}
		
		
		if(!empty($lastVisit)){
			//前台登陆
			$this->session->set_userdata(array($this->_lastVisitKey => $this->_reqtime));
		}
		
	}
	
	
	public function isLogin(){
		if($this->_profile && ($this->_reqtime - $this->session->userdata($this->_lastVisitKey)) < 86400 * 30){
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
				//$city_id = 176; //默认宁波市;
				$city_id = 0; //默认全国;
			}
		}
		
		$this->input->set_cookie('city',$city_id,2592000);
		
		return $city_id;
	}
	
	/**
	 * 签名生成算法
	 * @param  array  $params API调用的请求参数集合的关联数组，不包含sign参数
	 * @param  string $secret 签名的密钥即获取access token时返回的session secret
	 * @return string 返回参数签名值
	 */
	function getSignature($params, $secret)
	{
		$str = '';  //待签名字符串
		//先将参数以其参数名的字典序升序进行排序
		ksort($params);
		//遍历排序后的参数数组中的每一个key/value对
		foreach ($params as $k => $v) {
		    //为key/value对生成一个key=value格式的字符串，并拼接到待签名字符串后面
		    $str .= "$k=$v";
		}
		//将签名密钥拼接到签名字符串最后面
		$str .= $secret;
		//通过md5算法为签名字符串生成一个md5签名，该签名就是我们要追加的sign参数值
	    return md5($str);
	}
	
	
 	
 
 
	/**
	 * 校验 hash 值
	 */
	public function verifySignature(){
		
		$output = array();
		$sign = '';
		
		$queryString = $this->input->server('QUERY_STRING');
		parse_str($queryString,$output);
		
		if($output['sign']){
			$sign = $output['sign'];
			unset($output['sign']);
			
			if($sign == $this->getSignature($output,config_item('api_secret'))){
				
				return true;
			}else{
				
				return false;
			}
			
		}else{
			return false;
		}
	}
	
}
