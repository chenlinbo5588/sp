<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->verifySignature()){
			//$this->responseJSON('签名验证失败');
		}
	}
	
	
	/**
	 * 获得队伍列表
	 */
	public function getList(){
		$this->load->library('Team_service');
		$teamList = $this->team_service->getTeamListByCondition();
		
		
		$this->jsonOutput('success',$teamList);
	}
	
	public function index()
	{
		
		
		
		
	}
}
