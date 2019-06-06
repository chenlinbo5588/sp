<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Wx_Tdkc_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library('Weixin_xcx_api');
	}
	
	
	/**
	 * 登陆获取 session key
	 */
	public function login(){
		$code = $this->postJson['code'];
		
		if($code){
			$weixinUser = $this->weixin_service->getWeixinUserByCode($code);
			if($weixinUser){
				$this->session->set_userdata(array(
					'weixin_user' =>  $weixinUser
				));
				
				$bindInfo = $this->weixin_service->checkUserBind($weixinUser);
				
				$this->initMemberInfo();
				if($this->memberInfo){
					if(11 == strlen($this->memberInfo['mobile'])){
						$bindInfo['mobile'] = mask_mobile($this->memberInfo['mobile']);
					}
				}
				$bindInfo['user_type'] = $this->userInfo['user_type'];
				$bindInfo['site_tel'] = $this->_getSiteSetting('site_tel');
				$bindInfo['default_address'] = $this->_getSiteSetting('service_default_address');
				
				$this->jsonOutput2(RESP_SUCCESS,$bindInfo);
			}else{
				//$this->jsonOutput2("微信登陆失败",array('sessionId' => $this->session->session_id));
				$this->jsonOutput2("微信登陆失败");
			}
			
		}else{
			$this->jsonOutput2('参数错误');
		}
	}
	
	public function getUserInfo(){
		if($this->userInfo){
			$userId = $this->postJson['uid'];
			if($userId){
				$userInfo = $this->Member_Model->getList(array(
					'select' => 'uid,mobile,name,user_type',
					'where' => array('uid' => $userId),
				));
				if($userInfo)
					$this->jsonOutput2(RESP_SUCCESS,$userInfo);
			}
		}
	}
	
	public function setInviter(){
		if($this->userInfo){
			$userInfo = $this->Member_Model->getFirstByKey($this->userInfo['uid'],'uid');
			if(!$userInfo['inviter_id']){
				$inviterId = $this->postJson['inviter_id'];
				$inviterInfo = $this->Member_Model->getFirstByKey($inviterId,'uid');
				if($inviterId){
				 	$result = $this->Member_Model->updateByCondition(
						array(
							'inviter_id' => $inviterInfo['uid'],
							'inviter_name' => $inviterInfo['name'],
							
						),
						array('where' => array('uid' => $this->userInfo['uid']))
					);
				}
				if($result){
					$this->jsonOutput2(RESP_SUCCESS,array('data' => '邀请人绑定成功'));
				}else{
					$this->jsonOutput2(RESP_ERROR,array('data' => '邀请人绑定失败'));
				}
			}else{
				$this->jsonOutput2(RESP_ERROR,array('data' => '邀请人已绑定'));
			}
			
		}
	}
	
	public function getuid(){
		if($this->userInfo){
			$this->jsonOutput2(RESP_SUCCESS,array('uid' => $this->userInfo['uid']));
		}else{
			$this->jsonOutput2(RESP_SUCCESS,array('data' => '用户未创建'));
		}
	}
	
	public function createQR(){
		$param =config_item('mp_xcxTdkc');
		
		$this->weixin_xcx_api->initSetting($param);
		
		$id =$this->userInfo['uid'];
		$path ='./qrImage/'.$id.'.jpg';
		$imgurl ='/qrImage/'.$id.'.jpg';
		$information = $this->weixin_xcx_api->createQR($id,$path,$imgurl);

		$this->jsonOutput2(RESP_SUCCESS,$information);
	}
	
	public function judgeBindGZH(){
		if($this->userInfo){
			$uid = $this->userInfo['uid'];
			$memberInfo = $this->Member_Model->getFirstByKey($uid,'uid');
			if($memberInfo['service_openid']){
				$this->jsonOutput2(RESP_SUCCESS,array('data' => '已绑定'));
			}else{
				$this->jsonOutput2(RESP_SUCCESS,array('data' => '未绑定'));
			}
		}else{
			$this->jsonOutput2(RESP_SUCCESS,array('data' => '用户未创建'));
		}
	}
	
/*	public function getopenid(){
		
		
	 	$code = $_GET["code"];
		$param =config_item('mp');

		$data = array(
		  'method' =>"GET",
		  'url' =>"/sns/oauth2/access_token?appid=".$param['appid']."&secret=".$param['app_secret']."&code=".$code."&grant_type=authorization_code",
		);   	 
		
		$openArr= $this->weixin_xcx_api->request($data);

		$openid =$openArr['openid'];

		$result = $this->Member_Model->updateByCondition(
			array(
				'service_openid' => $openid,		
			),
			array('where' => array('uid' => $this->userInfo['uid']))
		);
		
	}*/
	public function bingService(){
		if($this->userInfo['uid']){
			$serviceOpenid = $this->postJson['service_openid'];
			$memberInfo = $this->Member_Model->getFirstByKey($this->userInfo['uid'],'uid');
			if(!$memberInfo['service_openid']){
				$result = $this->Member_Model->updateByCondition(
					array(
						'service_openid' => $serviceOpenid,		
					),
					array('where' => array('uid' => $this->userInfo['uid']))
				);
				if($result){
					$this->jsonOutput2(RESP_SUCCESS,array('data' => '公众号绑定成功'));
				}else{
					$this->jsonOutput2(RESP_SUCCESS,array('data' => '公众号绑定失败'));
				}
			}else{
				$this->jsonOutput2(RESP_SUCCESS,array('data' => '已绑定公众号'));
			}
		}else{
			$this->jsonOutput2(RESP_SUCCESS,array('data' => '用户未创建'));
		}
		
	}
}
