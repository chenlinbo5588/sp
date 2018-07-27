<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notify extends Ydzj_Admin_Controller {
	
	private $_msgMode = null;
	private $_sendWays = null;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Message_service');
		
		$this->_msgModel = array(
			'不指定',
			'白名单',
			'黑名单'
		);
		
		$this->_sendWays = config_item('notify_ways');
		
		$this->_moduleTitle = '通知';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'msgMode' => $this->_msgModel,
			'sendWays' => $this->_sendWays
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '新增'),
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
		
		foreach($list['data'] as $index => $notifyItem){
			
			$notifyItem['send_ways'] = implode(',',json_decode($notifyItem['send_ways'],true));
			$notifyItem['users'] = implode(',',json_decode($notifyItem['users'],true));
			
			$list['data'][$index] = $notifyItem;
			
			
		}
		
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'group' => $keyedGroupList
		
		));
		
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
			
			$this->form_validation->set_rules('send_group[]','目标组','required');
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
					$feedback = getErrorTip('数据校验失败,'.$this->form_validation->error_string('',''));
					break;
				}
				
				$data = array(
					'msg_type' => 0,
					'msg_mode' => $this->input->post('msg_mode'),
					'send_ways' => json_encode($this->input->post('send_ways')),
					'title' => $this->input->post('title'),
					'content' => $this->input->post('content'),
					'groups' => json_encode($this->input->post('send_group')),
					'users' => json_encode(array())
				);
				
				if($info['msg_mode'] != 0){
					$userString = $this->input->post('users');
					
					$tempUserList = explode("\n",str_replace(array("\r\n","\r","\n"),"\n",$userString));
					$userList = array();
					
					foreach($tempUserList as $userItem){
						if(trim($userItem)){
							$userList[] = trim($userItem);
						}
					}
					
					$data['users'] = json_encode($userList);
				}else{
					
					$data['users'] = json_encode(array());
				}
				
				$this->message_service->addSitePmMessage($data);
				$error = $this->Site_Message_Model->getError();
				
				if(QUERY_OK != $error['code']){
					$feedback = getErrorTip($error['message']);
					break;
				}
				
				$feedback = getSuccessTip('保存成功,页面即将刷新');
				$redirectUrl = admin_site_url($this->_className);
			}
		}
		
		
		$uid = $this->input->get_post('uid');
		$memberInfo = $this->Member_Model->getFirstByKey($uid,'uid');
		
		$info['msg_mode'] = 0;
		$info['send_ways'] = array('站内信');
		
		if($memberInfo){
			$info['msg_mode'] = 1;
			$info['users'] = $memberInfo['username'];
		}
		
		$this->assign(array(
			'info' => $info,
			'feedback' => $feedback,
			'group' => $groupList,
			'redirectUrl' => $redirectUrl
		));
		
		$this->display();
		
		
	}
	
	
	
	public function detail(){
		
		$groupList = $this->message_service->getAllMemberGroup();
		$id = $this->input->get_post('id');
		$info = $this->Site_Message_Model->getFirstByKey($id);
		
		$info['users'] = json_decode($info['users'],true);
		$info['groups'] = json_decode($info['groups'],true);
		$info['send_ways'] = json_decode($info['send_ways'],true);
		
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '通知详情');
		
		$this->assign(array(
			'info' => $info,
			'detail' => true
		));
		
		$this->assign('group',$groupList);
		$this->assign('inDetail',true);
		$this->display('notify/add');
	}
}
