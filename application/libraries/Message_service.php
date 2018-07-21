<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once AliyunEmail_PATH.'aliyun-php-sdk-core/Config.php';
//use Dm\Request\V20151123 as Dm;


class Message_service extends Base_service {

	private $_msgTemplateModel = null;
	private $_pmMessageModel = null;
	
	//用户组
	private $_memberGroupModel = null;
	
	
	//系统消息表
	private $_siteMessageModel = null;
	
	
	//邮件推送给 客户待发记录操作对象
	private $_emailModel = null;
	
	
	//邮件记录给网站管理员 待发记录操作对象
	private $_siteEmailModel = null;
	
	
	//邮件推送对象
	private $_email = null;
	private $_emailRequest = null;
	
	
	//网站设置
	private $_setting ;
	
	//hash 对象
	private $_pmHashObject = null;
	private $_emailHashObject = null;

	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Msg_Template_Model','Pm_Message_Model','Site_Message_Model','Email_Model'));
		
		//self::$CI->load->library(array('Email'));
		//$this->_email = self::$CI->email;
		
		$this->_msgTemplateModel = self::$CI->Msg_Template_Model;
		$this->_pmMessageModel = self::$CI->Pm_Message_Model;
		
		$this->_siteMessageModel = self::$CI->Site_Message_Model;
		
		
		$this->_emailModel = self::$CI->Email_Model;
	}
	
	
	
	/* ----------------以下站内信----------------------------------------------- */
	public function getAllMemberGroup(){
		
		
		if(!$this->_memberGroupModel){
			self::$CI->load->model('Resident_Model');
			
			$this->_memberGroupModel = self::$CI->Resident_Model;
		}
		
		
		/*
		 * @todo 加入缓存
		$cacheObject = self::$CI->getCacheObject();
		$groupList = $cacheObject->get('Member_Group');
		
		if(empty($groupList)){
			$groupList = $this->_memberGroupModel->getList(array('order' => 'displayorder ASC , id DESC'),'');
			$cacheObject->save('Member_Dept',$groupList,CACHE_ONE_DAY);
		}
		*/
		
		return $this->_memberGroupModel->getList(array('order' => 'displayorder ASC , id DESC'));
		
	}
	
	
	/**
	 * 发送系统广播信息
	 */
	public function addSitePmMessage($data){
		return $this->_siteMessageModel->_add($data);
	}
	
	
	/**
	 * 更加用户id列表 添加系统消息
	 */
	public function sendSitePmMessageToUsersByUid($userIds,$moredata){
		
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
	public function getPmHashObj(){
		if(!$this->_pmHashObject){
			$this->_pmHashObject = new Flexihash();
			$this->_pmHashObject->addTargets(self::$CI->load->get_config('split_pm'));
		}
		return $this->_pmHashObject;
	}
	
	/**
	 * 获得 email hash object
	 */
	public function getEmailHashObj(){
		if(!$this->_emailHashObject){
			$this->_emailHashObject = new Flexihash();
			$this->_emailHashObject->addTargets(self::$CI->load->get_config('split_push_email'));
		}
		return $this->_emailHashObject;
	}
	
	
	
	/**
	 * 获得 添加 送给管理的员的邮件model
	 */
	public function getSiteEmailModel(){
		if(!$this->_siteEmailModel){
			self::$CI->load->model(array('Site_Email_Model'));
			$this->_siteEmailModel = self::$CI->Site_Email_Model;
		}
		
		return $this->_siteEmailModel;
	}
	
	
	/**
	 * 设置 Pm Message tableid
	 */
	public function setPmTableByUid($uid){
		$tableId = $this->getPmHashObj()->lookup($uid);
		$this->_pmMessageModel->setTableId($tableId);
	}
	
	/**
	 * 设置 Email Message tableid
	 */
	public function setEmailTableByUid($uid){
		$tableId = $this->getEmailHashObj()->lookup($uid);
		$this->_emailModel->setTableId($tableId);
	}
	
	/**
	 * 设置 Push Chat tableid
	 */
	
	/*
	public function setPushChatTableByUid($uid){
		$tableId = $this->getChatHashObj()->lookup($uid);
		$this->_pushChatModel->setTableId($tableId);
	}
	*/
	
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
	public function sendPrivatePm($user,$from_uid,$title,$content,$senderAutoPm = true,$escape = true){
		
		if(!is_array($user)){
			$userInfo = self::$CI->Member_Model->getById(array(
				'select' => 'uid',
				'where' => array(
					'username' => $user
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
			'msg_type' => 0,
			'readed' => 1,
			'msg_direction' => 1,
			'title' => $escape == true ? strip_tags($title) : $title,
			'content' => $escape == true ? strip_tags($content) : $content
		);
		
		
		if($senderAutoPm){
			// 发送方先记录
			$this->setPmTableByUid($from_uid);
			$sendId = $this->_pmMessageModel->_add($data);
		}else{
			$sendId = 0;
		}
		
		// 接受方再次记录
		$this->setPmTableByUid($userInfo['uid']);
		$data['uid'] = $userInfo['uid'];
		$data['readed'] = 0;
		$data['msg_direction'] = 0;
		
		$receiveId = $this->_pmMessageModel->_add($data);
		
		return array('id_send' => $sendId, 'id_receive' => $receiveId);
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
	
	/*
	 * 
	 * 获得未读消息数量
	 */
	public function getUserUnreadCount($uid){
		$this->setPmTableByUid($uid);
		
		return $this->_pmMessageModel->getCount(array(
			'where' => array(
				'uid' => $uid,
				'readed' => 0
			)
		));
		
		
		
	}
	
	
	/**
	 * 更新用户 最新的站内新
	 */
	public function getLastestSysPm($userProfile,$uid){
		//file_put_contents('debug.txt',print_r($userProfile,true));
		$list = $this->_siteMessageModel->getList(array(
			'where' => array(
				'id > ' => intval($userProfile['basic']['msgid']),
				//取用户创建日期之后的
				'gmt_create >= ' => $userProfile['basic']['gmt_create']
			),
			'limit' => 10
		));
		
		$userPmList = $this->getPmListByUid(array(
			'select' => 'id',
			'where' => array(
				'uid' => $uid,
				'id > ' => intval($userProfile['basic']['pm_id'])
			),
			'limit' => 10
		),$uid);
		
		$systemCount = count($list);
		$userPmCount = count($userPmList);
		
		//print_r($list);
		///print_r($userPmList);
		$accept = false;
		
		//站内信,仅保存下标
		$sysPm = array();
		
		//用户站内信;
		$userPm = array();
		
		$userEmail = array();
		
		if($systemCount){
			foreach($list as $key => $item){
				$accept = false;
				
				$destDepts = explode(',',$item['groups']);
				if(in_array($userProfile['basic']['dept_id'],$destDepts)){
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
				
				
				if(strpos($sendWays,'邮件') !== false){
					// do nothing current
					
					$userEmail[] = array(
						'uid' => $uid,
						'msg_type' => -1,
						'username' => $userProfile['basic']['username'],
						'email' => $userProfile['basic']['email'],
						'title' => $list[$pmIndex]['title'],
						'content' => $list[$pmIndex]['content'],
					);
					
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
			
			
			
			//插入 用户邮件表 后台自动发送， 私人邮件发送数量太多，可能会被禁
			
			/*
			if($userEmail){
				$this->addMutiEmailMessageToUser($userEmail,$uid);
			}
			*/
			
			//插入 用户短信 , 暂时不做 太费钱 
			
			
		}
		
		
		// 无论前面是否,用户系统消息 下标更新
		$updateData = array();
		
		if($systemCount){
			$userProfile['basic']['msgid'] = $list[$systemCount - 1]['id'];
			$updateData['msgid'] = $userProfile['basic']['msgid'];
		}
		
		if($userPmCount){
			$userProfile['basic']['pm_id'] = $userPmList[$userPmCount - 1]['id'];;
			$updateData['pm_id'] = $userProfile['basic']['pm_id'];
		}
		
		if($updateData){
			self::$CI->Member_Model->update($updateData,array('uid' => $uid));
			self::$CI->session->set_userdata(array(
				'profile' => $userProfile
			));
		}
		
		return $systemCount + $userPmCount;
	}
	
	
	/* ---------------以下站内 -------------------------------------------- */

	
	
	
	/**
	 * 添加一条站内信，后台自动发送
	 */
	public function pushPmMessageToUser($data,$uid){
		
		if(!$data['msg_type']){
			$data['msg_type'] = -1;
		}
		
		$this->setPmTableByUid($uid);
		$this->_pmMessageModel->_add($data);
	}
	
	
	/**
	 * 添加一条待发邮件记录，后台自动发送
	 */
	public function pushEmailMessageToUser($data,$uid){
		if(!$data['msg_type']){
			$data['msg_type'] = -1;
		}
		
		$this->setEmailTableByUid($uid);
		$this->_emailModel->_add($data);
	}
	
	/**
	 * 添加邮件记录到管理员
	 */
	public function emailToSiteAdmin(){
		
		
	}
	
	
	/**
	 * 批量添加
	 */
	public function addMutiEmailMessageToUser($data,$uid = 0){
		$this->setEmailTableByUid($uid);
		
		return $this->_emailModel->batchInsert($data);
	}
	
	
	
	/**
	 * 获得邮件队列中的数据
	 */
	public function getEmailListByQueueId($condition,$id){
		$this->_emailModel->setTableId($id);
		return $this->_emailModel->getList($condition);
	}
	
	
	/* ----------------以下邮件相关----------------------------------------------- */
	
	/**
	 * 初始化邮件发送设置
	 */
	public function initEmail($setting){
		if(!$this->_email){
			
			$this->_setting = $setting;
			
    		$emailConfig = config_item('aliyun_dm');
    		$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $emailConfig['api_key'], $emailConfig['api_secret']);
    		$this->_email = new DefaultAcsClient($iClientProfile);
    		//$this->_emailRequest = new Dm\SingleSendMailRequest();
		    $this->_emailRequest->setAccountName($emailConfig['account']);
		    $this->_emailRequest->setFromAlias($this->_setting['site_name']);
		    $this->_emailRequest->setAddressType(1);
		    $this->_emailRequest->setReplyToAddress("false");
		}
		
		
		/*
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
		*/
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
	    $this->_emailRequest->setToAddress($to);        
	    $this->_emailRequest->setSubject($subject);
	    $this->_emailRequest->setHtmlBody($content);
	    
	    $flag = false;
	    
	    try {
	        $response = $this->_email->getAcsResponse($this->_emailRequest);
	        $flag = true;
	        //echo $response->RequestId;
	        //print_r($response);
	    }
	    catch (ClientException  $e) {
	        //print_r($e->getErrorCode());   
	        //print_r($e->getErrorMessage());   
	    }
	    catch (ServerException  $e) {        
	        //print_r($e->getErrorCode());   
	        //print_r($e->getErrorMessage());
	    }
		
		/*
		$this->_email->to($to);
		$this->_email->from($this->_setting['email_addr']);
		$this->_email->subject($subject);
		$this->_email->message($content);
		$this->_email->set_mailtype('html');
		
		$flag = $this->_email->send();
		*/
		
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
