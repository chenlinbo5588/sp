<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Inventory extends MyYdzj_Controller {
	
	private $_isExpired ;
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('Inventory_service');
		$this->assign('reqtime',$this->_reqtime);
		
		$this->_isExpired = array(
			'0' => '不限',
			'1' => '已过期',
			'2' => '未过期',
		);
		
		
		$this->assign('isExpired',$this->_isExpired);
		
		$this->_checkHasVerify();
	}
	
	
	private function _checkHasVerify(){
		//认证卖家
		if(2 == $this->_profile['basic']['group_id']){
			$this->load->library('Member_service');
			$groupId = $this->member_service->getUserGroupId($this->_loginUID);
			
			if(3 == $groupId){
				$this->_profile['basic']['group_id'] = $groupId;
				$this->refreshProfile();
				$this->getCacheObject()->delete($this->member_service->getUserGroupKey($this->_loginUID));
			}
		}
	}
	
	
	
	/**
	 * 准备查询参数
	 */
	private function _prepareParam($pageParam){
		
		//$searchKeys['gn'] = $this->input->get_post('gn');
		//$searchKeys['gc'] = $this->input->get_post('gc');
		
		
		$searchKeys['sex'] = intval($this->input->get_post('sex'));
		
		//尺码
		$searchKeys['s1'] = floatval($this->input->get_post('s1'));
		$searchKeys['s2'] = floatval($this->input->get_post('s2'));
		
		//价格范围
		$searchKeys['pr1'] = intval($this->input->get_post('pr1'));
		$searchKeys['pr2'] = intval($this->input->get_post('pr2'));
		
		//是否已经过期
		$searchKeys['isexpired'] = intval($this->input->get_post('isexpired'));
		
		
		//发布日期
		$searchKeys['sdate'] = $this->input->get_post('sdate');
		$searchKeys['edate'] = $this->input->get_post('edate');
		
		if($searchKeys['sdate']){
			$searchKeys['sdate'] = strtotime($searchKeys['sdate']);
		}else{
			$searchKeys['sdate'] = 0;
		}
		
		if($searchKeys['edate']){
			$searchKeys['edate'] = strtotime($searchKeys['edate']);
			$searchKeys['edate'] = strtotime("+1 day",$searchKeys['edate']);
		}else{
			$searchKeys['edate'] = 0;
		}
		
		//print_r($searchKeys);
		$searchCondition = array(
			'pager' => $pageParam,
			'order' => 'gmt_modify DESC',
			'fields' => array(
				'goods_name' => $this->input->get_post('gn'),
				'goods_code' => $this->input->get_post('gc'),
				'uid' => array($this->_loginUID)
			)
		);
		
		//性别
		if($searchKeys['sex']){
			$searchCondition['fields']['sex'] = array($searchKeys['sex']);
			//$client->SetFilter ('sex', array($searchKeys['sex']));
		}
		
		if($searchKeys['s1'] || $searchKeys['s2']){
			$sizeOrderedValue = orderValue(array($searchKeys['s1'],$searchKeys['s2']));
			$searchCondition['fields']['goods_size'] = $sizeOrderedValue;
		}
		
		if($searchKeys['pr1'] || $searchKeys['pr2']){
			$prOrderedValue = orderValue(array($searchKeys['pr1'],$searchKeys['pr2']),10000);
			$searchCondition['fields']['price_max'] = $prOrderedValue;
		}
		
		if($searchKeys['sdate'] || $searchKeys['edate']){
			$dateValue = orderValue(array($searchKeys['sdate'],$searchKeys['sdate']),$this->_reqtime);
			$searchCondition['fields']['gmt_create'] = $dateValue;
			
		}
		
		
		if($searchKeys['isexpired']){
			if($searchKeys['isexpired'] == 1){
				$searchCondition['fields']['gmt_modify'] = array(
					0,
					$this->_reqtime - config_item('hp_expired')
				);
			
			}else if($searchKeys['isexpired'] == 2){
				$searchCondition['fields']['gmt_modify'] = array(
					$this->_reqtime - config_item('hp_expired'),
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
	
	/**
	 * 库存 货柜列表
	 */
	public function index()
	{
		$userInventory = $this->inventory_service->getUserCurrentInventory($this->_loginUID);
		
		if(empty($userInventory) && 3 == $this->_profile['basic']['group_id']){
			//已认证了则初始化用户库存
			$this->inventory_service->initUserInventory($this->_loginUID);
		}
		
		if($userInventory['hp_cnt'] == 0){
			$this->_importStep(1);
			
			$this->_breadCrumbs[] = array(
				'title' => '库存导入',
				'url' => $this->uri->uri_string
			);
			
		}else{
			$this->_breadCrumbs[] = array(
				'title' => '我的库存',
				'url' => $this->uri->uri_string
			);
		
		
			$pageParam = $this->_preparePager();
			$pager = pageArrayGenerator($pageParam,$userInventory['hp_cnt']);
			$startIndex = ($pager['pager']['current_page'] - 1) * $pager['pager']['page_size'];
			$endIndex = $pager['pager']['page_size'];
			
			
			$list = array_slice($userInventory['goods_list'],$startIndex,$endIndex);
			//print_r($list);
			$this->assign('list',$list);
			$this->assign('page',$pager['pager']);
			$this->assign('last_update',$userInventory['gmt_modify']);
		}
		
		$this->assign('currentHpCnt',$userInventory['hp_cnt']);
		$this->assign('currentGroupId',$this->_profile['basic']['group_id']);
		
		$this->display();
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
	
	
	private function _getExcelColumnConfig(){
		return array(
    		array(
    			'col' => 'A',
    			'name' => '货号',
    			'width' => 20,
    			'db_key' => 'goods_code'
    		),
    		array(
    			'col' => 'B',
    			'name' => '货名',
    			'width' => 20,
    			'db_key' => 'goods_name'
    		),
    		array(
    			'col' => 'C',
    			'name' => '尺寸',
    			'width' => 10,
    			'db_key' => 'goods_size'
    		),
    		array(
    			'col' => 'D',
    			'name' => '颜色',
    			'width' => 30,
    			'db_key' => 'goods_color'
    		),
    		array(
    			'col' => 'E',
    			'name' => '性别',
    			'width' => 8,
    			'db_key' => 'sex'
    		),
    		array(
    			'col' => 'F',
    			'name' => '库存数量',
    			'width' => 10,
    			'db_key' => 'quantity'
    		),
    		array(
    			'col' => 'G',
    			'name' => '可接受最低价',
    			'width' => 10,
    			'db_key' => 'price_min'
    		)
    	);
		
	}    
	
	
	/**
	 * 货品导入
	 */
	public function import(){
		
		$infreezen = 0;
		
		$lastUpdate = $this->inventory_service->getLastUpdate($this->_loginUID);
		$freezen = config_item('inventory_freezen');
		
		if($lastUpdate && ($this->_reqtime - $lastUpdate ) < $freezen){
			$infreezen = 1;
			$this->assign('leftseconds', $freezen  - ($this->_reqtime - $lastUpdate ));
			$this->assign('infreezen',$infreezen);
		}
		
		$this->_breadCrumbs[] = array(
			'title' => '我的库存',
			'url' => 'inventory/index'
		);
		$this->_breadCrumbs[] = array(
			'title' => '库存导入',
			'url' => $this->uri->uri_string
		);
		
		
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
				
				
				$this->load->file(PHPExcel_PATH.'PHPExcel.php');
				//$filePath = ROOTPATH.'/test.xlsx';
				$keyConfig = $this->_getExcelColumnConfig();
				
				$this->load->config('hp');
				$validationConfig = config_item('hp_validation');
				$slots = $this->inventory_service->getUserAllInventory($this->_loginUID);
				
				try {
					$objPHPexcel = PHPExcel_IOFactory::load($filePath); 
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					$startRow = 0;
					$readAddOn = 2;	
					$findKeyword = false;
					$highestRow = $objWorksheet->getHighestRow();
					
					if($highestRow > 1000){
						$highestRow = 1000;
					}
					
					$rowsIgnored = array();
					
					$goodsList = array();
					$kwList = array();
					
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
						
						$this->form_validation->reset_validation();
						//用于数据校验得数组
						foreach($validationConfig['inventory'] as $filename){
							$this->form_validation->set_rules($filename,$validationConfig['rule_list'][$filename]['title'],$validationConfig['rule_list'][$filename]['rules']);
						}
						
						$this->form_validation->set_data($rowValue);
						$flag = $this->form_validation->run();
						
						if(!$flag){
							echo '<div class="success">行'.$rowIndex.'导入失败</div>';
							$rowsIgnored[] = $rowIndex;
							continue;
						}else{
							//echo '<div class="tip_success">行'.$rowIndex.'导入成功</div>';
						}
						
						$goodsList[] = $rowValue;
						//用一个
						$kwList[] = strtolower(code_replace($rowValue['goods_code']).str_replace(array('.','#'),'',$rowValue['goods_size']))."#{$rowValue['price_min']}#";
					}
					
					//更新库存
					$rows = $this->inventory_service->updateUserInventory(array(
						'username' => $this->_profile['basic']['username'],
						'email' => $this->_profile['basic']['email'],
						'qq' => $this->_profile['basic']['qq'],
						'mobile' => $this->_profile['basic']['mobile'],
						'goods_list' => $goodsList,
						'kw' => implode(',',$kwList),
						'ip' => $this->input->ip_address()
					),$this->_loginUID);
					
					
					$feedback = getSuccessTip('导入完成,成功导入 ' . count($goodsList).'记录，其中失败'.count($rowsIgnored).'条');
					
					
				}catch(Exception $re){
					$feedback = getErrorTip('导入错误,请检查文件格式是否正确');
				}
				
				@unlink($filePath);
			}
			
			$this->_importStep($step);
			
		}else{
			$this->_importStep(1);
		}
		
		$this->assign('feedback',$feedback);
		$this->display('inventory/upload');
			
	}
	
	private function _importStep($step){
		$this->assign('stepHTML',step_helper(array(
			'选择要上传的文件',
			'批量导入货品',
		),$step));
	}

}
