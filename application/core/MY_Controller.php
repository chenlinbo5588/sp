<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model('Member_Model');
		$this->load->library('Base_Service');
	}
	
	public function isGetRequest(){
        return 'get' == strtolower($_SERVER['REQUEST_METHOD']) ? 1 : 0;
    }
    public function isPostRequest(){
        return 'post' == strtolower($_SERVER['REQUEST_METHOD']) ? 1 : 0;
    }
    
    
    public function jsonOutput($message , $data){
    	header("Content-Type:text/json; charset=".config_item('charset'));
    	echo json_encode(array('message' => $message,'data' => $data));
    }
	
}
