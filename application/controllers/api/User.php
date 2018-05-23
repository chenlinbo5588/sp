<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
        
    	$this->load->library('Weixin_xcx_api');
	}
	
	public function index()
	{
		

	}
	
	public function login(){
		
		$code = $this->input->get_post('code');
		
		if($code){
			
			$this->weixin_xcx_api->setConfig(config_item('mp_xcxTdkc'));
			$resp = $this->weixin_xcx_api->getWeixinUserByCode($code);
			
			if(!empty($resp['session_key'])){
				
				
				/**
				$this->session->set_userdata(array(
					'profile' => $result['data'],
					'lastvisit' => $this->_reqtime
				));
				
				$this->member_service->updateUserInfo(
						array(
							'sid' => $this->session->session_id,
							'last_login' => $this->_reqtime,
							'last_loginip' => $this->input->ip_address()
						),
						$result['data']['basic']['uid']);
						
				}
				*/
				
				//file_put_contents('debug.txt',print_r($resp,true));
			}
		
		}
	}
}
