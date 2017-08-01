<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment_service extends Base_service {

	protected $_userInfo;
	private $_imageSizeConfig ;
	
	//private $_attachtHashObj = null;

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->library('image_lib');
		$this->_imageSizeConfig = config_item('default_img_size');
		
	}
	
	/**
	 * 获得 file hash object
	 
	public function getAttachmentHashObj(){
		if(!$this->_attachtHashObj){
			$this->_attachtHashObj = new Flexihash();
			$this->_attachtHashObj->addTargets(self::$CI->load->get_config('split_attachment'));
		}
		return $this->_attachtHashObj;
	}
	*/
	
	
	/**
	 * 设置操作用户
	 */
	public function setUserInfo($userProfile){
		$this->_userInfo = $userProfile;
	}
	
	
	/**
	 * 设置图片大小
	 */
	public function setImageSizeConfig($config){
		$this->_imageSizeConfig = $config;
	}
	
	/**
	 * 默认的上传设置
	 */
	public function getUploadConfig(){
		$config['file_path'] = 'static/attach/'.date("Y/m/d/");
        $config['upload_path'] = ROOTPATH . '/'.$config['file_path'];
        
        make_dir($config['upload_path']);
        $config['file_ext_tolower'] = true;
		$config['encrypt_name'] = true;
		$config['max_size'] = config_item('image_max_filesize');
		
		return $config;
	
	}
	
	
	/**
	 * 获得文件上传的路径
	 */
	public function getFileConfig(){
		
		$config['file_path'] = date("Y/m/d/");
        $config['upload_path'] = dirname(ROOTPATH) . '/filestore/'.$config['file_path'];
        
        make_dir($config['upload_path']);
        $config['file_ext_tolower'] = true;
		$config['encrypt_name'] = true;
		$config['max_size'] = config_item('image_max_filesize');
		
		return $config;
	}
	
	
	/**
	 * 获得附件信息
	 */
	public function getFileInfoByIds($pFileIds,$uid,$fileds = 'id,orig_name,file_size,is_image,file_url,uid,username,gmt_create'){
		
		return self::$attachmentModel->getList(array(
			'select' => $fileds,
			'where' => array(
				'uid' => $uid
			),
			'where_in' => array(
				array('key' => 'id','value' => $pFileIds)
			),
			'order' => 'id DESC'
		),'id');
		
	}
	
	
	/**
	 * 添加文件
	 */
	public function addFile($filename, $moreConfig = array(),$from = 0,$mod = ''){
		$config = $this->getFileConfig();
		
		if(FROM_BACKGROUND == $from){
			$config['allowed_types'] = str_replace(',','|',config_item('background_image_allow_ext'));
		}else{
			$config['allowed_types'] = str_replace(',','|',config_item('forground_image_allow_ext'));
		}
		
		
		if(!empty($moreConfig)){
			$config = array_merge($config,$moreConfig);
		}
		
		return $this->addAttachment($filename,$config,$from,$mod);
	}
	
	
	/**
	 * 根据路径删除文件
	 */
	public function deleteByFileUrl($files){
		if(is_array($files)){
			foreach($files as $del){
				$del = urlToPath($del);
				@unlink(ROOTPATH.DIRECTORY_SEPARATOR.$del);
			}
		}else{
			$files = urlToPath($files);
			@unlink(ROOTPATH.DIRECTORY_SEPARATOR.$files);
		}
		
	}
	
	
	/**
	 * @param array $files id列表
	 * @param array $size array('big','middle')
	 */
	public function deleteFiles($files , $size = array()){
		if(is_array($files)){
			$list = self::$attachmentModel->getList(array(
				'select' => 'file_url',
				'where_in' => array(
					array('key' => 'id','value' => $files)
				)
			));
			
		}else{
			$list = self::$attachmentModel->getList(array(
				'select' => 'file_url'
			));
		}
		
		foreach($list as $file){
			//删除缩放的其他图片
			if($size == 'all'){
				$size = array_keys($this->_imageSizeConfig);
			}
			
			$delList = getImgPathArray($file['file_url'] , $size);
			foreach($delList as $del){
				@unlink(ROOTPATH.DIRECTORY_SEPARATOR.$del);
			}
		}
	}
	
	
	
	/**
	 * $fileUrl      文件路径
	 * $resizeConfig 缩放配置
	 * $axis         裁切设置
	 * $assocKey     返回数组key前缀
	 * 
	 */
	public function resize($fileUrl , $resizeConfig , $axis = array(), $assocKey = 'img' ){
		
		//兼容处理
		$fileData['file_url'] = urlToPath($fileUrl);
		
		
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
				$fileData[$assocKey.'_'.$resizeName] = substr($fileData['file_url'] , 0 , strrpos($fileData['file_url'], '/') + 1).$resize['new_image'];
			}
		}
		
		return $fileData;
	}
	
	
	/**
	 * 
	 */
	public function addAttachment($filename, $config = array(),$from = 0,$mod = ''){
		self::$CI->load->library('upload', $config);
		if(self::$CI->upload->do_upload($filename)){
			$fileData = self::$CI->upload->data();
			//print_r($fileData);
			$fileData['file_url'] = $config['file_path'].$fileData['file_name'];
			$fileData['ip'] = self::$CI->input->ip_address();
			
			if($config['without_db']){
				return $fileData;
			}
			
			if($this->_userInfo){
				$fileData['uid'] = $this->_userInfo['uid'];
				$fileData['username'] = $this->_userInfo['username'];
				
				/*
				if($from == FROM_BACKGROUND){
					$fileData['username'] = $this->_userInfo['username'];
				}else{
					$fileData['username'] = $this->_userInfo['nickname'];
				}
				*/
			}
			
			
			$fileData['image_width'] = $fileData['image_width'] ? $fileData['image_width'] : 0;
			$fileData['image_height'] = $fileData['image_height'] ? $fileData['image_height'] : 0;
			$fileData['mod'] = $mod;
			$fileData['upload_from'] = $from;
			
			if($config['expire_time']){
				$fileData['expire_time'] = $config['expire_time'];
			}
			
			$file_id = self::$attachmentModel->_add($fileData);
			$fileData['id'] = $file_id;
			
			//print_r($fileData);
			return $fileData;
		}else{
			log_message('error',$this->getErrorMsg());
			return false;
		}
		
	}
	
	
	
	
	
	/**
	 * 添加图片附件信息
	 * 
	 */
	public function addImageAttachment($filename, $moreConfig = array(),$from = 0,$mod = ''){
		//处理照片
		$config = $this->getUploadConfig();
		
		if(FROM_BACKGROUND == $from){
			$config['allowed_types'] = str_replace(',','|',config_item('background_image_allow_ext'));
		}else{
			$config['allowed_types'] = str_replace(',','|',config_item('forground_image_allow_ext'));
		}
		
		if(!empty($moreConfig)){
			$config = array_merge($config,$moreConfig);
		}
		
		return $this->addAttachment($filename,$config,$from,$mod);
		
	}
	
	/**
	 *  显示错误信息
	 */
	public function getErrorMsg($open = '<div class="form_error">',$close = '</div>'){
		return self::$CI->upload->display_errors($open,$close);
	}
	
	
	/**
	 * 前后台上传图片公共逻辑
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
			return array('error' => 0, config_item('csrf_token_name') =>self::$CI->security->get_csrf_hash(),'id' => $fileData['id'], 'url'=>base_url($fileData['file_url']));
		}else{
			return array('error' => 1, config_item('csrf_token_name') =>self::$CI->security->get_csrf_hash(),'msg'=>$this->getErrorMsg('',''));
		}
	}
	
	
}
