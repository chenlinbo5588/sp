<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Authority extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Adminuser_Model');
	}
	
	public function user(){
		
		$currentPage = $this->input->get('page') ? $this->input->get('page') : 1;
		$condition = array(
			'order' => 'uid DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$list = $this->Adminuser_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		
		$this->display();
	}
	
	
	public function user_add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('admin_email','登陆名',array(
						'required',
						'valid_email',
						array(
							'email_callable[email]',
							array(
								$this->Adminuser_Model,'checkExists'
							)
						)
					),
					array(
						'email_callable' => '%s已经被占用'
					)
				);
				
				
			$this->form_validation->set_rules('admin_password','密码','required|min_length[6]|max_length[12]|alpha_dash');
			$this->form_validation->set_rules('admin_rpassword','确认密码','required|matches[admin_password]');
			$this->form_validation->set_rules('group_id','权限组','required');
			
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
			}
		}
		
		
		$this->display();
	}
	
	
	public function user_edit(){
		
	}
	
	
	
	public function role(){
		
		
		$this->display();
	}
	
	
	public function role_add(){
		
		
		
		
		
		
		$this->display();
	}
	
	
	public function role_edit(){
		
		
	}
	
}
