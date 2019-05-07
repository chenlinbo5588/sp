<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class AdminPmStatus {
	
	const USER_PM = 1;
	
	const SYS_PM = 2;
	
	//交易信息
	const TRANS_PM = 3;

}



class Admin_pm_service extends Base_service {

	private $_adminPmModel = null;

	//系统消息表
	private $_siteMessageModel = null;
	
	//hash 对象
	private $_pmHashObject = null;

	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Admin_Pm_Model','Site_Message_Model','Group_Model'));
		
		$this->_adminPmModel = self::$CI->Admin_Pm_Model;
		$this->_siteMessageModel = self::$CI->Site_Message_Model;
		
	}
	
	
	/**
	 * 获得所有组
	 */
	public function getAllGroup(){
		return self::$CI->Group_Model->getList(array(
			'select' => 'id,name,group_data',
			'where' => array(
				'enable' => 1
			)
		));
	}
	
	
	/**
	 * 发送系统广播信息
	 */
	public function addSitePmMessage($data){
		return $this->_siteMessageModel->_add($data);
	}
	
	
	/**
	 * 更具用户id列表 添加系统消息
	 */
	public function sendSitePmMessageToUsersByUid($userIds,$moredata = array()){
		
		if(empty($userIds)){
			return ;
		}
		
		if(!is_array($userIds)){
			$userIds = (array)$userIds;
		}
		
		
		$data = array(
			'msg_mode' => 1,//白名单
			'send_ways' => '站内信',
		);
		
		$data = array_merge($data,$moredata);
		
		$userList = self::$memberModel->getList(array(
			'select' => 'username',
			'where_in' => array(
				array('key' => 'uid','value' => $userIds)
			)
		));
		
		if($data['msg_mode'] != 0){
			$tempList = array();
			foreach($userList as $user){
				$tempList[] = $user['username'];
			}
			
			$data['users'] = str_replace(array("\r\n","\r","\n"),'',implode('|',$tempList)).'|';
		}
		
		
		return $this->addSitePmMessage($data);
	}
	
	
	/**
	 * 获得 pm hash object
	 */
	public function getAdminPmHashObj(){
		if(!$this->_pmHashObject){
			$this->_pmHashObject = new Flexihash();
			$this->_pmHashObject->addTargets(self::$CI->load->get_config('split_admin_pm'));
		}
		
		return $this->_pmHashObject;
	}
	
	
	/**
	 * 设置 Pm Message tableid
	 */
	public function setAdminPmTableByUid($uid){
		$tableId = $this->getAdminPmHashObj()->lookup($uid);
		$this->_adminPmModel->setTableId($tableId);
	}
	
	
	/**
	 * 获得用户 站内信列表
	 */
	public function getAdminPmListByUid($condition , $uid){
		$this->setAdminPmTableByUid($uid);
		
		return $this->_adminPmModel->getList($condition);
		
	}
	
	
	/**
	 * 获得用户站内信详情
	 */
	public function getAdminPmDetailById($pmId,$uid){
		$this->setAdminPmTableByUid($uid);
		return $this->_adminPmModel->getFirstByKey($pmId);
	}
	
	
	/**
	 * 检查过去的给定的秒数内 已发送私信的最大条数
	 */
	public function pmFreqLimit($secondPast,$limitCount,$uid){
		$this->setAdminPmTableByUid($uid);
		
		$count = $this->_adminPmModel->getCount(array(
			'where' => array(
				'gmt_create >=' => time() - $secondPast,
				'uid' => $uid,
				'msg_direction' => 1
			)
		));
		
		if($count >= $limitCount ){
			return false;
		}
		
		return true;
		
	}
	

	
	/**
	 * 发送后台 私信
	 */
	public function sendPrivateAdminPm($user,$from_uid,$title,$content,$escape = true){
		
		if(!is_array($user)){
			$userInfo = self::$CI->Adminuser_Model->getById(array(
				'select' => 'uid',
				'where' => array(
					'email' => $user
				)
			));
			
			if(empty($userInfo)){
				return false;
			}
		}else{
			$userInfo = $user;
		}
		
		
		// 插入两条记录
		$data = array(
			'uid' => $from_uid,
			'from_uid' => $from_uid,
			'msg_type' => 1,
			'readed' => 1,
			'msg_direction' => 1,//发
			'title' => $escape == true ? strip_tags($title) : $title,
			'content' => $escape == true ? strip_tags($content) : $content
		);
		
		
		// 发送方先记录
		$this->setAdminPmTableByUid($from_uid);
		$sendId = $this->_adminPmModel->_add($data);
		
		// 接受方再次记录
		$this->setAdminPmTableByUid($userInfo['uid']);
		
		$data['uid'] = $userInfo['uid'];
		$data['readed'] = 0;
		$data['msg_direction'] = 0;//收
		
		$receiveId = $this->_adminPmModel->_add($data);
		
		return array('send_id' => $sendId, 'receive_id' => $receiveId);
	}
	
	
	/**
	 * 批量或者单个删除 用户站内信
	 */
	public function deleteUserPm($pmIds , $uid){
		if(!is_array($pmIds)){
			$pmIds = (array)$pmIds;
		}
		
		if(empty($pmIds)){
			return false;
		}
		
		$condition = array(
			'where' => array(
				'uid' => $uid
			),
			'where_in' => array(
				array('key' => 'id' , 'value' => $pmIds)
			)
		);
		
		$this->setAdminPmTableByUid($uid);
		
		return $this->_adminPmModel->deleteByCondition($condition);
		
	}
	
	
	
	/**
	 * 批量设置已读
	 */
	public function  setUserPmReaded($pmIds, $uid){
		if(!is_array($pmIds)){
			$pmIds = (array)$pmIds;
		}
		
		if(empty($pmIds)){
			return false;
		}
		
		$condition = array(
			'where' => array(
				'uid' => $uid
			),
			'where_in' => array(
				array('key' => 'id' , 'value' => $pmIds)
			)
		);
		
		$this->setAdminPmTableByUid($uid);
		
		return $this->_adminPmModel->updateByCondition(array('readed' => 1),$condition);
		
	}
	
	/*
	 * 
	 * 获得未读消息数量
	 */
	public function getUserUnreadCount($uid){
		$this->setAdminPmTableByUid($uid);
		
		return $this->_adminPmModel->getCount(array(
			'where' => array(
				'uid' => $uid,
				'readed' => 0
			)
		));
		
	}
	
	
	/**
	 * 更新用户 最新的站内新
	 */
	public function refreshAdminPm($pUser){
		
		$now = time();
		
		$newPmStat = array(
			'site_pm' => 0,//系统消息数
			'trans_pm' => 0,//交易消息数
			'user_pm' => 0 //用户私信
		);
		
		//获得当前最大 系统消息记录
		$maxSysPm = $this->getAdminPmListByUid(array(
			'select' => 'site_msgid',
			'where' => array(
				'msg_type !=' => AdminPmStatus::USER_PM,
				'uid' => $pUser['uid'],
			),
			'order' => 'id DESC',
			'limit' => 1
		),$pUser['uid']);
		
		
		$currentSysId = 0 ;
		
		//最的站内新记录
		if(!empty($maxSysPm)){
			$currentSysId = $maxSysPm[0]['site_msgid'];
		}
		
		$compareTs = $now - CACHE_ONE_DAY * 7;
		if($pUser['gmt_create'] > $compareTs){
			$compareTs = $pUser['gmt_create'];
		}
		
		//系统广播消息
		$sysPmList = $this->_siteMessageModel->getList(array(
			'where' => array(
				'msg_type !=' => AdminPmStatus::USER_PM,
				'id > ' => $currentSysId,
				//取用户创建日期之后的
				'gmt_create >= ' => $compareTs
			),
			'limit' => 10
		),'id');
		
		
		//获得用户 未读 私信
		$userPmList = $this->getAdminPmListByUid(array(
			'select' => 'id',
			'where' => array(
				'msg_type' => AdminPmStatus::USER_PM,
				'readed' => 0,
				'uid' => $pUser['uid'],
				'id > ' => intval($pUser['pm_id'])
			),
			'limit' => 10
		),$pUser['uid']);
		
		
		
		$accept = false;
		$needAddIndex = array();
		
		$groupInfo = self::$CI->Group_Model->getFirstByKey($pUser['group_id'],'id','id,group_data');
		$residentList = array();
		
		if($groupInfo){
			$residentList = json_decode($groupInfo['group_data'],true);
		}
		
		foreach($sysPmList as $key => $item){
			$accept = false;
			
			$item['users'] = json_decode($item['users'],true);
			$item['groups'] = json_decode($item['groups'],true);
			
			//交易提醒信息
			if($item['msg_type'] == AdminPmStatus::TRANS_PM){
				
				if(empty($residentList)){
					$accept = true;
				}else if(in_array($item['groups'][0],$residentList)){
					$accept = true;
				}
			}
			
			if(!$accept){
				if(1 == $item['msg_mode']){
					//白名单
					if(in_array($pUser['username'],$item['users'])){
						$accept = true;
					}
					
					if(in_array($pUser['group_id'],$item['groups'])){
						$accept = true;
					}
					
				}else if(2 == $item['msg_mode']){
					// 黑名单
					if(!in_array($pUser['username'],$item['users'])){
						$accept = true;
					}
					
					if(!in_array($pUser['group_id'],$item['groups'])){
						$accept = true;
					}
					
				}else{
					$accept = true;
				}
			}
			
			
			if($accept){
				$needAddIndex[] = $key;
			}
		}
		
		//需要插入用户表的消息
		$userPm = array();
		
		if($needAddIndex){
			foreach($needAddIndex as $pmIndex){
				$sendWays = json_decode($sysPmList[$pmIndex]['send_ways'],true);
				
				if($item['msg_type'] == AdminPmStatus::TRANS_PM){
					$newPmStat['trans_pm']++;
				}else{
					$newPmStat['site_pm']++;
				}
				
				if(in_array('站内信',$sendWays)){
					$userPm[] = array(
						'uid' => $pUser['uid'],
						'site_msgid' => $sysPmList[$pmIndex]['id'],
						'msg_type' => $sysPmList[$pmIndex]['msg_type'],
						'from_id' => $sysPmList[$pmIndex]['from_id'],
						'title' => str_replace('{username}',$pUser['username'],$sysPmList[$pmIndex]['title']),
						'content' => str_replace('{username}',$pUser['username'],$sysPmList[$pmIndex]['content']),
					);
				}
				
				
				if(in_array('短信',$sendWays)){
					//@TODO
				}
			}
			
			//插入管理 用户站内信表 $userPm
			if($userPm){
				$this->setAdminPmTableByUid($pUser['uid']);
				$pmInsertRows = $this->_adminPmModel->batchInsert($userPm);
			}
		}
		
		$userPmCount = count($userPmList);
		
		if($userPmCount){
			
			$latestPmId = $userPmList[$userPmCount - 1]['id'];
			
			self::$CI->Adminuser_Model->update(array('pm_id' => $latestPmId),array('uid' => $pUser['uid']));
			
			$userProfile = self::$CI->session->userdata('manage_profile');
			$userProfile['basic']['pm_id'] = $latestPmId;
			
			self::$CI->session->set_userdata(array(
				'manage_profile' => $userProfile
			));
			
			$newPmStat['user_pm'] = $userPmCount;
		}
		
		return $newPmStat;
		
	}
	
	
	/* ---------------以下站内 -------------------------------------------- */

	
	/**
	 * 添加一条站内信，后台自动发送
	 */
	public function pushPmMessageToUser($data,$uid){
		if(!$data['msg_type']){
			$data['msg_type'] = 2;
		}
		
		$this->setAdminPmTableByUid($uid);
		$this->_adminPmModel->_add($data);
	}
	
	
	/**
	 * 添加交易数据 站内信
	 */
	public function addTransMessage($pData,$pGroups = array(),$pUsers = array()){
		
		$default = array(
			'msg_type' => AdminPmStatus::TRANS_PM,
			'send_ways' => json_encode(array('站内信')),
			'msg_mode' => 1,
			'groups' => json_encode($pGroups),
			'users' => json_encode($pUsers),
		);
		
		return $this->_siteMessageModel->_add(array_merge($default,$pData));
		
	}
	
	/**
	 * 订单站内信
	 */
	public function addOrderMessage($pOrderInfo){
		
		$jumpUrl = 0 == $pOrderInfo['is_refund'] ? admin_site_url('order/detail?id='.$pOrderInfo['id']) : admin_site_url('refund/detail?id='.$pOrderInfo['id']);
		
		$this->addTransMessage(array(
			'title' => '新订单 '.$pOrderInfo['order_typename'].($pOrderInfo['is_refund'] == 1 ? '退款' : '').' '.$pOrderInfo['yewu_name'],
			'content' => '<div>订单号码:<a target="workspace" href="'. $jumpUrl.'">'.$pOrderInfo['order_id'].' 点击订单查看详情</a></div>'
		),array($pOrderInfo['resident_id']));
	
	}
	/**
	 * 业务站内信
	 */
	public function addYewuMessage($yewuInfo,$id){
		
		$jumpUrl  = admin_site_url('yewu/edit?id='.$id);
		$this->addMessage(array(
			'title' => '新业务 '.$yewuInfo['service_area'].$yewuInfo['yewu_name'],
			'content' => '<div>业务描述:<a target="workspace" href="'. $jumpUrl.'">'.$yewuInfo['yewu_describe'].' 点击描述查看详情</a></div>',
			'form_id' => $yewuInfo['user_id'],
		));
	
	}
	public function addMessage($pData,$pGroups = array(),$pUsers =  array()){
		
		$default = array(
			'msg_type' => 3,
			'send_ways' => json_encode(array('站内信')),
			'msg_mode' => 3,
			'groups' => json_encode($pGroups),
			'users' => json_encode($pUsers),
		);
		
		return $this->_siteMessageModel->_add(array_merge($default,$pData));
		
	}
	
}
