<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_pm extends MyYdzj_Controller {
	
	
	public function __construct(){
		parent::__construct();
	}
	
	public function sendbox(){
		
		
	}
	
	public function index()
	{
		$type = $this->input->get_post('type');
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$readed = intval($this->input->get_post('read'));
		
		if(!$readed){
			$readed = 0;
		}
		
		$pageParam = array(
			'page_size' => config_item('page_size'),
			'current_page' => intval($currentPage),
			'call_js' => 'search_page',
			'form_id' => '#formSearch',
			
		);
		
		$condition = array(
			'select' => 'id,uid,msg_type,readed,title,gmt_create,gmt_modify',
			'where' => array(
				'uid' => $this->_loginUID
			),
			'order' => 'gmt_create DESC',
			'pager' => $pageParam
		);
		
		if($type == 'send'){
			$condition['where']['msg_direction'] = 1;
			
			
		}else{
			$type = 'receive';
			$condition['where']['msg_direction'] = 0;
			$condition['where']['readed'] = $readed;
		}
		
		$pmList = $this->message_service->getPmListByUid($condition,$this->_loginUID);
		//print_r($pmList);
		$this->assign('read',$readed);
		$this->assign('type',$type);
		$this->assign('page',$pmList['pager']);
		$this->assign('list',$pmList['data']);
		$this->display();
	}
	
	/**
	 * 发送私信
	 */
	public function sendpm(){
		$username = trim($this->input->post('to_username'));
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				if(empty($username)){
					$this->jsonOutput('找不到对方登陆账号',$this->getFormHash());
					break;
				}
				
				if($username == $this->_profile['basic']['username']){
					$this->jsonOutput('不能给自己发私信',$this->getFormHash());
					break;
				}
				
				/*
				 * 一分钟内最多发三条私信
				 */
				$isAllowed = $this->message_service->pmFreqLimit(60,3,$this->_loginUID);
				
				if(!$isAllowed){
					$this->jsonOutput('发送私信频率过于频繁，请稍后尝试',$this->getFormHash());
					break;
				}
				
				$this->form_validation->set_rules('to_username','用户账号','in_db_list['.$this->Member_Model->getTableRealName().'.username]');
				$this->form_validation->set_rules('title','required|min_length[1]|max_length[30]');
				$this->form_validation->set_rules('content','required|min_length[1]|max_length[200]');
				
				
				if(!$this->form_validation->run()){
					$this->jsonOutput($this->form_validation->error_string('',''),$this->getFormHash());
					break;
				}
				
				$title = $this->input->post('title',true);
				$content = $this->input->post('content',true);
				$return = $this->message_service->sendPrivatePm($username,$this->_loginUID,$title,$content);
				$this->jsonOutput('发送成功',$return);
				
			}
			
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	
	
	/**
	 * 检查用户名是否存在
	 */
	public function username_check(){
		$username = trim($this->input->get_post('to_username'));
		$flag = 'false';
		
		$info = $this->Member_Model->getById(array(
			'select' => 'uid',
			'where' => array(
				'username' => $username
			)
		));
		
		if($info){
			$flag = 'true';
		}
		
		echo $flag;
		
	}
	
	/**
	 * 删除pm
	 */
	public function delete(){
		$id = $this->input->post('id');
		if($id && $this->isPostRequest()){
			$rows = $this->message_service->deleteUserPm($id,$this->_loginUID);
			$this->jsonOutput('删除成功',array('id' => $id));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	/**
	 * 
	 */
	public function setread(){
		$id = $this->input->post('id');
		if($id && $this->isPostRequest()){
			$rows = $this->message_service->setUserPmReaded($id,$this->_loginUID);
			$this->jsonOutput('设置已读成功',array('id' => $id));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	/**
	 * 站内信详情
	 */
	public function detail(){
		$id = $this->input->get_post('id');
		$info = $this->message_service->getUserPmDetailById($id,$this->_loginUID);
		
		if($info){
			$uids = array(
				$info['uid']
			);
			
			if($info['from_uid']){
				$uids[] = $info['from_uid'];
			}
			
			$memberInfo = $this->Member_Model->getList(array(
				'select' => 'uid,username',
				'where_in' => array(
					array('key' => 'uid','value' => $uids)
				)
			));
			
			foreach($memberInfo as $member){
				if($member['uid'] == $info['uid']){
					$info['username'] = $member['username'];
				}
				
				if($member['uid'] == $info['from_uid']){
					$info['from_username'] = $member['username'];
				}
			}
			
			
			/* 用户收到的未读消息 ,点击详情自动设置为已读 */
			if($info['readed'] == 0 && $info['uid'] != $info['from_uid']){
				$this->message_service->setUserPmReaded($info['id'],$this->_loginUID);
			}
		}
		
		//$this->output->http_cache(CACHE_ONE_YEAR);
		$this->output->set_cache_header($info['gmt_create'],$this->_reqtime + CACHE_ONE_YEAR);
		$this->assign('info',$info);
		$this->display();
	}
	
	
	/**
	 * 检查是否有新想消息s
	 */
	public function check_newpm(){
		$this->jsonOutput('请求成功',array('newpm' => count($this->_newpm)));
	}
}
