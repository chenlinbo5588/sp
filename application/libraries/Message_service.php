<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_service extends Base_service {

	private $_msgTemplateModel = null;
	private $_pmMessageModel = null;
	private $_memberGroupModel = null;
	private $_siteMessageModel = null;
	private $_pushChatModel = null;
	
	
	private $_email = null;
	
	private $_setting ;
	
	private $_hashObject;

	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Msg_Template_Model','Pm_Message_Model','Member_Group_Model','Site_Message_Model','Push_Chat_Model'));
		self::$CI->load->library(array('Email','Flexihash'));
		
		
		$this->_email = self::$CI->email;
		$this->_hashObject = self::$CI->flexihash;
		
		$this->_msgTemplateModel = self::$CI->Msg_Template_Model;
		$this->_pmMessageModel = self::$CI->Pm_Message_Model;
		$this->_memberGroupModel = self::$CI->Member_Group_Model;
		$this->_siteMessageModel = self::$CI->Site_Message_Model;
		$this->_pushChatModel = self::$CI->Push_Chat_Model;
		
		$pmConfig = self::$CI->load->get_config('split_pm');
		//print_r($pmConfig);
		$this->_hashObject->addTargets($pmConfig);
		
	}
	
	/* ----------------以下站内信----------------------------------------------- */
	public function getAllMemberGroup(){
		$cacheObject = self::$CI->getCacheObject();
		$groupList = $cacheObject->get('Member_Group');
		
		if(empty($groupList)){
			$groupList = $this->_memberGroupModel->getList();
			$cacheObject->save('Member_Group',$groupList,CACHE_ONE_DAY);
		}
		
		return $groupList;
		
	}
	
	
	/**
	 * 发送系统广播信息
	 */
	public function addSitePmMessage($data){
		return $this->_siteMessageModel->_add($data);
	}
	
	
	/**
	 * 设置 Push Chat tableid
	 */
	public function setPushChatTableByUid($uid){
		$tableId = $this->_hashObject->lookup($uid);//file_put_contents("user_pm_push.txt",$tableId);
		$this->_pushChatModel->setTableId($tableId);
	}
	
	
	/**
	 * 设置 Pm Message tableid
	 */
	public function setPmTableByUid($uid){
		$tableId = $this->_hashObject->lookup($uid);//file_put_contents("user_pm.txt",$tableId);
		$this->_pmMessageModel->setTableId($tableId);
	}
	
	
	/**
	 * 获得用户 站内信列表
	 */
	public function getPmListByUid($condition , $uid){
		$this->setPmTableByUid($uid);
		return $this->_pmMessageModel->getList($condition);
		
	}
	
	
	/**
	 * 获得用户站内信详情
	 */
	public function getUserPmDetailById($pmId,$uid){
		$this->setPmTableByUid($uid);
		return $this->_pmMessageModel->getFirstByKey($pmId);
	}
	
	
	/**
	 * 检查过去的给定的秒数内 已发送私信的最大条数
	 */
	public function pmFreqLimit($secondPast,$limitCount,$uid){
		$this->setPmTableByUid($uid);
		$count = $this->_pmMessageModel->getCount(array(
			'where' => array(
				'gmt_create >=' => self::$CI->input->server('REQUEST_TIME') - $secondPast,
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
	 * 发送私信
	 */
	public function sendPrivatePm($toUsername,$from_uid,$title,$content){
		
		$userInfo = self::$CI->Member_Model->getById(array(
			'select' => 'uid',
			'where' => array(
				'username' => $toUsername
			)
		));
		
		if(empty($userInfo)){
			return false;
		}
		
		// 插入两条记录
		$data = array(
			'uid' => $from_uid,
			'from_uid' => $from_uid,
			'msg_type' => 0,
			'readed' => 1,
			'msg_direction' => 1,
			'title' => strip_tags($title),
			'content' => strip_tags($content)
		);
		
		
		// 发送方先记录
		$this->setPmTableByUid($from_uid);
		$sendId = $this->_pmMessageModel->_add($data);
		
		
		// 接受方再次记录
		$this->setPmTableByUid($userInfo['uid']);
		$data['uid'] = $userInfo['uid'];
		$data['readed'] = 0;
		$data['msg_direction'] = 0;
		
		
		$receiveId = $this->_pmMessageModel->_add($data);
		
		
		return array('id_send' => $sendId, 'id_receive' => $receiveId);
	}
	
	
	
	/**
	 * 添加 站内消息
	 */
	public function addSystemPmMessage($data){
		$this->setPmTableByUid($data['uid']);
		
		$data['msg_type'] = -1;
		$data['from_uid'] = 0;
		
		return $this->_pmMessageModel->_add($data);
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
		
		$this->setPmTableByUid($uid);
		
		return $this->_pmMessageModel->deleteByCondition($condition);
		
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
		
		$this->setPmTableByUid($uid);
		
		return $this->_pmMessageModel->updateByCondition(array('readed' => 1),$condition);
		
	}
	
	
	
	/**
	 * 获得用户最近的一条系统消息
	 */
	public function getLastestSysPm($userProfile,$uid){
		//print_r($userProfile);
		$maxUserSysId = intval($userProfile['basic']['msgid']);
		
		$list = $this->_siteMessageModel->getList(array(
			'where' => array(
				'id > ' => $maxUserSysId
			),
			'limit' => 5
		));
		
		$listCount = count($list);
		
		//print_r($list);
		
		$userGroup = $userProfile['basic']['group_id'];
		$accept = false;
		
		//站内信,仅保存下标
		$sysPm = array();
		
		
		//用户站内信;
		$userPm = array();
		$userChat = array();
		$userEmail = array();
		
		if($listCount){
			foreach($list as $key => $item){
				$accept = false;
				
				if( 1 == $item['msg_type']){
					//全体
					$accept = true;
				}else if($item['msg_type'] == $userGroup){
					//已当前用户所属的 用户组一致
					$accept = true;
				}
				
				if(1 == $item['msg_mode']){
					//白名单
					if(strpos($item['users'],$userProfile['basic']['username'].'|') !== false){
						$accept = true;
					}else{
						$accept = false;
					}
					
				}else if(2 == $item['msg_mode']){
					// 黑名单
					if(strpos($item['users'],$userProfile['basic']['username'].'|') !== false){
						$accept = false;
					}else{
						$accept = true;
					}
				}
				
				if($accept){
					$sysPm[] = $key;
				}
			}
		}
		
		//@todo update ,需要完成邮件数据插入
		if($sysPm){
			foreach($sysPm as $pmIndex){
				$sendWays = $list[$pmIndex]['send_ways'];
				
				$list[$pmIndex]['title'] = str_replace('{username}',$userProfile['basic']['username'],$list[$pmIndex]['title']);
				$list[$pmIndex]['content'] = str_replace('{username}',$userProfile['basic']['username'],$list[$pmIndex]['content']);
				
				
				if(strpos($sendWays,'站内信') !== false){
					$userPm[] = array(
						'uid' => $uid,
						'site_msgid' => $list[$pmIndex]['id'],
						'msg_type' => -1,
						'title' => $list[$pmIndex]['title'],
						'content' => $list[$pmIndex]['content'],
					);
				}
				
				if(strpos($sendWays,'聊天窗口') !== false){
					// do insert
					$userChat[] = array(
						'uid' => $uid,
						'username' => $userProfile['chat']['username'],
						'content' => strip_tags($list[$pmIndex]['content']),
					);
				}
				
				if(strpos($sendWays,'邮件') !== false){
					// do nothing current
					
				}
				
				/*
				if(strpos($pm['send_ways'],'短信') !== false){
					// do nothing current
				}
				*/
			}
			
			//插入 用户站内信表 $userPm
			if($userPm){
				$this->setPmTableByUid($uid);
				$pmInsertRows = $this->_pmMessageModel->batchInsert($userPm);
			}
			
			/*
			//插入 用户聊天信息表 后台自动发送
			if($userChat){
				$this->setPushChatTableByUid($uid);
				$this->_pushChatModel->batchInsert($userChat);
			}
			*/
			//插入 用户邮件表 后台自动发送， 私人邮件发送数量太多，可能会被禁
			//插入 用户短信 , 暂时不做 太费钱 
		}
		
		
		// 无论前面是否,用户系统消息 下标更新
		if($listCount){
			$maxUserSysId = $list[$listCount - 1]['id'];
			self::$CI->Member_Model->update(array(
				'msgid' => intval($maxUserSysId)
			),array('uid' => $uid));
			
			$userProfile['basic']['msgid'] = $maxUserSysId;
			self::$CI->session->set_userdata(array(
				'profile' => $userProfile
			));
		}
		
		return $sysPm;
	}
	
	
	/* ----------------以下邮件相关----------------------------------------------- */
	
	
	/**
	 * 初始化邮件发送设置
	 */
	public function initEmail($setting){
		
		$this->_setting = $setting;
		
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = $setting['email_host'];
		$config['smtp_port'] = $setting['email_port'];
		$config['smtp_user'] = $setting['email_id'];
		
		$psw = self::$CI->encrypt->decode($setting['email_pass']);
		
		$config['smtp_pass'] = $psw;
		$config['smtp_timeout'] = 5;
		$config['charset'] = config_item('charset');
		
		
		//print_r($config);
		
		$this->_email->initialize($config);
	}
	
	
	/**
	 * 主要用于邮件正文 加密链接生成
	 */
	public function getEncodeParam($param,$expired = 86400){
		$param[] = self::$CI->input->server('REQUEST_TIME') + $expired;
		$str = implode("\t",$param);
		
		return urlencode(self::$CI->encrypt->encode($str));
	}
	
	
	/**
	 * 邮件确认 发送
	 */
	public function sendEmailConfirm($to,$confirm_url){
		$tpl = $this->getMsgTemplateByCode('email_confirm');
		
		$tpl['title'] = str_replace(array(
			'{site_name}'
		),array(
			$this->_setting['site_name']
		),$tpl['title']);
		
		$tpl['content'] = str_replace(array(
			'{site_name}',
			'{site_logo}',
			'{site_url}',
			'{confirm_url}',
			'{confirm_url_plain}'
			
		),array(
			$this->_setting['site_name'],
			resource_url($this->_setting['site_logo']),
			site_url(),
			$confirm_url,
			htmlentities($confirm_url)
		),$tpl['content']);
		
		//print_r($this->_setting);
		
		return $this->sendEmail($to,$tpl['title'],$tpl['content']);
	}
	
	
	
	/*
	 * 发送邮件
	 */
	public function sendEmail($to,$subject,$content){
		
		$this->_email->to($to);
		$this->_email->from($this->_setting['email_addr']);
		$this->_email->subject($subject);
		$this->_email->message($content);
		$this->_email->set_mailtype('html');
		
		$flag = $this->_email->send();
		//var_dump($flag);
		//$this->_email->print_debugger();
		//echo 'success ';
		
		return $flag;
	}
	
	
	public function getMsgTemplateByCode($code){
		return $this->_msgTemplateModel->getFirstByKey($code,'code');
	}
	
	/**
	 * 
	 */
	public function getListByCondition($condition){
		return $this->toEasyUseArray($this->_msgTemplateModel->getList($condition),'code');
	}
	
	
	
	public function updateMsgTemplate($param){
		
		return $this->_msgTemplateModel->update(array(
			'title' => $param['title'],
			'content' => $param['content'],
		
		),array('code' => $param['code']));
		
	}
	
		
	/**
	 * 
	 */
	public function switchMsgTemplateStatus($switchValue,$ids){
		
		$data = array();
		foreach($ids as $id){
			$data[] = array(
				'code' => $id,
				'is_off' => $switchValue == 'mail_switchON' ? 0 : 1
			);
		}
		
		if($data){
			return $this->_msgTemplateModel->batchUpdate($data,'code');
		}
		
		return false;
	}
}
