<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_Service extends Base_Service {

	protected $_verifyCodeModel;


	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('VerifyCode_Model');
		$this->_verifyCodeModel = $this->CI->VerifyCode_Model;
	}
	
	public function createVerifyCode(){
		
		return '';
	}
}
