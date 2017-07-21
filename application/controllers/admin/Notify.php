<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notify extends Ydzj_Admin_Controller {
	
	private $_msgMode = null;
	private $_sendWays = null;
	
	public function __construct(){
		parent::__construct();
		$this->_msgModel = array(
			'不指定',
			'白名单',
			'黑名单'
		);
		
		$this->_sendWays = config_item('notify_ways');
		
		$this->assign('msgMode',$this->_msgModel);
		$this->assign('sendWays',$this->_sendWays);
		$this->load->library('Message_service');
		
		
		$this->_subNavs = array(
			'modulName' => '会员通知',
			'subNavs' => array(
				'管理' => 'notify/index',
				'添加' => 'notify/add',
			),
		);
		
	}
	
	
	public function index(){
		//echo $this->_reqtime;
		//print_r($this->session->all_userdata());
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$groupList = $this->message_service->getAllMemberGroup();
		$keyedGroupList = array();
		
		foreach($groupList as $group){
			$keyedGroupList[$group['id']] = $group['name'];
		}
		
		$condition = array(
			'where' => array(),
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		
		//print_r($condition);
		$list = $this->Site_Message_Model->getList($condition);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('group',$keyedGroupList);
		$this->display();
		
	}
	
	
	public function add(){
		
		$groupList = $this->message_service->getAllMemberGroup();
		$feedback = '';
		
		$info = array();
		
		if($this->isPostRequest()){
			//print_r($_POST);
			
			$info['send_group'] = $this->input->post('send_group');
			$info['msg_mode'] = $this->input->post('msg_mode');
			$info['username_list'] = $this->input->post('username_list');
			$info['send_ways'] = $this->input->post('send_ways');
			
			if(empty($info['send_ways'])){
				$info['send_ways'] = array();
			}
			
			
			$info['title'] = $this->input->post('title');
			$info['content'] = $this->input->post('content');
			$info['users'] = $this->input->post('users');
			
			$this->form_validation->set_rules('send_group','目标组','required|in_list[1,2,3,4]');
			$this->form_validation->set_rules('msg_mode','发送模式','required|in_list[0,1,2]');
			
			$this->form_validation->set_rules('send_ways[]','发送方式','required|in_list['.implode(',',$this->_sendWays).']');
			
			if($info['msg_mode'] != 0){
				$this->form_validation->set_rules('users','会员列表','required');
			}
			
			$this->form_validation->set_rules('title','消息标题','required|min_length[1]|max_length[30]');
			$this->form_validation->set_rules('content','消息正文','required');
			
			//$this->assign('msg_mode',$msg_mode);
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string('',''));
					break;
				}
				
				$data = array(
					'msg_type' => $this->input->post('send_group'),
					'msg_mode' => $this->input->post('msg_mode'),
					'send_ways' => implode(',',$this->input->post('send_ways')),
					'title' => $this->input->post('title'),
					'content' => $this->input->post('content'),
					'groups' => '',
					'users' => ''
				);
				
				if($info['msg_mode'] != 0){
					$userList = $this->input->post('users');
					
					//最后已个 |  重要，用户搜索用户
					$data['users'] = str_replace(array("\r\n","\r","\n"),'|',$userList).'|';
				}
				
				$this->message_service->addSitePmMessage($data);
				
				$feedback = getSuccessTip('发送成功');
				
			}
		}else{
			
			$uid = $this->input->get_post('uid');
			$memberInfo = $this->Member_Model->getFirstByKey($uid,'uid');
			
			$info['send_group'] = 1;
			$info['msg_mode'] = 0;
			$info['send_ways'] = array('站内信');
			if($memberInfo){
				$info['msg_mode'] = 1;
				$info['users'] = $memberInfo['username'];
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('group',$groupList);
		$this->display();
	}
	
	
	
	public function detail(){
		
		$groupList = $this->message_service->getAllMemberGroup();
		$id = $this->input->get_post('id');
		$info = $this->Site_Message_Model->getFirstByKey($id);
		
		$info['users'] = str_replace('|',"\r\n",$info['users']);
		$info['send_ways'] = explode(',',$info['send_ways']);
		
		$this->_subNavs['subNavs']['通知详情'] = 'notify/detail?id='.$id;
		
		$this->assign(array(
			'info' => $info,
			'group' => $groupList,
			'detail' => true
		));
		
		
		$this->display('notify/add');
	}
}
