<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 获取 profile
	 */
	public function profile(){
		
		$this->output->set_cache_header(time() - 60, time() + 10);
		
		header('Custom: '.time());
		header("Content-Type: json; charset=utf-8");
		echo json_encode(array('code' => 0,'message' => '请求成功','data' => array(
			
			'username' => 'chen',
			'password' => '123',
				'deptId' => 1,
				'deptName' => '销售部',
				'roleId' => 1,
				'roleName' => '管理员'
		
		)));
		//file_put_contents('1.txt',time());


	}

	public function testfailed(){

		echo json_encode(array('code' => 100,'message' => '失败啦,数据库连接不上'));

	}

	public function test500(){

		header('HTTP/1.1 500 Internal Server Error');

		echo "service error";


	}

}
