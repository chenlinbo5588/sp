<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weixin_service extends Base_service {
	
	private $_mpApi;
	
	
	//发送失败日志
	private $_weixinMessageModel;
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library(array('Weixin_mp_api'));
		
		self::$CI->load->model('Weixin_Message_Model');
		
		$this->_mpApi = self::$CI->weixin_mp_api;
		
		$this->_weixinMessageModel = self::$CI->Weixin_Message_Model;
		
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
	public function bindMobile($param,$weixinInfo){
		
		self::$CI->load->library(array('Register_service'));
		$regData = array(
			'username' => $param['phoneNo'],
			'nickname' => $param['phoneNo'],
			'mobile' => $param['phoneNo'],
			'openid' => $weixinInfo['openid'],
			'unionid' => $weixinInfo['unionid'] ? $weixinInfo['unionid'] : '',
			'channel' => 1,   //小程序注册进入的
		);
		
		self::$memberModel->beginTrans();
		$resp = self::$CI->register_service->createMember($regData);
		
		if($resp['data']['uid']){
			self::$CI->load->model('Yezhu_Model');
			//更新业主表中  对应的 uid
			
			$yezhuInfo = self::$CI->Yezhu_Model->getFirstByKey($param['phoneNo'],'mobile');
			
			if($yezhuInfo){
				self::$memberModel->updateByWhere(array(
					'username' => $yezhuInfo['name'],
					'sex' => $yezhuInfo['sex'],
				),array(
					'uid' => $resp['data']['uid']
				));
				
				self::$CI->Yezhu_Model->updateByWhere(array('uid' => $resp['data']['uid']),array('mobile' => $param['phoneNo']));
			}
			
		}
		
		if(self::$memberModel->getTransStatus() === FALSE){
			self::$memberModel->rollBackTrans();
			return false;
		}else{
			self::$memberModel->commitTrans();
			return $resp['data']['uid'];
		}
	}
	
	
	/**
	 * 记入重发队列
	 */
	public function queueResend($pOrderInfo,$pData,$pWeixinResp){
		
		//发送失败
    	return $this->_weixinMessageModel->_add(array(
    		'uid' => $pOrderInfo['uid'],
    		'order_id' => $pOrderInfo['order_id'],
    		'content' => json_encode($pData),
    		'resp' => json_encode($pWeixinResp)
    	));
    	
	}
	
	/**
	 * 公共动作
	 */
	public function weixinNotifyCommon($pOrderInfo,$pData){
		
		$isSendOk = false;
		
	    $resp = $this->_mpApi->sendTemplateMsg($pData);
	    
	    if($resp && !$resp['errcode']){
	    	$isSendOk = true;
	    }
	    
	   	if(!$isSendOk){
	   		$this->queueResend($pOrderInfo,$pData,$resp);
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
	    	'template_id' => 'thLs5shnt45ne7y6IWy9sIfJNrUxgMuFG3lN4dK2MvA',
	    	'page' => "pages/select/select?id=".$orderInfo['order_id'],
	    	'form_id' => $orderInfo['prepay_id'],
	    	'data' => array(
	    	  'keyword1' => array(
			       "value" => $orderInfo['order_id'],
			   ),
			   'keyword2' => array(
			       "value" => $orderInfo['order_typename'],
			   ),
			   'keyword3' => array(
			       "value" => date('Y-m-d H:i:s'),
			   ),
			   'keyword4' => array(
			       "value" => $orderInfo['goods_name'],
			   ),
			   'keyword5' => array(
			       "value" => sprintf("%.2f",$orderInfo['amount']/100),
			   ),
			   'keyword6' => array(
			       "value" => '缴费起止时间: 从'.date('Y年m月d日',$orderInfo['extra_info']['newStartTimeStamp']).'到'.date('Y年m月d日',$orderInfo['extra_info']['newEndTimeStamp']),
			   ),
	    	)
	    );
	    
	    
	    return $this->weixinNotifyCommon($orderInfo,$data);
		
	}
	
	
	/**
	 * 发送微信 月嫂 、保姆、 护工
	 */
	public function staffOrderNotify($orderInfo){
        
        $weixinUser = self::$CI->Member_Model->getFirstByKey($orderInfo['uid'],'uid','openid');
        
        $cartList = $orderInfo['extra_info']['cart'];
        
		$data = array(
	    	'touser' => $weixinUser['openid'],
	    	'template_id' => 'AYpZNqIt3yAbbP6psq3lwXtIvbBAYgNoR-Afen5RgfY',
	    	'page' => "pages/select/select?id=".$orderInfo['order_id'],
	    	'form_id' => $orderInfo['prepay_id'],
	    	'data' => array(
	    	  'keyword1' => array(
			       "value" => $orderInfo['order_id'],
			   ),
			   'keyword2' => array(
			       "value" => $orderInfo['goods_name'],
			   ),
			   'keyword3' => array(
			       "value" => $orderInfo['extra_info']['booking_time'],
			   ),
			   'keyword4' => array(
			       "value" => $orderInfo['extra_info']['address'],
			   ),
			   'keyword5' => array(
			       "value" => date('Y-m-d H:i:s',$orderInfo['gmt_create']),
			   ),
			   'keyword6' => array(
			       "value" => count(array_keys($cartList)),
			   ),
			   'keyword7' => array(
			       "value" => $orderInfo['order_typename'],
			   ),
			   'keyword8' => array(
			       "value" => '正常',
			   ),
			   'keyword9' => array(
			       "value" => '',
			   ),
	    	)
	    );
	    
	    
	    return $this->weixinNotifyCommon($orderInfo,$data);
		
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
			       "value" => $orderInfo['goods_name'],
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
	    
	    
	    return $this->weixinNotifyCommon($orderInfo,$data);
	}
	
	
}
