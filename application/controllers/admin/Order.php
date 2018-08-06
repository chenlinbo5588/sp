<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Ydzj_Admin_Controller {
	
	public $_moduleTitle;
	public $_className;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Order_service'));
		
		$this->_moduleTitle = '订单';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'OrderStatus' => OrderStatus::$statusName,
			'OrderVerify' => OrderVerify::$statusName,
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/unpay','title' => '未支付'),
			array('url' => $this->_className.'/payed','title' => '已支付'),
			array('url' => $this->_className.'/closed','title' => '已关闭'),
			array('url' => $this->_className.'/deleted','title' => '已删除'),
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
			'where' => array_merge(array('is_refund' => 0),$moreSearchVal),
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
			$condition['where']['amount >='] = intval($search['amount_s']*100);
		}
		
		if($search['amount_e']){
			$condition['where']['amount <='] = intval($search['amount_e']*100);
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
			
			'status != ' => OrderStatus::$closed,
			'status   != ' => OrderStatus::$deleted,
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	
	/**
	 * 未支付
	 */
	public function unpay(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$unPayed
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	/**
	 * 已支付
	 */
	public function payed(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$payed
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	
	/**
	 * 已取消
	 */
	public function closed(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$closed
		));
		
		$this->display($this->_className.'/index');
	}
	
	/**
	 * 已删除
	 */
	public function deleted(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$deleted
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	
	/**
	 * 校验退款数据
	 */
	private function _getVerifyRules(){
		
		$this->form_validation->set_rules('id','记录ID必填','required');
		$this->form_validation->set_rules('reason','原因','required|min_length[2]|max_length[100]');
		$this->form_validation->set_rules('remark','备注','required|min_length[2]|max_length[100]');
	}
	
	/**
	 * 批量删除
	 */
	public function batch_delete(){
		
		$ids = $this->input->post('id');
		
		
	
		if($this->isPostRequest() && !empty($ids)){
		
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$affectRow = $this->order_service->updateOrderStatusByIds($ids,OrderStatus::$deleted,OrderStatus::$closed);
			
			if($affectRow){
				$this->jsonOutput('删除成功',array('jsReload' => true));
			}else{
				$this->jsonOutput('删除操作完成,没有订单被删除,只有已关闭的订单可以被删除');
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 删除
	 */
	public function single_delete(){
		
		$ids = $this->input->get_post('id');
		
		if($this->isPostRequest() && !empty($ids)){
		
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$affectRow = $this->order_service->updateOrderStatusByIds($ids,OrderStatus::$deleted,OrderStatus::$closed);
  			
  			if($affectRow){
				$this->jsonOutput('删除成功',array('jsReload' => true));
			}else{
				$this->jsonOutput('删除操作完成,没有订单被删除,只有已关闭的订单可以被删除',array('jsReload' => true));
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	/**
	 * 批量关闭
	 */
	public function batch_close(){
		
		$ids = $this->input->post('id');
	
		if($this->isPostRequest() && !empty($ids)){
		
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$affectRow = $this->order_service->updateOrderStatusByIds($ids,OrderStatus::$closed,OrderStatus::$unPayed);
			
			if($affectRow){
				$this->jsonOutput('关闭成功',array('jsReload' => true));
			}else{
				$this->jsonOutput('关闭操作完成,没有订单被关闭');
			}
			
		}else{
			
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	/**
	 * 关闭
	 */
	public function single_close(){
		
		$ids = $this->input->get_post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$affectRow = $this->order_service->updateOrderStatusByIds($ids,OrderStatus::$closed,OrderStatus::$unPayed);
			
			if($affectRow){
				$this->jsonOutput('关闭成功',array('redirectUrl' => $this->lastUrl));
			}else{
				$this->jsonOutput('关闭操作完成,没有订单被关闭',array('jsReload' => true));
			}
			
  			$this->session->unset_userdata('jumpUrl');
  			

		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
		
	}
	
	
	/**
	 * 详情
	 */
	public function detail(){
		
		$this->session->set_userdata('jumpUrl',$this->lastUrl);
		
		$id = $this->input->get_post('id');
		$info = $this->order_service->getOrderInfoById($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id,'title' => $this->_moduleTitle.'详情');
		
  		
		$this->assign(array(
			'info' => $info,
			'extraItem' => $this->order_service->extraInfoToArray($info)
		));
		
		
		$this->display();
		
	}

	
}
