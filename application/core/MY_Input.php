<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Input extends CI_Input {
	public function __construct(){
		parent::__construct();
		
	}
	
	public function cookie($index = NULL, $xss_clean = NULL)
	{
		$prefix = config_item('cookie_prefix');
		return $this->_fetch_from_array($_COOKIE, $prefix.$index, $xss_clean);
	}
	
	
	
}
