<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Input extends CI_Input {
	private $_inApp = false;

	public function __construct(){
		parent::__construct();
		
		if($this->server('HTTP_APP_SP')){
			$this->_inApp = true;
		}
	}
	
}
