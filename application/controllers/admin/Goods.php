<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Goods extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->_moduleTitle = '商品';
		$this->_className = strtolower(get_class());
		
		
		$this->load->library(array('Goods_service','Attachment_service'));
		$this->attachment_service->setUid($this->_adminProfile['basic']['uid']);
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/unverify','title' => '未审核'),
			array('url' => $this->_className.'/verify','title' => '审核通过'),
			array('url' => $this->_className.'/published','title' => '正常'),
			array('url' => $this->_className.'/unpublished','title' => '下架'),
			array('url' => $this->_className.'/recommend','title' => '推荐'),
			array('url' => $this->_className.'/unrecommend','title' => '未推荐'),
			array('url' => $this->_className.'/add','title' => '新增'),
			
		);
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'goodsVerify'=> GoodsVerift::$statusName,
			'goodsCommend'=> GoodsReCommend::$statusName,
			'goodsState'=> GoodsStatus::$statusName
		));
	}
	/**
	 * 查询条件
	 */
	public function _searchCondition($moreSearchVal = array()){

		
		$brandList = $this->Brand_Model->getList(array(),'brand_id');
		
		$treelist = $this->goods_service->toEasyUseArray($this->goods_service->getGoodsClassTreeHTML(),'gc_id');
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
	
		$condition = array(
			'where' => array(),
			'order' => 'goods_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		$goodsName = $this->input->get_post('search_goods_name');
		$searchMap['goods_verify'] = $this->input->get_post('goods_verify') ? $this->input->get_post('goods_verify') : '';
		$searchMap['goods_commend'] = $this->input->get_post('goods_commend') ? $this->input->get_post('goods_commend') : '';
		$searchMap['goods_state'] = $this->input->get_post('goods_state') ? $this->input->get_post('goods_state') : '';
		$searchMap['brand_id'] = $this->input->get_post('brand_id') ? $this->input->get_post('brand_id') : '';
		$goodsClassId = $this->input->get_post('gc_id') ? $this->input->get_post('gc_id') : 0;
		$searchMap = array_merge($searchMap,$moreSearchVal);
		if($goodsName){
			$condition['like']['goods_name'] = $goodsName;
		}
		
		if($searchMap['goods_commend']){
			$condition['where']['goods_commend'] = $searchMap['goods_commend'];
		}
		
		if($searchMap['goods_verify']){
			$condition['where']['goods_verify'] = $searchMap['goods_verify'];
		}
		
		if($searchMap['goods_state']){
			$condition['where']['goods_state'] = $searchMap['goods_state'];
		}
		if($searchMap['brand_id']){
			$condition['where']['brand_id'] = $searchMap['brand_id'];
		}
		
		
		if($goodsClassId){
			$goodsClassIdList = $this->Goods_Class_Model->getAllChildGoodsClassByPid($goodsClassId);
			$goodsClassIdList[] = $goodsClassId;
			
			$condition['where_in'][] = array('key' => 'gc_id', 'value' => $goodsClassIdList);
			
		}
		
		
		//print_r($condition);
		
		$list = $this->Goods_Model->getList($condition);
		

		
		$this->assign(array(
			'goodsClassList' => $treelist,
			'list' => $list,
			'brandList'=>$brandList,
			'page' => $list['pager'],
			'searchMap' => $searchMap,
			'currentPage' =>$currentPage
		));
		
		
	}
	
	public function index(){
		$this->_searchCondition();
		$this->display($this->_className.'/index');
	}
	
	public function unverify(){
		$this->_searchCondition(array(
			'goods_verify' =>GoodsVerift::$draft
		));
		$this->display($this->_className.'/index');
	}
	
	public function verify(){
		$this->_searchCondition(array(
			'goods_verify' =>GoodsVerift::$unverify
		));
		$this->display($this->_className.'/index');
	}
	
	public function published(){
		$this->_searchCondition(array(
			'goods_state' =>GoodsStatus::$unverify
		));
		$this->display($this->_className.'/index');
	}
	
	public function unpublished(){
		$this->_searchCondition(array(
			'goods_state' =>GoodsStatus::$draft
		));
		$this->display($this->_className.'/index');
	}
	
	public function recommend(){
		$this->_searchCondition(array(
			'goods_commend' =>GoodsReCommend::$unverify
		));
		$this->display($this->_className.'/index');
	}
	
	public function unrecommend(){
		$this->_searchCondition(array(
			'goods_commend' =>GoodsReCommend::$draft
		));
		$this->display($this->_className.'/index');
	}
	
	/**
	 * 提交审核
	 */
	
	public function handle_verify(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->goods_service->changeGoodsStatus($ids,GoodsVerift::$unverify,GoodsVerift::$draft,'goods_verify');
			
			if($returnVal > 0){
				$this->jsonOutput('提交审核成功',$this->getFormHash());
			}else{
				$this->jsonOutput('提交审核失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}	
	}
	
	
	public function batch_verify(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->goods_service->changeGoodsStatus($ids,GoodsReCommend::$unverify,GoodsReCommend::$draft,'goods_commend');
			
			if($returnVal > 0){
				$this->jsonOutput('推荐成功',$this->getFormHash());
			}else{
				$this->jsonOutput('推荐失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	public function batch_published(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->goods_service->changeGoodsStatus($ids,GoodsStatus::$unverify,GoodsStatus::$draft,'goods_state');
			
			if($returnVal > 0){
				$this->jsonOutput('上架成功',$this->getFormHash());
			}else{
				$this->jsonOutput('上架失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	public function batch_offline(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->goods_service->changeGoodsStatus($ids,GoodsStatus::$draft,GoodsStatus::$unverify,'goods_state');
			
			if($returnVal > 0){
				$this->jsonOutput('下架成功',$this->getFormHash());
			}else{
				$this->jsonOutput('下架失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	private function _getRules(){
		$this->form_validation->set_rules('goods_name','商品中文名称','required|max_length[60]');
		$this->form_validation->set_rules('gc_id','商品分类',"required|in_db_list[".$this->Goods_Class_Model->getTableRealName().".gc_id]");
		$this->form_validation->set_rules('goods_intro','商品中文简介','required');
		$this->form_validation->set_rules('goods_commend','是否推荐','required|in_list[0,1]');
		$this->form_validation->set_rules('goods_verify','是否审核','required|in_list[0,1]');
		$this->form_validation->set_rules('goods_state','是否发布','required|in_list[0,1]');
	}
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Goods_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'goods_id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	private function _prepareGoodsData(){
		
		$fileInfo = $this->attachment_service->addImageAttachment('goods_pic',array(),FROM_BACKGROUND,'goods');
		
		$info = array(
			'goods_name' => $this->input->post('goods_name'),
			'gc_id' => $this->input->post('gc_id') ? $this->input->post('gc_id') : 0,
			'brand_id' => $this->input->post('brand_id') ? $this->input->post('brand_id') : 0,
			'goods_intro' => $this->input->post('goods_intro') ? $this->input->post('goods_intro') : '',
			'goods_commend' => $this->input->post('goods_commend') ? $this->input->post('goods_commend') : 0,
			'goods_verify' => $this->input->post('goods_verify'),
			'goods_state' => $this->input->post('goods_state'),
		);
		
		if($fileInfo){
			$fileInfo = $this->attachment_service->resize($fileInfo);
			
			$info['goods_pic'] = $fileInfo['file_url'];
			
			if($fileInfo['img_b']){
				//如果裁剪了大图用大图,防止上传超级大图后，前台页面显示好几兆的图片
				$info['goods_pic_b'] = $fileInfo['img_b'];
			}
			
			if($fileInfo['img_m']){
				//如果裁剪了大图用大图,防止上传超级大图后，前台页面显示好几兆的图片
				$info['goods_pic_m'] = $fileInfo['img_m'];
			}
			
			// 标记上传了新文件
			$info['file_url'] = $fileInfo['file_url'];
			
		}
		
		
		return $info;
	}
	
	
	
	private function _getFileList(){
		$file_ids = $this->input->post('file_id');
		
		$fileList = array();
		
		if($file_ids){
			$fileList = $this->Attachment_Model->getList(array(
				'select' => 'id,file_url',
				'where_in' => array(
					array('key' => 'id', 'value' => $file_ids)
				),
				'order' => 'id DESC'
			));
			
			
		}
		
		return $fileList;
		
	}
	
	public function add(){
		$feedback = '';
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		$brandList = $this->Brand_Model->getList();
		
		if($this->isPostRequest()){
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareGoodsData();
				$fileList = $this->_getFileList();
				
				$this->assign('fileList',$fileList);
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->input->post('goods_state') == 1){
					$info['public_time'] = $this->input->server('REQUEST_TIME');
				}
				
				$info['goods_code'] = $info['gc_id'] . '-' . strtolower(substr(md5($info['goods_name']),0,6));
				
				if(($newid = $this->Goods_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				
				if($fileList){
					$insData = array();
					
					foreach($fileList as $fileInfo){
						$imgs = getImgPathArray($fileInfo['file_url'],array('b','m'));
						
						$insData[] = array(
							'goods_id' => $newid,
							'goods_image' => $fileInfo['file_url'],
							'goods_image_b' => $imgs['img_b'],
							'goods_image_m' => $imgs['img_m'],
							'uid' => $this->_adminProfile['basic']['uid'],
							'gmt_create' => $this->input->server('REQUEST_TIME'),
							'gmt_modify' => $this->input->server('REQUEST_TIME'),
						);
					}
					
					$this->Goods_Images_Model->batchInsert($insData);
				}
				
				$feedback = getSuccessTip('保存成功');
				
				$info = $this->Goods_Model->getFirstByKey($newid,'goods_id');
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		
		$this->assign('brandList',$brandList);
		$this->assign('goodsClassList',$treelist);
		$this->display();
	}
	
	
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上次失败');
		
		$fileData = $this->attachment_service->addImageAttachment('fileupload',array(),FROM_BACKGROUND,'goods');
		if($fileData){
			
			
			$fileData = $this->attachment_service->resize($fileData);
			
			$info = array(
				'goods_id' => $this->input->post('goods_id') ? $this->input->post('goods_id') : 0,
				'goods_image' => $fileData['file_url'],
				'goods_image_b' => !empty($fileData['img_b']) ? $fileData['img_b'] : '',
				'goods_image_m' => !empty($fileData['img_m']) ? $fileData['img_m'] : '',
				'uid' => $this->_adminProfile['basic']['uid']
			);
			if($info['goods_id']){
				$imageId = $this->Goods_Images_Model->_add($info);
				if($imageId){
					$json['error'] = 0;
					$json['id'] = $imageId;
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
				//maybe run here
				
				$json['error'] = 0;
				$json['id'] = $fileData['id'];
				$json['url'] = base_url($fileData['file_url']);
				
				//尽量选择小图
				if($fileData['img_b']){
					$json['url'] = base_url($fileData['img_b']);
				}
			}
			
		}else{
			$json['msg'] = $this->attachment_service->getErrorMsg('','');
		}
		
		exit(json_encode($json));
		
	}
	
	
	public function delimg(){
		$file_id = $this->input->get_post('file_id');
		$goods_id = $this->input->get_post('goods_id');
		
		if($goods_id){
			//如果在商品编辑页面
			$this->Goods_Images_Model->deleteByCondition(array(
				'where' => array(
					'goods_id' => $goods_id,
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
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('goods_id');
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		$brandList = $this->Brand_Model->getList();
		
		$info = $this->Goods_Model->getFirstByKey($id,'goods_id');
		
		$fileList = array();
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$postInfo = $this->_prepareGoodsData();
				$fileList = $this->_getFileList();
				
				//没有新上传文件，并且原来有图片
				if(empty($postInfo['goods_pic']) && !empty($info['goods_pic'])){
					$postInfo['goods_pic'] = $info['goods_pic'];
				}
				
				$info = $postInfo;
				$info['goods_id'] = $id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				
				if($this->input->post('goods_state') == 1){
					$info['public_time'] = $this->input->server('REQUEST_TIME');
				}
				
				if($this->Goods_Model->update($info,array('goods_id' => $id)) < 0){
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
			
		}
		
		
		$fileList = $this->Goods_Images_Model->getList(array(
			'where' => array('goods_id' => $id)
		));
			
			
		$this->assign('fileList',$fileList);
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->assign('brandList',$brandList);
		$this->assign('goodsClassList',$treelist);
		$this->display('goods/add');
	}
}
