<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Goods extends Ydzj_Admin_Controller {
    public function __construct(){
		parent::__construct();
		
		$this->load->model('Lab_Model');
		
		$this->load->helper('cookie');
		$this->load->library('Goods_service');
		
		$this->assign('isSystemManager',$this->_checkIsSystemManager());
		
		$this->assign('action',$this->uri->rsegment(2));

    }
    
    public function index()
    {
    	
    	$this->_getPageData();
    	$this->assign('queryStr',$_SERVER['QUERY_STRING']);
    	$this->assign('managedLabs',$this->session->userdata('user_manager_labs'));
    	$this->assign('joinedLabs',$this->session->userdata('user_labs'));
        $this->display();
    }
	
	
	private function _getExcelColumnConfig(){
		return array(
    		array(
    			'col' => 'A',
    			'name' => '实验室',
    			'width' => 20,
    			'db_key' => 'lab_address'
    		),
    		array(
    			'col' => 'B',
    			'name' => '药品柜/试验台',
    			'width' => 20,
    			'db_key' => 'code'
    		),
    		array(
    			'col' => 'C',
    			'name' => '货品名称',
    			'width' => 30,
    			'db_key' => 'name'
    		),
    		array(
    			'col' => 'D',
    			'name' => '类别',
    			'width' => 10,
    			'db_key' => 'category_name'
    		),
    		array(
    			'col' => 'E',
    			'name' => '单位',
    			'width' => 8,
    			'db_key' => 'measure'
    		),
    		array(
    			'col' => 'F',
    			'name' => '规格',
    			'width' => 10,
    			'db_key' => 'specific'
    		),
    		array(
    			'col' => 'G',
    			'name' => '库存',
    			'width' => 10,
    			'db_key' => 'quantity'
    		),
    		array(
    			'col' => 'H',
    			'name' => '单价',
    			'width' => 10,
    			'db_key' => 'price'
    		),
    		array(
    			'col' => 'I',
    			'name' => '实验名称/课程名称',
    			'width' => 20,
    			'db_key' => 'subject_name'
    		),
    		array(
    			'col' => 'J',
    			'name' => '备注',
    			'width' => 20,
    			'db_key' => 'project_name'
    		)
    	);
		
	}    


    public function export(){
    	
    	$this->load->helper('download');
    	
    	
    	//$condition['select'] = 'a,b';
        $condition['order'] = "gmt_create DESC";
        
        if(!empty($_GET['lab_address'])){
            $condition['like']['lab_address'] = $_GET['lab_address'];
        }
        
        if(!empty($_GET['name'])){
            $condition['like']['name'] = $_GET['name'];
        }
        
        if(!empty($_GET['subject_name'])){
            $condition['like']['subject_name'] = $_GET['subject_name'];
        }
        
        if(!empty($_GET['project_name'])){
            $condition['like']['project_name'] = $_GET['project_name'];
        }
        
        $condition['where']['status'] = '正常';
        
        if($_GET['threshold_active']){
        	$condition['where']['quantity < threshold'] = null;
        }
        
        $this->Goods_Category_Model->clearMenuTree();
        $list = $this->Goods_Category_Model->getListByTree($_GET['category_id']);
        
        $ids = array_keys($list);
        $ids[] = intval($_GET['category_id']);
        
        $condition['where_in'] = array(
        	array(
        		'key' => 'category_id',
        		'value' => array_unique($ids)
        	),
        	array(
        		'key' => 'lab_id',
        		'value' => $this->session->userdata['user_labs']
        	)
        );
        
        $data = $this->Goods_Model->getList($condition);
    	
    	//$list = $this->Goods_Model->getList();
    	
    	require_once PHPExcel_PATH.'PHPExcel.php';
    	
    	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
        $cacheSettings = array( 'dir'  => PHPExcel_TEMP_PATH );
        PHPExcel_Settings::setLocale('zh_CN');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        
        $objPHPExcel = new PHPExcel();
    	$objPHPExcel->setActiveSheetIndex(0);
    	
    	
    	$title = "货品列表";
    	
        $objPHPExcel->getActiveSheet()->setTitle($title);
    	
    	
    	$this->_writeTitleLine($objPHPExcel);
    	$this->_writeDetailTitleLine($objPHPExcel,4,$this->_getExcelColumnConfig());
    	
    	$this->_writeDetailLine($objPHPExcel,6,$data);
    	
    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	
        $showFilename = "{$title}.xls";
        $filename = "{$title}".$this->_loginUID.".xls";
        
        $objWriter->save(PHPExcel_TEMP_PATH.$filename);
        $objPHPExcel->disconnectWorksheets(); 
        unset($objPHPExcel,$objWriter); 
        force_download($showFilename,  file_get_contents(PHPExcel_TEMP_PATH.$filename));
    }
    
    private function _writeTitleLine($objPHPExcel){
    	$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
    	$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
    	$objPHPExcel->getActiveSheet()->setCellValue('A2', '货物列表');
    	
    	$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
    	
    	$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray(
                array(
                    'alignment' => array(
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ),
                    'font'    => array(
                        'bold'      => true,
                        'size'     => 16
                    )
                )
        );
        
    	
    }
    
    private function _writeDetailTitleLine($objPHPExcel, $start , $title){
    	foreach($title as $value){
    		$objPHPExcel->getActiveSheet()->setCellValue($value['col'].$start, $value['name']);
    		$objPHPExcel->getActiveSheet()->getColumnDimension($value['col'])->setWidth($value['width']);
    	}
    }
    
    private function _writeDetailLine($objPHPExcel, $row_start , $list){
    	if(empty($list)){
    		return;
    	}
    	
        $i = 0;
        foreach($list as $p){
            $current_row = $i + $row_start;
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$current_row,$p['lab_address']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$current_row,$p['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$current_row, $p['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$current_row, $p['category_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$current_row, $p['measure']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$current_row, $p['specific']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$current_row, $p['quantity']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$current_row, $p['price']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$current_row, $p['subject_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$current_row, $p['project_name']);
            $i++;
        }
        
        /*
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
                array(
                    'font'    => array(
                        'bold'      => true,
                        'size'     => 20
                    )

                )
        );
        */
        
        $objPHPExcel->getActiveSheet()->getStyle('A4:J4')->applyFromArray(
                array(
                    'font'    => array(
                        'bold'      => true,
                        'size'     => 12
                    )
                )
        );
        
        $objPHPExcel->getActiveSheet()->getStyle('A4:J'.$current_row)->applyFromArray(
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
        
    }
    
    
    public function import(){
    	
    	if($this->isPostRequest()){
    		ob_end_flush();//关闭缓存
	    	header("Content-Type: text/html; charset=utf-8");
	    	
	    	echo '<style type="text/css"> body { font: 62.5% "Microsoft Yahei", "Lucida Grande", Verdana, Lucida, Helvetica, Arial, "Simsun", sans-serif; } .import_result { border-collapse: collapse; } .import_result td { font-size:100%; border: 1px solid black; }  .success td {color:blue;} .failed td {color:red;}</style>';
	    	//flush();
	    	$id = $this->input->post('file_id');
	    	
	    	if(!empty($id)){
	    		
	    		$this->load->model('Attachment_Model');
	    		//$this->load->model('Goods_Import_Model');
	    		$excelFile = $this->Attachment_Model->getById(array(
	    			'select' => 'file_url',
	    			'where' => array(
	    				'id' => $id
	    			)
	    		));
	    		
	    		$filePath = ROOTPATH.'/'.$excelFile['file_url'];
	    		
	    		if(is_file($filePath)){
	    			
	    			//init basic data
	    			$categoryList = $this->Goods_Category_Model->getList();
					$hashCategory = array();
					foreach($categoryList as $item){
						$hashCategory[$item['name']] = $item['id'];
					}
					
					$keyConfig = $this->_getExcelColumnConfig();
					
					$userLabs = $this->_getUserLab();
					$hashLabs = array();
					foreach($userLabs as $lab){
						$lab['address'] = sbc_to_dbc(str_replace(array("\n","\r","\r\n"),"",trim($lab['address'])));
						$hashLabs[$lab['address']] = $lab;
					}
					
					// begin
	    			require_once PHPExcel_PATH.'PHPExcel.php';
	    			$objPHPexcel = PHPExcel_IOFactory::load($filePath); 
					$objWorksheet = $objPHPexcel->getActiveSheet(0); 
					$startRow = 0;
					$readAddOn = 2; //标题行下再两行开始读  hard code , need make it configureable
					$keyword = '实验室';
					$findKeyword = false;
					$highestRow = $objWorksheet->getHighestRow();
					
					$result = array('success' => 0 , 'failed' => 0 ,'duplicate' => 0 );
					echo '<table class="import_result"><tbody><tr>';
					$temp = array();
					
					foreach($keyConfig as $config){
						$temp[] = "<td>".$config['name']."</td>";
					}
					echo implode('',$temp);
					echo '</tr>';
					
					flush();
					
					foreach ($objWorksheet->getRowIterator() as $row) { 
						 $cellIterator = $row->getCellIterator();
						 $startRow++;
						 
						 foreach ($cellIterator as $cell) {
						    if(str_replace(array("\n","\r","\r\n"),"",trim($cell->getValue())) == $keyword){
						    	$findKeyword = true;
						    	break;
						    }
						 }
						 
						 if($findKeyword){
						 	break;
						 }
					}
					
					$extraMessage = '';
					
					for($rowIndex = ($startRow + $readAddOn); $rowIndex <= $highestRow; $rowIndex++){
						$rowValue = array();
						$flag = false;
						$affectRow = 0;
						$classname ="failed";
						
						///$rowValue['id'] = NULL;
						$rowValue['file_id'] = $id;
						
						foreach($keyConfig as $config){
							$rowValue[$config['db_key']] = sbc_to_dbc(str_replace(array("\n","\r","\r\n"),"",trim($objWorksheet->getCell($config['col'].$rowIndex)->getValue())));
						}
						
						//important , do not drop this line
						if(empty($rowValue['lab_address'])){
							break;
						}
						
						//实验室地址不存在
						if(empty($hashLabs[$rowValue['lab_address']])){
							$temp = array();
							$temp[] = '<tr class="failed">';
							foreach($keyConfig as $config){
								$temp[] = "<td>".$rowValue[$config['db_key']]."</td>";
							}
							$temp[] = "</tr>";
							
							echo implode('',$temp);
							flush();
							
							$result['failed']++;
							continue;
						}
						
						$rowValue['lab_id'] = empty($hashLabs[$rowValue['lab_address']]['id']) ? 0 : $hashLabs[$rowValue['lab_address']]['id'];
						$rowValue['category_id'] = empty($hashCategory[$rowValue['category_name']]) ? 0 : $hashCategory[$rowValue['category_name']];
						$rowValue['specific'] = strtoupper($rowValue['specific']);
						if(preg_match("/^-+$/",$rowValue['specific'])){
							$rowValue['specific'] = '';
						}
						
						$rowValue['quantity'] = intval($rowValue['quantity']);
						$rowValue['price'] = (double)$rowValue['price'];
						$rowValue['creator'] = $this->_adminProfile['basic']['name'];
						
						$rowValue['hash'] = $this->_getGoodsHash(
							array(
								'lab_address' => $rowValue['lab_address'],
								'code' => $rowValue['code'],
								'name' => $rowValue['name'],
								'specific' => strtoupper($rowValue['specific'])
							)
						);
						
						//检查是否已经存在
						$flag = $this->Goods_Model->_add($rowValue);
						if($flag > 0){
							$result['success']++;
							$classname = "success";
						}else{
							$errorInfo = $this->db->get_error_info();
							
							///file_put_contents('debug.txt',print_r($errorInfo,true));
							
							if($errorInfo['code'] == 1062){
								foreach($rowValue as $rowKey => $rowV){
									$this->db->set($rowKey, $rowV);
								}
								
								//hash key duplicate
								if($_POST['import_mode'] == '累加模式'){
									$this->db->set('quantity', "quantity + {$rowValue['quantity']}",false);
								}else{
									$this->db->set('quantity', $rowValue['quantity']);
								}
								
								
								$this->db->set('status', '正常');
								$this->db->set('updator', $this->_adminProfile['basic']['name']);
								$this->db->set('gmt_modify', time());
								
								$this->db->where(array('hash' => $rowValue['hash']));
								
								$affectRow = $this->db->update($this->Goods_Model->getTableRealName());
								if($affectRow >= 0){
									$classname = "success";
									$result['duplicate']++;
								}else{
									$classname = "failed";
									$result['failed']++;
								}
								
							}else{
								$classname = "failed";
								$result['failed']++;
							}
						}
						
						$trRow = array();;
						$trRow[] = "<tr class=\"{$classname}\">";
						foreach($keyConfig as $config){
							$trRow[] = "<td>".$rowValue[$config['db_key']]."</td>";
						}
						$trRow[] = "</tr>";
						
						echo implode('',$trRow);
						
						unset($trRow);
						
						flush();
					}
					
					echo '</tbody></table>';
					echo '<p>导入结果</p>';
					echo '<p><span style="color:blue">新建'.($result['success']).'行</span>';
					echo '&nbsp;<span style="color:blue">更新(含重复数据行)'.($result['duplicate']).'行</span>';
					echo '&nbsp;<span style="color:red">失败'.$result['failed'].'行</span></p>';
					
					$script = <<< EOF
					<script>
						window.onload = function(){
							parent.document.getElementById("begin_import").disabled = false;
						}
					</script>
EOF;

					echo $script;
					flush();
	    		}
	    	}
    		
    	}else{
    		
    		$this->assign('labList',$this->_getUserLab());
    		$this->display();
    	}
    	
    	
        
    }
    
    
    public function empty_goods(){
    	
    	$this->assign('labList',$this->_getUserLab());
    	
    	$message = "";
    	if($this->isPostRequest()){
    		$this->load->helper('text');
    		
    		$lab_ids = $_POST['lab_id'];
    		$valid_id = array();
    		$lab_checked = array();
    		
    		foreach($lab_ids as $id ){
    			$lab_checked[$id] = true;
    			if(in_array($id, $this->session->userdata['user_labs'])){
    				$valid_id[] = $id;
    			}
    		}
    		
    		if($valid_id){
    			$condition = array(
    				'where_in' => array(
    					array('key' => 'lab_id', 'value' => $valid_id)
    				)
    			);
    			
    			if(!empty($_POST['goods_name'])){
    				$condition['where']['name'] = sbc_to_dbc($_POST['goods_name']);
    			}
    			
    			$this->Goods_Model->deleteByCondition($condition);
    		}
    		
    		$message = "清空成功";
    		
    		$this->assign('success',1);
    		$this->assign('lab_checked',$lab_checked);
    		$this->assign('message',$message);
    	}
    	
        $this->display();
    }
    
    
    private function _addRules(){
    	$this->form_validation->set_rules('lab_id','实验室地址',  'required|is_natural_no_zero');
    	$this->form_validation->set_rules('code','药品柜/试验台编号',  'required|max_length[100]');
		$this->form_validation->set_rules('name','名称',  'required|max_length[100]');
		$this->form_validation->set_rules('category_id','分类名称',  'required|callback_check_category');
		$this->form_validation->set_rules('measure','度量单位',  'required');
		
		if(!empty($_POST['specific'])){
			$this->form_validation->set_rules('specific','规格',  'max_length[50]');
		}
		
		if(!empty($_POST['cas'])){
			$this->form_validation->set_rules('cas','药品CAS号',  'max_length[30]');
		}
		
		if(!empty($_POST['danger_remark'])){
			$this->form_validation->set_rules('danger_remark','危险等级',  'max_length[50]');
		}
		
		if(!empty($_POST['manufacturer'])){
			$this->form_validation->set_rules('manufacturer','生产厂家',  'max_length[100]');
		}
		
		$this->form_validation->set_rules('price','价格',  'required|is_numeric');
		$this->form_validation->set_rules('quantity','库存',  'required|is_natural');
		
		
		if(!empty($_POST['threshold'])){
			$this->form_validation->set_rules('threshold','低库存预警阀值',  'greater_than[0]');
		}
		
		
		if(!empty($_POST['subject_name'])){
			$this->form_validation->set_rules('subject_name','实验名称/课程名称',  'max_length[50]');
		}
		
		if(!empty($_POST['project_name'])){
			$this->form_validation->set_rules('project_name','备注',  'max_length[50]');
		}
    }
    
    
    private function _getPageData(){
    	try {
            
            
            $page = $this->input->get_post('page');
            if(empty($page)){
                $page = 1;
            }
            
            $page_size = get_cookie('page_size');
            
            if(!$page_size){
            	$page_size = config_item('page_size');
            }
            
            if($page_size > 100){
            	$page_size = config_item('page_size');
            }
            
            //$condition['select'] = 'a,b';
            $condition['order'] = "gmt_modify DESC";
            $condition['pager'] = array(
                'page_size' => $page_size,
                'current_page' => $page,
                'query_param' => '',
                'call_js' => 'search_page',
				'form_id' => '#formSearch'
            );
            
            
            if(!empty($_GET['lab_address'])){
                $condition['like']['lab_address'] = $_GET['lab_address'];
            }
            
            if(!empty($_GET['name'])){
                $condition['like']['name'] = $_GET['name'];
            }
            
            if(!empty($_GET['subject_name'])){
                $condition['like']['subject_name'] = $_GET['subject_name'];
            }
            
            if(!empty($_GET['project_name'])){
                $condition['like']['project_name'] = $_GET['project_name'];
            }
            
            $condition['where']['status'] = '正常';
            
            if($_GET['threshold_active']){
            	$condition['where']['quantity <= threshold'] = null;
            	$condition['where']['threshold !='] = 0;
            }
            
            $this->Goods_Category_Model->clearMenuTree();
            $list = $this->Goods_Category_Model->getListByTree($_GET['category_id']);
            
            $ids = array_keys($list);
            $ids[] = intval($_GET['category_id']);
            
            
            $condition['where_in'] = array(
            	array(
            		'key' => 'category_id',
            		'value' => array_unique($ids)
            	)
            );
            
            if($this->_loginUID != LAB_FOUNDER_ID){
            	$condition['where_in'][] = array(
            		'key' => 'lab_id',
            		'value' => $this->session->userdata['user_labs']
            	);
            }
            
            $data = $this->Goods_Model->getList($condition);
            
            $this->Goods_Category_Model->clearMenuTree();
            $categoryList = $this->Goods_Category_Model->getListByTree();
            
            $this->assign('categoryList',$categoryList);
            $data['pager']['shortStyle'] = false;
            $this->assign('page',$data['pager']);
            $this->assign('data',$data);
            
        }catch(Exception $e){
            //@todo error code here
        }
    }
    
    
    private function _prepareData(){
    	
    	
    	$categoryName = $this->Goods_Category_Model->getFirstByKey($_POST['category_id']);
		$labName = $this->Lab_Model->getFirstByKey($_POST['lab_id']);
		
		$_POST['category_name'] = $categoryName['name'];
		$_POST['lab_address'] = $labName['address'];
		
		//实验室地址 + 药品柜/试验台编号 + 名称 + 规格  确定唯一性
		$_POST['hash'] = $this->_getGoodsHash(
			array(
				'lab_address' => $_POST['lab_address'],
				'code' => $_POST['code'],
				'name' => $_POST['name'],
				'specific' => strtoupper($_POST['specific'])
			)
		);
		
		$_POST['threshold'] = intval($_POST['threshold']);
    	
    }
    
    public function edit(){
		
		$id = $this->input->get_post('id');
		
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				$this->_addRules();
				
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$_POST['updator'] = $this->_adminProfile['basic']['name'];
				$this->_prepareData();
				
				unset($_POST['lab_id']);
				
				$flag = $this->Goods_Model->update($_POST,array('id' => $id));
				
				if($flag >= 0){
					$this->jsonOutput('保存成功');
				}else{
					$errorInfo = $this->db->get_error_info();
					
					if($errorInfo['code'] == 1062){
						$this->jsonOutput('您修改后的货品已经存在,保存失败');
					}else{
						$this->jsonOutput('保存失败');
					}
					
				}
			}
			
			
		}else{
			$this->_initBasicData();
			$info = $this->Goods_Model->getFirstByKey($id);
			$this->assign('info',$info);
			
			$this->display('goods/add');
		}
    }
    
    
    public function info(){
    	$id = $this->input->get_post('id');
    	
    	$this->_initBasicData();
		$info = $this->Goods_Model->getFirstByKey(intval($id));
		$this->assign('info',$info);
		
    	$this->display('goods/add_body');
    }
    
    public function delete(){
    	
    	$id = $this->input->post('id');
    	if($id && $this->isPostRequest()){
			for($i = 0; $i < 1; $i++){
				
				if(is_array($id)){
					$id = $id[0];
				}
				
				$info = $this->Goods_Model->getFirstByKey($id);
				
				
				if(empty($info)){
					$this->jsonOutput('参数错误');
					break;
				}
			
				if($this->_loginUID != LAB_FOUNDER_ID){
					$labManager = $this->Lab_Member_Model->getLabManager($this->_loginUID,$info['lab_id']);
		    		if(empty($labManager)){
		    			$this->jsonOutput('您不是该实验室管理员，不能删除');
		    			break;
		    		}
				}
				
	    		$this->Goods_Model->deleteByWhere(array('id' => $id));
	    		$this->jsonOutput('删除成功');
			}
    	}else{
    		$this->jsonOutput('请求错误');
    	}
    }
    
    
    
    public function check_category($val){
    	
    	$val = intval($val);
    	
    	$info = $this->Goods_Category_Model->getFirstByKey($val);
    	
    	if(empty($info)){
    		$this->form_validation->set_message('check_category', '%s 无效');
            return FALSE;
    	}else{
    		return true;
    	}
    	
    }
    
    
    private function _getGoodsHash($hash){
    	ksort($hash);
    	$md5Value = array_values($hash);
    	//return md5($_POST['code'].$_POST['name'].strtoupper($_POST['specific']));
    	return md5(implode($md5Value,''));
    }
    
    
    private function _getUserLab(){
    	return $this->lab_service->getUserOwnedLabsHTML($this->_loginUID);
    	
    }
    
    private function _initBasicData(){
    	
		$categoryList = $this->Goods_Category_Model->getListByTree();
		$measureList = $this->Measure_Model->getList(array(
			'where' => array(
				'status' => '正常'
			)
		));
		
		$this->assign('labList',$this->_getUserLab());
		$this->assign('categoryList',$categoryList);
		$this->assign('measureList',$measureList);
    }
    
    
    
    public function add()
    {
		if($this->isPostRequest()){
			
			$this->_addRules();
			
			for($i = 0; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$d['errors'] = $this->form_validation->error_array();
					$this->jsonOutput('数据不能通过校验,添加失败',$d);
					break;
				}
				
				$_POST['creator'] = $this->_adminProfile['basic']['name'];
				
				$this->_prepareData();
				$flag = $this->Goods_Model->_add($_POST);
				
				if($flag > 0){
					$this->jsonOutput('保存成功');
				}else{
					$errorInfo = $this->db->get_error_info();
					
					if($errorInfo['code'] == 1062){
						$this->jsonOutput('该货品已经存在,添加失败');
					}else{
						$this->jsonOutput('保存失败');
					}
				}
			}
			
		}else{
			$this->_initBasicData();
	        $this->display();
		}
    }
    
}
