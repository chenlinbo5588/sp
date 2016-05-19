<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Attachment extends Ydzj_Admin_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Attachment_Model');
    }
    
    public function index()
    {
        die(0);
    }
    
    public function dir(){
        
    }
    
    public function upload(){
        die(0);
        $this->load->helper('directory');
        $this->load->helper('file');
        $this->load->helper('number');
        
        $uid = (int)gpc('uid','G',0);
        
        $keyname = 'Filedata';
        if(0 === $_FILES['imgFile']['error']){
            $keyname = 'imgFile';
		}
        //file_put_contents("text.txt", print_r($_FILES,true));
        $attachment = array(
            'filename'  => $_FILES[$keyname]['name'],
            'filesize'  => $_FILES[$keyname]['size'],
            'type'		=> $_FILES[$keyname]["type"],
        );
        
        $suffix = substr($attachment['filename'],strrpos($attachment['filename'],'.'));
        $suffix = strtolower($suffix);

        $imgTypes = array(
            "image/jpeg",
            "image/pjpeg",
            "image/png",
            "image/x-png",
            "image/gif",
            "image/bmp"
        );

        $isImage = 0;
        if(in_array($_FILES[$keyname]['type'],$imgTypes) || in_array($suffix,array('.jpg','.jpeg','.png','.gif','.bmp'))){
            $isImage = 1;
        }
        
        $width = true == empty($_POST['width']) ? 960 : intval($_POST['width']);
        $height = true == empty($_POST['height']) ? 540 : intval($_POST['height']);
        if(1 == $isImage){
            list($width, $height, $type, $attr) = getimagesize($_FILES[$keyname]['tmp_name']);
        }
        
        $urlPath = config_item('filestore_path');
        $filePath = realpath(dirname(APPPATH)).$urlPath;
        
        $fileDir = date("Y/m/d");
        
        make_dir($filePath.'/'.$fileDir.'/');
        $file_md5 = md5($attachment['filename'].$attachment['filesize'].mt_rand(100, 999));
        $newFilePath = $fileDir.'/'.$file_md5.$suffix;
        
        $now = time();
        $file_key = md5($attachment['filename']);
        $file_description = '';
        
        
        $data = array(
            'file_name' => $attachment['filename'],
            'file_key' => $file_key,
            'file_extension' => $suffix,
            'is_image' => $isImage,
            'file_mime' => $attachment['type'],
            'file_description' => $file_description ? $file_description : '',
            'file_store_path' => $fileDir.'/',
            'file_real_name' => $attachment['filename'],
            'file_md5' => $file_md5 ? $file_md5 : '',
            'file_size' => $attachment['filesize'],
            'thumb_size' => 0,
            'expire_time' => empty($_GET['expire_time']) ? 0 : ($now + intval($_GET['expire_time'])),
            'user_id' => $uid,
            'createtime' => $now,
            'updatetime' => $now,
            'ip' => get_ip()
        );
      	$data['file_url'] = $data['file_store_path'].$data['file_md5'].$data['file_extension'];
      	$previewUrl = base_url(substr($urlPath,1).$data['file_url']);
      	//file_put_contents("aaaa.txt",$previewUrl);
        try {
            
            $fileId = $this->Attachment_Model->add($data);
            
            $retAry = array('error' => 1,'id' => $fileId, "message" => '上传成功','width' => $width,'height'=> $height, 'url' => $previewUrl,'title' => htmlspecialchars($attachment['filename']));
                
            if(!$fileId){
                $retAry['message'] = '数据库错误';
            }else{
                $retAry['id'] = $fileId;
                if(move_uploaded_file($_FILES[$keyname]['tmp_name'],$filePath.$newFilePath)){
                	if(!empty($_GET['thumb']) && in_array($_GET['thumbsize'],array('small','middle'))) {
                		$thumb_config = config_item('pic_thumb');
                		
            			$config['image_library'] = 'gd2';
						$config['source_image'] = $filePath.$newFilePath;
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = TRUE;
						
						$config['width'] = $thumb_config[$_GET['thumbsize']][0];
						$config['height'] = $thumb_config[$_GET['thumbsize']][1];
						
						$this->load->library('image_lib', $config); 
						$this->image_lib->resize();
	                	
                	}
                	
                    $retAry['error'] = 0;
                }
            }
        }catch(Exception $e){
            $retAry = array('error' => 1,"message" => '数据库错误,'.$e->getCode());
        }
        
        @unlink($_FILES[$keyname]['tmp_name']);
        if('Filedata' == $keyname){
            $this->sendJson($retAry);
        }else{
            header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
            header('Content-type: text/html; charset=UTF-8');
            echo json_encode(array('error' => 0,'id' => $fileId, 'url' => $previewUrl ,'title' => htmlspecialchars($attachment['filename']) ));
        }
    }
}
