<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Member_service','Common_district_service'));
		
		
		
		$this->_moduleTitle = '会员';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			//array('url' => $this->_className.'/add','title' => '新增'),
		);
		
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
		
		$ds = array();
		$dkeys = array('d1','d2','d3','d4');
		$tempK = '';
		$searchDk = '';
		foreach($dkeys as $dk){
			$tempK = $this->input->get_post($dk);
			if($tempK){
				$searchDk = $dk;
				$ds[$dk] = $tempK ;
			}
		}
		
		if(empty($ds)){
			$this->assign('ds',$this->common_district_service->prepareCityData());
		}else{
			$condition['where'][$searchDk] = $ds[$searchDk];
			
			$this->assign('ds',$this->common_district_service->prepareCityData($ds));
		}
		
		$search_map['search_field'] = array('mobile' => '账号','email' => '电子邮箱','username' => '真实姓名');
		
		/*
		$search_map['activity_sort'] = array(
				'-30 minutes' => '最近30分钟内活跃','-2 hours' => '最近2小时内活跃',
				'-6 hours' => '最近6小时内活跃', '-12 hours' => '最近12小时内活跃',  
				'-1 days' => '最近1天内活跃' , '-3 days' => '最近3天内活跃' , 
				'-7 days' => '最近7天内活跃' ,'-15 days' => '最近15天内活跃');
		*/
		
		$search_map['register_channel'] = array('1' => '手机网页版','2' => '后台管理中心' ,'3' => 'PC网页版','4' => 'iOS客户端','5' => 'Android客户端');
		$search_map['register_sort'] = array('uid DESC' => '按时间倒序','uid ASC' => '按时间顺序');
		$search_map['member_state'] = array('avatar_status@0' => '待验证头像','district_bind@0' => '未设置地区','freeze@1' => '已禁止登录');
		
		
		$search['search_field_name'] = $this->input->get_post('search_field_name');
		$search['search_field_value'] = $this->input->get_post('search_field_value');
		//$search['activity_sort'] = $this->input->get_post('activity_sort');
		$search['register_channel'] = $this->input->get_post('register_channel');
		$search['register_sort'] = $this->input->get_post('register_sort');
		$search['member_state'] = $this->input->get_post('member_state');
		
		if(!empty($search['search_field_value']) && in_array($search['search_field_name'], array_keys($search_map['search_field']))){
			$condition['like'][$search['search_field_name']] = $search['search_field_value'];
		}
		
		/*
		if(in_array($search['activity_sort'], array_keys($search_map['activity_sort']))){
			$condition['where']['last_activity >='] = strtotime($search['activity_sort']);
		}
		*/
		if(in_array($search['register_channel'], array_keys($search_map['register_channel']))){
			$condition['where']['channel'] = $search['register_channel'];
		}
		
		if(in_array($search['register_sort'], array_keys($search_map['register_sort']))){
			$condition['order'] = $search['register_sort'];
		}
		
		if(in_array($search['member_state'], array_keys($search_map['member_state']))){
			$statArray = explode('@',$search['member_state']);
			$condition['where'][$statArray[0]] = $statArray[1];
		}
		
		$list = $this->member_service->getListByCondition($condition);
		
		//邀请人和地区名称
		$dqList = array();
		$inviterList = array();
		foreach($list['data'] as $member){
			if($member['d1']){
				$dqList[] = $member['d1'];
			}
			if($member['d2']){
				$dqList[] = $member['d2'];
			}
			
			if($member['d3']){
				$dqList[] = $member['d3'];
			}
			
			if($member['d4']){
				$dqList[] = $member['d4'];
			}
			
			if($member['inviter']){
				$inviterList[] = $member['inviter'];
			}
		}
		
		if($inviterList){
			$this->assign('inviterInfo',$this->member_service->getListByCondition(array(
				'select' => 'uid,mobile,avatar_middle,avatar_small',
				'where_in' => array(
					array('key' => 'uid' ,'value' => $inviterList )
				)
			)));
		}
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		$this->assign('search_map',$search_map);
		$this->assign('memberDs',$this->common_district_service->getDistrictByIds($dqList));
		//$this->assign('d1',$this->common_district_service->getDistrictByPid(0));
		
		$this->display('member/index');
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
	private function _addRules(){
		
		
		$param['email'] = $this->input->post('member_email');
		if($param['email']){
			$this->form_validation->set_rules('member_email','电子邮箱','valid_email');
		}
		
		$param['username'] = $this->input->post('member_username');
		if($param['username']){
			$this->form_validation->set_rules('member_username','真实名称','min_length[1]|max_length[6]');
		}
		
		$param['member_qq'] = $this->input->post('member_qq');
		
		if($param['member_qq']){
			$this->form_validation->set_rules('member_qq','QQ','regex_match[/^\d+$/]');
		}
		
		$param['member_weixin'] = $this->input->post('member_weixin');
		
		if($param['member_weixin']){
			$this->form_validation->set_rules('member_weixin','微信号','alpha_dash|min_length[6]|max_length[20]');
		}
		
		$this->form_validation->set_rules('allowtalk','允许发表言论','required|in_list[N,Y]');
		$this->form_validation->set_rules('memberstate','允许登录','required|in_list[N,Y]');
	}
	
	
	/**
	 * 后台创建用户
	 */
	public function add(){
		/*
		if($this->isPostRequest()){
			
			$this->_setMobileRule('member_mobile');
			$this->form_validation->set_rules('member_passwd','密码','required|alpha_dash|min_length[6]|max_length[12]');
			$this->form_validation->set_rules('member_passwd2','密码确认','required|matches[member_passwd]');
			
			$this->_addRules();
			
			
			for($i = 0 ; $i < 1; $i++){
				if(!$this->form_validation->run()){
					break;
				}
				
				$aid = $this->input->post('avatar_id');
				if($aid){
					$member_avatar = $this->input->post('member_avatar');
					$avatar = getImgPathArray($member_avatar,array('m','s'));
					$avatar['aid'] = $aid;
				}
				
				$addParam = array(
					'mobile' => $this->input->post('member_mobile'),
					'nickname' => $this->input->post('member_mobile'),
					'password' => $this->input->post('member_passwd'),
					'qq' => $this->input->post('member_qq'),
					'weixin' => $this->input->post('member_weixin'),
					'email' => $this->input->post('member_email'),
					'username' => $this->input->post('member_username'),
					'sex' => $this->input->post('member_sex'),
					'allowtalk' => $this->input->post('allowtalk'),
					'freeze' => $this->input->post('memberstate'),
					'status' => 0,
					'channel' => 1	//1 标志直接后台增加
				);
				
				if($avatar){
					$addParam = array_merge($addParam,$avatar);
				}
				
				//print_r($addParam);
				$this->load->library('Register_service');
				$result = $this->register_service->createMember($addParam);
				
				if($result['code'] == 'success'){
					$this->assign('feedback','<div class="tip_success">添加成功</div>');
				}else{
					$this->assign('feedback','<div class="tip_error">添加失败</div>');
				}
			}
		}
		
		$this->display();
		*/
		
	}
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$urlParam = $this->uri->uri_to_assoc();
		$this->assign('id',$urlParam['edit']);
		
		$this->load->library('Member_service');
		$info = $this->member_service->getUserInfoById($urlParam['edit']);
		
		//print_r($urlParam);
		
		if(!empty($urlParam['edit']) && $this->isPostRequest()){
			$this->assign('inpost',true);
			
			$this->form_validation->set_rules('member_nickname','昵称','min_length[3]|max_length[30]|is_unique_not_self['.$this->Member_Model->getTableRealName().'.nickname.uid.'.$urlParam['edit'].']');
			
			$password = $this->input->post('member_passwd');
			$password2 =  $this->input->post('member_passwd2');
			
			if($password || $password2){
				$this->form_validation->set_rules('member_passwd','密码','alpha_dash|min_length[6]|max_length[12]');
				$this->form_validation->set_rules('member_passwd2','密码确认','matches[member_passwd]');
			}
			
			
			$this->_addRules();
			
			for($i = 0 ; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->assign('feedback','<div class="tip_error">'.$this->form_validation->error_string('','').'</div>');
					break;
				}
				
				$updateData = array(
					'nickname' => $this->input->post('member_nickname'),
					'qq' => $this->input->post('member_qq'),
					'weixin' => $this->input->post('member_weixin'),
					'email' => $this->input->post('member_email'),
					'username' => $this->input->post('member_username'),
					'sex' => $this->input->post('member_sex'),
					'allowtalk' => $this->input->post('allowtalk'),
					'freeze' => $this->input->post('memberstate'),
				);
				
				$aid = $this->input->post('avatar_id');
				
				if($aid){
					$member_avatar = $this->input->post('member_avatar');
					$avatar = getImgPathArray($member_avatar,array('m','s'));
					$avatar['aid'] = $aid;
					$updateData = array_merge($updateData,$avatar);
				}
				
				if($password){
					$updateData['password'] = $password;
				}
				
				//print_r($updateData);
				$this->load->library('Member_service');
				$flag = $this->member_service->updateUserInfo($updateData,$urlParam['edit']);
				
				if($flag >= 0){
					if($aid && $info['aid']){
						//传新的图片
						$this->load->library('Attachment_service');
						$this->attachment_service->deleteByFileUrl(array($info['avatar_middle'],$info['avatar_small']));
					}
					
					$info = $this->member_service->getUserInfoById($urlParam['edit']);
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
			
			$this->load->library('Attachment_service');
			
			$fileData = $this->attachment_service->resize(array('file_url' => $src_file) , 
				array('m' => array('width' => $this->input->post('w'),'height' => $this->input->post('h'),'maintain_ratio' => false , 'quality' => 100)) , 
				array('x_axis' => $this->input->post('x1'), 'y_axis' => $this->input->post('y1')));
			
			
			
			if($fileData['img_m']){
				$smallImg = $this->attachment_service->resize(array(
					'file_url' => $fileData['img_m']
				) , array('s') );
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
					'url'=>base_url($fileData['img_m']),
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
