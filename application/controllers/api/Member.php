<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();
		
		if(!$this->verifySignature()){
			//$this->responseJSON('签名验证失败');
		}
		
		$this->load->library('Member_service');
		
	}
	
	public function getInfoByMobileOrNickname(){
		$word = $this->input->get_post('word');
		$info = $this->member_service->getListByCondition(array(
			'select' => 'uid,avatar_s,username,mobile,nickname',
			'where' => array('mobile' => $word),
			'or_where' => array('nickname' => $word)
		));
		
		if($info){
			foreach($info as $value){
				$this->jsonOutput('success',$value);
				break;
			}
			
		}else{
			$this->jsonOutput('failed',$info[0]);
		}
	}
}
