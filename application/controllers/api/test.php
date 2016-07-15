<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		
	}
	
	
	public function index(){
		$code = $this->_verify();
		//file_put_contents("code.txt",$code);
		$this->load->view('test',array('code' => $code));
	}
	
}
