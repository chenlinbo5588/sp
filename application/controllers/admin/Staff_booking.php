<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Staff_booking extends Ydzj_Admin_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Staff_service'));
		$this->_moduleTitle = '预约单';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'bookingMeet' => BookingMeet::$statusName,
			'bookingStatus' => BookingStatus::$statusName
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

		$returnVal = $this->staff_bookingVerify(array(
			'op' => $op,
			'id' => $idAr,
			'reason' => $this->input->post('reason')
		),$this->_reqtime, $this->addWhoHasOperated('verify'));
		
	
	
		return $returnVal;

	}
	
	/**
	 * 批量取消/恢复预约
	 */
	public function batch_cancel(){
		
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
			$id=$this->input->get_post('id');
			$this->assign('id',implode(',',$this->input->get_post('id')));
			$bookingList=$this->Staff_Booking_Model->getById(array(
				'where' => array(
					'id' => $id[0]
				)
			));
			
			if(1 == $bookingList['is_cancel'])
			{
				$this->display($this->_className.'/batch_recovery');
			}
			else
			{
				$this->display($this->_className.'/batch_verify');
			}
			
		}
		
	}
	

	/**
	 * 审核
	 */
	public function staff_bookingVerify($param ,$when, $who){
		$updateData = array(
			'reason' => $param['reason']
		);
		
		//$where['status'] = 1;
		
		switch($param['op']){
			case '取消预约':
				$updateData['is_cancel'] = 1;
				$updateData = array_merge($updateData,$who);
				$updateData['verify_time'] = $when;
				break;
			case '恢复预约':
				$updateData['is_cancel'] = 0;
				break;
			default:
				break;
		}
		
		return $this->Staff_Booking_Model->updateByCondition($updateData,array(
			'where_in' => array(
				array('key' => 'id', 'value' => $param['id'])
			)
		));
	}
	
	/**
	 * 提醒
	 */
	
	public function notify(){
		$ids = $this->input->post('id');
		
		//@TOOD
		//待加入微信通知
		if($this->isPostRequest() && !empty($ids)){
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->Staff_Booking_Model->updateByCondition(array_merge(array(
				'is_notify' => 1
			)),array(
				'where' => array(
					'is_notify' => 0
				),
				'where_in' => array(
					array('key' => 'id', 'value' => $ids)
				)
			));
			
			if($returnVal > 0){
				$this->jsonOutput('提醒成功',$this->getFormHash());
			}else{
				$this->jsonOutput('提醒失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
		
		
	}
	
	
	/**
	 * 
	 */
	public function detail(){
		
		$feedback = '';
		$id = $this->input->get_post('id');

		$info = $this->Staff_Booking_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '预约单详情');
		
		$this->assign('info',$info);
		$this->display($this->_className.'/add');
			
		
	}
	
	private function _getVerifyRules(){
		
		$this->form_validation->set_rules('id','记录ID必填','required');
		$this->form_validation->set_rules('reason','备注','required|min_length[2]|max_length[100]');
	}	
}