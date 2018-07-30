<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Ydzj_Admin_Controller {
	
	public $_moduleTitle;
	public $_className;
	
	public $statusConfig = array();
	
	
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Order_service','Attachment_service'));
		
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
		
		
		$this->_moduleTitle = '订单';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/unpay','title' => '未支付'),
			array('url' => $this->_className.'/payed','title' => '已支付'),
			array('url' => $this->_className.'/cancel','title' => '已取消'),
			array('url' => $this->_className.'/deleted','title' => '已删除'),
		);
		
		
		$this->statusConfig = array(
			'未提交',
			'待审核',
			'已审核'
		);
		
		$this->assign(array(
			'statusConfig' => $this->statusConfig,
		));
	}
	
	
	
	/**
	 * 查询条件
	 */
	public function _searchCondition($moreSearchVal = array()){
		
		
		
		$search['currentPage'] = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$search['order_id'] = $this->input->get_post('order_id') ? $this->input->get_post('order_id') : '';
		$search['amount_s'] = $this->input->get_post('amount_s') ? $this->input->get_post('amount_s') : '';
		$search['amount_e'] = $this->input->get_post('amount_e') ? $this->input->get_post('amount_e') : '';
		
		//$search['status'] = $this->input->get_post('status') ? $this->input->get_post('status') : '';
		
		$search['mobile'] = $this->input->get_post('mobile') ? $this->input->get_post('mobile') : '';
	
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
	
		//print_r($condition);
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
	public function cancel(){
		$this->_searchCondition(array(
			'status' => OrderStatus::$closed
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	/**
	 * 未审核 列表
	 */
	public function unverify(){
		
		$this->_searchCondition(array(
			'status' => '待审核'
		));
		$this->display($this->_className.'/index');
		
	}
	
	
	/**
	 * 已审核列表
	 */
	public function verify(){
		
		$this->_searchCondition(array(
			'status' => '已审核'
		));
		
		
		$this->display($this->_className.'/index');
	}
	
	
	/**
	 * 提交审核
	 */
	public function handle_verify(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			
			
			if($returnVal > 0){
				$this->jsonOutput('提交审核成功',$this->getFormHash());
			}else{
				$this->jsonOutput('提交审核失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	
	/**
	 * 单个审核
	 */
	public function single_verify(){
		
		$feedback = '';
		$inPost = false;
		$id = $this->input->get_post('id');
		
		$info = array();
		
		$info = $this->staff_service->getStaffInfoById($id);
		$fileList = array();
		$workerFileList = array();
		
		
		if($info['worker_id']){
			$workerInfo = $this->Worker_Model->getFirstByKey($info['worker_id']);
			$this->assign('workerInfo',$workerInfo);
			$workerFileList = $this->Worker_Images_Model->getImagesListByWorkerId($info['worker_id']);
		}
		
		
		$this->_subNavs[] = array('url' => $this->_className.'/single_verify?id='.$id, 'title' => '审核');
		
		if($this->isPostRequest()){
			$this->_getVerifyRules();
			
			$inPost = true;
			
			for($i = 0; $i < 1; $i++){
				//$fileList = $this->_getFileList();
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$op = $this->input->get_post('op');
				
				$returnVal = $this->staff_service->staffVerify(array(
					'op' => $op,
					'id' => array($id),
					'reason' => $this->input->post('reason')
				),$this->_reqtime, $this->addWhoHasOperated('verify'));
				
				
				if($returnVal < 0){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
				
			}
		}else{
			$fileList = $this->Staff_Images_Model->getImagesListByStaffId($id);
			
			
			//print_r($info);
		
			$this->_commonPageData();
			$this->assign(array(
				'fileList' => $fileList,
				'workerFileList' => $workerFileList,
				'inPost' => $inPost,
				'info' => $info,
				'feedback' => $feedback,
			));
			
			$this->display($this->_className.'/single_verify');
		}
		
		
		
	}
	
	
	private function _getVerifyRules(){
		
		$this->form_validation->set_rules('id','记录ID必填','required');
		$this->form_validation->set_rules('reason','备注','required|min_length[2]|max_length[100]');
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
				
				
				$returnVal = $this->staff_service->staffVerify(array(
					'op' => $op,
					'id' => $idAr,
					'reason' => $this->input->post('reason')
				),$this->_reqtime, $this->addWhoHasOperated('verify'));
				
				
				if($returnVal < 0){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				$this->jsonOutput($op.'操作成功',array('jsReload' => true));
			}
			
		}else{
			
			$this->assign('id',implode(',',$this->input->get_post('id')));
			
			$this->display($this->_className.'/batch_verify');
		}
		
	}
	
	
	/**
	 * 校验规则
	 */
	private function _getRules(){
		$this->staff_service->addWorkerRules();
		$this->staff_service->addServRule($this->_moduleTitle);
	}
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			
			$this->jsonOutput('删除失败，待开发完善',$this->getFormHash());
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 批量关闭
	 */
	public function batch_close(){
		
		
	}
	
	
	/**
	 * 准备数据
	 */
	private function _prepareData($action = 'add'){
		
		$info = array();
		$remark = $this->input->post('remark');
		
		if($remark){
			$info['remark'] = $remark;
		}
		
		
		return $info;
	}
	
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		
		$info = array();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			
			for($i = 0; $i < 1; $i++){
				
				
			}
		}else{
			
		}
		
		//print_r($servAblity);
		
		
		$this->_commonPageData();
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
		));
		
		$this->display();
	}
	
	
	
	
	
	
	/**
	 * 详情
	 */
	public function detail(){
		
		$feedback = '';
		$inPost = false;
		$id = $this->input->get_post('id');
		
		$info = array();
		
		
		$info = $this->staff_service->getStaffInfoById($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '详情');
		
		if($this->isPostRequest()){
			
		}else{
			
			$fileList = $this->Staff_Images_Model->getImagesListByStaffId($id);
		}
		
		$this->_commonPageData();
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
		));
		
		$this->display($this->_className.'/add');
	}
	
	
	
	
	
	
	
	
}
