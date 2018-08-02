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
			'OrderStatus' => OrderStatus::$statusName
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
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
			'where' => array_merge(array(),$moreSearchVal),
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
		
		$this->_searchCondition(array(
			'status' => OrderStatus::$refounding
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
	 * 批量审核
	 */
	public function batch_verify(){
		
		if($this->isPostRequest()){
				
			$this->_getVerifyRules();
			
			for($i = 0; $i < 1 ; $i++){
			
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				$id = $this->input->post('id');
				$idAr = explode(',',$id);
				$op = $this->input->get_post('op');
	
				$returnVal = $this->order_service->orderVerify(array(
					'op' => $op,
					'id' => $idAr,
					'reason' => $this->input->post('reason'),
					'remark' => $this->input->post('remark')
				),$this->_reqtime, $this->addWhoHasOperated('verify'));
				
			
				if($returnVal < 0){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('redirectUrl' => $this->lastUrl));
			}
		}else{
			$this->assign('id',implode(',',$this->input->get_post('id')));
			
			$this->assign(array(
				'reasonList' => $this->basic_data_service->getTopChildList('退款原因'),
			));
			$this->display();
		}
	}
	
	/**
	 * 解析自定义额外信息
	 */
	private function _staffOrderExtra($extraJson){
		
		$item = array();
		
		foreach ($extraJson as $key => $value) {
			
			if('cart' == $key){
				$item['extra_info']=$item['extra_info']."预约人姓名: ";
				foreach ($value as $key2 => $value2) {
					$item['extra_info'] = $item['extra_info'].$value2['name']." ";
		 		}
			}else if('meet_time' == $key){
				$item['extra_info']=$item['extra_info']."面试时间: ".$value." ";
			}else if('address' == $key){
				$item['extra_info']=$item['extra_info']."面试地址: ".$value." ";
			}else if('reason' == $key){
				$item['extra_info']=$item['extra_info']."退款原因: ".$value." ";
			}else if('remark' == $key){
				$item['extra_info']=$item['extra_info']."备注: ".$value." ";
			}		
	 	}
		
		return $item;
	}
	
	
	/**
	 * 解析物业能耗费自定义数据
	 */
	private function _wuyeOrderExtra($extraJson){
		
		$item = array();
		
		foreach( $extraJson as $key => $value ){
			if('fee_start'  == $key){
				$value =date('Y-m-d', $value);
				$item['extra_info']=$item['extra_info']."上次缴费到期时间: ".$value." ";
			}else if('fee_expire'  == $key){
				$value =date('Y-m-d', $value);
				$item['extra_info']=$item['extra_info']."本次缴费到期时间: ".$value." ";
			}else if('reason' == $key){
	 		 	$item['extra_info']=$item['extra_info']."退款原因: ".$value." ";
	 		}else  if('remark' ==$key){
	 		 	$item['extra_info']=$item['extra_info']."备注: ".$value." ";
	 		}
	 	}
	 	
	 	return $item;
	}
	
	/**
	 * 转译
	 */
	
	private function _valueChange($info){
		
		$item= $info;
		
		switch($info['pay_channel']){
			case 1:
				$item['pay_channel']="微信支付";
				break;
			case 2:
				break;
			default:
				break;
		}
		switch($info['pay_method']){
			case 1001:
				$item['pay_method']="小程序支付";
				break;
			case 1:
				break;
			default:
				break;
		}
		
	
		if(!empty($info['extra_info'])){
			
			$item['extra_info'] ="";
			$arr = json_decode($info['extra_info'],true);
			
			
			if(strpos($info['order_typename'],'预约单') !== false){
				$item = $this->_staffOrderExtra($arr);
			}else if(strpos($info['order_typename'],'报修') !== false){
				//@TODO 待完成
			}else{
				$item = $this->_wuyeOrderExtra($arr);
			}
		}
		return $item;
		
	}	
	
	
	public function detail(){
		
		$id = $this->input->get_post('id');
		$info = $this->Order_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id,'title' => $this->_moduleTitle.'详情');
		
		$item = array();	
		$item = $this->_valueChange($info);
  			
  		$this->assign('info',$info);
		$this->assign('item',$item);
		
		$this->assign(array(
			'reasonList' => $this->basic_data_service->getTopChildList('退款原因'),
		));
		
		$this->display();
	}
	/**
	 * 退款
	 * 
	 */
	public function refund(){
		
		$id = $this->input->get_post('id');
		$info = $this->Order_Model->getFirstByKey($id);
		$this->_subNavs[] = array('url' => $this->_className.'/refund?id='.$id, 'title' => '审核');
		
		if($this->isPostRequest()){
			
			$this->_getVerifyRules();
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				$op =$this->input->get_post('op');
				
				$this->order_service->setWeixinAppConfig(config_item('mp_xcxCswy'));
				
				$returnVal = $this->order_service->createRefundOrder($info);

				if(empty($returnVal)){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
			}
		}else{
			
			$info = $this->Order_Model->getFirstByKey($id);	
			$this->assign(array(
				'info' => $info,		
			));		
			$this->assign(array(
				'reasonList' => $this->basic_data_service->getTopChildList('退款原因'),
			));
			$this->display();
		}
		
	}
	
	
	
	
}
