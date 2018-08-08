<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Staff_booking extends Ydzj_Admin_Controller {
	
	private $_bookingStatus;
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Staff_service','Order_service'));
		$this->_moduleTitle = '预约单';
		$this->_className = strtolower(get_class());
		$this->_bookingStatus = array(
			OrderStatus::$payed => OrderStatus::$statusName[OrderStatus::$payed],
			OrderStatus::$refounding => OrderStatus::$statusName[OrderStatus::$refounding],
			OrderStatus::$refounded => OrderStatus::$statusName[OrderStatus::$refounded],
		);
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'bookingMeet' => BookingMeet::$statusName,
			'bookingStatus' => $this->_bookingStatus
		));
		


		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/canceled','title' => '已取消'
		));
	}
	
	/**
	 * 查询
	 */
	
	public function _searchCondition($moreSearchVal = array()){
		$search['currentPage'] = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$search['username'] = $this->input->get_post('username') ? $this->input->get_post('username') : '';
		$search['mobile'] = $this->input->get_post('mobile') ? $this->input->get_post('mobile') : '';
		$search['meet_result'] = $this->input->get_post('meet_result') ? $this->input->get_post('meet_result') : '';
		$search['order_status'] = $this->input->get_post('order_status') ? $this->input->get_post('order_status') : '';
		$search['staff_name'] = $this->input->get_post('staff_name') ? $this->input->get_post('staff_name') : '';
		$search['staff_mobile'] = $this->input->get_post('staff_mobile') ? $this->input->get_post('staff_mobile') : '';
		$search['order_id'] = $this->input->get_post('order_id') ? $this->input->get_post('order_id') : '';
		$search['order_refund'] = $this->input->get_post('order_refund') ? $this->input->get_post('order_refund') : '';
		
		
		$condition = array(
			'where' => array(),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $search['currentPage'],
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		$search = array_merge($search,$moreSearchVal);
		
		if($search['username']){
			$condition['like']['username'] = $search['username'];
		}
		if($search['mobile']){
			$condition['like']['mobile'] = $search['mobile'];
		}
		if($search['meet_result']){
			$condition['where']['meet_result'] = $search['meet_result'];
		}
		if($search['order_status']){
			$condition['where']['order_status'] = $search['order_status'];
		}
		if($search['staff_name']){
			$condition['like']['staff_name'] = $search['staff_name'];
		}
		if($search['staff_mobile']){
			$condition['like']['staff_mobile'] = $search['staff_mobile'];
		}
		
		if(isset($search['is_cancel'])){
			$condition['where']['is_cancel'] = intval($search['is_cancel']);
		}
		if(isset($search['order_id'])){
			$condition['like']['order_id'] = $search['order_id'];
		}
		if(isset($search['order_refund'])){
			$condition['like']['order_refund'] = $search['order_refund'];
		}
		
		$list = $this->Staff_Booking_Model->getList($condition);
		
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'search' => $search,
			'currentPage' => $search['currentPage']
		));
		
		
	}
	
	public function index(){
		$this->_searchCondition(array(
			'is_cancel' => 0
		));
		$this->display($this->_className.'/index');
	}

	public function canceled(){
		
		$this->_searchCondition(array(
			'is_cancel' => 1
		));
		$this->display($this->_className.'/index');
	}
	
	
	/**
	 * 
	 */
	private function _doBatchOp($op){
		
			
		$id = $this->input->post('id');
		$idAr = explode(',',$id);
		
		$returnVal = $this->_bookingVerify(array(
			'op' => $op,
			'id' => $idAr,
			'reason' => $this->input->post('reason')
		),$this->_reqtime, $this->addWhoHasOperated('verify'));
		
		return $returnVal;

	}
	
	/**
	 * 批量取消预约
	 */
	public function batchCancel(){
		//@TOOD
		//待加入微信通知
		if($this->isPostRequest()){
			$this->_getVerifyRules();
			
			for($i = 0; $i < 1 ; $i++){
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$op = $this->input->get_post('op');
				
				$returnVal = $this->_doBatchOp($op);
				
				if($returnVal < 1){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				if($returnVal > 0)
				{
					$this->jsonOutput($op.'操作成功',array('jsReload' => true));
				}
			}
			
		}else{
			$this->assign('id',implode(',',$this->input->get_post('id')));
			

			$this->display($this->_className.'/batch_verify');
		}	
				
	}
	/**
	 * 批量恢复预约
	 */
	public function batchRestore(){
		//@TOOD
		//待加入微信通知
		if($this->isPostRequest()){
			$this->_getVerifyRules();
			
			for($i = 0; $i < 1 ; $i++){
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$op = $this->input->get_post('op');
				$returnVal = $this->_doBatchOp($op);
				
				if($returnVal < 1){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				if($returnVal > 0)
				{
					$this->jsonOutput($op.'操作成功',array('jsReload' => true));
				}
			}
			
		}else{
			$this->assign('id',implode(',',$this->input->get_post('id')));
			

			$this->display($this->_className.'/batch_restore');
		}	
				
	}
	
	/**
	 * 更改状态
	 */
	public function changeState(){
		
		//@TOOD
		//待加入微信通知
		if($this->isPostRequest()){
			$this->_getVerifyRules();
			
			for($i = 0; $i < 1 ; $i++){
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$op = $this->input->post("meetResult");
				if($op){
					$returnVal = $this->_doBatchOp($op);
				}
				
				if($returnVal < 1){
					$this->jsonOutput('服务器发生错误,'.$op.'操作失败');
					break;
				}
				
				if($returnVal > 0)
				{
					$this->jsonOutput($op.'操作成功',array('jsReload' => true));
				}
			}
			
		}else{
			$id=$this->input->get_post('id');

			$this->assign('id',implode(',',$this->input->get_post('id')));
			$bookingList=$this->Staff_Booking_Model->getById(array(
				'where' => array(
					'id' => $id[0]
				)
			));
			
			$this->display($this->_className.'/change_state');
		}		
	}
	

	/**
	 * 
	 */
	private function _bookingVerify($param ,$when, $who,$moreWhere = array()){
		$updateData = array(
			'reason' => $param['reason']
		);
		
		//$where['status'] = 1;
		$where = array();
		
		switch($param['op']){
			case '取消预约':
				$moreWhere = array('is_cancel' => 0);
				$updateData['is_cancel'] = 1;
				break;
			case '恢复预约':
				$moreWhere = array('is_cancel' => 1);
				$updateData['is_cancel'] = 0;
				break;
			case '未碰面':
				$moreWhere = array('is_cancel' => 0);
				$where = array_merge($where,$moreWhere);
				$moreWhere = array('meet_result' => BookingMeet::$unmeet);
				$updateData['meet_result'] = BookingMeet::$unmeet;
				break;
			case '有意向':
				$moreWhere = array('is_cancel' => 0);
				$where = array_merge($where,$moreWhere);
				$moreWhere = array('meet_result' => BookingMeet::$unmeet);
				$updateData['meet_result'] = BookingMeet::$intention;
				break;
			case '预约完成':
				$moreWhere = array('is_cancel' => 0);
				$where = array_merge($where,$moreWhere);
				$moreWhere = array('meet_result !=' => BookingMeet::$meetfail);
				$updateData['meet_result'] = BookingMeet::$meetsuccess;
				break;
			case '预约失败':
				$moreWhere = array('is_cancel' => 0);
				$where = array_merge($where,$moreWhere);
				$moreWhere = array('meet_result !=' => BookingMeet::$meetfail);
				$updateData['meet_result'] = BookingMeet::$meetfail;
				break;
			case '提醒':
				$moreWhere = array('is_notify' => 0);
				$updateData['is_notify'] = 1;
				break;
				
			default:
				break;
		}
		$updateData = array_merge($updateData,$who);
		$updateData['verify_time'] = $when;
		
		$where = array_merge($where,$moreWhere);
		
		
		if($where){
			return $this->Staff_Booking_Model->updateByCondition($updateData,array(
				'where' => $where,
				'where_in' => array(
					array('key' => 'id', 'value' => $param['id'])
				)
			));
			
		}else{
			return $this->Staff_Booking_Model->updateByCondition($updateData,array(
				'where_in' => array(
					array('key' => 'id', 'value' => $param['id'])
				)
			));
		}
		
	}
	
	/**
	 * 提醒
	 */
	public function remind(){
		
		if($this->isPostRequest() ){
				$op = $this->input->get_post('op');
				
				$returnVal = $this->_doBatchOp($op);
			
			if($returnVal > 0){
				$this->jsonOutput('提醒成功',array('jsReload' => true));
			}else{
				$this->jsonOutput('提醒失败',array('jsReload' => true));
			}
			
		}else{
			$this->assign('id',implode(',',$this->input->get_post('id')));
			$this->display($this->_className.'/remind');
		}

	}
	
	
	/**
	 * 预约单详情
	 */
	public function detail(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		$submit = $this->input->get_post('submit');

		$info = $this->Staff_Booking_Model->getFirstByKey($id);
		if(empty($submit)){
			$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '预约单详情');
		}
		else{
			$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '编辑');
		}
		
		$this->assign('info',$info);
		$this->assign('compile',$submit);
		$this->display($this->_className.'/detail');


	}
	/**
	 * 编辑
	 */
	public function edit(){

		$id = $this->input->get_post('id');
		$info = $this->Staff_Booking_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getEditRules();
			
			for($i = 0; $i < 1; $i++){
				$info = array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$info['meet_time'] = strtotime($info['meet_time']);
				
				$info['staff_sex'] = 1;
				
				if('男' == $info['staff_sex']){
					$info['staff_sex'] = 1;
				}elseif('女' == $info['staff_sex']){
					$info['staff_sex'] = 2;
				}
				
				$returnVal = $this->Staff_Booking_Model->update($info,array('id' => $id));
				
				if($returnVal < 0){
					$this->jsonOutput('保存失败',$this->getFormHash());
				}
				else{
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{

			$this->assign('info',$info);
			$this->display($this->_className.'/add');
			
		}
		
	}
	private function _getEditRules(){
		$this->form_validation->set_rules('staff_name','服务人员姓名','required');
		$this->form_validation->set_rules('staff_sex','性别','required');
		$this->form_validation->set_rules('staff_mobile','手机号码','required|valid_mobile');
	}
	
	private function _getVerifyRules(){
		
		$this->form_validation->set_rules('id','记录ID必填','required');
		$this->form_validation->set_rules('reason','备注','required|min_length[2]|max_length[100]');
	}
	private function _prepareData(){
		$data['displayorder'] = $this->input->post('displayorder');
		return array(
			'displayorder' => $data['displayorder'] ? $data['displayorder'] : 255
		);
	}
	
	public function getStaffMobile(){
		
		$searchKey = $this->input->get_post('term');
		
		$return = array();
		
		if($searchKey){
			$workerList = $this->Worker_Model->getList(array(
				'like' => array(
					'mobile' => $searchKey
				),
				'limit' => 50
			));

			
			foreach($workerList as $workerItem ){
				if(1 == $workerItem['sex']){
					$workerItem['sex'] = '男';
				}elseif(2 == $workerItem['sex']){
					$workerItem['sex'] = '女';
				}
				$return[] = array(
					'id' => $workerItem['id'],
					'label' => $workerItem['mobile'],
					'value' => $workerItem['mobile'],
					'name'=> $workerItem['name'],
					'sex'=> $workerItem['sex'],
				);
			}
		}
		
		$this->jsonOutput2('',$return,false);
		
	}
}