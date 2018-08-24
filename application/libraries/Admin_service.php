<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_service extends Base_service {
	
	
	private $_adminUserModel;
	private $_groupModel;
	private $_roleModel;

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array(
			'Adminuser_Model','Fn_Model','Role_Model','Group_Model'
		));
		
		$this->_adminUserModel = self::$CI->Adminuser_Model;
		$this->_roleModel = self::$CI->Role_Model;
		$this->_groupModel = self::$CI->Group_Model;
		
	}
    
    /**
	 * 更新用户信息
	 */
	public function updateUserInfo($data,$uid){
		return $this->_adminUserModel->update($data,array('uid' => $uid));
	}
	
	
    public function do_adminlogin($email,$password){
		$user = $this->_adminUserModel->getFirstByKey($email,'email');
		$message = '失败';
		
		for($i = 0; $i < 1; $i++){
			if(empty($user)){
				$message = '用户名不存在';
				break;
			}
			
			if(0 == $user['enable']){
				$message = '用户已禁用';
				break;
			}
			
			if($password != self::$CI->encrypt->decode($user['password'],config_item('encryption_key').md5($user['email']))){
				$message = '密码不正确';
				break;
			}
			
			$message = '成功';
		}
		
		
		return $this->formatArrayReturn(0,$message,array('basic' => $user));
	}
	
	
	
	/**
	 * 
	 */
	public function saveUser($pUser,$pOldInfo = array()){
		
		$this->_adminUserModel->beginTrans();
		
		$rowId = 0;
		
		
		if($pUser['uid']){
			$id = $pUser['uid'];
			unset($pUser['uid']);
			
			if($pOldInfo['role_id'] && $pOldInfo['role_id'] != $pUser['role_id']){
				$this->_roleModel->increseOrDecrease(array(
					array('key'  => 'user_cnt', 'value' => 'user_cnt - 1'),
				),array('id' => $pOldInfo['role_id']));
				
				
				$this->_roleModel->increseOrDecrease(array(
					array('key'  => 'user_cnt', 'value' => 'user_cnt + 1'),
				),array('id' => $pUser['role_id']));
				
			}
			
			if($pOldInfo['group_id'] && $pOldInfo['group_id'] != $pUser['group_id']){
				$this->_groupModel->increseOrDecrease(array(
					array('key'  => 'user_cnt', 'value' => 'user_cnt - 1'),
				),array('id' => $pOldInfo['group_id']));
				
				
				$this->_groupModel->increseOrDecrease(array(
					array('key'  => 'user_cnt', 'value' => 'user_cnt + 1'),
				),array('id' => $pUser['group_id']));
				
			}
			
			$rowId = $this->_adminUserModel->update($pUser,array(
				'uid' => $id
			));
			
		}else{
			
			if($pUser['role_id']){
				$this->_roleModel->increseOrDecrease(array(
					array('key'  => 'user_cnt', 'value' => 'user_cnt + 1'),
				),array('id' => $pUser['role_id']));
			}
			
			if($pUser['group_id']){
				$this->_groupModel->increseOrDecrease(array(
					array('key'  => 'user_cnt', 'value' => 'user_cnt + 1'),
				),array('id' => $pUser['group_id']));
			}
			
			
			$rowId = $this->_adminUserModel->_add($pUser);
		}
		
		
		
		if($this->_adminUserModel->getTransStatus() === FALSE){
			$this->_adminUserModel->rollBackTrans();
			return false;
		}
		
		$isOk = $this->_adminUserModel->commitTrans();
		
		
		if($isOk){
			return $rowId;
		}else{
			
			return false;
		}
		
	}
	
	
	
	/**
	 * 用户组
	 */
	public function saveGroup($pGroup){
		
		$pGroup['data_cnt'] = count($pGroup['group_data']);
		
		$pGroup['group_data'] = json_encode($pGroup['group_data']);
		
		
		if(empty($pGroup['expire'])){
			$pGroup['expire'] = 0;
		}else{
			$pGroup['expire'] = strtotime($pGroup['expire']);
		}
		
		
		if($pGroup['id']){
			$id = $pGroup['id'];
			unset($pGroup['id']);
			
			return $this->_groupModel->update($pGroup,array(
				'id' => $id
			));
		}else{
			return $this->_groupModel->_add($pGroup);
		}
	}
	
	
	
	/**
	 * 角色
	 */
	public function saveRole($pRole){
		
		if($pRole['id']){
			$id = $pRole['id'];
			unset($pRole['id']);
			
			return $this->_roleModel->update($pRole,array(
				'id' => $id
			));
		}else{
			return $this->_roleModel->_add($pRole);
		}
	}
	
	
	
	/**
	 * 获得角色信息
	 */
	public function getRoleInfo($pId){
		
		$info = $this->_roleModel->getFirstByKey($pId,'id');
		
		if($info){
			$info['permission'] = self::$CI->encrypt->decode($info['permission'],config_item('encryption_key').md5($info['name']));
			$info['permission'] = explode('|',$info['permission']);
			$info['permission'] = array_flip($info['permission']);
		}
		
		return $info;
		
	}
	
	
	/**
	 * 获得组信息
	 */
	public function getGroupInfo($id){
		
		$groupInfo = $this->_groupModel->getFirstByKey($id);
		
		if($groupInfo){
			$groupInfo['group_data'] = json_decode($groupInfo['group_data'],true);
		}
		
		return $groupInfo;
	}
	
	
	/**
	 * 获得开启关闭
	 */
	private function _getOnOff($pOnOrOff,$who){
		
		$data = array();
		
		switch($pOnOrOff){
			case '开启':
				$data['enable'] = 1;
				break;
			case '关闭':
				$data['enable'] = 0;
				break;
		}
		
		
		if(empty($data)){
			return false;
		}
		
		$data = array_merge($data,$who);
		
		return $data;
	}
	
	
	/**
	 * 用户开启 关闭
	 */
	public function userOnOff($ids,$pOnOrOff,$who){
		
		$data = $this->_getOnOff($pOnOrOff,$who);
		
		return $this->_adminUserModel->updateByCondition($data,array(
			'where_in' => array(
				array('key' => 'uid', 'value' => $ids)
			)
		));
		
	}
	
	/**
	 * 组开启 关闭
	 */
	public function groupOnOff($ids,$pOnOrOff,$who){
		
		$data = $this->_getOnOff($pOnOrOff,$who);
		
		return $this->_groupModel->updateByCondition($data,array(
			'where_in' => array(
				array('key' => 'id', 'value' => $ids)
			)
		));
	}
	
	/**
	 * 角色开启 关闭
	 */
	public function roleOnOff($ids,$pOnOrOff,$who){
		
		$data = $this->_getOnOff($pOnOrOff,$who);
		
		return $this->_roleModel->updateByCondition($data,array(
			'where_in' => array(
				array('key' => 'id', 'value' => $ids)
			)
		));
	}
	
}
