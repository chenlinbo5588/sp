<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Worker extends Ydzj_Admin_Controller {
	
	
	private $_idTypeList;
	
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service','Goods_service','Attachment_service'));
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
		
		$this->_moduleTitle = '家政从业人员';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '新增'),
		);
		
		$this->_idTypeList = $this->wuye_service->getBasicDataByName('证件类型');
	}
	
	
	
	public function index(){
		$searchMap['goods_commend'] = array('未推荐' => '0','已推荐' => '1');
		$searchMap['goods_state'] = array('未发布' => '0','正常' => '1');
		$searchMap['goods_verify'] = array('未通过' => '0','通过' => '1');
		
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
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
		
		
		$goodsName = $this->input->get_post('search_goods_name');
		$goodsVerify = $this->input->get_post('goods_verify') ? $this->input->get_post('goods_verify') : '';
		
		$goodsCommend = $this->input->get_post('goods_commend') ? $this->input->get_post('goods_commend') : '';
		$goodsState = $this->input->get_post('goods_state') ? $this->input->get_post('goods_state') : '';
		$goodsClassId = $this->input->get_post('gc_id') ? $this->input->get_post('gc_id') : 0;
		
		if($goodsName){
			$condition['like']['goods_name'] = $goodsName;
		}
		
		if($searchMap[$goodsCommend]){
			$condition['where']['goods_commend'] = $searchMap[$goodsCommend];
		}
		
		if($searchMap[$goodsVerify]){
			$condition['where']['goods_verify'] = $searchMap[$goodsVerify];
		}
		
		if($searchMap[$goodsState]){
			$condition['where']['goods_state'] = $searchMap[$goodsState];
		}
		
		//print_r($condition);
		$list = $this->Worker_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		$this->assign('searchMap',$searchMap);
		
		$this->display();
	}
	
	
	/**
	 * 校验规则
	 */
	private function _getRules(){
		$this->form_validation->set_rules('name','姓名','required|max_length[60]');
		
		if(ENVIRONMENT == 'production'){
			$idType = $this->input->post('id_type');
			if('身份证' == $this->_idTypeList[$idType]['show_name'] || '驾驶证' == $this->_idTypeList[$idType]['show_name']){
				$this->form_validation->set_rules('id_no','证件号码',"required|valid_idcard");
			}else{
				$this->form_validation->set_rules('id_no','证件号码',"required|max_length[30]");
			}
		}else{
			$this->form_validation->set_rules('id_no','证件号码',"required|max_length[30]");
		}
		
		$this->form_validation->set_rules('birthday','出生年月','required|valid_date');
		$this->form_validation->set_rules('age','年龄','required|is_natural_no_zero');
		$this->form_validation->set_rules('sex','性别','required|is_natural_no_zero');
		$this->form_validation->set_rules('jiguan','籍贯',"required|is_natural_no_zero");
		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		$this->form_validation->set_rules('address','现居住地址','required|max_length[100]');
		
		//$this->form_validation->set_rules('shu','属相','required|is_natural_no_zero');
		$this->form_validation->set_rules('degree','最高学历','required|is_natural_no_zero');
		
		
		/*
		$remark = $this->input->post('remark');
		
		if($remark){
			$this->form_validation->set_rules('remark','备注','required');
		}
		*/
	}
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Worker_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'goods_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 准备数据
	 */
	private function _prepareData($action = 'add'){
		$fileInfo = $this->attachment_service->addImageAttachment('worker_pic',array('widthout_db' => true),FROM_BACKGROUND,'worker');
		
		$info = array(
			'name' => $this->input->post('name'),
			'id_type' => $this->input->post('id_type'),
			'id_no' => $this->input->post('id_no'),
			'age' => $this->input->post('age'),
			'sex' => $this->input->post('sex'),
			'birthday' => $this->input->post('birthday'),
			'jiguan' => $this->input->post('jiguan'),
			'mobile' => $this->input->post('mobile'),
			'address' => $this->input->post('address'),
			'degree' => $this->input->post('degree'),
		);
		
		
		if($fileInfo){
			$oldFile = $this->input->post('old_pic');
			if('add' == $action){
				//添加页面 删除上一次上传的图片
				if($oldFile){
					$oldFiles = getImgPathArray($oldFile,array('b','m','s'));
					$this->attachment_service->deleteByFileUrl($oldFiles);
				}
				
				//设置新上传的图片
				$info['avatar'] = $fileInfo['file_url'];
				
				//设置上一张图片
				$info['old_pic'] = $info['avatar'];
			}else{
				//编辑页面 
				
				$info['old_pic'] = $oldFile;
				$info['avatar'] = $fileInfo['file_url'];
			}
			
			$fileInfo = $this->attachment_service->resize($fileInfo,array('b','m','s'));
			
			if($fileInfo['img_b']){
				//如果裁剪了大图用大图,防止上传超级大图后，前台页面显示好几兆的图片
				$info['avatar_b'] = $fileInfo['img_b'];
			}
			
			if($fileInfo['img_m']){
				//如果裁剪了大图用大图,防止上传超级大图后，前台页面显示好几兆的图片
				$info['avatar_m'] = $fileInfo['img_m'];
			}
			
			if($fileInfo['img_s']){
				//如果裁剪了大图用大图,防止上传超级大图后，前台页面显示好几兆的图片
				$info['avatar_s'] = $fileInfo['img_m'];
			}
			
			// 标记上传了新文件,用于删除旧文件用
			$info['file_url'] = $fileInfo['file_url'];
		}else{
			
			$pic = $this->input->post('old_pic');
			if($pic){
				//还是记住上一张
				$info['old_pic'] = $pic;
				
				$info['avatar'] = $pic;
				$imgs = getImgPathArray($info['avatar'],array('b','m','s'));
				$info = array_merge($info,$imgs);
			}
		}
		
		$remark = $this->input->post('remark');
		
		if($remark){
			$info['remark'] = $remark;
		}
		
		return $info;
	}
	
	
	
	private function _getFileList(){
		$file_ids = $this->input->post('file_id');
		
		$fileList = array();
		
		if($file_ids){
			$fileList = $this->Worker_Images_Model->getList(array(
				//'select' => 'id as image_aid,file_url',
				'where_in' => array(
					array('key' => 'image_aid', 'value' => $file_ids)
				),
				'order' => 'id DESC'
			));
		}
		
		return $fileList;
	}
	
	
	
	public function add(){
		$feedback = '';
		
		$jiguanList = $this->wuye_service->getBasicDataByName('籍贯');
		$xueliList = $this->wuye_service->getBasicDataByName('学历');
		$shuList = $this->wuye_service->getBasicDataByName('属相');
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData();
				$fileList = $this->_getFileList();
				
				$this->assign('fileList',$fileList);
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				//获得属相
				foreach($shuList as $shuItem){
					if($shuItem['show_name'] == getShuXiang($info['birthday'])){
						$info['shu'] = $shuItem['id'];
						break;
					}
				}
				
				if(($newid = $this->Worker_Model->_add(array_merge($info,$this->addWhoHasOperated()))) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				if($fileList){
					$updateFileIds = array();
					foreach($fileList as $fileInfo){
						$updateFileIds[] = $fileInfo['image_aid'];
					}
					
					$this->Worker_Images_Model->updateByCondition(array(
						'worker_id' => $newid
					
					),array(
						'where_in' => array(
							array('key' => 'image_aid', 'value' => $updateFileIds)
						)
					));
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Worker_Model->getFirstByKey($newid);
			}
		}
		
		
		$this->assign(array(
			'province_idcard' => json_encode(config_item('province_idcard')),
			'idTypeList' => $this->_idTypeList,
			'jiguanList' => $jiguanList,
			'xueliList' => $xueliList,
			'info' => $info,
			'feedback' => $feedback
		));
		
		$this->display();
	}
	
	
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addImageAttachment('fileupload',array(),FROM_BACKGROUND,'worker');
		if($fileData){
			
			$fileData = $this->attachment_service->resize($fileData);
			
			$info = array(
				'worker_id' => $this->input->post('worker_id') ? $this->input->post('worker_id') : 0,
				'image_aid' => $fileData['id'],
				'image' => $fileData['file_url'],
				'image_b' => !empty($fileData['img_b']) ? $fileData['img_b'] : '',
				'image_m' => !empty($fileData['img_m']) ? $fileData['img_m'] : '',
				'uid' => $this->_adminProfile['basic']['uid']
			);
			
			$imageId = $this->Worker_Images_Model->_add($info);
			if($imageId){
				$json['error'] = 0;
				$json['id'] = $fileData['id'];
				$json['image_id'] = $imageId;
				$json['url'] = base_url($fileData['file_url']);
				
				//尽量选择小图
				if($fileData['img_b']){
					$json['url'] = base_url($fileData['img_b']);
				}
				
			}else{
				$json['error'] = 0;
				$json['msg'] = '系统异常';
				$this->attachment_service->deleteByFileUrl(array(
					$fileData['file_url'],
					$fileData['img_b'],
					$fileData['img_m'],
				));
			}
			
		}else{
			$json['msg'] = $this->attachment_service->getErrorMsg('','');
		}
		
		exit(json_encode($json));
		
	}
	
	
	public function delimg(){
		$file_id = intval($this->input->get_post('file_id'));
		$worker_id = intval($this->input->get_post('worker_id'));
		
		if($worker_id){
			//如果在编辑页面
			$this->Worker_Images_Model->deleteByCondition(array(
				'where' => array(
					'image_aid' => $file_id,
					'worker_id' => $worker_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}else{
			//在新增界面，还没有worker id
			$this->Worker_Images_Model->deleteByCondition(array(
				'where' => array(
					'image_aid' => $file_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}
		
		if($file_id){
			//文件删除，数据库记录不删除
			$this->attachment_service->deleteFiles($file_id,'all',FROM_BACKGROUND);
		}
		
		$this->jsonOutput('成功',$this->getFormHash());
	}
	
	
	/**
	 * 编辑工作人员
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$jiguanList = $this->wuye_service->getBasicDataByName('籍贯');
		$xueliList = $this->wuye_service->getBasicDataByName('学历');
		$shuList = $this->wuye_service->getBasicDataByName('属相');
		
		$info = $this->Worker_Model->getFirstByKey($id);
		
		$fileList = array();
		
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$postInfo = $this->_prepareData('edit');
				$fileList = $this->_getFileList();
				
				$info = $postInfo;
				$info['id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				//获得属相
				foreach($shuList as $shuItem){
					if($shuItem['show_name'] == getShuXiang($info['birthday'])){
						$info['shu'] = $shuItem['id'];
						break;
					}
				}
				
				if($this->Worker_Model->update($info,array('id' => $id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$originalPic = $this->input->post('old_pic');
				if($postInfo['file_url'] && $originalPic){
					//如果上传了新文件,则删除原文件
					$this->attachment_service->deleteByFileUrl($originalPic);
				}
				
				$feedback = getSuccessTip('保存成功');
			}
			
		}else{
			$fileList = $this->Worker_Images_Model->getList(array(
				'where' => array('worker_id' => $id)
			));
		}
		
		$this->assign(array(
			'province_idcard' => json_encode(config_item('province_idcard')),
			'idTypeList' => $this->_idTypeList,
			'fileList' => $fileList,
			'jiguanList' => $jiguanList,
			'xueliList' => $xueliList,
			'info' => $info,
			'feedback' => $feedback
		));
		
		$this->display($this->_className.'/add');
	}
}
