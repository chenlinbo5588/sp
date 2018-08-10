<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Repair extends Ydzj_Admin_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('Wuye_service','Basic_data_service','Attachment_service'));
		
		$this->_moduleTitle = '报修';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className,
			'repairType' => RepairType::$typeName,
			'repairStatus' => RepairStatus::$statusName
		));
		
		$this->_subNavs = array(
			array('url' => $this->_className.'/index','title' => '管理'),
			array('url' => $this->_className.'/add','title' => '添加')
		);
		
		$this->form_validation->set_error_delimiters('<div>','</div>');
	}
	
	public function  _searchCondition($moreSearchVal = array()){
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
		$search['mobile'] = $this->input->get_post('mobile');
		$search['repair_type'] = $this->input->get_post('repair_type');
		$search['status'] = $this->input->get_post('status');
		
		$search = array_merge($search,$moreSearchVal);
		
		if($search['name']){
			$condition['like']['yezhu_name'] = $search['name'];
		}
		
		if($search['mobile']){
			$condition['like']['mobile'] = $search['mobile'];
		}
		if($search['repair_type']){
			$condition['where']['repair_type'] = $search['repair_type'];
		}
		if($search['status']){
			$condition['where']['status'] = $search['status'];
		}
		
		if($search['status != ']){
			$condition['where']['status !='] = $search['status != '];
		}
		
		$list = $this->Repair_Model->getList($condition);
		
		
		$this->assign('list',$list);
		$this->assign('page',$list['pager']);
		$this->assign('currentPage',$currentPage);
		$this->assign('search',$search);

	}
	
	
	
	public function index(){
		$this->_searchCondition(array('status != ' => RepairStatus::$deleted));
		$this->display($this->_className.'/index');
		
	}
	
	private function _getRules(){
		$this->form_validation->set_rules('repair_type','报修类型','required');
		$this->form_validation->set_rules('address','地址','required|in_db_list['.$this->House_Model->getTableRealName().'.address]');
		$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
		$this->form_validation->set_rules('remark','备注','required');		
	}
	
	/**
	 * 
	 */
	public function add(){
		$feedback = '';
		
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				
				$this->_getRules();
				
				$info = $this->_prepareData();
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				if(!empty($_POST['address'])){
					$houseInfo = $this->House_Model->getById(array(
						'where' => array(
							'address' => $_POST['address']
						)
					));
					
					$_POST['resident_id'] = $houseInfo['resident_id'];
				}
				
				if(!empty($_POST['yezhu_name'])){
					$yezhuInfo = $this->Yezhu_Model->getById(array(
						'where' => array(
							'name' => $_POST['yezhu_name']
						)
					));
					
					if(!empty($yezhuInfo)){
						$_POST['yezhu_id'] = $yezhuInfo['id'];
					}
				}
				
				if(!empty($_POST['worker_name']) && !empty($_POST['worker_mobile'])){
					$_POST['status'] = RepairStatus::$sendOrder;
				}
				
				$newId = $this->Repair_Model->_add(array_merge($_POST,$this->_prepareData(),$this->addWhoHasOperated('add')));
				$error = $this->Repair_Model->getError();
				if(QUERY_OK != $error['code']){
					$this->jsonOutput('数据库错误,请稍后再次尝试');
					break;
				}
				
				if($_POST['img_file_id']){
					$this->Repair_Images_Model->updateByCondition(array(
						'repair_id' => $newId
					),array(
					
						'where' => array(
							'repair_id' => 0
						),
						'where_in' => array(
							array('key' => 'id','value' => $_POST['img_file_id'])
						)
					));
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			$this->display();
		}

	}	
	
	private function _prepareData(){
		$data['displayorder'] = $this->input->post('displayorder');
		return array(
			'displayorder' => $data['displayorder'] ? $data['displayorder'] : 255
		);
	}
	
	/**
	 * 受理
	 */
	public function received(){
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$returnVal = $this->wuye_service->changeRepairStatus($ids,RepairStatus::$received,RepairStatus::$unReceived,'status');
			
			if($returnVal > 0){
				$this->jsonOutput('受理成功',$this->getFormHash());
			}else{
				$this->jsonOutput('受理失败',$this->getFormHash());
			}
			
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
		}	
	}
	
	/**
	 * 派遣
	 */
	public function dispatch(){
		$ids = $this->input->get_post('id');
		//@TOOD
		//待加入微信通知
		if($this->isPostRequest() && !empty($ids)){
			for($i = 0; $i < 1 ; $i++){
				
				$this->_getWorkerRules();
				$info = $this->_prepareData();
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$ids = explode(',',$ids);
				$returnstatusVal = $this->wuye_service->changeRepairStatus($ids,RepairStatus::$sendOrder,RepairStatus::$received,'status',array(
					'worker_name' => $this->input->post("worker_name"),
					'worker_mobile' => $this->input->post("worker_mobile")
				));
				
				if($returnstatusVal < 1){
					$this->jsonOutput('派遣操作失败,无受理记录');
					break;
				}
				
				$this->jsonOutput('派遣操作成功',array('jsReload' => true));
			}
			
		}else{
			$this->assign('id',implode(',',$this->input->get_post('id')));
			$this->display();
		}		
	}
	
	/**
	 * 
	 */
	public function complete_repair(){
		$ids = $this->input->get_post('id');
		//@TOOD
		//待加入微信通知
		if($this->isPostRequest() && !empty($ids)){
			for($i = 0; $i < 1 ; $i++){	
				
				$this->_getWorkerRules();
				
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$ids = explode(',',$ids);	
				$returnstatusVal = $this->wuye_service->changeRepairStatus($ids,RepairStatus::$accomplish,RepairStatus::$sendOrder,'status',array(
					'worker_name2' => $this->input->post("worker_name"),
					'worker_mobile2' => $this->input->post("worker_mobile")
				));
				
				
				if($returnstatusVal < 1){
					$this->jsonOutput('报修完成操作失败,没有已派单记录');
					break;
				}
				
				$this->jsonOutput('报修完成操作成功',array('jsReload' => true));
			}
			
		}else{
			$info = $this->Repair_Model->getById(array(
				'where' => array(
					'id' => $ids[0]
				)
			));
			$this->assign('info',$info);
			$this->assign('id',implode(',',$this->input->get_post('id')));
			$this->display();
		}		
	}
	

	public function delete(){
		
		$ids = $this->input->post('id');
		
		if($this->isPostRequest() && !empty($ids)){
			
			if(!is_array($ids)){
				$ids = (array)$ids;
			}
			
			$this->Repair_Model->updateByCondition(array(
				'status' =>  RepairStatus::$deleted
			),
			array(
				'where_in' => array(
					array('key' => 'id','value' => $ids)
				)
			));
			
			$this->jsonOutput('删除成功',array('jsReload' => true));
		}else{
			$this->jsonOutput('请求非法',$this->getFormHash());
			
		}
	}
	
	public function edit(){
		
		$feedback = '';
		$id = $this->input->get_post('id');

		$info = $this->Repair_Model->getFirstByKey($id);
		
		$this->_subNavs[] = array('url' => $this->_className.'/edit?id='.$id, 'title' => '编辑');
		
		if($this->isPostRequest()){
			
			$this->_getRules();
			
			for($i = 0; $i < 1; $i++){
				$info = array_merge($info,$_POST,$this->_prepareData(),$this->addWhoHasOperated('edit'));
				
				if(!$this->form_validation->run()){
					$this->jsonOutput('数据校验失败,'.$this->form_validation->error_string(),array('errors' => $this->form_validation->error_array()));
					break;
				}
				
				$this->Repair_Model->update(array_merge($info,$_POST,$this->addWhoHasOperated('edit')),array('id' => $id));
				$error = $this->Repair_Model->getError();
				
				if(QUERY_OK != $error['code']){
					$this->jsonOutput('数据库错误,请稍后再次尝试');
					break;
				}
				
				$this->jsonOutput('保存成功,页面即将刷新',array('redirectUrl' => admin_site_url($this->_className.'/index')));
			}
		}else{
			
			$imgList = $this->Repair_Images_Model->getImagesListByRepairId($id);
			
			$this->assign('imgList',$imgList);
			$this->assign('info',$info);
			$this->display($this->_className.'/add');
			
		}
		
	}
	/**
	 * 快速编辑
	 */
	public function inline_edit(){
		$fieldName = $this->input->get_post('fieldname');
		$id = $this->input->get_post('id');
		$newValue = $this->input->get_post('value');
		
		$this->form_validation->set_error_delimiters('','');
		
		
		for($i = 0 ; $i < 1; $i++){
			
			$data = array(
				'id' => $id,
				'fieldname' => $fieldName,
				$fieldName => $newValue
			);
			
			$this->form_validation->set_data($data);
			
			$this->form_validation->set_rules('id','数据标识','required');
			$this->form_validation->set_rules('fieldname','字段','in_list[mobile,address,remark,worker_name,worker_mobile]');
			
			
			
			switch($fieldName){
				case 'mobile':
					$this->form_validation->set_rules('mobile','手机号码','required|valid_mobile');
					break;
				case 'remark':
					$this->form_validation->set_rules('remark','备注','required');
					break;
				case 'worker_name':
					$this->form_validation->set_rules('worker_name','工作人员姓名','required');
					break;
				case 'worker_mobile':
					$this->form_validation->set_rules('worker_mobile','工作人员电话','required|valid_mobile');
					break;
				case 'address';
					$this->form_validation->set_rules('address','地址','required|in_db_list['.$this->House_Model->getTableRealName().'.address]');
					break;
				default:
					break;
			}
			
			$message = '修改失败';
			
			if(!$this->form_validation->run()){
				$message = $this->form_validation->error_html();
			}else{
				if($this->Repair_Model->update(array($fieldName => $newValue),array('id' => $id)) < 0){
					$message = '数据修改失败';
				}else{
					$message = '修改成功';
				}
			}
			
			$this->jsonOutput($message);
		}
	}
	
	
	
	
		
	public function addimg(){
		
		$json = array('error' => 1, 'formhash'=>$this->security->get_csrf_hash(),'id' => 0,'msg' => '上传失败');
		
		$fileData = $this->attachment_service->addImageAttachment('Filedata',array(),FROM_BACKGROUND,'repair');
		if($fileData){
			
			$fileData = $this->attachment_service->resize($fileData);
			
			$info = array(
				'repair_id' => $this->input->get_post('id') ? $this->input->get_post('id') : 0,
				'image' => $fileData['file_url'],
				'image_b' => !empty($fileData['img_b']) ? $fileData['img_b'] : '',
				'image_m' => !empty($fileData['img_m']) ? $fileData['img_m'] : '',
				'uid' => $this->_adminProfile['basic']['uid'],
				'ip' => $fileData['ip'],
			);
			$imageId = $this->Repair_Images_Model->_add($info);
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
				$json['error'] = 0;
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


	public function delimg(){
		$file_id = intval($this->input->get_post('file_id'));
		$repair_id = intval($this->input->get_post('id'));
		
		$fileInfo = $this->Repair_Images_Model->getFirstByKey($file_id);
		
		$rowsDelete = 0;
		if($repair_id){
			//如果在编辑页面
			$rowsDelete = $this->Repair_Images_Model->deleteByCondition(array(
				'where' => array(
					'id' => $file_id,
					'repair_id' => $repair_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}else{
			//在新增界面，还没有worker id
			$rowsDelete = $this->Repair_Images_Model->deleteByCondition(array(
				'where' => array(
					'id' => $file_id,
					'uid' => $this->_adminProfile['basic']['uid']
				)
			));
		}
		
		if($rowsDelete){
			$this->attachment_service->deleteByFileUrl(array($fileInfo['image'],$fileInfo['image_b'],$fileInfo['image_m']));
		}
		
		$this->jsonOutput('成功',$this->getFormHash());
	}
	
	
	/**
	 * 验证规则
	 * 
	 * return void
	 */
	private function _getWorkerRules(){
	
		$this->form_validation->set_rules('worker_name','工作人员姓名','required');
		$this->form_validation->set_rules('worker_mobile','工作人员电话','required|valid_mobile');
	}


}




