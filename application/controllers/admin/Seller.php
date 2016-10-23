<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller extends Ydzj_Admin_Controller {
	
	private $_mtime;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->_mtime = array(
			'不限' => '',
			'最近1天内' => '-1 days',
			'最近3天内' => '-3 days',
			'近一周内' => '-7 days',
			'近一个月内' => '-30 days',
		);
		
	}
	
	public function index(){
		
		$this->load->library('Member_service');
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'gmt_modify DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		$search_map['verify_result'] = $this->input->get_post('verify_result');
		$search_map['search_field'] = array('username' => '登陆账号' ,'mobile' => '手机号码');
		$search['search_field_name'] = $this->input->get_post('search_field_name');
		$search['search_field_value'] = $this->input->get_post('search_field_value');
		
		
		//搜索
		$memberCondition = array();
		
		if(!empty($search['search_field_value']) && in_array($search['search_field_name'], array_keys($search_map['search_field']))){
			$memberCondition['select'] = 'uid';
			$memberCondition['where'][$search['search_field_name']] = $search['search_field_value'];
		}
		
		
		if($memberCondition){
			$searchUid = $this->Member_Model->getList($memberCondition);
			if($searchUid){
				$condition['where']['uid'] = $searchUid[0]['uid'];
			}else{
				$condition['where']['uid'] = 0;
			}
		}
		
		$search['reg_time'] = $this->input->get_post('reg_time');
		if(empty($search_map['verify_result'])){
			$search_map['verify_result'] = array(0);
		}
		
		$condition['where_in'][] = array('key' => 'verify_result', 'value' => $search_map['verify_result']);
		
		if($this->_mtime[$search['apply_time']]){
			$condition['where']['gmt_modify >='] = strtotime($this->_mtime[$search['apply_time']],$this->_reqtime);
		}
		
		$list = $this->Member_Seller_Model->getList($condition);
		
		//会员信息
		$dqList = array();
		$memberIdList = array();
		foreach($list['data'] as $member){
			if($member['uid']){
				$memberIdList[] = $member['uid'];
			}
		}
		
		if($memberIdList){
			$memberList = $this->member_service->getListByCondition(array(
				'select' => 'uid,username,email,qq,mobile,avatar_m,avatar_s',
				'where_in' => array(
					array('key' => 'uid' ,'value' => $memberIdList )
				)
			));
			
			
		}
		
		
		$sendWays = config_item('notify_ways');
		
		//print_r($memberList);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		$this->assign('memberList',$memberList);
		
		
		$this->assign('sendWays',$sendWays);
		$this->assign('search_map',$search_map);
		$this->assign('mtime',$this->_mtime);
		$this->display();
	}
	
	
	/**
	 * 
	 */
	public function verify(){
		
		$id = $this->input->get_post('id');
		
		if($id && $this->isPostRequest()){
			
			$this->load->library('Member_service');
			
						
			for($i = 0; $i < 1; $i++){
				$this->form_validation->set_error_delimiters('','');
				$supportSendWays = config_item('notify_ways');
				
				//print_r($_POST);
				
				$this->form_validation->set_rules('pass','通过标志','required|in_list[-1,1]');
				$this->form_validation->set_rules('remark','备注','required|min_length[1]|max_length[30]');
				$this->form_validation->set_rules('send_ways[]','发送方式','required');
				
				
				if(!$this->form_validation->run()){
					//$this->jsonOutput('数据校验失败');
					$this->jsonOutput($this->form_validation->error_string('',''));
					break;
				}
				
				
				$isPass = $this->input->post('pass');
				$send_ways = $this->input->post('send_ways');
				$sendWaysStr = trim(implode(',',$send_ways));
				
				$message = '认证成功';
				if(-1 == $isPass){
					$message = $this->input->post('remark');
				}
				
				$rows = $this->member_service->sellerVerify($id,$isPass,$message);
				
				if($rows > 0){
					$memberInfo = $this->Member_Model->getFirstByKey($id,'uid','uid,username,email');
					
					
					//默认发送站内信
					$data = array(
						'title' => "卖家认证审核" . ($isPass == 1 ? '通过': '未通过'),
						'content' => "卖家<strong>{$memberInfo['username']}</strong>您好，您提交的认证信息经过通过审核，您现在去维护库存<a class=\"hightlight\" href=\"".site_url('inventory/index')."\">维护库存</a>",
					);
					
					if(-1 == $isPass){
						//审核不通过
						$data['content'] = "卖家<strong>{$memberInfo['username']}</strong>您好，您提交的认证信息未通过审核，点击查看原因:<a class=\"hightlight\" href=\"".site_url('my/seller_verify')."\">卖家认证</a>";
					}
					
					$this->message_service->pushPmMessageToUser(
						array_merge($data,array(
							'uid' => $id,
							'from_uid' => 0,
						)),$id);
					
					if(strpos($sendWaysStr,'邮件')){
						
						//立即发送一封邮件，如果不成功，则插入一条待发记录
						$this->message_service->initEmail($this->_siteSetting);
						$flag = $this->message_service->sendEmail(
							 $memberInfo['email'],
							 $data['title'],
							 $data['content']
						);
						
						if(!$flag){
							$this->message_service->pushEmailMessageToUser(array_merge($data,$memberInfo),$memberInfo['uid']);
						}
					}
				}
				
				$this->jsonOutput('操作成功,消息将会通知给用户:'.$memberInfo['username'],array('id' => $id));
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
}
