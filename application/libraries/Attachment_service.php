<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment_Service extends Base_Service {

	protected $_uid = 0;
	
	private $_attachModel ;
	private $_imageSizeConfig ;
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Attachment_Model');
		self::$CI->load->library('image_lib');
		
		$this->_attachModel = self::$CI->Attachment_Model;
		$this->_imageSizeConfig = $this->getImageSizeConfig();
	}
	
	/*
	 * 
	 */
	public function isAllowedSize($size){
		return isset($this->_imageSizeConfig[$size]) ? true : false;
	}
	
	/**
	 * 获得图片上传配置
	 */
	public function getImageConfig(){
		
		$config['file_path'] = 'static/attach/'.date("Y/m/d/");
        $config['upload_path'] = ROOTPATH . '/'.$config['file_path'];
        
        make_dir($config['upload_path']);
        
		$config['allowed_types'] = 'jpg';
		$config['file_ext_tolower'] = true;
		$config['encrypt_name'] = true;
		$config['max_size'] = 4096;
		
		return $config;
	}
	
	
	/**
	 * 获得图片尺寸配置参数
	 */
	private function getImageSizeConfig(){
		return array(
			'large' => array('width' => 1200,'height' => 900, 'maintain_ratio' => true,'quality' => 90),
			'big' => array('width' => 400,'height' => 300 , 'maintain_ratio' => true,'quality' => 90),
			'middle' => array('width' => 150,'height' => 150,'maintain_ratio' => true,'quality' => 90),
			'small' => array('width' => 100,'height' => 100,'maintain_ratio' => true,'quality' => 100)
		);
	}
	
	
	/**
	 * 设置用户id
	 */
	public function setUid($uid){
		$this->_uid = intval($uid);
	}
	
	
	/**
	 * 根据路径删除文件
	 */
	public function deleteByFileUrl($files){
		if(is_array($files)){
			foreach($files as $del){
				@unlink(ROOTPATH.DIRECTORY_SEPARATOR.$del);
			}
		}else{
			@unlink(ROOTPATH.DIRECTORY_SEPARATOR.$files);
		}
		
	}
	
	/**
	 * @param array $files id列表
	 * @param array $size array('big','middle')
	 * @param array $fromBg  0 = 前台用户上传的  1 = 后台用户上传的
	 */
	public function deleteFiles($files , $size = array() , $fromBg = 0){
		if(is_array($files)){
			$list = $this->_attachModel->getList(array(
				'select' => 'file_url',
				'where' => array('uid' => $this->_uid > 0 ?  $this->_uid : -1 , 'from_bg' => $fromBg),
				'where_in' => array(
					array('key' => 'id','value' => $files)
				)
			));
			
		}else{
			$list = $this->_attachModel->getList(array(
				'select' => 'file_url',
				'where' => array('uid' => $this->_uid > 0 ?  $this->_uid : -1 , 'from_bg' => $fromBg ,  'id' => $files)
			));
		}
		
		foreach($list as $file){
			//删除缩放的其他图片
			if($size == 'all'){
				$size = array_keys($this->_imageSizeConfig);
			}
			
			$delList = getImgPathArray($file['file_url'] , $size);
			//file_put_contents("deleteFiles.txt",print_r($delList,true));
			foreach($delList as $del){
				@unlink(ROOTPATH.DIRECTORY_SEPARATOR.$del);
			}
		}
	}
	
	/**
	 * 根据实际需要采裁切图片
	 */
	public function resize($fileData , $resizeConfig , $axis = array() ){
		
		if(!$fileData['full_path']){
			$fileData['full_path'] = ROOTPATH .DIRECTORY_SEPARATOR. $fileData['file_url'];
		}
		
		if(!$fileData['file_ext']){
			$fileData['file_ext'] = substr($fileData['file_url'] , strrpos($fileData['file_url'],'.'));
		}
		
		if(!$fileData['raw_name']){
			$fileData['raw_name'] = substr($fileData['file_url'] , strrpos($fileData['file_url'],'/') + 1 , 32);
		}
		
		$resize['source_image'] = $fileData['full_path'];
		$resize['create_thumb'] = false;
		
		
		// 'big' =
		//$resizeConfig = array('big');
		// 用于覆盖默认配置
		//$resizeConfig = array('big' => array('maintain_ratio' => false));
		foreach($resizeConfig as $resizeIndex => $overideConfig){
			$isOk = false;
			$resizeName = '';
			if(is_array($overideConfig)){
				$resizeName = $resizeIndex;
				$currentConfig = array_merge($this->_imageSizeConfig[$resizeName],$overideConfig);
			}else{
				$resizeName = $overideConfig;
				$currentConfig = $this->_imageSizeConfig[$resizeName];
			}
			
			//file_put_contents("currentResizeConfig.txt",print_r($currentConfig,true));
			$fileData['img_'.$resizeName] = '';
			$resize['maintain_ratio'] = $currentConfig['maintain_ratio'];
			$resize['new_image'] = $fileData['raw_name'].'@'.$resizeName.$fileData['file_ext'];
			$resize['width']         = $currentConfig['width'];
			$resize['height']       = $currentConfig['height'];
			$resize['quality']      = $currentConfig['quality'];
			
			$resize = array_merge($resize , $axis);
			
			self::$CI->image_lib->initialize($resize);
			
			if(!empty($axis)){
				$action = 'crop';
				$isOk = self::$CI->image_lib->crop();
			}else{
				$action = 'resize';
				$isOk = self::$CI->image_lib->resize();
			}
			
			if(!$isOk){
				log_message('error',$resize['new_image'] ." {$action} failed.");
			}else{
				$fileData['img_'.$resizeName] = substr($fileData['file_url'] , 0 , strrpos($fileData['file_url'], '/') + 1).$resize['new_image'];
			}
		}
		
		return $fileData;
	}
	
	
	/**
	 * 添加图片附件信息
	 * 
	 */
	public function addImageAttachment($filename, $moreConfig = array(),$from = 0){
		
		//处理照片
		$config = $this->getImageConfig();
		
		if(!empty($moreConfig)){
			$config = array_merge($config,$moreConfig);
		}
		
		//print_r($config);
		self::$CI->load->library('upload', $config);
		if(self::$CI->upload->do_upload($filename)){
			$fileData = self::$CI->upload->data();
			//print_r($fileData);
			
			$fileData['file_url'] = $config['file_path'].$fileData['file_name'];
			$fileData['ip'] = self::$CI->input->ip_address();
			
			if($this->_uid){
				$fileData['uid'] = $this->_uid;
			}
			
			
			$fileData['image_width'] = $fileData['image_width'] ? $fileData['image_width'] : 0;
			$fileData['image_height'] = $fileData['image_height'] ? $fileData['image_height'] : 0;
			
			
			$fileData['from_bg'] = $from;
			$file_id = self::$CI->Attachment_Model->_add($fileData);
			$fileData['id'] = $file_id;
			
			//print_r($fileData);
			return $fileData;
		}else{
			log_message('info',$this->getErrorMsg());
			return false;
		}
		
	}
	
	/**
	 *  显示错误信息
	 */
	public function getErrorMsg($open = '<div class="form_error">',$close = '</div>'){
		return self::$CI->upload->display_errors($open,$close);
	}
	
	
	
	
	/**
	 * 前后台上传图片公告逻辑
	 * 
	 * @param datatype $uid 操作人
	 * @param datatype $uploadName 上传名
	 * @param datatype $fromBg description
	 */
	public function pic_upload($uid,$uploadName , $fromBg = 0){
		
		$this->setUid($uid);
		$fileData = $this->addImageAttachment($uploadName,array(),$fromBg);
		
		//$Orientation[$exif[IFD0][Orientation]];
		//$exif = exif_read_data($fileData['file_url'],0,true);
		if($fileData){
			
			//上传多次情况下，清理上一次上传的文件
			if(self::$CI->input->post('id')){
				$this->deleteFiles(array(self::$CI->input->post('id')),'all');
			}
			
			return array('error' => 0, 'status'=>1,'formhash'=>self::$CI->security->get_csrf_hash(),'id' => $fileData['id'], 'url'=>base_url($fileData['file_url']));
		}else{
			return array('error' => 1, 'status'=>0,'formhash'=>self::$CI->security->get_csrf_hash(),'msg'=>$this->getErrorMsg('',''));
		}
	}
	
	
}
