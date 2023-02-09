<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	private $postJson = array();
	
	public function __construct(){
		parent::__construct();



		if($this->isPostRequest()){
			$this->postJson = json_decode($GLOBALS["HTTP_RAW_POST_DATA"],true);
		}
		
		if(empty($this->postJson)){
			$this->postJson = array();
		}

	}
	
	
	/**
	 * 登陆获取 token
	 */
	public function index(){
		
		echo json_encode(array('tokenStr' => md5(mt_rand())));


	}

	public function getToken(){
		
		if($this->postJson['username'] == 'chen' && $this->postJson['password'] == '123' ){

			$this->jsonOutput(array('code' => 0,'message' => '成功'),array('tokenStr' => md5(mt_rand())));
			
		}else{

			$this->jsonOutput(array('code' => 20,'message' => '用户密码错误,登陆失败'),array('tokenStr' => ''));
		}

	}

}
