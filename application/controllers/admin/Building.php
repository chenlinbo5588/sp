<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Building extends Ydzj_Admin_Controller {
	
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->library('arcgis/FeatureRest');
		$this->load->config('arcgis_server');
	}
	
	
	public function index(){
		$this->display();
	}
	
	
	/**
	 * 验证账户重复性
	 */
	public function check(){
		
		$urlParam = $this->uri->uri_to_assoc();
		//print_r($urlParam);
		$this->load->library('Member_Service');
		
		$flag = "false";
		switch($urlParam['check']){
			case 'mobile':
				$mobile = $this->input->get('member_mobile');
				$info = $this->member_service->getUserInfoByMobile($mobile);
				
				if(empty($info)){
					$flag = "true";
				}
				break;
			case 'nickname':
				$nickname =  $this->input->get('member_nickname');
				$info = $this->member_service->getUserInfoByMobile($nickname,'nickname');
				$id = $this->input->get('member_id');
				
				if($info){
					if($id == $info['uid']){
						//自己
						$flag = "true";
					}
				}else{
					$flag = "true";
				}
				
				break;
			default:
				break;
		}
		
		echo $flag;
		
		
	}
	
	
	private function _fieldAttr(){
		
		$field = array(
			'x' => array('title' => 'X坐标' , 'tip' => '(请点击地图)', 'required' => true , 'rule' => 'required|numeric' , 'readonly' => true),
			'y' => array('title' => 'Y坐标' , 'required' => true , 'rule' => 'required|numeric' ,'readonly' => true),
			'bh' => array('title' => '编号' , 'required' => true , 'rule' => 'required' ),
			/*
			'cate1' => array('title' => '档案分类' , 'required' => true , 'rule' => 'required',
				'type' => 'select' , 'dataSource' => array('' => '请选择' , '一宅一档' => '一宅一档' ,'一幢一档' => '一幢一档' )
			),*/
			'cate' => array('title' => '使用分类' , 'required' => false , 'rule' => 'required' ,
				'type' => 'select' , 'dataSource' => array('' => '请选择' , '民宅类' => '民宅类' ,'公益类' => '公益类' ,'经营类' => '经营类','农民公寓' => '农民公寓')
			),
			'village' => array('title' => '村名' , 'required' => true , 'rule' => 'required|max_length[30]'),
			'jddw' => array('title' => '建档单位' , 'required' => true , 'rule' => 'required|max_length[50]'),
			'name' => array('title' => '户主名称' , 'required' => true , 'rule' => 'required|max_length[255]'),
			'owner' => array('title' => '权力人名称' , 'required' => false , 'rule' => 'required|max_length[255]'),
			'id_card' => array('title' => '身份证号码' , 'required' => false , 'rule' => 'required|min_length[15]|max_length[18]'),
			'people_num' => array('title' => '家庭在册人数' , 'required' => false , 'rule' => 'required'),
			'address' => array('title' => '地址' , 'required' => true , 'rule' => 'required|max_length[80]'),
			'land_no' => array('title' => '土地使用权证号' , 'required' => false , 'rule' => 'max_length[50]'),
			'zddh' => array('title' => '宗地地号' , 'required' => false , 'rule' => 'max_length[50]'),
			'land_oa' => array('title' => '土地权属性质' , 'required' => false , 'rule' => 'max_length[20]'),
			'jzw_ydmj' => array('title' => '用地面积' , 'required' => false , 'rule' => 'numeric|greater_than_equal_to[0]'),
			'jzw_jzzdmj' => array('title' => '建筑占地面积' , 'required' => false , 'rule' => 'numeric|greater_than_equal_to[0]'),
			'jzw_jzmj' => array('title' => '建筑面积' , 'required' => false , 'rule' => 'numeric|greater_than_equal_to[0]'),
			'jzw_jg' => array('title' => '房屋结构' , 'required' => false , 'rule' => 'max_length[20]'),
			'jzw_plies' => array('title' => '建筑层数' , 'required' => false , 'rule' => 'is_natural_no_zero'),
			'jzw_unit' => array('title' => '建筑单元数' , 'required' => false , 'rule' => 'is_natural_no_zero'),
			'sp_new' => array('title' => '审批时间(新建)' , 'required' => false , 'rule' => 'max_length[30]'),
			'sp_ycyj' => array('title' => '审批时间(原拆原建)' , 'required' => false , 'rule' => 'max_length[30]'),
			'sp_ydmj' => array('title' => '批准用地面积' , 'required' => false , 'rule' => 'numeric|greater_than_equal_to[0]'),
			'sp_jzmj' => array('title' => '批准建筑面积' , 'required' => false , 'rule' => 'numeric|greater_than_equal_to[0]'),
			'illegal_de' => array('title' => '违法现象' , 'required' => false , 'rule' => '',
						'type' => 'select' , 'dataSource' => array('请选择' => '请选择' , '全部违建' => '全部违建' ,'部分违建' => '部分违建' , '全部合法' => '全部合法','其他' => '其他')
			),
			'is_yhdz' => array('title' => '一户多宅' , 'required' => false , 'rule' => '',
						'type' => 'select' , 'dataSource' => array('是' => '是' , '否' => '否' )
			),
			'wf_wjsj' => array('title' => '违建时间' , 'required' => false , 'rule' => 'max_length[30]'),
			'wf_wjmj' => array('title' => '违建面积' , 'required' => false , 'rule' => 'numeric|greater_than_equal_to[0]'),
			'land_cate' => array('title' => '土地类别' , 'required' => false , 'rule' => 'max_length[30]'),
			'purpose' => array('title' => '用途' , 'required' => false , 'rule' => 'max_length[50]'),
			'deal_way' => array('title' => '分类处置意见' , 'required' => false , 'rule' => '' , 
								 'type' => 'select' , 'dataSource' => array('请选择' => '请选择' , '暂缓' => '暂缓' ,'补办' => '补办' , '没收' => '没收' , '拆除' => '拆除')
			),
			'remark' => array('title' => '备注' , 'required' => false , 'rule' => 'max_length[200]' ),
		);
		
		return $field;
	}
	
	
	
	/**
	 * 验证规则
	 */
	private function _addRules(){
		
		$data = array();
		$fields = $this->_fieldAttr();
		
		foreach($fields as $key => $f){
			
			if($f['required']){
				$this->form_validation->set_rules($key,$f['title'],$f['rule']);
				$data[$key] = trim($this->input->post($key));
			}else{
				$temp = trim($this->input->post($key));
				if(!empty($temp)){
					
					if($f['rule'] == ''){
						if($f['dataSource']){
							$this->form_validation->set_rules($key,$f['title'],'in_list['.implode(',',array_keys($f['dataSource'])).']');
						}
					}else{
						$this->form_validation->set_rules($key,$f['title'],$f['rule']);
					}
					
					$data[$key] = $temp;
				}
			}
		}
		
		return $data;
		
		
	}
	
	
	/**
	 * 后台创建调查点
	 */
	public function add(){
		
		
		//echo config_item('arcgis_server');
		
		if($this->isPostRequest()){
			//print_r($_POST);
			$addParam = $this->_addRules();
			for($i = 0 ; $i < 1; $i++){
				if(!$this->form_validation->run()){
					break;
				}
				
				$geometry = array('x' => $addParam['x'] ,'y' => $addParam['y']);
				unset($addParam['x'],$addParam['y']);
				
				//一个点
				$param = array(
					array(
						"geometry" => $geometry,  
						"attributes" => $addParam
					)
				);
			
				//print_r($addParam);
				$wz = config_item('feature_url');
				$this->featurerest->setUrl(config_item('arcgis_server'),$wz['wzd']);
				
				//print_r($param);
				
				$result = $this->featurerest->addFeatures($param);
				
				
				if($result['addResults'][0]['success'] == 'true'){
					$this->assign('feedback','<div class="tip_success">添加成功</div>');
				}else{
					
					if($result){
						$this->assign('feedback','<div class="tip_error">'.$result['addResults'][0]['error']['message'].'</div>');
					}else{
						$this->assign('feedback','<div class="tip_error">系统没有响应，请重新保存</div>');
					}
					
					
				}
			}
		}
		
		$this->assign('fields',$this->_fieldAttr());
		$this->display();
		
		
	}
	
	
	public function photo(){
		$this->load->library('Attachment_Service');
		
		$fileData = $this->attachment_service->addImageAttachment('_pic',array(
			'without_db' => true
		),0,'');
		
		//file_put_contents('debug.txt',print_r($fileData,true));
		
		if($fileData){
			/*
			$cutImage = $this->attachment_service->resize($fileData['file_url'],
				array('m' => array('width' => 255,'height' => 255,'maintain_ratio' => true,'quality' => 100)
			));
			
			*/
			
			$wz = config_item('feature_url');
			$this->featurerest->setUrl(config_item('arcgis_server'),$wz['wzd']);
			
			$updateParam['OBJECTID'] = intval($this->input->post('id'));
			$origPhotos = trim($this->input->post('photos'));
			
			if($origPhotos){
				$origPhotos .= ",".$fileData['file_url'];
			}else{
				$origPhotos = $fileData['file_url'];
			}
			
			$param = array(
				array(
					"attributes" => array(
						'OBJECTID' => $updateParam['OBJECTID'],
						'photos' => $origPhotos
					)
				)
			);

			$result = $this->featurerest->updateFeatures($param);
			
			if($result['updateResults'][0]['success'] != 'true'){
				$json['status'] = 0;
				$json['msg'] = '图片数据更新失败';
			}else{
				$json['status'] = 1;
				$json['path'] = $fileData['file_url'];
				$json['msg'] = "上传成功";
			}
			
		}else{
			$json['status'] = 0;
			$json['msg'] = '参数错误';
		}
		
		exit(json_encode($json));
		
		
		
	}
	
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$id = $this->input->get_post('id');
		$this->assign('id',$id);
		
		$wz = config_item('feature_url');
		$this->featurerest->setUrl(config_item('arcgis_server'),$wz['wzd']);
		
		if(!empty($id) && $this->isPostRequest()){
			$updateParam = $this->_addRules();
			
			$this->form_validation->set_error_delimiters('','');
			
			for($i = 0 ; $i < 1; $i++){
				if(!$this->form_validation->run()){
					$this->responseJSON($this->form_validation->error_string('',''),array());
					break;
				}
				
				$geometry = array('x' => $updateParam['x'] ,'y' => $updateParam['y']);
				//unset($updateParam['x'],$updateParam['y']);
				
				//一个点, 要转成整数 比较重要
				$updateParam['OBJECTID'] = intval($id);
				$param = array(
					array(
						"geometry" => $geometry,
						"attributes" => $updateParam
					)
				);

				$result = $this->featurerest->updateFeatures($param);
				
				if($result['updateResults'][0]['success'] == 'true'){
					$this->responseJSON('保存成功',array());
				}else{
					$this->responseJSON('保存失败',array());
				}
			}
			
		}else{
			$info = $this->featurerest->query(array(
				'objectIds' => $id
			));
			
			
			if($info['features']){
				$info['features'][0]['attributes'] = array_merge($info['features'][0]['attributes'],$info['features'][0]['geometry']);

				/*
				foreach($info['features'][0]['attributes'] as $key => $value){
					$info['features'][0]['attributes'][$key] = trim($value);
				}
				*/
			}
			
			$this->assign('info',$info['features'][0]);
			$photos = trim($info['features'][0]['attributes']['photos']);
			//file_put_contents("query.txt",print_r($photos,true),FILE_APPEND);
			$photoUrl = array();
			if($photos){
				$tempUrl = explode(",",$photos);
				foreach($tempUrl as $url){
					if(strpos($url,'static/attach') !== false){
						$photoUrl[] = base_url($url);
					}else{
						$photoUrl[] = base_url('static/IMAGE_255x255/'.$url);
					}
					
				}
			}
			
			$this->assign('photos',$photoUrl);
			$this->assign('fields',$this->_fieldAttr());
			$this->display('building/edit');
		}
		
		
	}
	
	
	
	/**
	 * 裁剪用户头像
	 */
	public function pic_cut(){
		if($this->isPostRequest()){
			$src_file = str_ireplace(base_url(),'',$this->input->post('url'));
			
			$this->load->library('Attachment_Service');
			
			$fileData = $this->attachment_service->resize(array('file_url' => $src_file) , 
				array('middle' => array('width' => $this->input->post('w'),'height' => $this->input->post('h'),'maintain_ratio' => false , 'quality' => 100)) , 
				array('x_axis' => $this->input->post('x1'), 'y_axis' => $this->input->post('y1')));
			
			
			
			if($fileData['img_middle']){
				$smallImg = $this->attachment_service->resize(array(
					'file_url' => $fileData['img_middle']
				) , array('small') );
			}
			
			//删除原图
			unlink($fileData['full_path']);

			if (empty($fileData['img_middle'])){
				exit(json_encode(array(
					'status' => 0, 
					'formhash'=>$this->security->get_csrf_hash(),
					'msg'=>$this->attachment_service->getErrorMsg('','')
				)));
			}else{
				exit(json_encode(array(
					'status' => 1, 
					'formhash'=>$this->security->get_csrf_hash(),
					'url'=>base_url($fileData['img_middle']),
					'picname' => $src_file
				)));
			}
			
		}else{
			$save_file = str_ireplace(base_url(),'',$this->input->get('url'));
			
			list($width, $height, $type, $attr) =  getimagesize(ROOTPATH.'/'.$save_file);
			
			$this->assign('image_width',$width);
			$this->assign('image_height',$height);
			$this->display('member/pic_cut');
		}
		
	}
	
}
