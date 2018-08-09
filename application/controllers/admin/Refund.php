<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refund extends Ydzj_Admin_Controller {
	
	public $_moduleTitle;
	public $_className;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Order_service','Basic_data_service'));
		
		$this->_moduleTitle = '退款订单';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'OrderStatus' => OrderStatus::$statusName,
			'OrderVerify' => OrderVerify::$statusName,
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/unverify','title' => '未审核'),
			array('url' => $this->_className.'/verifyok','title' => '已审核'),
			array('url' => $this->_className.'/sendback','title' => '已退回'),
		);

	}
	
	
	
	/**
	 * 查询条件
	 */
	public function _searchCondition($moreSearchVal = array()){
		$search['currentPage'] = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$search['order_id'] = $this->input->get_post('order_id') ? $this->input->get_post('order_id') : '';
		$search['amount_s'] = $this->input->get_post('amount_s') ? $this->input->get_post('amount_s') : '';
		$search['amount_e'] = $this->input->get_post('amount_e') ? $this->input->get_post('amount_e') : '';
			
		$search['mobile'] = $this->input->get_post('mobile') ? $this->input->get_post('mobile') : '';
		$search['add_username'] = $this->input->get_post('add_username') ? $this->input->get_post('add_username') : '';
		$search = array_merge($search,$moreSearchVal);
		
		$condition = array(
			'where' => array_merge(array('is_refund' => 1),$moreSearchVal),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $search['currentPage'],
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		if($search['order_id']){
			$condition['like']['order_id'] = $search['order_id'];
		}
		
		if($search['amount_s']){
			$condition['where']['amount >='] = $search['amount_s'];
		}
		
		if($search['amount_e']){
			$condition['where']['amount <='] = $search['amount_e'];
		}
		
		if($search['mobile']){
			$condition['where']['mobile'] = $search['mobile'];
		}
		
		if($search['add_username']){
			$condition['like']['add_username'] = $search['add_username'];
		}
		
		$list = $this->Order_Model->getList($condition);
		
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $search['currentPage']
		));
		
	}
	
	/**
	 * 获得列表
	 */
	public function index(){
		
		$this->_searchCondition();
		$this->display($this->_className.'/index');
	}
	
	
	/**
	 * 未审核
	 */
	public function unverify(){
		
		$this->_searchCondition(array(
			'verify_status' => OrderVerify::$unVerify
		));
		
		$this->display($this->_className.'/index');
	}
	
	/**
	 * 已审核
	 */
	public function verifyok(){
		
		$this->_searchCondition(array(
			'verify_status' => OrderVerify::$verifyOK
		));
		
		$this->display($this->_className.'/index');
	}
	
	/**
	 * 已退回
	 */
	public function sendback(){
		
		$this->_searchCondition(array(
			'verify_status' => OrderVerify::$sendBack
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	
	/**
	 *设置规则
	 */
	private function _getVerifyRules(){
		
		$this->form_validation->set_rules('id','记录ID必填','required');
		$this->form_validation->set_rules('reason','原因','required|min_length[2]|max_length[100]');
		$this->form_validation->set_rules('remark','备注','required|min_length[2]|max_length[100]');
	}
	
	/**
	 * 审核
	 */
	public function verify(){
		
		
		if($this->isPostRequest()){
				
			$this->form_validation->set_rules('id','记录ID必填','required');
			$this->form_validation->set_rules('remark','备注','required|min_length[2]|max_length[100]');
			
			for($i = 0; $i < 1 ; $i++){
			
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败',array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				//目前只能单条
				$id = $this->input->post('id');
				//$idAr = explode(',',$id);
				$op = $this->input->get_post('op');
				
				$refundOrder = $this->order_service->getOrderInfoById($id);
				$updateData = array(
					'verify_time' => $this->_reqtime,
				);
				
				
				switch($op){
					case '审核通过':
						$updateData['verify_status'] = OrderVerify::$verifyOK;
						break;
					case '退回':
						$updateData['status'] = OrderStatus::$closed;
						$updateData['verify_status'] = OrderVerify::$sendBack;
						break;
					default:
						break;
				}
				
				
				$updateData['extra_info'] = json_encode(array_merge($refundOrder['extra_info'],array('verify_remark' => $this->input->post('remark'))));
				$updateData = array_merge($updateData,$this->addWhoHasOperated('verify'));
				
				$affectRow = $this->Order_Model->updateByCondition($updateData,array(
					'where' => array(
						'id' => $id,
						'status' => OrderStatus::$refounding
					)
				));
				
				if($affectRow <= 0){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
			}
		}else{
			$this->assign(array(
				'id' => $this->input->get_post('id'),
			));
			
			$this->display();
		}
	}
	
	
	
	/**
	 * 订单详情
	 */
	public function detail(){
		
		
		$this->session->set_userdata('jumpUrl',$this->lastUrl);
		
		$id = $this->input->get_post('id');
		$info = $this->order_service->getOrderInfoById($id);
		
		if(empty($id)){
			$orderId = $this->input->get_post('order_id');
			$info = $this->order_service->getOrderInfoById($orderId,'order_id');
			
			$this->_subNavs[] = array('url' => $this->_className.'/detail?order_id='.$orderId,'title' => $this->_moduleTitle.'详情');
			
		}else{
			$info = $this->order_service->getOrderInfoById($id);
			
			$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id,'title' => $this->_moduleTitle.'详情');
			
		}
		
		$showSubmit = false;
		
		if($info['verify_status'] == OrderVerify::$unVerify && $info['status'] == OrderStatus::$refounding){
			$showSubmit = true;
		}
			
		$this->assign(array(
			'info' => $info,
			'extraItem' => $this->order_service->extraInfoToArray($info),
			'showSubmit' => $showSubmit,
			'lastUrl' => $this->session->userdata('jumpUrl'),
			'reasonList' => $this->basic_data_service->getTopChildList('退款原因'),
		));
		
		$this->display();
	}
	
	
	
	
	/**
	 * 真正退款
	 */
	public function refund(){
		
		
		$id = $this->input->get_post('id');
		
		$info = $this->order_service->getOrderInfoById($id);
		$this->_subNavs[] = array('url' => $this->_className.'/refund?id='.$id, 'title' => '退款');
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$op =$this->input->get_post('op');
				$this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
				
				$refundOrder = $this->order_service->getOrderInfoById($id);
				
				/*
				if($refundOrder['verify_status'] != OrderVerify::$verifyOK){
					$this->jsonOutput('订单未审核');
					break;
				}
				*/
				
				
				if($refundOrder['status'] != OrderStatus::$refounding){
					$this->jsonOutput('订单状态错误');
					break;
				}
				
				if($refundOrder['status'] == OrderStatus::$refounded){
					$this->jsonOutput('订单已退款完成');
					break;
				}
				
				
				//业务处理
				$filePath = Order_service::$orderType['nameKey'][$refundOrder['order_typename']]['refund_url'];
				
				if($filePath){
					$fullPath = LIB_PATH.$filePath;
					$this->load->file($fullPath);
					$className = basename($fullPath,'.php');
					$refundObj = new $className;
					$refundObj->setController($this);
					
					$message = '退款失败';
					
					$isOk = $this->order_service->requestWeixinRefund($refundOrder,$refundObj,$message);
					
					if(!$isOk){
						$this->jsonOutput($message);
						break;
					}
				}
				
				
				//@TODO 微信通知用户
				
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
			}
		}else{
			
			$this->assign(array(
				'info' => $info,
				'extraItem' => $this->order_service->extraInfoToArray($info),
				'showSubmit' => ($info['verify_status'] == OrderVerify::$verifyOK && $info['status'] == OrderStatus::$refounding)
			));	
			
			$this->display();
		}
		
	}
	
	
	
	/**
	 * 申请退款
	 * 
	 */
	public function apply_refund(){
		
		$id = $this->input->get_post('id');
		
		$info = $this->order_service->getOrderInfoById($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/apply_refund?id='.$id, 'title' => '审核');
		
		if($this->isPostRequest()){
			
			$this->_getVerifyRules();
			
			$this->form_validation->set_rules('refund_amount','退款金额','required|is_numeric|greater_than[0]|less_than_equal_to['.(($info['amount'] - $info['refund_amount'])/100).']');
			$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$op =$this->input->get_post('op');
				
				$this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
				
				//更新相关信息
				$info = array_merge($info,$this->addWhoHasOperated('add'));
				$info['amount'] = intval(100 * $this->input->post('refund_amount')); 
				
				
				$info['extra_info'] = array_merge($info['extra_info'],array(
					'reason' => $this->input->post('reason'),
					'remark' => $this->input->post('remark'),
				));
				
				$isNewRefund = true;
				
				$refundOrderInfo = $this->order_service->createRefundOrder($info,$isNewRefund);
				if(empty($refundOrderInfo)){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				if(!$isNewRefund){
					$this->jsonOutput('退款申请失败,您已经有一笔该退款订单号:'.$refundOrderInfo['order_id'].'为的退款订单');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
			}
		}else{
			
			$this->assign(array(
				'info' => $info,
				'reasonList' => $this->basic_data_service->getTopChildList('退款原因'),
			));		
			
			$this->display();
		}
		
	}
	
}
