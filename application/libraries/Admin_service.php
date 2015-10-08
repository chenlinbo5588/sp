<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Service extends Base_Service {
	protected $_adminUserModel;

	public function __construct(){
		
		parent::__construct();
		$this->CI->load->model('Adminuser_Model');
		
		$this->_adminUserModel = $this->CI->Adminuser_Model;
	}
    
    /**
	 * 更新用户信息
	 */
	public function updateUserInfo($data,$uid){
		return $this->_adminUserModel->update($data,array('uid' => $uid));
	}
	
    public function do_adminlogin($param){
		
		$result = $this->formatArrayReturn();
		$result['message'] = '登陆失败';
		
		$userInfo = $this->_adminUserModel->getFirstByKey($param['email'],'email');
		
		if(!empty($userInfo)){
			if($userInfo['password'] == $param['password']){
				unset($userInfo['password']);
				
				if($userInfo['freeze'] == 'Y'){
					$result['message'] = '您的账号已被冻结,请联系网站客服人员';
				}else{
					$result = $this->successRetun(array('basic' => $userInfo));
				}
				
			}else{
				$result['message'] = '密码错误';
			}
		}else{
			$result['message'] = '用户不存在';
		}
		
		return $result;
	}
}
