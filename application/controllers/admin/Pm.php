<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pm extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->_moduleTitle = '消息中心';
		$this->_className = strtolower(get_class());
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/unread','title' => '未读'),
			array('url' => $this->_className.'/readed','title' => '已读'),
			array('url' => $this->_className.'/send','title' => '已发送'),
			array('url' => $this->_className.'/add','title' => '发私信'),
		);
		
	}
	
	
	/**
	 * 搜索条件
	 */
	private function _searchCondition($moreCondition = array()){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$where = array();
		
		
		if($moreCondition['where']){
			$where = array_merge($where,$moreCondition['where']);
		}
		
		
		$search['date_s'] = $this->input->get_post('date_s');
		$search['date_e'] = $this->input->get_post('date_e');
		
		
		if($search['date_s']){
			$where['gmt_create >= '] = strtotime($search['date_s']);
		}
		
		if($search['date_e']){
			$where['gmt_create <= '] = strtotime($search['date_e']) + CACHE_ONE_DAY;
		}
		
		$condition = array(
			'where' => $where,
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$list = $this->admin_pm_service->getAdminPmListByUid($condition,$this->_adminUID);
		
		$this->assign(array(
			'search' => $search,
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage
		));
		
	}
	
	
	
	/**
	 * 未读
	 */
	public function unread(){
		
		$this->_searchCondition(array(
			'where' => array(
				'msg_direction' => 0,
				'readed' => 0
			)
		));
		
		$this->assign('showSetRead', true);
		
		$this->display($this->_className.'/index');
	}
	
	/**
	 * 已读
	 */
	public function readed(){
		
		$this->_searchCondition(array(
			'where' => array(
				'msg_direction' => 0,
				'readed' => 1
			)
		));
		
		$this->display($this->_className.'/index');
	}
	
	
	/**
	 * 已发送
	 */
	public function send(){
		$this->_searchCondition(array(
			'where' => array(
				'msg_direction' => 1
			)
		));
		
		$this->display($this->_className.'/index');
		
	}
	
	
	/**
	 * 设置为已读
	 */
	public function setread(){
		$id = $this->input->post('id');
		if($id && $this->isPostRequest()){
			
			
			$rows = $this->admin_pm_service->setUserPmReaded($id,$this->_adminUID);
			$this->jsonOutput('设置已读成功',array('id' => $id));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	/**
	 * 详情
	 */
	public function detail(){
		
		$id = $this->input->get_post('id');
		$info = $this->admin_pm_service->getAdminPmDetailById($id,$this->_adminUID);
		
		if($info){
			$uids = array(
				$info['uid']
			);
			
			if($info['from_uid']){
				$uids[] = $info['from_uid'];
			}
			
			$memberInfo = $this->Adminuser_Model->getList(array(
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
				$this->admin_pm_service->setUserPmReaded($info['id'],$this->_adminUID);
			}
		}
		
		$this->output->set_cache_header($info['gmt_create'],$this->_reqtime + CACHE_ONE_YEAR);
		$this->assign('info',$info);
		$this->display();
		
	}
	
	
	
	/**
	 * 发送私信
	 */
	public function add(){
		
		$feedback = '';
		
		
		$info = array();
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				$info = $_POST;
				$email = $this->input->post('email');
				
				/*
				if($email == $this->_adminProfile['basic']['email']){
					$feedback = getErrorTip('不能给自己发私信');
					break;
				}
				*/
				
				/*
				 * 一分钟内最多发三条私信
				 */
				$isAllowed = $this->admin_pm_service->pmFreqLimit(60,3,$this->_adminUID);
				
				if(!$isAllowed){
					$feedback = getErrorTip('发送私信频率过于频繁，请稍后尝试');
					
					break;
				}
				
				
				$this->form_validation->set_rules('email','对方登陆邮箱','required|valid_email|in_db_list['.$this->Adminuser_Model->getTableRealName().'.email]');
				$this->form_validation->set_rules('title','标题','required|min_length[1]|max_length[30]');
				$this->form_validation->set_rules('content','内容','required|min_length[1]|max_length[200]');
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip('数据校验失败');
					break;
				}
				
				$toUserInfo = $this->Adminuser_Model->getFirstByKey($this->input->post('email'),'email');
				
				$title = $this->input->post('title',true);
				$content = $this->input->post('content',true);
				
				$return = $this->admin_pm_service->sendPrivateAdminPm($email,$this->_adminUID,$title,$content,false);
				
				if($return){
					
					$redirectUrl = admin_site_url('pm/send');
					
					$this->assign('redirectUrl',$redirectUrl);
					
					$feedback = getSuccessTip('发送成功,页面即将刷新');
				}else{
					$feedback = getErrorTip('发送失败');
				}
			}
		}
		
		$this->assign('info',$info);
		
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	
}
