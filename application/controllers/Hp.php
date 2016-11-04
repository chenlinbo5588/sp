<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Hp extends MyYdzj_Controller {
	
	private $_mtime ;
	private $_maxRowPerReq;
	
	public function __construct(){
		parent::__construct();
		$this->load->library('Hp_service');
		
		$this->_mtime = array(
			'不限' => '',
			'10分钟内' => '-600 seconds',
			'20分钟内' => '-1200 seconds',
			'30分钟内' => '-1800 seconds',
			'1小时内' => '-3600 seconds',
			'2小时内' => '-7200 seconds',
			'2小时以上' => '-7200 seconds',
		);
		
		$this->_maxRowPerReq = 100;
		
		$this->assign('maxRowPerReq',$this->_maxRowPerReq);
	}
	
	
	private function _prepareParam($pageParam){
		$searchKeys['sex'] = intval($this->input->get_post('sex'));
		
		//尺码
		$searchKeys['s1'] = floatval($this->input->get_post('s1'));
		$searchKeys['s2'] = floatval($this->input->get_post('s2'));
		
		//价格范围
		$searchKeys['pr1'] = intval($this->input->get_post('pr1'));
		$searchKeys['pr2'] = intval($this->input->get_post('pr2'));
		
		$searchKeys['mtime'] = trim($this->input->get_post('mtime'));
		
		$searchCondition = array(
			'sph_select' => '@ID',
			'pager' => $pageParam,
			'order' => 'gmt_modify DESC',
			'fields' => array(
				'goods_name' => $this->input->get_post('gn'),
				'goods_code' => $this->input->get_post('gc'),
				'kw' => $this->input->get_post('kw'),
				//'kw'=> 'asdsd#42.5'
			)
		);
		
		//性别
		if($searchKeys['sex']){
			$searchCondition['fields']['sex'] = array($searchKeys['sex']);
		}
		
		if($searchKeys['s1'] || $searchKeys['s2']){
			$sizeOrderedValue = orderValue(array($searchKeys['s1'],$searchKeys['s2']));
			$searchCondition['fields']['goods_size'] = $sizeOrderedValue;
		}
		
		/*
		if($searchKeys['pr1'] || $searchKeys['pr2']){
			$prOrderedValue = orderValue(array($searchKeys['pr1'],$searchKeys['pr2']),10000);
			$searchCondition['fields']['price_max'] = $prOrderedValue;
		}
		*/
		
		if($searchKeys['mtime'] && $this->_mtime[$searchKeys['mtime']]){
			
			if('2小时以上' == $searchKeys['mtime']){
				$searchCondition['fields']['gmt_modify'] = array(
					0,
					strtotime($this->_mtime[$searchKeys['mtime']],$this->_reqtime)
				);
			}else{
				$searchCondition['fields']['gmt_modify'] = array(
					strtotime($this->_mtime[$searchKeys['mtime']],$this->_reqtime),
					$this->_reqtime
				);
			}
			
		}
		
		return $searchCondition;
		
	}
	
	private function _preparePager(){
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		
		$pageParam = array(
			'page_size' => config_item('page_size'),
			'current_page' => $currentPage,
			'call_js' => 'search_page',
			'form_id' => '#formSearch'
		);
		
		return $pageParam;
	}
	
	public function index()
	{
		$searchCondition = $this->_prepareParam($this->_preparePager());
		
		$this->hp_service->setServer(0);
		
		$results = $this->hp_service->query($searchCondition);
		
		
		//print_r($results);
		$uid = array();
		
		if($results){
			$this->assign('list',$results['data']);
			$this->assign('page',$results['pager']);
			/*
			foreach($results['data'] as $item){
				$uid[] = $item['uid'];
			}
			$userList = $this->Member_Model->getUserListByIds($uid,'uid,nickname,qq,mobile');
			$this->assign('userList',$userList);
			*/
		}
		
		
		$this->_breadCrumbs[] = array(
			'title' => '求货查询',
			'url' => $this->uri->uri_string
		);
		
		$this->assign('mtime',$this->_mtime);
		$this->display();
	}
	
	
	private function _getKW($code,$size){
		//关键，创建一个完整的词组 ，防止分词  关系到精确匹配的问题
		$code = strtolower(code_replace(trim($code)));
		
		//重要   用户后台自动匹配的字段， 货号&尺码  确定一个货品
		$kw = $code.strtolower(str_replace(array('.','#'),'',$size));
		
		return array('search_code' => $code,'kw' => $kw);
	}
	
	
	/**
	 * 发布求货
	 */
	private function _pubGoods($insertData){
		
		$mutilData = $this->_dealPub($insertData);
		
		//print_r($mutilData);
		
		$rowsAffected = $this->hp_service->addHp($mutilData['insert'],$mutilData['update'],$this->_reqtime,$this->_loginUID);
		if($rowsAffected){
			$this->input->set_cookie('repub','','');
			$this->assign('norepub',true);
			
			$feedback = getSuccessTip('求货发布成功,您发布的信息蒋在一分钟内开始生效');
			
		}else{
			$errorInfo = $this->db->get_error_info();
			$feedback = getErrorTip(str_replace(array('{code}','{message}'),array($errorInfo['code'],$errorInfo['message']),"系统错误,{code}:{message}"));
		}
		
		return $feedback;
	}
	
	
	public function add(){
		
		$initRow = array(0);
		$feedback = '';
		
		
		$postData = array();
		
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				/*
				$this->form_validation->set_rules('goods_code[]','货号','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_name[]','货名','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_color[]','颜色','required|min_length[1]|max_length[10]');
				$this->form_validation->set_rules('goods_size[]','尺码','required|is_numeric|greater_than[0]|less_than[60]');
				$this->form_validation->set_rules('quantity[]','数量','required|is_natural_no_zero|less_than[100]');
				$this->form_validation->set_rules('sex[]','性别','required|in_list[0,1]');
				$this->form_validation->set_rules('price_max[]','最高价','required|is_numeric|greater_than[0]|less_than[100000]');
				*/
				
				$this->load->config('hp');
				$validationKey = config_item('hp_validation');
				
				foreach($validationKey['hp_req'] as $value){
					$postData[$value] = $this->input->post($value,true);
					$postData[$value] = $postData[$value];
				}
				
				// 提交了多少行
				$rowCount = intval(count($postData['goods_code']));
				
				if($rowCount == 0){
					$initRow = array(0);
				}else{
					//最多行,保护机制
					if($rowCount > $this->_maxRowPerReq){
						$rowCount = $this->_maxRowPerReq;
					}
					$initRow = range(0,$rowCount - 1);
				}
				
				$remainSeconds = $this->hp_service->getPubTimeRemain($this->_reqtime,$this->_loginUID);
				if($remainSeconds){
					$feedback = getErrorTip('求货发布冻结时间内还剩'. $remainSeconds.'秒,请稍候尝试');
					break;
				}
				
				if($rowCount == 0){
					$feedback = getErrorTip('请提供求货信息');
					break;
				}
				
				//用于数据校验得数组
				$data = array();
				
				foreach($validationKey['hp_req'] as $key){
					foreach($initRow as $item){
						$dk = "{$key}{$item}";
						$data[$dk] = $postData[$key][$item];
						if($key == 'send_zone' || $key == 'send_day' || 'price_status' == $key){
							if(!empty($data[$dk])){
								$this->form_validation->set_rules($dk,$validationKey['rule_list'][$key]['title'],$validationKey['rule_list'][$key]['rules']);
							}
						}else{
							$this->form_validation->set_rules($dk,$validationKey['rule_list'][$key]['title'],$validationKey['rule_list'][$key]['rules']);
						}
					}
				}
				
				/*
				foreach($validationKey['rule_list'] as $key => $validation){
					foreach($initRow as $item){
						$dk = "{$key}{$item}";
						$data[$dk] = $postData[$key][$item];
						if($key == 'send_zone' || $key == 'send_day'){
							if(!empty($data[$dk])){
								$this->form_validation->set_rules($dk,$validation['title'],$validation['rules']);
							}
						}else{
							$this->form_validation->set_rules($dk,$validation['title'],$validation['rules']);
						}
					}
				}
				*/
				
				$this->form_validation->set_data($data);
				if(!$this->form_validation->run()){
					//$feedback = getErrorTip($this->form_validation->error_string('',''));
					$feedback = getErrorTip('数据输入格式有误,请检查录入格式');
					break;
				}
				
				$insertData = array();
				
				$date_key = date("Ymd",$this->_reqtime);
				$ip = $this->input->ip_address();
				foreach($initRow as $rowIndex){
					$rowData = array(
						'date_key' => $date_key,
						'ip' => $ip,
						'gmt_create' => $this->_reqtime,
						'gmt_modify' => $this->_reqtime,
						'uid' => $this->_loginUID,
						'username' => $this->_profile['basic']['username'],
						'qq' => $this->_profile['basic']['qq'],
						'email' => $this->_profile['basic']['email'],
						'mobile' => $this->_profile['basic']['mobile'],
					);
					
					foreach($validationKey['hp_req'] as $field){
						$postData[$field][$rowIndex] = trim($postData[$field][$rowIndex]);
						
						if($field == 'send_day'){
							if(empty($postData[$field][$rowIndex])){
								$rowData[$field] = 0;
							}else{
								$rowData[$field] = strtotime($postData[$field][$rowIndex]);
							}
						}else if($field == 'goods_size'){
							if(is_numeric($postData[$field][$rowIndex])){
								//数字
								$rowData['goods_size'] = $postData[$field][$rowIndex];
								$rowData['goods_csize'] = $rowData['goods_size'];
							}else{
								//非数字
								$rowData['goods_size'] = 0;
								$rowData['goods_csize'] = $postData[$field][$rowIndex];
							}
						}else{
							$rowData[$field] = $postData[$field][$rowIndex];
						}
					}
					
					
					//重要   用户后台自动匹配的字段， 货号&尺码  确定一个货品
					$rowData = array_merge($rowData,$this->_getKW($rowData['goods_code'],$rowData['goods_csize']));
					
					
					//保存kw 和 $insertData 数组下标的关联
					//用户可能同一个货号 同已个尺码发布两条，默认只发一条
					
					if(!isset($insertData[$rowData['kw']])){
						$insertData[$rowData['kw']] = $rowData;
					}
				}
				
				$feedback = $this->_pubGoods($insertData);
				
			}
		}else{
			
			$repubIds = $this->input->get_cookie('repub');
			$repubIdArray = array();
			
			$this->output->set_content_type('text/html');
			
			if($repubIds){
				$repubIdArray = explode('|',$repubIds);
				
				if(count($repubIdArray) > 50){
					$repubIdArray = array_slice($repubIdArray,0,49);
				}
				
				$list = $this->hp_service->getPubHistory(array(
					'where_in' => array(
						array('key' => 'goods_id' , 'value' => $repubIdArray)
					)
				),$this->_loginUID);
			
			}
			
			if($list){
				$initRow = range(0,count($list) - 1);
				$this->load->config('hp');
				
				$validationKey = config_item('hp_validation');
				foreach($list as $hpitem){
					foreach($validationKey['hp_req'] as $field){
						
						if($field == 'send_day'){
							if(!empty($hpitem[$field])){
								$postData[$field][] = date("Y-m-d",$hpitem[$field]);
							}else{
								$postData[$field][] = '';
							}
						}else{
							$postData[$field][] = $hpitem[$field];
						}
						
						
					}
				}
			}
		}
		
		
		
		
		$this->_breadCrumbs[] = array(
			'title' => '求货发布',
			'url' => $this->uri->uri_string
		);
		
		
		$this->assign('postData',$postData);
		$this->assign('initRow',$initRow);
		$this->assign('feedback',$feedback);
		$this->display();
		
	}
	
	/**
	 * 
	 */
	private function _dealPub($insertData){
		
		$searchCondition = array(
			'sph_select' => '@ID',
			'select' => 'goods_id,kw',
			'pager' => array(
				'page_size' => 200,
				'current_page' => 1,
			),
			'order' => 'gmt_modify DESC',
			'fields' => array(
				'uid' => array($this->_loginUID),
				'kw' => implode('|',array_keys($insertData))
			)
		);
		
		//进行一次查询,看有哪些 kw 是在发布状态,如果是则做额外处理
		$this->hp_service->setServer(0);
		$activeList = $this->hp_service->query($searchCondition);
		
		$updateData = array();
		
		//如果已经存在有效发布，则更新
		if($activeList){
			foreach($activeList['data'] as $activeItem){
				if($insertData[$activeItem['kw']]){
					$updateData[$activeItem['kw']] = array_merge($insertData[$activeItem['kw']],array('goods_id' => $activeItem['goods_id']));
				}
			}
			
			$keysDropInsert = array_keys($updateData);
			foreach($keysDropInsert as $dropKey ){
				unset($insertData[$dropKey]);
			}
		}
		
		return array('insert' => $insertData,'update' => $updateData);
		
	}
	
	
	
	
	private function _importStep($step){
		$this->assign('stepHTML',step_helper(array(
			'选择要上传的文件',
			'批量发布货品结果',
		),$step));
	}
	
	
	public function _doUpload(){
		
		$this->load->library('Attachment_service');
		$config = $this->attachment_service->getUploadConfig();
		$config['without_db'] = true;
		$config['allowed_types'] = 'xlsx|xls';
		$fileInfo = $this->attachment_service->addAttachment('Filedata',$config);
		
		return $fileInfo['file_url'];
		/*
		///print_r($_FILES);
		if($this->isPostRequest()){
			
			
			
			$this->session->set_userdata(array(
				'import_file' => $fileInfo['file_url'],
			));
			
			$this->jsonOutput('上传成功');
		}
		*/
	}
	
	/**
	 * 货品导入
	 */
	public function import(){
		
		$infreezen = 0;
		
		
		$remainSeconds = $this->hp_service->getPubTimeRemain($this->_reqtime,$this->_loginUID);
		if($remainSeconds){
			$infreezen = 1;
			$this->assign('leftseconds', $remainSeconds);
			$this->assign('infreezen',$infreezen);
		}
				
		
		if(0 == $infreezen && $this->isPostRequest()){
			$excelFile = $this->_doUpload();
			
			$step = 1;
			
			for($i = 0; $i < 1; $i++){
				if(empty($excelFile)){
					$feedback = $this->attachment_service->getErrorMsg('<div class="tip_error">','</div>');
					break;
				}
				
				$filePath = ROOTPATH.'/'.$excelFile;
				if(!is_file($filePath)){
					$feedback = getErrorTip('文件上传失败');
					break;
				}
				
				$step = 2;
				
				$this->load->config('hp');
				$this->load->file(PHPExcel_PATH.'PHPExcel.php');
				$keyConfig = config_item('hp_col');
				$validationConfig = config_item('hp_validation');
				
				try {
					$objPHPexcel = PHPExcel_IOFactory::load($filePath); 
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					$startRow = 0;
					$readAddOn = 2;	
					$findKeyword = false;
					$highestRow = $objWorksheet->getHighestRow();
					
					if($highestRow > $this->_maxRowPerReq){
						$highestRow = $this->_maxRowPerReq;
					}
					
					$rowsIgnored = array();
					
					$insertData = array();
					$kwList = array();
					
					$date_key = date("Ymd",$this->_reqtime);
					$ip = $this->input->ip_address();
					
					for($rowIndex = ($startRow + $readAddOn); $rowIndex <= $highestRow; $rowIndex++){
						$rowValue = array();
						$flag = false;
						$affectRow = 0;
						
						foreach($keyConfig as $config){
							$rowValue[$config['db_key']] = sbc_to_dbc(str_replace(array("\n","\r","\r\n"),"",trim($objWorksheet->getCell($config['col'].$rowIndex)->getValue())));
						}
						
						//important , do not drop this line
						if(empty($rowValue['goods_code'])){
							continue;
						}
						
						//print_r($rowValue);
						
						$this->form_validation->reset_validation();
						
						foreach($validationConfig['hp_req'] as $fieldName){
							$rowValue[$fieldName] = trim($rowValue[$fieldName]);
						
							if($fieldName == 'send_zone' || $fieldName == 'send_day' || 'price_status' == $fieldName){
								if(!empty($rowValue[$fieldName])){
									$this->form_validation->set_rules($fieldName,$validationConfig['rule_list'][$fieldName]['title'],$validationConfig['rule_list'][$fieldName]['rules']);
								}
							}else{
								$this->form_validation->set_rules($fieldName,$validationConfig['rule_list'][$fieldName]['title'],$validationConfig['rule_list'][$fieldName]['rules']);
							}
							
						}
						
						$this->form_validation->set_data($rowValue);
						$flag = $this->form_validation->run();
						
						if(!$flag){
							echo $this->form_validation->error_string();
							$rowsIgnored[] = $rowIndex;
							continue;
						}
						
						$rowValue = array_merge($rowValue, array(
							'goods_csize' => $rowValue['goods_size'],
							'date_key' => $date_key,
							'ip' => $ip,
							'gmt_create' => $this->_reqtime,
							'gmt_modify' => $this->_reqtime,
							'uid' => $this->_loginUID,
							'username' => $this->_profile['basic']['username'],
							'qq' => $this->_profile['basic']['qq'],
							'email' => $this->_profile['basic']['email'],
							'mobile' => $this->_profile['basic']['mobile'],
						));
						
						if(empty($rowValue['send_day'])){
							$rowValue['send_day'] = 0;
						}else{
							$rowValue['send_day'] = strtotime($rowValue['send_day']);
						}
						
						if(!is_numeric($rowValue['goods_size'])){
							$rowValue['goods_size'] = 0;
						}
						
						if($rowValue['sex'] == '男'){
							$rowValue['sex'] = 1;
						}else if($rowValue['sex'] == '女'){
							$rowValue['sex'] = 2;
						}else {
							$rowValue['sex'] = 1;
						}
						
						if($rowValue['price_status'] == '是'){
							$rowValue['price_status'] = 1;
						}
						
						//重要   用户后台自动匹配的字段， 货号&尺码  确定一个货品
						$rowValue = array_merge($rowValue,$this->_getKW($rowValue['goods_code'],$rowValue['goods_csize']));
						
						//保存kw 和 $insertData 数组下标的关联
						//用户可能同一个货号 同已个尺码发布两条，默认只发一条
						
						if(!isset($insertData[$rowValue['kw']])){
							$insertData[$rowValue['kw']] = $rowValue;
						}
					}
					//print_r($insertData);
					
					$feedback = $this->_pubGoods($insertData);
					
				}catch(Exception $re){
					$feedback = getErrorTip('导入错误,请检查文件格式是否正确');
				}
				
				@unlink($filePath);
			}
			
			$this->_importStep($step);
			
		}else{
			$this->_importStep(1);
		}
		
		
		$this->_breadCrumbs[] = array(
			'title' => '导入求货',
			'url' => $this->uri->uri_string
		);
		
		$this->assign('feedback',$feedback);
		$this->display();
			
	}
	
}
