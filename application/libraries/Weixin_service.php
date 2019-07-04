<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weixin_service extends Base_service {
	
	private $_mpApi;
	
	
	//发送失败日志
	private $_weixinMessageModel;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library(array('Weixin_mp_api'));
		
		self::$CI->load->model('Weixin_Message_Model','Member_Model','Yewu_Model');
		
		$this->_mpApi = self::$CI->weixin_mp_api;
		
		$this->_weixinMessageModel = self::$CI->Weixin_Message_Model;
		$this->_memberModel = self::$CI->Member_Model;
	}
	
	
	/**
	 * 初始化 小程序api 对象
	 */
	public function setConfig($tempConfig){
		$this->_mpApi->initSetting($tempConfig);
	}
	
	
	/**
	 * 获得微信
	 */
	public function getWeixinUserByCode($pCode){
		//检查 code 
		$resp = $this->_mpApi->getWeixinUserByCode($pCode);
		
		if(!empty($resp['session_key'])){
			return $resp;
		}else{
			
			return false;
		}
	}
	
	
	/**
	 * 检查绑定状态
	 */
	public function checkUserBind($pWeixinUser){
		
		$bind = array(
			'isBind' => false
		);
		
		$userInfo = $this->getBindUser($pWeixinUser);
		if($userInfo['uid']){
			$bind['isBind'] = true;
		}
		
		return $bind;
		
	}
	
	
	 /**
     * 获得绑定用户
     */
    public function getBindUser($wxResp){
		
		$userInfo = array();
		$mpConfig = $this->_mpApi->getSetting();
		
		if($wxResp['unionid']){
			$userInfo = self::$memberModel->getList(array(
				'where' => array(
					'unionid' => $wxResp['unionid'],
				),
				'limit' => 1
			));
			
		}else if($wxResp['openid']){
			$userInfo = self::$memberModel->getList(array(
				'where' => array(
					'openid' => $wxResp['openid'],
				),
				'limit' => 1
			));
		}
		
		return $userInfo[0];
	}
	
	
	/**
	 * 绑定手机号码
	 */
		public function bindMobile($param,$weixinInfo,&$message = null){
		$memberinfo = self::$memberModel->getFirstByKey($param['phoneNo'],'mobile');
		if($memberinfo){
			if($memberinfo['openid']){
				$message = '该号码已被绑定';
				return false;
			}else{
				self::$memberModel->deleteByCondition(array('where' =>array('openid' => $weixinInfo['openid'])));
				self::$memberModel->updateByWhere(array('openid' => $weixinInfo['openid'],'name' => $param['name']),array('uid' => $memberinfo['uid']));
				self::$memberExtendModel->updateByWhere(array('name' => $param['name'],'mobile' => $memberinfo['mobile'],'member_uid' =>$memberinfo['uid']),array('uid' => $weixinInfo['openid']));
				return $memberinfo['uid'];
			}
		}
		self::$CI->load->library(array('Register_service'));
		$regData = array(
			'name' => $param['name'],
			'mobile' => $param['phoneNo'],
			'openid' => $weixinInfo['openid'],
			'unionid' => $weixinInfo['unionid'] ? $weixinInfo['unionid'] : '',
			'user_type' => 1, //默认新绑定的用户为普通用户
			'channel' => 1,   //小程序注册进入的
		);
		$member = self::$memberExtendModel->getFirstByKey($regData['openid'],'uid');
		
		self::$memberExtendModel->beginTrans();
		self::$memberExtendModel->update($regData,array('id' => $member['id']));
		
		if($member){
			$user = $this->_memberModel->getFirstByKey($member['member_uid'],'uid');
			if($user){
				self::$memberExtendModel->updateByWhere(array('name' => $regData['name'],'mobile' => $regData['mobile']),array('uid' => $regData['openid']));
				self::$memberModel->updateByWhere(array('name' => $regData['name'],'user_type' => $regData['user_type'],'mobile' => $regData['mobile']),array('uid' => $user['uid']));
			}

		}
		$member = self::$memberExtendModel->getFirstByKey($regData['openid'],'uid');
		
		if(self::$memberExtendModel->getTransStatus() === FALSE){
			self::$memberExtendModel->rollBackTrans();
			return false;
		}else{
			self::$memberExtendModel->commitTrans();
			return $member['member_uid'];
		}
	}

	
	
	/**
	 * 记入重发队列
	 */
	public function queueSend($pOrderInfo,$pData,$pWeixinResp){
		
		//发送失败
    	return $this->_weixinMessageModel->_add(array(
    		'uid' => $pOrderInfo['uid'],
    		'order_id' => $pOrderInfo['order_id'],
    		'content' => json_encode($pData),
    		'resp' => json_encode($pWeixinResp)
    	));
    	
	}
	
	
	/**
	 * 发送微信通知
	 */
	public function sendWeixinNotify($pData){
		$isSendOk = false;
		$resp = $this->_mpApi->sendTemplateMsg($pData);
	    
	    if($resp && !$resp['errcode']){
	    	$isSendOk = true;
	    }
		
		return $isSendOk;
	}
	
	
	/**
	 * 公共动作
	 */
	public function weixinNotifyCommon($pOrderInfo,$pData){
		
		/*
		$sendList = $this->_weixinMessageModel->getList(array(
			'select' => 'id,content,retry_cnt',
			'where' => array(
				'status' => 0,
				'retry_cnt <=' => 2 
			),
			'limit' => 10
		));
		
		
		$deleteId = array();
		
		foreach($sendList as $item){
			$isSendOk = false;
			
			$resp = $this->sendWeixinNotify(json_decode($item['content'],true));
			
			if($resp && !$resp['errcode']){
		    	$isSendOk = true;
		    }
	    	
	    	if($isSendOk){
	    		$deleteId[] = $item['id'];
	    	}else{
	    		$this->_weixinMessageModel->updateByWhere(array(
		    		'retry' => $item['retry'] + 1,
		    		'resp' => json_encode($resp),
		    	),array('id' => $item['id']));
	    	}
		}
		
		
		if($deleteId){
			$this->_weixinMessageModel->deleteByCondition(array(
				'where_in' => array(
				
					array('key' => 'id', 'value' => $deleteId)
				)
			));
		}
		*/
		
		
		$isSendOk = false;
		
	    $resp = $this->_mpApi->sendTemplateMsg($pData);
	    
	    //file_put_contents('notify.txt',print_r($resp,true));
	    
	    if($resp && !$resp['errcode']){
	    	$isSendOk = true;
	    }
	    
	   	if(!$isSendOk){
	   		$this->queueSend($pOrderInfo,$pData,$resp);
	   	}
		
		return $isSendOk;
		
	}
	
	
	/**
	 * 物业交费 成功通知
	 */
	public function wuyeOrderNotify($orderInfo){
		
        $weixinUser = self::$CI->Member_Model->getFirstByKey($orderInfo['uid'],'uid','openid');
        
		$data = array(
	    	'touser' => $weixinUser['openid'],
	    	'template_id' => 'ouyjN79THMoy-aVNE4bFkHaj6gRLcw2w8hRAWJ6VCRA',
	    	'page' => "pages/select/select?id=".$orderInfo['order_id'],
	    	'form_id' => $orderInfo['prepay_id'],
	    	'data' => array(
	    	  'keyword1' => array(
			       "value" => $orderInfo['order_id'],
			   ),
			   'keyword2' => array(
			       "value" => sprintf("%.2f",$orderInfo['amount']/100),
			   ),
			   'keyword3' => array(
			       "value" => date('Y-m-d H:i:s'),
			   ),
			   'keyword4' => array(
			       "value" => $orderInfo['yewu_name'],
			   ),
			   'keyword5' => array(
					"value" => $orderInfo['order_typename'],
			   ),
   			   'keyword6' => array(
					 "value" => "微信支付",
			   ),
   			   'keyword7' => array(
					 "value" => sprintf("%.2f",$orderInfo['amount']/100),
			   ),
	    	)
	    );
	    
	    //return $this->queueSend($orderInfo,$data,array());
	    
	    return $this->weixinNotifyCommon($orderInfo,$data);
		
	}
	
	

	public function yewuNotify($yewuId,$status){
        $yewuInfo = self::$CI->Yewu_Model->getFirstByKey($yewuId);
        $weixinUser = self::$CI->Member_Model->getFirstByKey($yewuInfo['add_uid'],'uid','openid');
        
		$data = array(
	    	'touser' => $weixinUser['openid'],
	    	'template_id' => 'P6nesPjbqa5VkywJfGiLOq2wDu-uXuC9ZBRY7YqHUbs',
	    	'page' => "pages/index/index?url=/pages/order/orderdetail/orderdetail?id=".$yewuInfo['id'],
	    	'form_id' => $yewuInfo['from_id'],
	    	'data' => array(
	    	  'keyword1' => array(
			       "value" => $status,
			   ),
			   'keyword2' => array(
			       "value" => date('Y-m-d H:i:s',$yewuInfo['gmt_create']),
			   ),
			   'keyword3' => array(
			       "value" => $yewuInfo['user_name'],
			   ),
   			   'keyword4' => array(
			       "value" => $yewuInfo['worker_name'],
			   ),
			   'keyword5' => array(
			       "value" => $yewuInfo['worker_mobile'],
			   ),
			   'keyword6' => array(
			       "value" => date('Y-m-d H:i:s'),
			   ),
	    	)
	    );
	    
	    
	    //return $this->queueSend($orderInfo,$data,array());
	    
	    return $this->weixinNotifyCommon($yewuInfo,$data);
		
	}
	
	
	
	/**
	 * 发送退款通知
	 */
	public function refundOrderNotify($orderInfo){
        
        $weixinUser = self::$CI->Member_Model->getFirstByKey($orderInfo['uid'],'uid','openid');
        
        if(!is_array($orderInfo['extra_info'])){
        	$orderInfo['extra_info'] = json_decode($orderInfo['extra_info'],true);
        }
        
		$data = array(
	    	'touser' => $weixinUser['openid'],
	    	'template_id' => 'ekGV7_CKqlcFmC1Sh0Pchfl63tmSgygE2uruSvGhsJw',
	    	'page' => "pages/select/select?id=".$orderInfo['order_id'],
	    	'form_id' => $orderInfo['prepay_id'],
	    	'data' => array(
	    	  'keyword1' => array(
			       "value" => $orderInfo['order_id'],
			   ),
			   'keyword2' => array(
			       "value" => date('Y-m-d H:i:s'),
			   ),
			   'keyword3' => array(
			       "value" => $orderInfo['yewu_name'],
			   ),
			   'keyword4' => array(
			       "value" => sprintf("%.2f",$orderInfo['amount']/100),
			   ),
			   'keyword5' => array(
			       "value" => $orderInfo['extra_info']['reason'],
			   ),
			   'keyword6' => array(
			       "value" => isset($orderInfo['extra_info']['verify_remark']) == true ? $orderInfo['extra_info']['verify_remark'] : '无'
			   )
	    	)
	    );
	    
	    
	    //return $this->queueSend($orderInfo,$data,array());
	    
	    return $this->weixinNotifyCommon($orderInfo,$data);
	}
	
	
}
