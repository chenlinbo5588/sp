<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recommend extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Cms_service','Attachment_service','Basic_data_service','Weixin_mp_api'));
		
		$this->assign('imageConfig',config_item('weixin_img_size'));
		
		$this->_moduleTitle = '推荐管理';
		$this->_className = strtolower(get_class());
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '新增'),
		);
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'styleList' => $this->basic_data_service->getTopChildList('推荐类型'),
			'dataformatList' => $this->basic_data_service->getTopChildList('时间格式'),
			'basicData' => $this->basic_data_service->getBasicDataList(),
		));
		
		
	}
	
	public function index(){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$list = $this->Recommend_Model->getList($condition);
		$this->assign(array(
			'list' => $list,
			'page' => $list['pager'],
			'currentPage' => $currentPage
			
		));
		$this->display();
		
	}
	
	
	public function add(){
		$mouldList = $this->Template_Model->getList(array(),'id');
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_setRule();		
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$insertData = array_merge($_POST,$this->addWhoHasOperated('add'));
				
				$insertData['template_name'] = $mouldList[$insertData['template_id']]['name'];
				$newid =$this->Recommend_Model->_add($insertData);
				
				if($newid){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
			$this->assign('mouldList',$mouldList);
			$this->display();
		}
	}
	
	public function edit(){
		$id = $this->input->get_post('id');
		$info = $this->Recommend_Model->getFirstByKey($id);
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		$mouldList = $this->Template_Model->getList(array(),id);
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$this->_setRule();		
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$updateData = array_merge($_POST,$this->addWhoHasOperated('edit'));
				
				$updateData['template_name'] = $mouldList[$updateData['template_id']]['name'];
				$updateData['gmt_edit'] = time();
				
				$resuit = $this->Recommend_Model->update($updateData,array('id' => $id));
				
				if($resuit){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
				}
				
			}
		}else{
			$this->assign('mouldList',$mouldList);
			$this->assign('info',$info);
			$this->display($this->_className.'/add');
		}
	}
	
	
	private function _setRule(){
		$this->form_validation->set_rules('name','推荐名称','required');
		$this->form_validation->set_rules('show_number','显示条数','required|numeric');
		$this->form_validation->set_rules('cachetime','缓存时间','required|numeric');
		$this->form_validation->set_rules('style','推荐类型','required');
		$this->form_validation->set_rules('dateformat','时间格式','required');
	}
	
	
	public function detail(){
		$id = $this->input->get_post('id') ? $this->input->get_post('id') : 0;
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '数据');
		$info = $this->Recommend_Detail_Model->getList(array('where' => array('recommend_id' => $id)));
		$recommendInfo = $this->Recommend_Model->getFirstByKey($id);
		$this->assign('info',$info);
		$this->assign('recommendInfo',$recommendInfo);
		$this->display();
	}
	
	
	public function add_detail(){
		$id = $this->input->get_post('id');
		$this->_subNavs[] = array('url' => $this->_className.'/add_detail?id='.$id, 'title' => '添加数据');
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '数据');		
		$recommendInfo = $this->Recommend_Model->getFirstByKey($id);
		if($this->isPostRequest()){			
			for($i = 0; $i < 1; $i++){
				$recommendInfo = $this->Recommend_Model->getFirstByKey($_POST['recommend_id']);
				$this->detailRule($recommendInfo['max_title']);
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				$info = array_merge($_POST,$this->addWhoHasOperated('add'));
				$info['recommend_id'] = $id;
				$info['startdate'] = strtotime($info['startdate']);
				$info['enddate'] = strtotime($info['enddate']);
				$info['release_time'] = strtotime($info['release_time']);
				
				$this->Recommend_Detail_Model->_add($info);
				
				$error = $this->Recommend_Detail_Model->getError();
				
				if(QUERY_OK != $error['code']){
					$feedback = getErrorTip('保存失败,'.$error['message']);
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
			
		}else{
			$this->assign('recommendinfo',$recommendInfo);
			$this->display();
		}
		
	}
	
	
	public function edit_detail(){
		$id = $this->input->get_post('id');
		$info = $this->Recommend_Detail_Model->getFirstByKey($id);
		$recommendInfo = $this->Recommend_Model->getFirstByKey($info['recommend_id']);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit_detail?id='.$id, 'title' => '编辑数据');	
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$recommendInfo['id'], 'title' => '数据');
		$basicBata = $this->basic_data_service->getBasicDataList();
		
		if($this->isPostRequest()){			
			for($i = 0; $i < 1; $i++){
				
				$this->detailRule($recommendInfo['max_title']);
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_html('<div>','<div>'),array('errors' => $this->form_validation->error_array()));
					break;
				}
				$updateInfo = array_merge($_POST,$this->addWhoHasOperated('edit'));
				if($basicBata[$recommendInfo['style']]['show_name'] != '文章'){
					if(!$updateInfo['img_url']){
						$this->jsonOutput('该类型的推荐位必须要图片,请上传图片');
						break;
					}
				}
				if(!$this->judgeRepeat($recommendInfo['id'],$updateInfo['display'],$id)){
					$this->jsonOutput('显示位置输入错误');
					break;
				}
				
				$updateData['gmt_edit'] = time();
				$updateInfo['recommend_id'] = $recommendInfo['id'];
				$updateInfo['startdate'] = strtotime($updateInfo['startdate']);
				$updateInfo['enddate'] = strtotime($updateInfo['enddate']);
				$updateInfo['release_time'] = strtotime($updateInfo['release_time']);
				
				$resuit = $this->Recommend_Detail_Model->update($updateInfo,array('id' => $id));
				
				if($resuit){
					$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/detail?id='.$recommendInfo['id'])));
				}
			}
			
		}else{
			$this->assign('info',$info);
			$this->display($this->_className.'/add_detail');
		}
		
	}
	
	
	
	public function gettitle(){
		$searchKey = $this->input->get_post('term');
		$return = array();
		
		if($searchKey){
			
			$cmsList = $this->Cms_Article_Model->getList(array(
				'where' => array(
					'id' => $searchKey
				),
				'limit' => 10
			));
			foreach($cmsList as $item ){
				$return[] = array(
					'id' => $item['id'],
					'label' => $item['id'].' '.$item['article_title'],
					'value' => $item['id'],
					'title'=> $item['article_title'],
					'jump_url' => $item['origin_address'],
					'img_url' => $item['image_url'],
					'synopsis' => $item['content'],
					'release_time' => date('Y-m-d h:i:s',$item['publish_time']),
				);
			}
		}
		
		$this->jsonOutput2('',$return,false);
	}
	
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		$recommendDetailInfo = $this->Recommend_Detail_Model->getFirstByKey($id);
		$recommendId = $recommendDetailInfo['recommend_id'];
		$recommendInfo = $this->Recommend_Model->getFirstByKey($recommendId);
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[display]');
			if($fieldName == 'display'){
				if($newValue > $recommendInfo['show_number']){
					$this->jsonOutput('位置不能超过最大显示条数');
					break;
				}
			}
			
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				
				if($this->Recommend_Detail_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
	}
	
	public function delete(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			$this->Recommend_Detail_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			$this->jsonOutput('删除成功',array('jsReload' => true));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
		
	/**
	 * 删除图片
	 */
	public function delimg(){
		
		$imgUrl = $this->input->get_post('img_url');
		$id = $this->input->get_post('id');
		
		if($id){
			//如果在编辑页面
			$rowsDelete = $this->Recommend_Detail_Model->updateByCondition(array(
				'img_url' => ''
			),array(
				'where' => array(
					'id' => $id,
				)
			));
		}
		if($rowsDelete){
			$fileList = getImgPathArray($imgUrl,array('b','m','s'));
			$this->attachment_service->deleteByFileUrl($fileList);
		}
		
		$this->jsonOutput('成功',$this->getFormHash());
	}
	
	private function detailRule($maxTitle){
		
		$this->form_validation->set_rules('title','标题','required|max_length['.$maxTitle.']');
		$this->form_validation->set_rules('url','跳转地址','required');
		$this->form_validation->set_rules('synopsis','简介','required');
		$this->form_validation->set_rules('release_time','发布时间','required');
		$this->form_validation->set_rules('display','位置','required');
	}
	
	private function judgeRepeat($recommendId,$showNumber,$recommendDetailId = 0){
		$recommendInfo = $this->Recommend_Model->getFirstByKey($recommendId);
		$recommendDetailList =$this->Recommend_Detail_Model->getList(array('where' => array('recommend_id' => $recommendId)),'display');
		if($showNumber > 0 && $showNumber <= $recommendInfo['show_number']){
			if($recommendDetailId){
				$recommendDetailIfon = $this->Recommend_Detail_Model->getFirstByKey($recommendDetailId);
				if($recommendDetailIfon['display'] == $showNumber){
					return true;
				}
			}
			if(in_array($showNumber,array_keys($recommendDetailList))){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
		
	}
	
	public function uploadMaterial(){
		$id = $this->input->get_post('id');
		$recommendinfo = $this->Recommend_Model->getFirstByKey($id);
		$condition['where']['startdate <='] = time();
		$condition['where']['enddate >='] = time();
		$condition['where']['recommend_id'] = $recommendinfo['id'];
		$newList = $this->Recommend_Detail_Model->getList($condition);
		if($newList){
			foreach($newList as $key => $item){
				$arctleInfo = $this->Cms_Article_Model->getFirstByKey($item['cms_id']);
				$content = $this->handleContent($arctleInfo['content']);
				file_put_contents('temp\1.jpg',$this->weixin_mp_api->download($item['img_url']));
				$result = $this->weixin_mp_api->upload($_SERVER['DOCUMENT_ROOT'].'\temp\1.jpg','image');
				if($result['media_id']){
					unlink($_SERVER['DOCUMENT_ROOT'].'\temp\1.jpg');
					$thumb_media_id = $result['media_id'];
					$articles[] = array(
						"title"=> $item['title'],
						"thumb_media_id"=> $thumb_media_id,
		   				"author"=> '孙亚龙',
						"show_cover_pic"=> 0,
						"content"=> $content,
						"content_source_url"=>$item['url'],
						"need_open_comment"=>0,
						"only_fans_can_comment"=>1,
					);
				}		
			}
		}

		
		$resulr = $this->weixin_mp_api->uploadnews($articles);
		if($resulr['media_id']){
			$result = $this->Recommend_Model->updateByCondition(array(
				'media_id' => $resulr['media_id']
			),array(
				'where' => array(
					'id' => $recommendinfo['id'],
				)
			));
			if($result){
				$this->jsonOutput('上传素材成功',array('jsReload' => true));
			}else{
				$this->jsonOutput('请求非法',$this->getFormHash());
			}
		}
		
	}
	
	public function handleContent($content){
		preg_match_all('/<img[^>]*?src="([^"]*?)"[^>]*?>/i',$content,$match);
		if(is_array($match[1])){
			foreach($match[1] as $key => $item){
				file_put_contents('temp\1.jpg',$this->weixin_mp_api->download($item));
				$result = $this->weixin_mp_api->uploadImg($_SERVER['DOCUMENT_ROOT'].'\temp\1.jpg');
				if($result['url']){
					unlink($_SERVER['DOCUMENT_ROOT'].'\temp\1.jpg');
					$content = str_replace($item,$result['url'],$content);
					
				}
			}
		}
		
		$content = $content. $this->weixin_mp_api->getMessageBottomHTML();
		return $content;
	}
	
	
	public function preview(){
		$id = $this->input->get_post('id');
		$recommendInfo = $this->Recommend_Model->getFirstByKey($id);
		$result = $this->weixin_mp_api->preview($recommendInfo['media_id']);
		if($result['errcode'] == QUERY_OK){
			$this->jsonOutput('预览成功',array('jsReload' => true));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	public function group_send(){
		$id = $this->input->get_post('id');
		$recommendInfo = $this->Recommend_Model->getFirstByKey($id);
		$result = $this->weixin_mp_api->sendMessageNews($recommendInfo['media_id']);
		if($result['errcode'] == QUERY_OK){
			$this->jsonOutput('群发成功',array('jsReload' => true));
		}else{
			print_r($result);
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
		
	}
	
	
}
