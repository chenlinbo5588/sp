<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'reg_date DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$condition = array_merge($condition,$this->getAllowChannelCondition());
		
		$search_map['search_field'] = array('username' => '用户名称', 'mobile' => '手机号码','channel_name' => '注册网站名称','reg_origname' => '来源平台名称','channel_word' => '对应关键字');
		
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
	
	
	
	
	public function export(){
		
		if($this->isPostRequest()){
			
	    	$this->form_validation->set_rules('sdate','注册开始日期','required');
	    	$this->form_validation->set_rules('edate','注册结束日期','required');
	    	
	    	
	    	for($i = 0; $i < 1; $i++){
	    		if(!$this->form_validation->run()){
	    			$this->display();
	    			break;
	    		}
	    		
	    		@header("Content-type: application/unknown");
	    		@header("Content-Disposition: attachment; filename=member.csv");
	    	
	    		$cd = array(
		            'where' => array(
		                'reg_date >= ' => strtotime($_POST['sdate']),
		                'reg_date < ' => strtotime($_POST['edate']) + 86400
		             ),
		            'order' => 'reg_date DESC'
		        );
		        
				$memberList = $this->Member_Model->getList($cd);
				
				if (is_array($memberList)){
					$tmp = array();
					$tmp[] = '注册时间';
					$tmp[] = '注册状态';
					$tmp[] = '用户名称';
					$tmp[] = '手机号码';
					$tmp[] = '注册来源网站域名';
					$tmp[] = '注册来源域名名称';
					$tmp[] = '注册页面链接地址';
					$tmp[] = '注册页面名称';
					$tmp[] = '来源平台名称';
					$tmp[] = '点击来源地址';
					$tmp[] = '推广关键字';
					$tmp[] = '推广尾巴代码';
					
					$tmp[] = '注册IP';
					
					//转码 utf-gbk
					if (strtoupper(config_item('charset')) == 'UTF-8'){
						switch ($_POST['if_convert']){
							case '1':
								$tmp_line = iconv('UTF-8','GB2312//IGNORE',join(',',$tmp));
								break;
							case '0':
								$tmp_line = join(',',$tmp);
								break;
						}
					}else {
						$tmp_line = join(',',$tmp);
					}
					$tmp_line = str_replace("\r\n",'',$tmp_line);
					echo $tmp_line."\r\n";
					
					
					foreach ($memberList as $k => $v){
						$tmp = array();
						$tmp[] = date("Y-m-d H:i:s",$v['reg_date']);
						$tmp[] = $v['status'] == -2 ? '注册未提交' : '注册完成';
						$tmp[] = str_replace(',',' ',$v['username']);
						$tmp[] = $v['mobile'];
						$tmp[] = $v['reg_domain'];
						$tmp[] = str_replace(',',' ',$v['channel_name']);
						$tmp[] = $v['page_url'];
						$tmp[] = str_replace(',',' ',$v['page_name']);
						$tmp[] = str_replace(',',' ',$v['reg_origname']);
						$tmp[] = $v['reg_orig'];
						$tmp[] = str_replace(',',' ',$v['channel_word']);
						$tmp[] = $v['channel_code'];
						$tmp[] = $v['reg_ip'];
						
						//转码 utf-gbk
						if (strtoupper(config_item('charset')) == 'UTF-8'){
							switch ($_POST['if_convert']){
								case '1':
									$tmp_line = iconv('UTF-8','GB2312//IGNORE',join(',',$tmp));
									break;
								case '0':
									$tmp_line = join(',',$tmp);
									break;
							}
						}else {
							$tmp_line = join(',',$tmp);
						}
						$tmp_line = str_replace("\r\n",'',$tmp_line);
						echo $tmp_line."\r\n";
					}
				}
	    	}
		}else{
			$this->display();
		}
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
