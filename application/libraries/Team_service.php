<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_Service extends Base_Service {

	private $_teamModel;
	private $_teamMemberModel;


	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Team_Model');
		$this->CI->load->model('Team_Memebr_Model');
		
		$this->_teamModel = $this->CI->Team_Model;
		$this->_teamMemberModel = $this->CI->Team_Memebr_Model;
	}
	
}
