<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Worker extends Ydzj_Admin_Controller {
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library(array('Basic_data_service','Staff_service','Attachment_service','Wuye_service'));
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
			array('url' => $this->_className.'/import','title' => '导入'),
			array('url' => $this->_className.'/export','title' => '导出'),
		);
		
		$this->assign('basicData',$this->basic_data_service->getBasicDataList());
	}
	
	public function index(){
		
		
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
		
		
		$name = $this->input->get_post('name');
		
		if($name){
			$condition['like']['name'] = $name;
		}
		
		
		$list = $this->Worker_Model->getList($condition);
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		
		$this->display();
	}
	
	
	
	public function delete(){
		
		$ids = $this->input->post('id');
		$message = '';
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
				
			$returnVal = $this->staff_service->deleteWorker($ids,$this->addWhoHasOperated('edit'),$message);
			if($returnVal <= 0){
				$this->jsonOutput('删除失败',$this->getFormHash());
			}else{
				$this->jsonOutput('删除成功',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	
	/**
	 * 准备数据
	 */
	private function _prepareData($action = 'add'){
		
		$info = array();
		
		$remark = $this->input->post('remark');
		
		if($remark){
			$info['remark'] = $remark;
		}
		
		if('add' == $action){
			// 删除老照片
			$originalPic = $this->input->post('old_pic');
			if($originalPic){
				//如果上传了新文件,则删除原文件
				$oldImgArray = getImgPathArray($originalPic,array('b','m','s'));
				$this->attachment_service->deleteByFileUrl($oldImgArray);
			}
		}
		
		return $info;
	}
	
	
	/**
	 * 图片列表
	 */
	private function _getImgList(){
		$file_ids = $this->input->post('img_file_id');
		return $this->Worker_Images_Model->getImageListByIds($file_ids);
		
	}
	
	
	/**
	 * 附件列表
	 */
	private function _getFileList(){
		$file_ids = $this->input->post('file_id');
		return $this->Worker_Files_Model->getFileListByIds($file_ids);
	}
	
	
	/**
	 * 
	 */
	private function _commonPageData(){
		$basicData = $this->basic_data_service->getAssocBasicDataTree();
		
		$this->assign(array(
			'province_idcard' => json_encode(config_item('province_idcard')),
			'idTypeList' => $this->basic_data_service->getTopChildList('证件类型'),
			'workerTypeList' => $this->basic_data_service->getTopChildList('工种类型'),
			'zzmmList' => $this->basic_data_service->getTopChildList('政治面貌'),
			'jobStatusList' => $this->basic_data_service->getTopChildList('职务状态'),
			'jiguanList' => $this->basic_data_service->getTopChildList('籍贯'),
			'xueliList' => $this->basic_data_service->getTopChildList('学历'),
			'marriageList' => $this->basic_data_service->getTopChildList('婚育状态'),
		));
		
	}
	
	
	
	public function add(){
		$feedback = '';
		
		$redirectUrl = '';
		$fileList = array();
		$imgList = array();
		
		if($this->isPostRequest()){
			
			$this->form_validation->set_rules('worker_type','工种类型',"required|is_natural_no_zero");
			
			$this->staff_service->addIDRules(
				$this->basic_data_service->getTopChildList('证件类型'),
				$this->input->post('id_type'), 0, true, 
				$this->Worker_Model->getTableRealName()
			);
			
			$this->staff_service->addWorkerRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData());
				$imgList = $this->_getImgList();
				$fileList = $this->_getFileList();
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip('数据校验失败');
					//$feedback = getErrorTip($this->form_validation->error_string());
					
					//校验出错时 ，记住上传的头像 避免用户重传
					if($info['avatar']){
						$info = array_merge($info,getImgPathArray($info['avatar'],array('b','m','s')));
					}
					
					break;
				}
				
				if(($newid = $this->staff_service->saveWorker($info,$this->addWhoHasOperated(),$imgList,$fileList)) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$this->assign('successMessage','保存成功,3秒后自动刷新');
				$info = $this->Worker_Model->getFirstByKey($newid);
				
				$redirectUrl = admin_site_url($this->_className.'/index');
			}
		}
		
		$this->_commonPageData();
		$this->assign(array(
			'editable' => true,
			'fileList' => $fileList,
			'imgList' => $imgList,
			'info' => $info,
			'redirectUrl' => $redirectUrl,
			'feedback' => $feedback
		));
		
		$this->display();
	}
	
	
	
	/**
	 * 添加其他附件
	 */
	public function addfile(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addAttachment('Filedata',array('allowed_types' => 'pdf|doc|docx'));
		
		if($fileData){
			
			$info = array(
				'worker_id' => $this->input->get_post('id') ? $this->input->get_post('id') : 0,
				'title' => $fileData['orig_name'],
				'file_url' => $fileData['file_url'],
				'file_size' => $fileData['orig_size'],
				'file_ext' => $fileData['file_ext'],
				'uid' => $this->_adminProfile['basic']['uid'],
				'ip' => $fileData['ip'],
			);
			
			$fileId = $this->Worker_Files_Model->_add($info);
			if($fileId){
				$json['error'] = 0;
				$json['id'] = $fileId;
				$json['url'] = base_url($fileData['file_url']);
				$json['file_name'] = $fileData['orig_name'];
				$json['file_size'] = byte_format($fileData['orig_size']);
				$json['msg'] = '上传成功';
			}else{
				$json['msg'] = '系统异常';
				$this->attachment_service->deleteByFileUrl(array($fileData['file_url']));
			}
			
		}else{
			$json['msg'] = $this->attachment_service->getErrorMsg('','');
		}
		
		exit(json_encode($json));
		
	}
	
	/**
	 * 删除文件公共方法
	 */
	private function _delFile($modelObj,$isImg = false){
		
		$file_id = intval($this->input->get_post('file_id'));
		$worker_id = intval($this->input->get_post('id'));
		
		
		$rowsDelete = 0;
		
		$fileInfo = $modelObj->getFirstByKey($file_id);
		
		if($worker_id){
			//如果在编辑页面
			$rowsDelete = $modelObj->deleteByCondition(array(
				'where' => array(
					'id' => $file_id,
					'worker_id' => $worker_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}else{
			//在新增界面，还没有worker id
			$rowsDelete = $modelObj->deleteByCondition(array(
				'where' => array(
					'id' => $file_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}
		
		
		if($rowsDelete){
			if($isImg){
				$this->attachment_service->deleteByFileUrl(array($fileInfo['image'],$fileInfo['image_b'],$fileInfo['image_m']));
			}else{
				$this->attachment_service->deleteByFileUrl(array($fileInfo['file_url']));
			}
		}
		
		$this->jsonOutput('成功',$this->getFormHash());
	}
	
	
	/**
	 * 删除文件
	 */
	public function delfile(){
		$this->_delFile($this->Worker_Files_Model);
	}
	
	
	
	/**
	 * 添加文件
	 */
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addImageAttachment('Filedata',array('allowed_types' => 'jpg'));
		
		if($fileData){
			
			$fileData = $this->attachment_service->resize($fileData);
			
			$info = array(
				'worker_id' => $this->input->get_post('id') ? $this->input->get_post('id') : 0,
				'image' => $fileData['file_url'],
				'image_b' => !empty($fileData['img_b']) ? $fileData['img_b'] : '',
				'image_m' => !empty($fileData['img_m']) ? $fileData['img_m'] : '',
				'uid' => $this->_adminProfile['basic']['uid'],
				'ip' => $fileData['ip'],
			);
			
			$imageId = $this->Worker_Images_Model->_add($info);
			if($imageId){
				$json['error'] = 0;
				$json['id'] = $imageId;
				$json['url'] = base_url($fileData['file_url']);
				$json['msg'] = '上传成功';
				//尽量选择小图
				if($fileData['img_b']){
					$json['img_b'] = base_url($fileData['img_b']);
				}
				
				if($fileData['img_m']){
					$json['img_m'] = base_url($fileData['img_m']);
				}
				
			}else{
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
	
	
	/**
	 * 删除图片文件
	 */
	public function delimg(){
		
		$this->_delFile($this->Worker_Images_Model,true);
	}
	
	
	public function detail(){
		
		
		$id = $this->input->get_post('id');
		
		$info = $this->Worker_Model->getFirstByKey($id);
		
		$fileList = array();
		$imgList = array();
		
		$this->_subNavs[] = array('url' => $this->_className.'/detail?id='.$id, 'title' => '详情');
		
		$imgList = $this->Worker_Images_Model->getImagesListByWorkerId($id);
		$fileList = $this->Worker_Files_Model->getFileListByWorkerId($id);
		
		
		$this->_commonPageData();
		$this->assign(array(
			'editable' => false,
			'imgList' => $imgList,
			'fileList' => $fileList,
			'info' => $info
		));
		
		$this->display($this->_className.'/add');
		
	}
	
	
	
	/**
	 * 编辑工作人员
	 */
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');
		
		
		$info = $this->Worker_Model->getFirstByKey($id);
		
		$fileList = array();
		$imgList = array();
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		$oldAvatar = $info['avatar'];
		
		if($this->isPostRequest()){
			
			$this->staff_service->addIDRules(
				$this->basic_data_service->getTopChildList('证件类型'),
				$this->input->post('id_type'), $id, true, 
				$this->Worker_Model->getTableRealName()
			);
			
			$this->form_validation->set_rules('worker_type','工种类型',"required|is_natural_no_zero");
			$this->staff_service->addWorkerRules();
			
			for($i = 0; $i < 1; $i++){
				
				$info = array_merge($_POST,$this->_prepareData('edit'));
				
				$imgList = $this->_getImgList();
				$fileList = $this->_getFileList();
				
				if(!$this->form_validation->run()){
					//$feedback = getErrorTip($this->form_validation->error_string());
					$feedback = getErrorTip('数据校验失败');
					
					//校验出错时 ，记住上传的头像 避免用户重传
					if($info['avatar']){
						$info = array_merge($info,getImgPathArray($info['avatar'],array('b','m','s')));
					}
					
					break;
				}
				
				
				if($this->staff_service->saveWorker($info,$this->addWhoHasOperated('edit'),$imgList,$fileList) < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$info = $this->Worker_Model->getFirstByKey($info['id']);
				
				if($oldAvatar && $oldAvatar != $info['avatar']){
					//如果上传了新文件,则删除原文件
					$oldImgsAr = getImgPathArray($oldAvatar,array('b','m','s'));
					$this->attachment_service->deleteByFileUrl($oldImgsAr);
				}
				
				
				$this->assign('successMessage','保存成功');
			}
			
		}else{
			
			$imgList = $this->Worker_Images_Model->getImagesListByWorkerId($id);
			$fileList = $this->Worker_Files_Model->getFileListByWorkerId($id);
		}
		
		
		$this->_commonPageData();
		$this->assign(array(
			'editable' => true,
			'imgList' => $imgList,
			'fileList' => $fileList,
			'info' => $info,
			'feedback' => $feedback
		));
		
		$this->display($this->_className.'/add');
	}
	

	
	/**
	 * 裁剪头像
	 */
	public function pic_cut(){
		if($this->isPostRequest()){
			$src_file = str_ireplace(base_url(),'',$this->input->post('url'));
			//echo $src_file;
			$fileData = $this->attachment_service->resize(array('file_url' => $src_file) , 
				array('m' => array('width' => $this->input->post('w'),'height' => $this->input->post('h'),'maintain_ratio' => false , 'quality' => 100)) , 
				array('x_axis' => $this->input->post('x1'), 'y_axis' => $this->input->post('y1')));
			
			if($fileData['img_m']){
				$smallImg = $this->attachment_service->resize(array(
					'file_url' => $fileData['img_m']
				) , array('s') );
			}
			
			//删除原图
			//@unlink($fileData['full_path']);
			
			if (empty($fileData['img_m'])){
				exit(json_encode(array(
					'status' => 0, 
					'formhash'=>$this->security->get_csrf_hash(),
					'msg'=>$this->attachment_service->getErrorMsg('','')
				)));
			}else{
				exit(json_encode(array(
					'status' => 1, 
					'formhash'=>$this->security->get_csrf_hash(),
					'url'=>base_url($fileData['img_m']),
					'picname' => $src_file
				)));
			}
			
		}else{
			$save_file = str_ireplace(base_url(),'',$this->input->get('url'));
			
			list($width, $height, $type, $attr) =  getimagesize(ROOTPATH.'/'.$save_file);
			
			$this->assign('image_width',$width);
			$this->assign('image_height',$height);
			$this->assign('formUrl', admin_site_url($this->_className.'/pic_cut'));
			$this->display('common/pic_cut');
		}
		
	}


	/**
	 * 导入输出
	 */
	private function _importOutput($result){
		
		$str = array();
		foreach($result as $key => $line){
			$str[] = "<tr class=\"{$line['classname']}\"><td>{$line['name']}</td><td>{$line['id_type']}</td><td>{$line['id_no']}</td><td>{$line['mobile']}</td><td>{$line['message']}</td></tr>";
		}
		
		return implode('',$str);
		
	}
	
	
	/**
     * 导入excel
     */
    public function import(){
    	$feedback = '';
    	
    	$this->form_validation->set_error_delimiters('','');
    	
    	if($this->isPostRequest()){
       		header('Content-Type: text/html;charset='.config_item('charset'));
       		
    		for($i = 0; $i < 1; $i++){
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
					
					$idTypeList = $this->basic_data_service->getTopChildList('证件类型');
					$jiguanList = $this->basic_data_service->getTopChildList('籍贯');
					$shuList = $this->basic_data_service->getTopChildList('属相');
					$marriageList = $this->basic_data_service->getTopChildList('婚育状态');
					$zzmmList = $this->basic_data_service->getTopChildList('政治面貌');
					$degreeList = $this->basic_data_service->getTopChildList('学历');
					$workerTypeList = $this->basic_data_service->getTopChildList('工种类型');
					
					
					//$basicData = $this->basic_data_service->getAssocBasicDataTree();
					
					$provinceIdcard = config_item('province_idcard');
					$currentYear = date('Y');
					
					// 列从 0 开始  行从1 开始
					for($rowIndex = $startRow; $rowIndex <= $highestRow; $rowIndex++){
						$tmpRow = array();
						
						$tmpRow['classname'] = 'failed';
						$tmpRow['worker_type'] = getCleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue());
						$tmpRow['name'] = getCleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue());
						$tmpRow['id_type'] = getCleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue());
						$tmpRow['id_no'] = getCleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue());
						$tmpRow['mobile'] = getCleanValue($objWorksheet->getCell('E'.$rowIndex)->getValue());
						$tmpRow['marriage'] = getCleanValue($objWorksheet->getCell('F'.$rowIndex)->getValue());
						$tmpRow['address'] = getCleanValue($objWorksheet->getCell('G'.$rowIndex)->getValue());
						$tmpRow['sg'] = getCleanValue($objWorksheet->getCell('H'.$rowIndex)->getValue());
						$tmpRow['zzmm'] = getCleanValue($objWorksheet->getCell('I'.$rowIndex)->getValue());
						$tmpRow['shu'] = getCleanValue($objWorksheet->getCell('J'.$rowIndex)->getValue());
						$tmpRow['degree'] = getCleanValue($objWorksheet->getCell('K'.$rowIndex)->getValue());
						
						$this->form_validation->reset_validation();
						
						
						if(('身份证' == $tmpRow['id_type'] || '驾驶证' == $tmpRow['id_type']) && strlen($tmpRow['id_no']) >= 15){
							$sex = intval(substr($tmpRow['id_no'],-2,1));
							$tmpRow['sex'] = $sex % 2 == 0 ? '2' : '1';
							
							$birthday = substr($tmpRow['id_no'],6,8);
							$tmpRow['birthday'] = substr($birthday,0,4). '-'.substr($birthday,4,2).'-' .substr($birthday,6,2);
							
							$tmpRow['age'] = $currentYear - intval(substr($birthday,0,4));
							$provinceName = $provinceIdcard[substr($tmpRow['id_no'],0,3)."000"];
							
							$tmpRow['jiguan'] = $provinceName;
						}
						
						$this->form_validation->set_data($tmpRow);
						
						$this->wuye_service->addIDRules($idTypeList,$tmpRow['id_type'],0,false);
						
						$this->form_validation->set_rules('name','姓名','required|max_length[50]');
						$this->form_validation->set_rules('birthday','出生年月','required|valid_date');
						$this->form_validation->set_rules('age','年龄','required|is_natural_no_zero');
						$this->form_validation->set_rules('sex','性别','required|in_list[1,2]');
						$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
						//设置籍贯
						$this->form_validation->set_rules('jiguan','籍贯','required|in_list['.implode(',',array_values($provinceIdcard)).']');
						$this->form_validation->set_rules('marriage','婚育状态','required|in_list['.implode(',',array_keys($marriageList)).']');
						$this->form_validation->set_rules('shu','属相','required|in_list['.implode(',',array_keys($shuList)).']');
						$this->form_validation->set_rules('zzmm','政治面貌','required|in_list['.implode(',',array_keys($zzmmList)).']');
						$this->form_validation->set_rules('degree','学历','required|in_list['.implode(',',array_keys($degreeList)).']');
						$this->form_validation->set_rules('worker_type','工种类型','required|in_list['.implode(',',array_keys($workerTypeList)).']');
						
						if(!$this->form_validation->run()){
							$tmpRow['message'] = $this->form_validation->error_first_html();
							$result[] = $tmpRow;
							continue;
						}
						$insertData = array_merge(array(
							'worker_type' => $workerTypeList[$tmpRow['worker_type']]['id'],
							'name' => $tmpRow['name'],
							'mobile' => $tmpRow['mobile'],
							'id_type' => $idTypeList[$tmpRow['id_type']]['id'],
							'id_no' => $tmpRow['id_no'],
							'sex' => $tmpRow['sex'],
							'age' => $tmpRow['age'],
							'birthday' => $tmpRow['birthday'],
							'jiguan' => $jiguanList[$provinceName]['id'],
							'marriage' => $marriageList[$tmpRow['marriage']]['id'],
							'shu' => $shuList[$tmpRow['shu']]['id'],
							'zzmm' => $zzmmList[$tmpRow['zzmm']]['id'],
							'degree' => $degreeList[$tmpRow['degree']]['id'],
							'sg' => $tmpRow['sg'],
							'address' => $tmpRow['address'],		
						),$this->addWhoHasOperated('add'));
						

						$this->Worker_Model->_add($insertData);
						
						$error = $this->Worker_Model->getError();
						if(QUERY_OK != $error['code']){
							$tmpRow['message'] = '数据库错误';
							if($error['code'] == MySQL_Duplicate_CODE){
								$tmpRow['message'] = '工作人员' .
										'已经存在';
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
    		
	    	$this->display();
    	}
    	
    	
    }

    /**
     * 工作人员数据导出
     */
    public function export(){
    	
    	$message = '';
    	
    	if($this->isPostRequest()){
    		
    		try {
    			
    			$search = $this->input->post(array('worker_type','name','mobile','age_s','age_e','page'));
    			$search['marriage'] = $this->input->get_post('marriage');
    			$condition = array();
    			
    			if($search['worker_type']){
    				$condition['where']['worker_type'] = $search['worker_type'];
    			}
    			if($search['name']){
    				$condition['where']['name'] = $search['name'];
    			}
    			
    			if($search['mobile']){
    				$condition['where']['mobile'] = $search['mobile'];
    			}
    			
    			if($search['marriage']){
    				$condition['where']['marriage'] = $search['marriage'];
    			}
    			
    			if($search['age_s']){
    				$condition['where']['age >='] = intval($search['age_s']);
    			}
    			
    			if($search['age_e']){
    				$condition['where']['age <='] = intval($search['age_e']);
    			}
		
    			$search['page'] = intval($search['page']) == 0 ? 1 : intval($search['page']);
    			
    			$dataCnt = $this->Worker_Model->getCount($condition);
    			
    			
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
    		'A' => array('db_key' => 'worker_type','width' => 15 ,'title' => '工种类型'),
    		'B' => array('db_key' => 'name','width' => 15 ,'title' => '姓名'),
    		'C' => array('db_key' => 'id_type','width' => 12 ,'title' => '证件类型'),
    		'D' => array('db_key' => 'id_no','width' => 25 ,'title' => '证件号码'),
    		'E' => array('db_key' => 'mobile','width' => 15 ,'title' => '手机号码'),
    		'F' => array('db_key' => 'sex','width' => 8 ,'title' => '性别'),
    		'G' => array('db_key' => 'age','width' => 8 ,'title' => '年龄'),
    		'H' => array('db_key' => 'birthday','width' => 15 ,'title' => '出生日期'),
    		'I' => array('db_key' => 'jiguan','width' => 20 ,'title' => '籍贯'),
    		'J' => array('db_key' => 'marriage','width' => 20 ,'title' => '婚育状态'),
    		'K' => array('db_key' => 'address','width' => 20 ,'title' => '居住地'),
    		'L' => array('db_key' => 'sg','width' => 20 ,'title' => '身高'),
    		'M' => array('db_key' => 'zzmm','width' => 20 ,'title' => '政治面貌'),
    		'N' => array('db_key' => 'shu','width' => 20 ,'title' => '属相'),
    		'O' => array('db_key' => 'degree','width' => 20 ,'title' => '最高学历'),
    		
    	);
    	
    }
    
    /**
     * 执行导出动作
     */
    private function _doExport($condition = array()){
    	
    	$this->_initPHPExcel();
    	
        $objPHPExcel = new PHPExcel();
        
        
        $data = $this->Worker_Model->getList($condition);
    	
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
        
        
        $basicData = $this->basic_data_service->getBasicData();
        
    	foreach($list as $rowId => $worker){
    		foreach($colConfig as $colKey => $colItemConfig){
    			
    			$val = $worker[$colItemConfig['db_key']];
    			
    			
    			switch($colItemConfig['title']){
    				case '性别':
    					$val = $val == 1 ? '男':'女';
    					break;
    				case '工种类型':
    				case '籍贯':
    				case '婚育状态':
    				case '属相':
    				case '学历':
    				case '证件类型':
    					$val = $basicData[$val]['show_name'];
    					break;
    				default:
    					break;
    			}
    			
    			
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
