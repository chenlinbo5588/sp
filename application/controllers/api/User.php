<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();

		$this->loadWeixinSupportFiles();
        
        $this->load->config('weixin');
    	$this->load->library('Weixin_mp_api');
	}
	
	public function index()
	{
		

	}
	
	public function login(){
		
		$code = $this->input->get_post('code');
		
		if($code){
			$resp = $this->weixin_mp_api->getWeixinUserByCode(config_item('mp_xcxTdkc'),$code);
			
			file_put_contents('debug.txt',print_r($resp,true));
		}
		
	}
}
