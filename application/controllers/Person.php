<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Person extends MyYdzj_Controller {
	
	private $_maxRowPerReq = 0;
	private $_mapConfig ;
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Building_service','Attachment_service'));
		$this->attachment_service->setUserInfo($this->_profile['basic']);
		
		$this->load->config('cljz_config');
		$this->load->config('arcgis_server');
		
		$this->_maxRowPerReq = config_item('import_per_limit');
		
		$this->assign('id_type',config_item('id_type'));
		$this->assign('sex_type',config_item('sex_type'));
		
		
		$mapGroup = config_item('mapGroup');
		$mapUrlConfig = config_item($mapGroup);
		
		$this->_mapConfig = $mapUrlConfig;
		
		
		$this->load->library('arcgis/FeatureRest');
		$this->load->config('arcgis_server');
	}
	
	
	//@todo 自动加入所在村条件
	private function _prepareCondition(){
		
		$currentPage = $this->input->get_post('page') ? $this->input->get_post('page') : 1;
		$pageParam = array(
			'page_size' => config_item('page_size'),
			'current_page' => $currentPage,
			'call_js' => 'search_page',
			'form_id' => '#formSearch'
		);
		
		$searchCondition = array(
			'pager' => $pageParam,
			'order' => 'id DESC',
		);
		
		return $searchCondition;
	}
	
	public function index()
	{
		$condition = $this->_prepareCondition();
		
		$where = array();
		
		$where['qlrName'] = $this->input->get_post('qlr_name');
		$where['idNo'] = $this->input->get_post('id_no');
		$where['sex'] = $this->input->get_post('sex');
		$where['yhdz'] = $this->input->get_post('yhdz');
		
		
		$condition['where'] = array(
			'village_id' => $this->_profile['basic']['village_id']
		);
		
		
		if($where['qlrName']){
			$condition['like']['qlr_name'] = $where['qlrName'];
		}
		
		if($where['idNo']){
			$condition['like']['id_no'] = $where['idNo'];
		}
		
		if($where['sex']){
			$condition['where']['sex'] = $where['sex'];
		}
		
		if($where['yhdz']){
			$condition['where']['yhdz'] = 1;
		}
		
		
		$results = $this->Person_Model->getList($condition);
		
		$this->assign('list',$results['data']);
		$this->assign('page',$results['pager']);
		
		$this->_breadCrumbs[] = array(
			'title' => '权利人管理',
			'url' => $this->uri->uri_string
		);
		
		$this->display();
	}
	
	
	/**
	 * 增滘校验规则
	 */
	private function _setValidationRules($validationConfig,$rowValue){
		foreach($validationConfig['person_import'] as $fieldName => $fieldValue){
			if($fieldValue['required']){
				$this->form_validation->set_rules($fieldName,$validationConfig['rule_list'][$fieldName]['title'],$validationConfig['rule_list'][$fieldName]['rules']);
			}else{
				
				if($fieldValue['condition']){
					if($fieldValue['whenSelfNotEmpty']){
						if($rowValue[$fieldName]){
							$this->form_validation->set_rules($fieldName,$validationConfig['rule_list'][$fieldName]['title'],$validationConfig['rule_list'][$fieldName]['rules']);
						}
					}else{
						$matchCount = 0;
						$mustMatchCount = count($fieldValue['whenFields']);
						foreach($fieldValue['whenFields'] as $fv){
							if($rowValue[$fv['key']] == $fv['value']){
								$matchCount += 1;
							}
						}
						
						if($matchCount == $mustMatchCount){
							$this->form_validation->set_rules($fieldName,$validationConfig['rule_list'][$fieldName]['title'],$validationConfig['rule_list'][$fieldName]['rules']);
						}
					}
				}
			}
		}
		
		
	}
	
	
	
	public function add(){
		
		$feedback = '';
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$postData = $this->_addRules();
				
				$this->form_validation->set_rules('id_no','证件号码',array(
						'required',
						'min_length[1]',
						'max_length[20]',
						array(
							'idno_callable[id_no]',
							array(
								$this->Person_Model,'isUnqiueByKey'
							)
						)
					),
					array(
						'idno_callable' => '%s已经存在'
					)
				);
		
		
				
				$this->form_validation->set_data($postData);
				$flag = $this->form_validation->run();
				
				if(!$flag){
					$info = $_POST;
					$feedback = getErrorTip('无法通过数据校验');
					break;
				}
				
				$postData['village_id'] = $this->_profile['basic']['village_id'];
				$postData['village'] = $this->_profile['basic']['village'];
				$postData['family_num'] = intval($postData['family_num']) == 0 ? 1 : intval($postData['family_num']) ;
				$postData['id_type'] = $postData['id_type'] == '居民身份证' ? 1 : 2;
				
				$insertId = $this->Person_Model->_add(array_merge($postData,$this->addWhoHasOperated('add')));
				
				if(!$insertId){
					$feedback = getErrorTip('数据库错误，请联系管理员');
					break;
				}
				
				$info = $_POST;
				$info['id'] = $insertId;
				
				$feedback = getSuccessTip('保存成功');
			}
		}else{
			$info = array(
				'sex' => 1,
				'id_type' => 1
			);
		}
		$this->_breadCrumbs[] = array(
			'title' => '添加权力人',
			'url' => $this->uri->uri_string
		);
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display();
		
	}
	
	
	private function _addRules(){
		
		$postData = array();
		
		
		$validationConfig = config_item('person_validation');
		$this->form_validation->reset_validation();
		foreach($validationConfig['person_import'] as $fieldName => $fieldValue){
			$postData[$fieldName] = $this->input->post($fieldName);
			$postData[$fieldName] = trim($postData[$fieldName]);
		}
				
		$this->_setValidationRules($validationConfig,$postData);
		
				
		return $postData;
	}
	
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				$postData = $this->_addRules();
				$this->form_validation->set_rules('id_no','证件号码','required|min_length[1]|max_length[20]|is_unique_not_self['.$this->Person_Model->getTableRealName().".id_no.id.{$id}]");
				
				$this->form_validation->set_data($postData);
				$flag = $this->form_validation->run();
				
				if(!$flag){
					$info = $_POST;
					$feedback = getErrorTip('无法通过数据校验');
					break;
				}
				
				$postData['family_num'] = intval($postData['family_num']) == 0 ? 1 : intval($postData['family_num']) ;
				$postData['id_type'] = $postData['id_type'] == '居民身份证' ? 1 : 2;
				
				$affectRow = $this->Person_Model->update(array_merge($postData,$this->addWhoHasOperated('edit')),array('id' => $id));
				
				if($affectRow < 0){
					$feedback = getErrorTip('数据库错误，请联系管理员');
					break;
				}
				
				$info = $_POST;
				$feedback = getSuccessTip('保存成功');
			}
		}else{
			
			$info = $this->Person_Model->getFirstByKey($id);
			
		}
		$this->_breadCrumbs[] = array(
			'title' => '添加权利人',
			'url' => $this->uri->uri_string
		);
		
		$this->assign('info',$info);
		$this->assign('feedback',$feedback);
		$this->display('person/add');
		
	}
	
	/**
	 * 删除人员
	 */
	public function delete(){
		
		$personList = $this->input->post('id');
		
		if($personList && $this->isPostRequest()){
			$this->building_service->setArcgisUrl(config_item('arcgis_server'),$this->_mapConfig['编辑要素']['标准建筑点']);
			
			$personId = $personList[0];
			$returnVal = $this->building_service->deletePersonById($personId);
			
			if($returnVal > 0){
				$this->jsonOutput('删除成功');
			}else{
				$this->jsonOutput('删除出错');
			}
			
		}else{
			$this->jsonOutput('参数错误');
		}
		
		
		
	}
	
	
	private function _importStep($step){
		$this->assign('stepHTML',step_helper(array(
			'选择要上传的文件',
			'批量处理结果',
		),$step));
	}
	
	
	public function _doUpload(){
		
		$this->load->library('Attachment_service');
		$config = $this->attachment_service->getUploadConfig();
		$config['without_db'] = true;
		$config['allowed_types'] = 'xlsx|xls';
		$fileInfo = $this->attachment_service->addAttachment('Filedata',$config);
		
		return $fileInfo['file_url'];
	}
	
	
	/**
	 * 输出
	 */
	private function _makeTableLines($pLines,$tag = 'td'){
		$output = array();
		foreach($pLines as $line){
			$trLine = array(
				'<tr>'
			);
			foreach($line as $lk => $lv){
				$trLine[] = "<{$tag}>{$lv}</$tag>";
			}
			
			$trLine[] = '</tr>';
			$output[] = implode('',$trLine);
		}
		
		return implode('',$output);
	}
	
	
	/**
	 * 货品导入
	 */
	public function import(){
		
		$step = 1;
		
		
		if($this->isPostRequest()){
			$excelFile = $this->_doUpload();
			
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
				
				$this->load->file(PHPExcel_PATH.'PHPExcel.php');
				$keyConfig = config_item('import_excel_column');
				$validationConfig = config_item('person_validation');
				
				
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
					
					
					$ip = $this->input->ip_address();
					$outputLine = array();
					
					for($rowIndex = ($startRow + $readAddOn); $rowIndex <= $highestRow; $rowIndex++){
						$rowValue = array();
						$flag = false;
						$affectRow = 0;
						
						$rowValue['lineCnt'] = $rowIndex;
						
						foreach($keyConfig as $config){
							$rowValue[$config['db_key']] = sbc_to_dbc(str_replace(array("\n","\r","\r\n"),"",trim($objWorksheet->getCell($config['col'].$rowIndex)->getValue())));
						}
						
						$this->form_validation->reset_validation();
						
						//先获得当前行的值
						foreach($validationConfig['person_import'] as $fieldName => $fieldValue){
							$rowValue[$fieldName] = trim($rowValue[$fieldName]);
						}
						
						$this->_setValidationRules($validationConfig,$rowValue);
						
						
						$this->form_validation->set_data($rowValue);
						$flag = $this->form_validation->run();
						
						if(!$flag){
							$rowValue['errorstring'] =  $this->form_validation->error_string();
							$outputLine[] = $rowValue;
							continue;
						}
						
						
						
						if($rowValue['sex'] == '男'){
							$rowValue['sex'] = 1;
						}else if($rowValue['sex'] == '女'){
							$rowValue['sex'] = 2;
						}else {
							if($rowValue['id_type'] == '居民身份证' && $rowValue['id_no']){
								$rowValue['sex'] = substr($rowValue['id_no'],-2,1) % 2 == 0 ? 2 : 1;
							}else{
								$rowValue['sex'] = 1;
							}
						}
						
						$rowValue['address'] = empty($rowValue['address']) ? '' : $rowValue['address'];
						$rowValue['family_num'] = intval($rowValue['family_num']);
						//$outputLine[] = $rowValue;
						
						$info = $this->Person_Model->getFirstByKey($rowValue['id_no'],'id_no','id');
						$rowValue['id_type'] = $rowValue['id_type'] == '居民身份证' ? 1 : 2;
						$rowValue['village'] = $this->_profile['basic']['village'];
						$rowValue['village_id'] = $this->_profile['basic']['village_id'];
						$rowValue = array_merge($rowValue, array(
							'town' => config_item('site_town'),
							'ip' => $ip
						));
						
						if($info){
							//do update,为了更新数据源安全性 只更新自己村，防止导入时身份证匹配自动更新其他村的
							$affectRow = $this->Person_Model->update(array_merge($rowValue,$this->addWhoHasOperated('edit')),array('id_no' => $rowValue['id_no'],'village_id' => $this->_profile['basic']['village_id']));
						}else{
							//do insert
							$affectRow = $this->Person_Model->_add(array_merge($rowValue,$this->addWhoHasOperated('add')));
						}
						
					}
					
					
					$theadLines = array();
					$aline[] = '行号';
					
					foreach($validationConfig['person_import'] as $fieldName => $fieldValue){
						$aline[] = $validationConfig['rule_list'][$fieldName]['title'];
					}
					
					$aline[] = '错误详情';
					
					$theadLines[] = $aline;
					$this->assign('titleLines',$this->_makeTableLines($theadLines,'th'));
					$this->assign('resultLines',$this->_makeTableLines($outputLine));
					
					$feedback = getSuccessTip('导出操作完成');
					
				}catch(Exception $re){
					$feedback = getErrorTip('导入错误,请检查文件格式是否正确');
				}
				
				@unlink($filePath);
			}
		}
		
		$this->_breadCrumbs[] = array(
			'title' => '导入权力人资料',
			'url' => $this->uri->uri_string
		);
		
		$this->_importStep($step);
		
		$this->assign('feedback',$feedback);
		$this->display();
	}
	
}
