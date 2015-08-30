<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment_Service extends Base_Service {

	protected $_uid = 0;
	
	private $_attachModel ;

	public function __construct(){
		parent::__construct();
		
		$this->CI->load->model('Attachment_Model');
		$this->CI->load->helper('img');
		
		$this->_attachModel = $this->CI->Attachment_Model;
	}
	
	
	/**
	 * 获得图片上传配置
	 */
	public function getImageConfig(){
		
		$config['file_path'] = 'static/attach/'.date("Y/m/d/");
        $config['upload_path'] = ROOTPATH . '/'.$config['file_path'];
        
        make_dir($config['upload_path']);
        
		$config['allowed_types'] = 'jpg|jpeg';
		$config['file_ext_tolower'] = true;
		$config['encrypt_name'] = true;
		
		return $config;
	}
	
	
	/**
	 * 图片裁剪配置
	 * 
	 * 队伍图片
	 */
	public function getImageResizeConfig(){
		return array(
			'large' => array('width' => 800,'height' => 800, 'maintain_ratio' => true),
			'big' => array('width' => 400,'height' => 400 , 'maintain_ratio' => false),
			'middle' => array('width' => 200,'height' => 200,'maintain_ratio' => false),
			'small' => array('width' => 100,'height' => 100,'maintain_ratio' => false)
		);
	}
	
	/**
	 * 设置用户id
	 */
	public function setUid($uid){
		$this->_uid = $uid;
	}
	
	
	public function deleteFiles($files){
		
		if($files){
			$list = $this->_attachModel->getList(array(
				'select' => 'file_url',
				'where' => array('uid' => $this->_uid > 0 ?  $this->_uid : -1),
				'where_in' => array(
					array('key' => 'id','value' => $files)
				)
			));
			
			foreach($list as $file){
				$delList = getImgPathArray($file['file_url']);
				foreach($delList as $del){
					@unlink(ROOTPATH.DIRECTORY_SEPARATOR.$del);
				}
			}
		}
		
		
	}
	
	
	/**
	 * 添加图片附件信息
	 */
	public function addImageAttachment($filename, $moreConfig = array(),$uid = 0){
		
		//处理照片
		$config = $this->getImageConfig();
		
		if(!empty($moreConfig)){
			$config = array_merge($config,$moreConfig);
		}
		
		//print_r($config);
		
		$this->CI->load->library('upload', $config);
		if($this->CI->upload->do_upload($filename)){
			$fileData = $this->CI->upload->data();
			//print_r($fileData);
			
			$fileData['file_url'] = $config['file_path'].$fileData['file_name'];
			$fileData['ip'] = $this->CI->input->ip_address();
			
			if($this->_uid){
				$fileData['uid'] = $this->_uid;
			}
			
			
			$file_id = $this->CI->Attachment_Model->_add($fileData);
			$fileData['id'] = $file_id;
			
			/**
			 * 裁剪图片
			 */
			$smallConfig = $this->getImageResizeConfig();
			foreach($smallConfig as $whKey => $whConfig){
				$fileData['img_'.$whKey] = '';
				
				$resize['image_library'] = 'gd2';
				$resize['source_image'] = $fileData['full_path'];
				$resize['create_thumb'] = false;
				$resize['maintain_ratio'] = $whConfig['maintain_ratio'];
				$resize['new_image'] = $fileData['raw_name'].'@'.$whKey.$fileData['file_ext'];
				$resize['width']         = $whConfig['width'];
				$resize['height']       = $whConfig['height'];
				
				$this->CI->image_lib->initialize($resize);
				if(!@$this->CI->image_lib->resize()){
					log_message('error',$resize['new_image'] .' resize failed.');
				}else{
					$fileData['img_'.$whKey] = $config['file_path'].$resize['new_image'];
				}
			}
			
			//print_r($fileData);
			return $fileData;
		}else{
			
			//echo $this->CI->upload->display_errors();
			return false;
		}
		
	}
	
	/**
	 *  显示错误信息
	 */
	public function getErrorMsg($open = '<div class="form_error">',$close = '</div>'){
		return $this->CI->upload->display_errors($open,$close);
	}
	
}
