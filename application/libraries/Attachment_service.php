<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment_service extends Base_service {

	protected $_uid = 0;
	protected $_userInfo;
	
	private $_attachModel ;
	private $_imageSizeConfig ;
	

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model('Attachment_Model');
		self::$CI->load->library('image_lib');
		
		$this->_attachModel = self::$CI->Attachment_Model;
		$this->_imageSizeConfig = config_item('default_img_size');
	}
	
	public function setImageSizeConfig($config){
		$this->_imageSizeConfig = $config;
	}
	
	/**
	 * 获得图片上传配置
	 */
	public function getImageUploadConfig(){
		
		$config['file_path'] = 'static/attach/'.date("Y/m/d/");
        $config['upload_path'] = ROOTPATH . '/'.$config['file_path'];
        
        make_dir($config['upload_path']);
        
		$config['allowed_types'] = config_item('allowed_img_types');
		$config['file_ext_tolower'] = true;
		$config['encrypt_name'] = true;
		$config['max_size'] = 4096;
		
		return $config;
	}
	
	
	/**
	 * 设置用户id ,废弃
	 */
	public function setUid($uid){
		$this->_uid = intval($uid);
	}
	
	public function setUserInfo($userProfile){
		$this->_userInfo = $userProfile;
	}
	
	
	/**
	 * 根据路径删除文件
	 */
	public function deleteByFileUrl($files){
		if(is_array($files)){
			foreach($files as $del){
				@unlink(ROOTPATH.DIRECTORY_SEPARATOR.str_replace(base_url(),'',$del));
			}
		}else{
			@unlink(ROOTPATH.DIRECTORY_SEPARATOR.str_replace(base_url(),'',$files));
		}
		
	}
	
	
	/**
	 * @param array $files id列表
	 * @param array $size array('big','middle')
	 */
	public function deleteFiles($files , $size = array()){
		if(is_array($files)){
			$list = $this->_attachModel->getList(array(
				'select' => 'file_url',
				'where_in' => array(
					array('key' => 'id','value' => $files)
				)
			));
			
		}else{
			$list = $this->_attachModel->getList(array(
				'select' => 'file_url'
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
			$resize['new_image'] = $fileData['raw_name'].'_'.$resizeName.$fileData['file_ext'];
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
	public function addImageAttachment($filename, $moreConfig = array(),$from = 0,$mod = ''){
		//处理照片
		$config = $this->getImageUploadConfig();
		
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
			
			if($this->_userInfo){
				$fileData['uid'] = $this->_userInfo['uid'];
				
				if($from == FROM_BACKGROUND){
					$fileData['username'] = $this->_userInfo['username'];
				}else{
					$fileData['username'] = $this->_userInfo['nickname'];
				}
			}
			
			
			$fileData['image_width'] = $fileData['image_width'] ? $fileData['image_width'] : 0;
			$fileData['image_height'] = $fileData['image_height'] ? $fileData['image_height'] : 0;
			$fileData['mod'] = $mod;
			
			$fileData['upload_from'] = $from;
			$file_id = self::$CI->Attachment_Model->_add($fileData);
			$fileData['id'] = $file_id;
			
			//print_r($fileData);
			return $fileData;
		}else{
			log_message('error',$this->getErrorMsg());
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
	public function pic_upload($uploadName ,$options = array(), $fromBg = 0,$mod = ''){
		$fileData = $this->addImageAttachment($uploadName,$options,$fromBg,$mod);
		//$Orientation[$exif[IFD0][Orientation]];
		//$exif = exif_read_data($fileData['file_url'],0,true);
		
		
		if($fileData){
			//上传多次情况下，清理上一次上传的文件
			/*
			 * 还没有解决，异步上上传后，放弃保存后， 原来的图变被删的问题, 暂时注释
			if(self::$CI->input->post('id')){
				$this->deleteFiles(array(self::$CI->input->post('id')),'all');
			}
			*/
			return array('error' => 0, 'formhash'=>self::$CI->security->get_csrf_hash(),'id' => $fileData['id'], 'url'=>base_url($fileData['file_url']));
		}else{
			return array('error' => 1, 'formhash'=>self::$CI->security->get_csrf_hash(),'msg'=>$this->getErrorMsg('',''));
		}
	}
	
	
}
