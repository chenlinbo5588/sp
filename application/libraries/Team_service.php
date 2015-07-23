<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_Service extends Base_Service {

	private $_teamModel;
	private $_teamMemberModel;
	private $_sportsCategoryModel;
	private $_districtStatModel;


	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Team_Model');
		$this->CI->load->model('Team_Member_Model');
		
		$this->CI->load->model('Sports_Category_Model');
		$this->CI->load->model('District_Stat_Model');
		
		
		$this->_teamModel = $this->CI->Team_Model;
		$this->_teamMemberModel = $this->CI->Team_Memebr_Model;
		$this->_sportsCategoryModel = $this->CI->Sports_Category_Model;
		$this->_districtStatModel = $this->CI->District_Stat_Model;
	}
	
	
	public function getAllPagerTeam($search = array(),$page = 1, $pageSize = 10)
	{
		$search['pager'] = array(
			'current_page' => $page,
			'page_size' => $pageSize
		);
		
		$data = $this->_teamModel->getList($search);
		return $data;
	}
	
	
	public function getSportsCategory($condition = array())
	{
		return $this->_sportsCategoryModel->getList($condition);
	}
}
