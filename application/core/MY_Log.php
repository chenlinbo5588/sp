<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Log extends CI_Log {
	
	public function __construct(){
		parent::__construct();
	}
	
	/*
	public function write_log($level, $msg){
		
		if ($this->_enabled === FALSE)
		{
			return FALSE;
		}
		
		$level = strtoupper($level);

		if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold))
			&& ! isset($this->_threshold_array[$this->_levels[$level]]))
		{
			return FALSE;
		}
		
		
		$CI = &get_instance();
		
		$CI->load->model('Log_Model');
		$CI->Log_Model->_add();
		
	}
	*/
}
