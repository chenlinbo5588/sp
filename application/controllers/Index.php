<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 首页
	 */
	public function index()
	{
		$this->display('index/index');
	}
	
	
	public function admin_login(){
		if($this->isPostRequest()){
			
			$this->load->library('Member_Service');
			
			$result = $this->member_service->do_login(array(
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password')
			));
			
			if($result['code'] == SUCCESS_CODE){
				$this->session->set_userdata(array(
					'memberinfo' => $result['memberinfo'],
					'last_activity' => $this->_reqtime
				));
				
				redirect(admin_site_url('stadium/index'));
				
			}else{
				
				$this->assign('message',$result['message']);
				$this->display('index/admin_login');
			}
			
			
		}else{
			$this->display('index/admin_login');
		}
		
	}
	
	
	public function map(){
		$this->display('index/map');
	}


	public function pipe_node(){
		
		$pipeNodeList = array(
		    array('x' => '40621429.246549085','y' => '3339105.6930607622','title' => '节点1'),
			array('x' => '40621427.659045905','y' => '3339096.168041712','title' => '节点2'),
			array('x' => '40621427.659045905','y' => '3339083.4680163125','title' => '节点3'),
			array('x' => '40621427.129878186','y' => '3339073.413829537','title' => '节点4'),
			array('x' => '40621425.542375006','y' => '3339068.6513200123','title' => '节点5'),
			array('x' => '40621443.53407766','y' => '3339060.713804137','title' => '节点6'),
			array('x' => '40621482.163321584','y' => '3339061.242971862','title' => '节点7'),
			array('x' => '40621486.39666338','y' => '3339077.1180036124','title' => '节点8'),
			array('x' => '40621486.39666338','y' => '3339108.8680671123','title' => '节点9'),
			array('x' => '40621486.39666338','y' => '3339130.0347761125','title' => '节点10'),
			array('x' => '40621490.63000518','y' => '3339152.7889882876','title' => '节点11'),
			array('x' => '40621535.080094084','y' => '3339157.0223300876','title' => '节点12'),
			array('x' => '40621556.77597081','y' => '3339155.9639946376','title' => '节点13'),
			
		);
		
		$this->jsonOutput('200',$pipeNodeList);
	}
}
