<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Ydzj_Controller {
	
	public function __construct(){
		parent::__construct();

	}
	
	
	/**
	 * 图片上传
	 *
	 */
	public function pic_upload(){
		$this->load->library('Attachment_Service');
		$this->attachment_service->setUid($this->_profile['basic']['uid']);
		$fileData = $this->attachment_service->addImageAttachment('_pic');
		
		//$Orientation[$exif[IFD0][Orientation]];
		//$exif = exif_read_data($fileData['file_url'],0,true);
		if($fileData){
			
			$size = $this->input->post('size');
			
			if(empty($size) || !$this->attachment_service->isAllowedSize($size)){
				$size = 'big';
			}
			
			$fileData = $this->attachment_service->resize($fileData , array($size));
			//删除原上传文件
			unlink($fileData['full_path']);
			if($this->input->post('id')){
				$type = $this->input->post('type');
				$delSize = array();
				
				switch($type){
					case 'member':
						$delSize = array('big');
						break;
					default:
						break;
				}
				
				$this->attachment_service->deleteFiles(array($this->input->post('id')) , $delSize);
			}
			
			exit(json_encode(array('status'=>1,'formhash'=>$this->security->get_csrf_hash(),'id' => $fileData['id'], 'url'=>base_url($fileData['img_big']))));
		}else{
			exit(json_encode(array('status'=>0,'formhash'=>$this->security->get_csrf_hash(),'msg'=>$this->attachment_service->getErrorMsg('',''))));
		}
	}
	
	
	/**
	 * 图片裁剪
	 *
	 */
	public function pic_cut(){
		
		$this->load->helper(array('img'));
		
		if($this->isPostRequest()){
			
			/*
			$thumb_width = $_POST['x'];
			$x1 = $_POST["x1"];
			$y1 = $_POST["y1"];
			$x2 = $_POST["x2"];
			$y2 = $_POST["y2"];
			$w = $_POST["w"];
			$h = $_POST["h"];
			$scale = $thumb_width/$w;
			*/
			
			$src_file = str_ireplace(base_url(),'',$this->input->post('url'));

			$this->load->library('image_lib');
			
			
			$index = strrpos($src_file,'/');
			$prefix = substr($src_file, 0 , $index + 1);

			$config['source_image'] = ROOTPATH.'/'.$src_file;
			$pathinfo = pathinfo($config['source_image']);
			
			
			$config['new_image'] = $pathinfo['dirname'].'/c_'.$pathinfo['basename'];
			$config['x_axis'] = $this->input->post('x1');
			$config['y_axis'] = $this->input->post('y1');
			$config['width'] = $this->input->post('w');
			$config['height'] = $this->input->post('h');
			$config['quality'] = 100;
			$config['maintain_ratio'] = false;
			
			$this->image_lib->initialize($config);
			
			
			//@todo 需要完善 , 裁切 200x200 100x100 各一套 , attachment 表增加 上传 uid 来源区分,是后台用户还是前台用户 uid
			
			if ( ! $this->image_lib->crop()){
				log_message('error',$config['source_image'] .' crop failed.');
				exit(json_encode(array('status' => 0, 'formhash'=>$this->security->get_csrf_hash(),'msg'=>$this->attachment_service->getErrorMsg('',''))));
			}else{
				exit(json_encode(array(
					'status' => 1, 'formhash'=>$this->security->get_csrf_hash(),
					'url'=>base_url($prefix . 'c_'.$pathinfo['basename']),
					'picname' => 'c_'.$pathinfo['basename']
				)));
			}
			
		}else{
			$save_file = str_ireplace(base_url(),'',$this->input->get('url'));
			
			list($width, $height, $type, $attr) =  getimagesize(ROOTPATH.'/'.$save_file);
			
			$this->assign('image_width',$width);
			$this->assign('image_height',$height);
			$this->display('common/pic_cut');
		}
		
	}
	
}
