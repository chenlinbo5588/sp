<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_Class extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Goods_service'));
	}
	
	public function category(){
		
		$id = $this->input->get_post('gc_parent_id') ? $this->input->get_post('gc_parent_id') : 0;
		
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		$deep = 0;
		
		
		//print_r($list);
		$parentId = 0;
		foreach($treelist as $item){
			if($id == $item['gc_id']){
				$deep = $item['level'];
				$parentId = $item['gc_parent_id'];
			}
			
		}
		
		$list = $this->goods_service->getGoodsClassByParentId($id);
		$this->assign('list',$list);
		$this->assign('parentId',$parentId);
		$this->assign('deep',$deep + 1);
		$this->assign('id',$id);
		
		$this->display();
	}
	
	
	public function delete(){
		
		$delId = $this->input->post('id');
		
		if($this->isPostRequest()){
			
			if(is_array($delId)){
				$delId = $delId[0];
			}
			
			$this->goods_service->deleteGoodsClass($delId);
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	public function add(){
		
		$feedback = '';
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		
		$gc_parent_id = $this->input->get_post('gc_parent_id');
		
		
		if($gc_parent_id){
			$info['gc_parent_id'] = $gc_parent_id;
		}
		
		if($this->isPostRequest()){
			
			$this->_getRules('add');
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareGoodClassData();
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid = $this->Goods_Class_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
				$info = $this->Goods_Class_Model->getFirstByKey($newid,'gc_id');
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);

		
		$this->assign('list',$treelist);
		$this->display();
	}
	
	
	public function checkpid($pid,$extra = ''){
		//不能是自己，也不能是其下级分类
		
		$currentGcId = $this->input->post('gc_id');
		
		$deep = $this->goods_service->getGoodsClassDeepById($pid);
		
		
		if($deep >=3){
			$this->form_validation->set_message('checkpid','父级只能是一级分类或者二级分类');
			return false;
		}
		
		if($extra == 'add'){
			//如果是增加的不需要再网后面继续执行了
			return true;
		}
		
		
		$list = $this->Goods_Class_Model->getList(array(
			'where' => array('gc_parent_id' => $currentGcId)
		));
		
		$subIds = array($currentGcId);
		$hasData = true;
		
		while($list && $hasData){
			
			$ids = array();
			foreach($list as $item){
				$subIds[] = $item['gc_id'];
				$ids[] = $item['gc_id'];
			}
			
			if(empty($ids)){
				$hasData = false;
			}else{
				$list = $this->Goods_Class_Model->getList(array(
					'where_in' => array(
						array('key' => 'gc_parent_id', 'value' => $ids)
					)
				));
			}
		}
		
		//print_r($subIds);
		if(in_array($pid,$subIds)){
			$this->form_validation->set_message('checkpid','上级不能选择自己和自己的下级分类');
			return false;
		}else{
			
			return true;
		}
		
		
	}
	
	
	private function _getRules($action = 'add'){
		
		$this->form_validation->set_rules('gc_name','分类名称',"required");
		
		if($this->input->post('gc_parent_id')){
			$this->form_validation->set_rules('gc_parent_id','上级分类', "in_db_list[{$this->Goods_Class_Model->_tableRealName}.gc_id]|callback_checkpid[{$action}]");
		}
		
		
		if($this->input->post('gc_pic')){
			$this->form_validation->set_rules('gc_pic','分类图片',"required|valid_url");
		}
		
			
		if($this->input->post('gc_sort')){
			$this->form_validation->set_rules('gc_sort','排序',"is_natural|less_than[256]");
		}
		
	}
	
	
	private function _prepareGoodClassData(){
		
		$info = array(
			'gc_name' => $this->input->post('gc_name'),
			'gc_parent_id' => $this->input->post('gc_parent_id') ? $this->input->post('gc_parent_id') : 0,
			'gc_pic_id' => $this->input->post('gc_pic_id') ? $this->input->post('gc_pic_id') : 0,
			'gc_pic' => $this->input->post('gc_pic') ? $this->input->post('gc_pic') : '',
			'gc_sort' => $this->input->post('gc_sort') ? $this->input->post('gc_sort') : 255
		);
		
		return $info;
	}
	
	
	public function edit(){
		
		$feedback = '';
		$treelist = $this->goods_service->getGoodsClassTreeHTML();
		
		$gc_id = $this->input->get_post('gc_id');
		
		$info = $this->Goods_Class_Model->getFirstByKey($gc_id,'gc_id');
		
		
		if($this->isPostRequest()){
			
			$this->_getRules('edit');
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareGoodClassData();
				$info['gc_id'] = $gc_id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Goods_Class_Model->update($info,array('gc_id' => $gc_id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		
		$this->assign('list',$treelist);
		
		
		$this->display('goods_class/add');
		
	}
	
	
	public function export(){
		
		$class_list = $this->goods_service->getGoodsClassTreeHTML();
		
		
		if($this->isPostRequest()){
			@header("Content-type: application/unknown");
	    	@header("Content-Disposition: attachment; filename=goods_class.csv");
			if (is_array($class_list)){
				foreach ($class_list as $k => $v){
					$tmp = array();
					//序号
					$tmp['gc_sort'] = $v['gc_sort'];
					//深度
					for ($i=1; $i<=$v['level']; $i++){
						$tmp[] = '';
					}
					//分类名称
					$tmp['gc_name'] = $v['gc_name'];
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
		}else{
			
			
			$this->display();
		}
		
	}
	
	
	
	public function import(){
		$feedback = '';
	
		if ($this->isPostRequest()){
			//得到导入文件后缀名
			$csv_array = explode('.',$_FILES['csv']['name']);
			$file_type = end($csv_array);
			if (!empty($_FILES['csv']) && !empty($_FILES['csv']['name']) && $file_type == 'csv'){
				$fp = @fopen($_FILES['csv']['tmp_name'],'rb');
				// 父ID
				$parent_id_1 = 0;
				
				
				/*
				$allLines = file($_FILES['csv']['tmp_name']);
				
				foreach($allLines as $line){
					$line = str_replace(array("\r\n","\n","\r",'"'),'', trim($line));
					switch (strtoupper($_POST['charset'])){
						case 'UTF-8':
							if (strtoupper(config_item('charset')) !== 'UTF-8'){
								$line = iconv('UTF-8',strtoupper(config_item('charset')),$line);
							}
							break;
						case 'GBK':
							if (strtoupper(config_item('charset')) !== 'GBK'){
								$line = iconv('GBK',strtoupper(config_item('charset')),$line);
							}
							break;
					}
					
					
					//print_r($line);
					
					
					//逗号去除
					$tmp_array = array();
					$tmp_array = explode(',',$line);
					if($tmp_array[0] == 'sort_order')continue;
					//第一位是序号，后面的是内容，最后一位名称
					$tmp_deep = 'parent_id_'.(count($tmp_array)-1);
					
					//echo $tmp_deep;
					$insert_array = array();
					$insert_array['gc_sort'] = $tmp_array[0];
					$insert_array['gc_parent_id'] = $$tmp_deep;
					$insert_array['gc_name'] = $tmp_array[count($tmp_array)-1];
					$gc_id = $this->Goods_Class_Model->_add($insert_array);
					
					//赋值这个深度父ID
					$tmp = 'parent_id_'.count($tmp_array);
					$$tmp = $gc_id;
				}
				*/
				
				
				while (!feof($fp)) {
					$data = fgets($fp, 4096);
					switch (strtoupper($_POST['charset'])){
						case 'UTF-8':
							if (strtoupper(config_item('charset')) !== 'UTF-8'){
								$data = iconv('UTF-8',strtoupper(config_item('charset')),$data);
							}
							break;
						case 'GBK':
							if (strtoupper(config_item('charset')) !== 'GBK'){
								$data = iconv('GBK',strtoupper(config_item('charset')),$data);
							}
							break;
					}
					
					if (!empty($data)){
						$data	= str_replace('"','',$data);
						$
						
						//逗号去除
						$tmp_array = array();
						$tmp_array = explode(',',$data);
						
						if($tmp_array[0] == 'sort_order')continue;
						//第一位是序号，后面的是内容，最后一位名称
						
						
						$tmp_deep = 'parent_id_'.(count($tmp_array)-1);
						
						$insert_array = array();
						$insert_array['gc_sort'] = $tmp_array[0];
						$insert_array['gc_parent_id'] = $$tmp_deep;
						$insert_array['gc_name'] = $tmp_array[count($tmp_array)-1];
						$gc_id = $this->Goods_Class_Model->_add($insert_array);
						
						//赋值这个深度父ID
						$tmp = 'parent_id_'.count($tmp_array);
						$$tmp = $gc_id;
					}
				}
				
				$feedback = getSuccessTip('导入成功');
			}else {
				$feedback = getErrorTip('导入失败,请上传文件');
			}
		}

		
		$this->assign('feedback',$feedback);
		$this->display();
		
	}
	
	public function tag_reset(){
		
		//商品分类
		$goods_class = $this->goods_service->getGoodClassTree();
		
		//格式化分类。组成三维数组
		if(is_array($goods_class) and !empty($goods_class)) {
			$return = $this->Goods_Class_Tag_Model->deleteByWhere(array(
				'gc_tag_id >' => 0
			));
			
			$this->goods_service->tagAdd($goods_class);
		}
		
		redirect(admin_site_url('goods_class/tag').'?page='.$this->input->get_post('page'));
	}
	
	
	public function tag_edit(){
		
		
		$gc_tag_id = $this->input->get_post('gc_tag_id');
		
		$info = $this->Goods_Class_Tag_Model->getFirstByKey($gc_tag_id,'gc_tag_id');
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('gc_tag_name','分类TAG名称',"required");
			$this->form_validation->set_rules('gc_tag_value','分类TAG值',"required");
			
			
			for($i = 0; $i < 1; $i++){
				
				$info = array(
					'gc_tag_id' => $gc_tag_id,
					'gc_tag_name' => $this->input->post('gc_tag_name'),
					'gc_tag_value' => $this->input->post('gc_tag_value'),
				);
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Goods_Class_Tag_Model->update($info,array('gc_tag_id' => $gc_tag_id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		
		$this->display();
		
	}
	
	
		
	public function tag_update(){
		
		//需要更新的TAG列表
		$tag_list = $this->Goods_Class_Tag_Model->getList();
		
		if(is_array($tag_list) && !empty($tag_list)){
			foreach ($tag_list as $val){
				//查询分类信息
				$in_gc_id = array(0);
				
				if($val['gc_id_1'] != '0'){
					$in_gc_id[] = $val['gc_id_1'];
				}
				if($val['gc_id_2'] != '0'){
					$in_gc_id[] = $val['gc_id_2'];
				}
				if($val['gc_id_3'] != '0'){
					$in_gc_id[] = $val['gc_id_3'];
				}
				
				$gc_list = $this->Goods_Class_Model->getList(array(
					'where_in' => array(
						array('key' => 'gc_id','value' => $in_gc_id)
					)
				));
				
				//更新TAG信息
				$update_tag	= array();
				if(isset($gc_list['0']['gc_id']) && $gc_list['0']['gc_id'] != '0'){
					$update_tag['gc_id_1']		= $gc_list['0']['gc_id'];
					$update_tag['gc_tag_name']	.= $gc_list['0']['gc_name'];
				}
				if(isset($gc_list['1']['gc_id']) && $gc_list['1']['gc_id'] != '0'){
					$update_tag['gc_id_2']		= $gc_list['1']['gc_id'];
					$update_tag['gc_tag_name']	.= "&nbsp;&gt;&nbsp;".$gc_list['1']['gc_name'];
				}
				if(isset($gc_list['2']['gc_id']) && $gc_list['2']['gc_id'] != '0'){
					$update_tag['gc_id_3']		= $gc_list['2']['gc_id'];
					$update_tag['gc_tag_name']	.= "&nbsp;&gt;&nbsp;".$gc_list['2']['gc_name'];
				}
				unset($gc_list);
				
				$return = $this->Goods_Class_Tag_Model->update($update_tag,array('gc_tag_id' => $val['gc_tag_id']));
			}
		}
		
		redirect(admin_site_url('goods_class/tag').'?page='.$this->input->get_post('page'));
	}
	
	public function tag_delete(){
		
		$ids = $this->input->get_post('id');
		
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->goods_service->deleteGoodsClassTag($ids);
			$this->jsonOutput('删除成功',$this->getFormHash());
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
		
	}
	
	public function tag(){
		
		$feedback = '';
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'gc_tag_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
		);
		
		$list = $this->Goods_Class_Tag_Model->getList($condition);
		
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
}
