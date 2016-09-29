<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_service extends Base_service {

	private $_msgTemplateModel = null;

	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	public function sendMessage($phoneNumber){
		
		
		
		return true;
		
	}
	
}
