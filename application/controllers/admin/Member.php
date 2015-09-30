<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->load->library('Member_Service');
		$list = $this->Member_Model->getList(array(
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $this->input->get('page') ? $this->input->get('page') : 1
			)
		));
		
		
		$this->assign('list',$list);
		$this->display('member/index');
	}
	
	
	public function add(){
		
		
		
		$this->display('member/add');
		
	}
	
}
