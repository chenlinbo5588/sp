<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends Ydzj_Controller {
	
	private $sideNavs = null;
	private $modKey = '联系我们';
	
	public function __construct(){
		parent::__construct();
		
		$this->assign('pgClass',strtolower(get_class()).'Pg');
		
		$this->load->model('Article_Model');
		
		$tempAr = config_item('pageConf');
		
		$this->sideNavs = $tempAr[$this->modKey]['sideNav'];
		$this->assign('sideNavs',$this->sideNavs);
		$this->assign('sideTitle',$this->modKey);
		$this->assign('sideTitleUrl',$tempAr[$this->modKey]['url']);
		
		
		$this->_navigation = array(
			'首页' => site_url('/'),
			$this->modKey => $tempAr[$this->modKey]['url']
		);
	}
	
	private function _getArticleContent($key){
		
		$this->_navigation[$key] = $this->sideNavs[$key];
		
		$article = $this->Article_Model->getFirstByKey($key,'article_title');
		$this->assign('article',$article);
		
		$this->assign('breadcrumb',$this->breadcrumb());
		
	}
	
	
	/**
	 * 
	 */
	public function index()
	{
		$key = '联系我们';
		$this->_getArticleContent($key);
		
		$this->display('common/art');
	}
	
	
	public function customer_service()
	{
		$key = '售后中心';
		$this->_getArticleContent($key);
		
		$this->display('common/art');
	}
	
	
	public function merchants_telephone()
	{
		$key = '招商电话';
		$this->_getArticleContent($key);
		$this->display('common/art');
	}
	
	
	private function _getRules(){
		
		$this->form_validation->set_rules('username','姓名','required|min_length[1]|max_length[30]');
		$this->form_validation->set_rules('company_name','公司名称','required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		
		$this->form_validation->set_rules('city','城市','required');
		
		
		if($this->input->post('tel')){
			$this->form_validation->set_rules('tel','固定电话','required|max_length[20]');
		}
		
		if($this->input->post('email')){
			$this->form_validation->set_rules('email','联系邮箱','required|valid_email');
		}
		
		if($this->input->post('weixin')){
			$this->form_validation->set_rules('weixin','微信号','required');
		}
		
		$this->form_validation->set_rules('doc_no','合同号','required');
		$this->form_validation->set_rules('remark','备注','required|max_length[200]');
		$this->form_validation->set_rules('auth_code','验证码','required|callback_validateAuthCode');
		
		$info = array(
			'username' => $this->input->post('username'),
			'company_name' => $this->input->post('company_name'),
			'mobile' => $this->input->post('mobile'),
			'city' => $this->input->post('city'),
			'tel' => $this->input->post('tel') ? $this->input->post('tel') : '',
			'email' => $this->input->post('email') ? $this->input->post('email') : '',
			'weixin' => $this->input->post('weixin') ? $this->input->post('weixin') : '',
			'doc_no' => $this->input->post('doc_no'),
			'remark' => strip_tags($this->input->post('remark')),
			'status' => '未处理'
		);
		
		
		return $info;
	}
	
	
	public function suggestion(){
		
		$feedback = '';
		$this->_navigation['投诉建议'] = site_url('contacts/suggestion');
		$this->assign('breadcrumb',$this->breadcrumb());
		
		if($this->isPostRequest()){
			
			$this->load->model('Suggestion_Model');
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_getRules();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string('','');
					$feedback = getErrorTip($feedback);
					break;
				}
				
				$newid = $this->Suggestion_Model->_add($info);
				unset($info);
				$this->assign('alertMsg', '您的投诉建议意见已完成提交,我们的工作人员交尽快处理。');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
	public function map(){
		
		$this->display();
	}
}
