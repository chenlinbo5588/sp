<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 日期更新
 */
class Hp_day extends MY_Controller {


	public function __construct(){
		parent::__construct();
		
		if(!is_cli()){
			exit();
		}
	}
	
	public function index(){
		$param = $this->input->server('argv');
		/*
		if(empty($param[3])){
			exit('table param is not given');
		}
		*/
		$reqTime = $this->input->server('REQUEST_TIME');
		
		$this->load->model('Hp_Counter_Model');
		
		$ts = strtotime("-3 days",$reqTime);
		
		
		$this->Hp_Counter_Model->update(array('date_key' => $ts),array('counter_id' => 1));
		echo "success\n";
	}
	
}
