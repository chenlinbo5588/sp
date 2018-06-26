<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Worker_Images_Model extends MY_Model {
    
    public $_tableName = 'worker_images';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    /**
     * 根据文件ID 获得文件列表
     */
    public function getImageListByFileIds($pFileIds){
    	
    	$fileList = array();
    	
    	if(!is_array($pFileIds)){
    		$pFileIds = (array)$pFileIds;
    	}
    	
		
		if($pFileIds){
			$fileList = $this->getList(array(
				//'select' => 'id as image_aid,file_url',
				'where_in' => array(
					array('key' => 'image_aid', 'value' => $pFileIds)
				),
				'order' => 'id DESC'
			));
		}
		
		return $fileList;
		
    }
    
    /**
     * 根据工作人员的ID
     */
    public function getImagesListByWorkerId($pId){
    	
    	return $this->getList(array(
    		'where' => array(
    			'worker_id' => $pId
    		)
    	));
    	
    }
    

}