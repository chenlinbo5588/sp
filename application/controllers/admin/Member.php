<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'uid DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$search_map['search_field'] = array('mobile' => '手机号码','email' => '电子邮箱','username' => '用户姓名');
		
		
		foreach($search_map['search_field'] as $key => $value){
			$v = trim($this->input->get_post($key));
			if(!empty($v)){
				$condition['like'][$key] = $v;
			}
		}
		
		
		$list = $this->Member_Model->getList($condition);
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->assign('search_map',$search_map);
		$this->display();
	}
	
	
	
	/**
	 * 
	 */
	private function _setMobileRule($key){
		$this->form_validation->set_rules($key,'手机号码',array(
				'required',
				'valid_mobile',
				array(
					'loginname_callable[mobile]',
					array(
						$this->Member_Model,'isUnqiueByKey'
					)
				)
			),
			array(
				'loginname_callable' => '%s已经被注册'
			)
		);
		
	}
	
	
	/**
	 * 验证规则
	 */
	private function _addRules($action){
		
		$param['username'] = $this->input->post('username');
		if($param['username']){
			$this->form_validation->set_rules('username','真实名称','min_length[1]|max_length[6]');
		}else{
			$param['username'] = '';
		}
		
		$param['email'] = $this->input->post('email');
		if($param['email']){
			$this->form_validation->set_rules('email','电子邮箱','valid_email');
		}else{
			$param['email'] = '';
		}
		
		$param['qq'] = $this->input->post('qq');
		if($param['qq']){
			$this->form_validation->set_rules('qq','QQ','regex_match[/^\d+$/]');
		}else{
			$param['qq'] = '';
		}
		
		$param['sex'] = $this->input->post('sex');
		if(empty($param['sex'])){
			$param['sex'] = 'M';
		}
		
		$param['weixin'] = $this->input->post('weixin');
		
		if($param['weixin']){
			$this->form_validation->set_rules('member_weixin','微信号','alpha_dash|min_length[6]|max_length[20]');
		}else{
			$param['weixin'] = '';
		}
		
		
		$param = array_merge($param,$this->addWhoHasOperated($action));
		
		return $param;
	}
	
	
	/**
	 * 信息浏览
	 */
	public function detail(){
		$uid = $this->input->get_post('uid');
		$info = $this->Member_Model->getFirstByKey($uid,'uid');
		
		$this->assign('action','detail');
		$this->assign('info',$info);
		$this->display('member/edit');
	}
	
	
	/**
	 * 编辑
	 */
	public function edit(){
		$this->assign('action','edit');
		
		$uid = $this->input->get_post('uid');
		$this->assign('uid',$uid);
		
		$info = $this->Member_Model->getFirstByKey($uid,'uid');
		
		//print_r($urlParam);
		
		if(!empty($uid) && $this->isPostRequest()){
			
			$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile|is_unique_not_self['.$this->Member_Model->getTableRealName().'.mobile.uid.'.$uid.']');
			
			$extraParam = $this->_addRules('edit');
			$info = array_merge($info,$extraParam);
			
			for($i = 0 ; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->assign('feedback','<div class="tip_error">'.$this->form_validation->error_string('','').'</div>');
					break;
				}
				
				$flag = $this->Member_Model->update($extraParam,array(
					'uid' => $uid
				));
				
				if($flag >= 0){
					$info = $this->Member_Model->getFirstByKey($uid,'uid');
					$this->assign('feedback','<div class="tip_success">保存成功</div>');
				}else{
					$this->assign('feedback','<div class="tip_error">保存失败</div>');
				}
			}
			
		}
		
		$this->assign('info',$info);
		$this->display();
	}
	
	
	
	/**
	 * 裁剪用户头像
	 */
	public function pic_cut(){
		if($this->isPostRequest()){
			$src_file = str_ireplace(base_url(),'',$this->input->post('url'));
			
			$this->load->library('Attachment_Service');
			
			$fileData = $this->attachment_service->resize(array('file_url' => $src_file) , 
				array('middle' => array('width' => $this->input->post('w'),'height' => $this->input->post('h'),'maintain_ratio' => false , 'quality' => 100)) , 
				array('x_axis' => $this->input->post('x1'), 'y_axis' => $this->input->post('y1')));
			
			
			
			if($fileData['img_middle']){
				$smallImg = $this->attachment_service->resize(array(
					'file_url' => $fileData['img_middle']
				) , array('small') );
			}
			
			//删除原图
			unlink($fileData['full_path']);
				
				
			if (empty($fileData['img_middle'])){
				exit(json_encode(array(
					'status' => 0, 
					'formhash'=>$this->security->get_csrf_hash(),
					'msg'=>$this->attachment_service->getErrorMsg('','')
				)));
			}else{
				exit(json_encode(array(
					'status' => 1, 
					'formhash'=>$this->security->get_csrf_hash(),
					'url'=>base_url($fileData['img_middle']),
					'picname' => $src_file
				)));
			}
			
		}else{
			$save_file = str_ireplace(base_url(),'',$this->input->get('url'));
			
			list($width, $height, $type, $attr) =  getimagesize(ROOTPATH.'/'.$save_file);
			
			$this->assign('image_width',$width);
			$this->assign('image_height',$height);
			$this->display();
		}
		
	}
	
}
