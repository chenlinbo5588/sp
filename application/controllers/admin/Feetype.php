<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Feetype extends Ydzj_Admin_Controller {
	
	private $_moduleTitle;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Wuye_service','Basic_data_service'));
		
		
		$this->wuye_service->setDataModule($this->_dataModule);
		
		
		$this->_moduleTitle = '費用类型';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'wuyeType' => $this->basic_data_service->getTopChildList('物业类型')
		));
		
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加'),
			//array('url' => $this->_className.'/import','title' => '导入'),
			//array('url' => $this->_className.'/export','title' => '导出'),
		);
		
	}
	
	
	/**
	 * 
	 */
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
		
		
		$search['name'] = $this->input->get_post('name');
		$search['resident_name'] = $this->input->get_post('resident_name');
		$search['year'] = $this->input->get_post('year');
	
		if($search['name']){
			$condition['like']['name'] = $search['name'];
		}
		
		if($search['resident_name']){
			$condition['like']['resident_name'] = $search['resident_name'];
		}
		
		if($search['year']){
			$condition['where']['year'] = $search['year'];
		}
		
		
		$list = $this->wuye_service->search( $this->_moduleTitle, $condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		
		
		$this->display();
		
	}
	
	
	/**
	 * 
	 */
	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Feetype_Model->deleteByCondition(array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功');
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 
	 */
	private function _prepareData(){
		
		$data['displayorder'] = $this->input->post('displayorder');
		
		return array(
			'displayorder' => $data['displayorder'] ? $data['displayorder'] : 255
		);
	}
	
	
	/**
	 * 
	 */
	private function _commonPageData(){
		$basicData = $this->basic_data_service->getAssocBasicDataTree();
		
		$this->assign(array(
			'residentList' => $this->wuye_service->search('小区',array(
    				'select' => 'id,name',
					'order' => 'displayorder DESC'
				),'id'),
			'feeNameList' => $this->basic_data_service->getTopChildList('费用类型'),
			'billingStyleList' => $this->basic_data_service->getTopChildList('计费方式'),
			'wuyeTypeList' => $this->basic_data_service->getTopChildList('物业类型'),
			'parkingTypeList' => $this->basic_data_service->getTopChildList('车位类型'),
		));
	}
	
	
	/**
	 * 添加费用类型验证规则
	 */
	private function _addFeetypeRules(){
		
		$feeNameList = $this->basic_data_service->getTopChildList('费用类型');
		
		$billingStyleList = $this->basic_data_service->getTopChildList('计费方式');
		$wuyeTypeList = $this->basic_data_service->getTopChildList('物业类型');
		$parkingTypeList = $this->basic_data_service->getTopChildList('车位类型');
		$wuyeTypeList = array_merge($wuyeTypeList,$parkingTypeList); 
		$this->form_validation->set_rules('name','费用名称','required|in_list[物业费,能耗费]');
		
		$this->form_validation->set_rules('feeName[]','费用类型','required|in_list['.implode(',',array_keys($feeNameList)).']');
		$this->form_validation->set_rules('year','缴费年份','required|is_natural_no_zero|greater_than_equal_to[2017]|less_than[2099]');
		
		$this->form_validation->set_rules('price[]','单价','required|is_numeric');
		
		$this->form_validation->set_rules('billingStyle[]','计费方式','required|in_list['.implode(',',array_keys($billingStyleList)).']');
		$this->form_validation->set_rules('wuyeType[]','物业类型','required|in_list['.implode(',',array_keys($wuyeTypeList)).']');
		
	}
	

	/**
	 * 
	 */
	public function add(){
		$feedback = '';

		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				
				$residentList = $this->wuye_service->search('小区',array(),'id');
				$this->form_validation->set_rules('resident_id','小区名称','required|in_list['.implode(',',array_keys($residentList)).']');
 				
				$this->_addFeeTypeRules();

				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				if($this->_validaterules($_POST)){
					break;
				}
				$feeRule = $this->wuye_service->combinationFeeRule($_POST);
				
				$info = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add'));
				$info['resident_name'] = $residentList[$info['resident_id']]['name'];
				$info['fee_rule'] = json_encode($feeRule);;
				
				$newid =$this->Feetype_Model->_add($info);
				
				$error = $this->Feetype_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput('当前费用已添加');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			
			$this->_commonPageData();
			$this->display();
		}
		
	}
	
	
	
	
	/**
	 * 编辑页面
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		$info = $this->Feetype_Model->getFirstByKey($id);
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		$feeRule = (json_decode($info['fee_rule'],true));
		if($this->isPostRequest()){
			
			$residentList = $this->wuye_service->search('小区',array(),'id');
			$this->form_validation->set_rules('resident_id','小区名称','required|in_list['.implode(',',array_keys($residentList)).']');
				
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				$this->_addFeeTypeRules();
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}

				if($this->_validaterules($_POST)){
					break;
				}
				$feeRule = $this->wuye_service->combinationFeeRule($_POST);
				$info = array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add'));
				$info['resident_name'] = $residentList[$info['resident_id']]['name'];
				$info['fee_rule'] = json_encode($feeRule);;
				$this->Feetype_Model->update($info,array('id' => $id));
				
				$error = $this->Feetype_Model->getError();
				
				if(QUERY_OK != $error['code']){
					if($error['code'] == MySQL_Duplicate_CODE){
						$this->jsonOutput('当前费用已存在');
					}else{
						$this->jsonOutput('数据库错误,请稍后再次尝试');
					}
					
					break;
				}
				
				$this->jsonOutput('保存成功');
			}
		}else{
			
			
			$this->assign('info',$info);
			$this->assign('fee_rule',$feeRule);
			$this->_commonPageData();
			$this->display($this->_className.'/add');
			
		}
		
	}
	private function _validaterules($validaterules){
		
		$nameList = array();
		foreach($validaterules['feeName'] as $key => $value){
			$onlyName = $validaterules['feeName'][$key].$validaterules['wuyeType'][$key];

			if(in_array($onlyName,$nameList)){
				$judgement = true;
				$this->jsonOutput('同一个类型不能重复设置');
				break;
			}else{
				$nameList[] = $onlyName;
			}
		}
		if('能耗费' == $_POST['name'] ){
			$this->jsonOutput('能耗费不能生成明细');
			$judgement = true;
		}
		return $judgement;
	}
	
	
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			switch($fieldName){
				
				case 'price';
					$this->form_validation->set_rules('price','单价','required|is_numeric');	
					break;
				case 'displayorder';
					$this->form_validation->set_rules('displayorder','排序',"required|is_natural|less_than[256]");
					break;
				default:
					break;
			}
			
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				if($this->Feetype_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
	}
	
	
	/**
	 * 导入输出
	 */
	private function _importOutput($result){
		
		$str = array();
		foreach($result as $key => $line){
			$str[] = "<tr class=\"{$line['classname']}\"><td>{$line['name']}</td><td>{$line['year']}</td><td>{$line['price']}</td><td>{$line['message']}</td></tr>";
		}
		
		return implode('',$str);
	}
	
	
	/**
     * 导入excel
     */
    public function import(){
    	$feedback = '';
    	$messageType = 'error';
    	
    	
    	$this->form_validation->set_error_delimiters('','');
    	if($this->isPostRequest()){
       		
    		for($i = 0; $i < 1; $i++){
    			
    			$this->form_validation->set_data(array(
	    			'resident_id' => $this->input->post('resident_id')
	    		));
	    		
	    		$this->form_validation->set_rules('resident_id','所在小区','required|is_natural_no_zero');
	    		
	    		if(!$this->form_validation->run()){
	    			$feedback = getErrorTip($this->form_validation->error_html());
	    			break;
	    		}
	    		
	    		//选中的小区ID
				$residentId = $this->input->post('resident_id');
				
				$tempInfoList = $this->wuye_service->search('小区',array(
					'where' => array(
						'id' => $residentId
					)
				));
				
				if(empty($tempInfoList[0])){
					$feedback = getErrorTip('找不到该小区信息');
	    			break;
				}
				
				$residentInfo = $tempInfoList[0];
				
    			if(0 != $_FILES['excelFile']['error']){
    				$feedback = getErrorTip('请上传文件');
	    			break;
	    		}
	    		
	    		$this->_initPHPExcel();
		        
	    		try {
	    			
	    			$excelFile = $_FILES['excelFile']['tmp_name'];
	    			$objPHPexcel = PHPExcel_IOFactory::load($excelFile);
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					
					$startRow = 2;
					$highestRow = $objWorksheet->getHighestRow();
					
					$importMaxLimit = config_item('excel_import_limit');
					
					if(($highestRow + 1) > $importMaxLimit){
						$highestRow = $importMaxLimit + 1;
					}
					
					$result = array();
					$successCnt = 0;
				
					$currentYear = date('Y');
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['classname'] = 'failed';
						
						$tmpRow['name'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['year'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						
						$tmpRow['resident_name'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						
						$tmpRow['fee_rule'][0]['feeName'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['fee_rule'][0]['price'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['fee_rule'][0]['wuyeType'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['fee_rule'][0]['billingStyle'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['fee_rule'][0]['cale'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						
						$this->form_validation->reset_validation();
						$this->form_validation->set_data($tmpRow);
						
						$this->form_validation->set_rules('resident_name','小区名称','required|in_list['.$residentInfo['name'].']',array(
							'in_list' => '小区名称必须为'.$residentInfo['name']
						));
					
						$this->_addFeeTypeRules();
						
						
						if(!$this->form_validation->run()){
							$tmpRow['message'] = $this->form_validation->error_first_html();
							$result[] = $tmpRow;
							continue;
						}
						
						$insertData = array_merge(array(
							'name' => $tmpRow['name'],
							'year' => $tmpRow['year'],
							'resident_id' => $residentId,
							'wuye_type' => $tmpRow['wuye_type'],
							'resident_name' => $tmpRow['resident_name'],
							'price' => $tmpRow['price'],
							'billing_style' => $tmpRow['billing_style'],
							
						),$this->addWhoHasOperated('add'));
						
						//print_r($residentInfo);
						//print_r($insertData);
						$this->Feetype_Model->_add($insertData);
						
						$error = $this->Feetype_Model->getError();
						
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = '费用类型已经存在';
							}
						}else{
							$tmpRow['message'] = '导入成功';
							$tmpRow['classname'] = 'ok';
							$successCnt++;
						}
						
						$result[] = $tmpRow;
					}
								
					$feedback = getSuccessTip('导入完成,导入'.$successCnt.'条,失败'.(count($result) - $successCnt).'条');
					
					$this->assign(array(
						'output' => '<table class="table">'.$this->_importOutput($result).'</table>',
						'successCnt' => $successCnt,
					));
					
	    			@unlink($excelFile);
	    		}catch(Exception $e){
	    			$feedback = '导入错误,请检查文件格式是否正确';
	    		}
    		}
    		
    		$this->assign(array(
    			'feedback' => $feedback,
    		));
    		
    		$this->display('common/import_resp');
    		
    	}else{
    		
    		$this->assign(array(
	    		'residentList' => $this->wuye_service->search('小区',array(
    				'select' => 'id,name',
					'order' => 'displayorder DESC'
				),'id')
	    	));
	    	
    		$this->display();
    	}
    	
    }
    /**
     * 费用管理数据导出
     */
    public function export(){
    	
    	$message = '';
    	
    	if($this->isPostRequest()){
    		
    		try {
    			
    			$search = $this->input->post(array('type_name','resident_name','page'));
    			
    			$condition = array(
    				'order' => 'year DESC'
    			);
    			
    			if($search['type_name']){
    				$condition['where']['name'] = $search['type_name'];
    			}
    			
    			if($search['resident_name']){
    				$condition['where']['resident_name'] = $search['resident_name'];
    			}
    			 			
    			$search['page'] = intval($search['page']) == 0 ? 1 : intval($search['page']);
    			
    			$dataCnt = $this->wuye_service->getRecordCount($this->_moduleTitle,$condition);
    			
    			$perPageSize = config_item('excel_export_limit');
    			
    			if($dataCnt > $perPageSize){
    				$condition['pager'] = array(
						'page_size' => $perPageSize,
						'current_page' => $search['page'],
						'form_id' => '#formSearch'
	    			);
    			}
    			
    			$this->_doExport($condition);
    		}catch(Exception $e){
    			//出错信息
    			$message = $e->getMessage();
    		}
    		
    	}else{
    		
    		$this->display();
    	}
    	
    }
    
    /**
     * 导出数据列
     */
    private function _getExportConfig(){
    	return array(
    		'A' => array('db_key' => 'name','width' => 15 ,'title' => '费用类型'),
    		'B' => array('db_key' => 'year','width' => 12 ,'title' => '缴费年份'),
    		'C' => array('db_key' => 'resident_name','width' => 25 ,'title' => '小区名称'),
    		'D' => array('db_key' => 'price','width' => 15 ,'title' => '单价'),
    		'E' => array('db_key' => 'wuye_type','width' => 20 ,'title' => '物业类型'),
    		'F' => array('db_key' => 'billing_style','width' => 20 ,'title' => '计费方式'),
    	);
	
    }
    
    /**
     * 执行导出动作
     */
    private function _doExport($condition = array()){
    	
    	$this->_initPHPExcel();
    	
        $objPHPExcel = new PHPExcel();
        
        $data = $this->wuye_service->search($this->_moduleTitle,$condition);
    	
    	$colConfig = $this->_getExportConfig();
    	
    	foreach($colConfig as $colKey => $colItemConfig){
    		$objPHPExcel->getActiveSheet()->getCell($colKey.'1')->setValueExplicit($colItemConfig['title'], PHPExcel_Cell_DataType::TYPE_STRING2);
    		$objPHPExcel->getActiveSheet()->getColumnDimension($colKey)->setWidth($colItemConfig['width']);
    	}
    	
    	$colKeys = array_keys($colConfig);
    	
    	$objPHPExcel->getActiveSheet()->getStyle($colKeys[0].'1:'.$colKeys[count($colKeys) - 1].'1')->applyFromArray(
    		array(
                'font'    => array(
                    'bold'      => true,
                    'size'     => 12
                ),

                'fill' => array(
                    'type'       => PHPExcel_Style_Fill::FILL_PATTERN_LIGHTGRAY,
                    'startcolor' => array(
                        'argb' => 'FFC0C0C0'
                    ),
                    'endcolor'   => array(
                        'argb' => 'FFC0C0C0'
                    )
                )
             )
	    );
        
        if($condition['pager']){
        	$list = $data['data'];
        	$objPHPExcel->getActiveSheet()->setTitle('第'.$data['pager']['pageNow'].'页之共'.$data['pager']['pageLastNum'].'页');
        }else{
        	$list = $data;
        }

    	foreach($list as $rowId => $yezhu){
    		foreach($colConfig as $colKey => $colItemConfig){
    			
    			$val = $yezhu[$colItemConfig['db_key']];
    			$objPHPExcel->getActiveSheet()->getCell($colKey.($rowId + 2))->setValueExplicit($val, PHPExcel_Cell_DataType::TYPE_STRING2);
    		}
    	}

    	$objPHPExcel->getActiveSheet()->getStyle($colKeys[0].'1:'.$colKeys[count($colKeys) - 1].(count($list) + 1))->applyFromArray(
            array(
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => 'FF000000')
                    )
                )
            )
        );
        
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $downloadName = $this->_moduleTitle.'.xlsx';
        $fileRealName = md5(uniqid());
        
        $filePath = ROOTPATH.'/temp/'.$fileRealName.'.xlsx';
        
        $objWriter->save($filePath);
        $objPHPExcel->disconnectWorksheets(); 
        
        unset($objPHPExcel,$objWriter);
        
        force_download($downloadName,  file_get_contents($filePath));
        
    }
}
