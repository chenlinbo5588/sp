<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Market_words extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Market_Words_Model');
	}
	
	public function index(){
		
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$condition = array(
			'order' => 'word_id DESC',
			'pager' => array(
				'page_size' => config_item('page_size'),
				'current_page' => $currentPage,
				'call_js' => 'search_page',
				'form_id' => '#formSearch'
			)
			
		);
		
		
		$search_map['search_field'] = array('word_name' => '关键词','word_url' => '推广链接');
		
		$search['search_field_name'] = $this->input->get_post('search_field_name');
		$search['search_field_value'] = $this->input->get_post('search_field_value');
		
		if(!empty($search['search_field_value']) && in_array($search['search_field_name'], array_keys($search_map['search_field']))){
			$condition['like'][$search['search_field_name']] = $search['search_field_value'];
		}
		
		$list = $this->Market_Words_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->assign('search_map',$search_map);
		$this->display();
		
		
	}
	
	
	public function delete(){
		$delId = $this->input->post('id');
		if($this->isPostRequest() && $delId){
			
			if(!is_array($delId)){
				$delId = (array)$delId;
			}
			
			$affectRows = $this->Market_Words_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'word_id' , 'value' => $delId)
				)
			
			));
			
			if($affectRows > 0){
				$this->jsonOutput('删除成功',$this->getFormHash());
			}else{
				$this->jsonOutput('删除失败',$this->getFormHash());
			}
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}
	}
	
	
	public function add(){
		
		$action = 'add';
		
		$feedback = '';
		if($this->isPostRequest()){
			$this->_getRules('add');
			
			
			for($i = 0; $i < 1; $i++){
				$info = $this->_prepareData('add');
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if(($newid = $this->Market_Words_Model->_add($info)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$this->cache->file->delete(Cache_Key_WordList);
				
				$action = 'edit';
				$feedback = getSuccessTip('保存成功');
				$info = $this->Market_Words_Model->getFirstByKey($newid,'word_id');
			}
		}
		
		
		$this->assign('action',$action);
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);

		$this->display();
	}
	
	
	
	private function _getRules($action = 'add'){
		
		$this->form_validation->set_rules('word_name','关键字','required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('word_url','推广链接','required|valid_starthttp|valid_url');
		
		
		if($this->input->post('word_sort')){
			$this->form_validation->set_rules('word_sort','排序',"is_natural|less_than[256]");
		}
		
	}
	
	
	private function _prepareData($action){
		
		$info = array(
			'word_name' => $this->input->post('word_name'),
			'word_url' => $this->input->post('word_url'),
			'word_sort' => $this->input->post('word_sort') ? $this->input->post('word_sort') : 255,
		);
		
		
		
		$url = parse_url($info['word_url']);
		$info['word_code'] = $url['fragment'] ? $url['fragment'] :'';
		$info['word_code'] = '#'.$info['word_code'];
		
		$info = array_merge($info,$this->addWhoHasOperated($action));
		
		return $info;
	}
	
	
	
	/**
	 * 信息浏览
	 */
	public function detail(){
		$word_id = $this->input->get_post('word_id');
		$info = $this->Market_Words_Model->getFirstByKey($word_id,'word_id');
		
		$this->assign('action','detail');
		$this->assign('info',$info);
		$this->display('market_words/add');
	}
	
	
	
	public function edit(){
		
		$this->assign('action','edit');
		
		$feedback = '';
		$word_id = $this->input->get_post('word_id');
		$info = $this->Market_Words_Model->getFirstByKey($word_id,'word_id');
		
		if($this->isPostRequest()){
			
			$this->_getRules('edit');
			
			
			for($i = 0; $i < 1; $i++){
				
				$info = $this->_prepareData('edit');
				$info['word_id'] = $word_id;
				
				if(!$this->form_validation->run()){
					$feedback = $this->form_validation->error_string();
					break;
				}
				
				if($this->Market_Words_Model->update($info,array('word_id' => $word_id)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$this->cache->file->delete(Cache_Key_WordList);
				
				$feedback = getSuccessTip('保存成功');
			}
		}
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		
		$this->display('market_words/add');
	}
	
	
	public function import(){
		$feedback = '';
	
		if ($this->isPostRequest()){
			//得到导入文件后缀名
			$csv_array = explode('.',$_FILES['csv']['name']);
			$file_type = end($csv_array);
			if (!empty($_FILES['csv']) && !empty($_FILES['csv']['name']) && $file_type == 'csv'){
				$fp = @fopen($_FILES['csv']['tmp_name'],'rb');
				
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
						$data	= str_replace(array('"',"\r\n","\r","\n"),'',$data);
						
						//逗号去除
						$tmp_array = array();
						$tmp_array = explode(',',$data);
						
						if($tmp_array[0] == '关键字')continue;
						
						$insert_array = array();
						$insert_array['word_name'] = $tmp_array[0];
						$insert_array['word_url'] = $tmp_array[1];
						
						
						$url = parse_url($insert_array['word_url']);
						$insert_array['word_code'] = $url['fragment'] ? $url['fragment'] :'';
						$insert_array['word_code'] = '#'.$insert_array['word_code'];
						
						$word_id = $this->Market_Words_Model->_add($insert_array);
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
	
	
	public function export(){
		
		$wordsList = $this->Market_Words_Model->getList();
		
		
		if($this->isPostRequest()){
			@header("Content-type: application/unknown");
	    	@header("Content-Disposition: attachment; filename=words.csv");
			if (is_array($wordsList)){
				
				foreach ($wordsList as $k => $v){
					$tmp = array();
					//序号
					$tmp[] = $v['word_name'];
					//名称
					$tmp[] = $v['word_url'];
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
	
}
