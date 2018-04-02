<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('NEED_CL','需测量');


class Dataimport extends Ydzj_Controller {

	private $_resident;
	private $_fileName = 'kdstnc.xlsx';

    public function __construct(){
        parent::__construct();
        
        $this->load->model(array('Person_Model', 'House_Model','House_Images_Model','House_Detail_Model'));
        
    }
    
    
    private function _cleanValue($value){
    	$value = preg_replace( '/[\x00-\x1F]/','',$value);
    	return trim(sbc_to_dbc(str_replace(array("\r\n","\n","\r",'  ','　',' '),'',$value)));
    }
    
    
    private function _prepareExcel(){
    	$this->load->file(PHPExcel_PATH.'PHPExcel.php');

        //$this->load->library('image_lib');
        
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
        $cacheSettings = array( 'dir'  => ROOTPATH.'/temp' );
        PHPExcel_Settings::setLocale('zh_CN');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    }
    
    
    public function import_person(){
    	
    	header("Content-Type: text/html;charset=utf8");
    	$this->_prepareExcel();
    	
        $objPHPexcel =  PHPExcel_IOFactory::load(ROOTPATH.'/'.$this->_fileName);
        $objPHPexcel->setActiveSheetIndex(0);
    	
    	$objWorksheet = $objPHPexcel->getActiveSheet(0); 
    	$highestRow = $objWorksheet->getHighestRow();
    	
    	$startRow = 0;
		$readAddOn = 5;	
		
    	//导入人
		for($rowIndex = ($startRow + $readAddOn); $rowIndex <= $highestRow; $rowIndex++){
    		
    		$personItem = array(
    			'town' => '坎墩街道',
    			'village_id' => 268,
    			'village' => '四塘南村',
    			'refno' => $this->_cleanValue($objWorksheet->getCell('B'.$rowIndex)->getValue()),
    			'qlr_name' => $this->_cleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue()),
    			'id_no' => $this->_cleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue()),
    			'id_type' => 1,
				'sex' => 0,
    			'isdie' => 0,
    			'address' => $this->_cleanValue($objWorksheet->getCell('F'.$rowIndex)->getValue()),
    			'housecnt' => 1,
    			'family_num' => 1,
    			'ip' => $this->input->ip_address(),
    			'gmt_create' => $this->_reqtime,
    			'gmt_modify' => $this->_reqtime,
    		);
    		
    		$familyNum = $this->_cleanValue($objWorksheet->getCell('E'.$rowIndex)->getValue());
    		
    		if(strpos($familyNum,'已故') !== false){
    			$personItem['isdie'] = 1;
    		}else{
    			$personItem['family_num'] = intval($familyNum);
    		}
    		
    		if($personItem['id_no']){
    			$sexFlag = substr($personItem['id_no'],-2,1);
    			if($sexFlag % 2  == 0){
    				$personItem['sex'] = 2;
    			}else if($sexFlag % 2  != 0){
    				$personItem['sex'] = 1;
    			}
    		}
    		
    		echo '<div>'.$this->db->insert_string($this->Person_Model->getTableRealName(),$personItem).';</div>';
		}
		
		
		
    }
    
    
    public function index(){
    	
    	
    	header("Content-Type: text/html;charset=utf8");

		$subPart = $this->input->get('part');
        
        if(empty($subPart)){
        	$subPart = 'house';
        }
        
        $this->_prepareExcel();
        
        
        // 人
        $personList = $this->Person_Model->getList(array(),'refno');
        
        // 房子
        $houseList = $this->House_Model->getList(array(),'refno');
        
        $objPHPexcel =  PHPExcel_IOFactory::load(ROOTPATH.'/'.$this->_fileName);
        $objPHPexcel->setActiveSheetIndex(0);
    	
    	$objWorksheet = $objPHPexcel->getActiveSheet(0); 
    	$highestRow = $objWorksheet->getHighestRow();
    	
    	$startRow = 0;
		$readAddOn = 5;	
		
		
    	for($rowIndex = ($startRow + $readAddOn); $rowIndex <= $highestRow; $rowIndex++){
    		
    		$houseItem = array(
    			'refno' => $this->_cleanValue($objWorksheet->getCell('A'.$rowIndex)->getValue()),
    			'owner_name' => $this->_cleanValue($objWorksheet->getCell('C'.$rowIndex)->getValue()),
    			'id_no' => $this->_cleanValue($objWorksheet->getCell('D'.$rowIndex)->getValue()),
    			'address' => $this->_cleanValue($objWorksheet->getCell('F'.$rowIndex)->getValue()),
    			'zddh' => $this->_cleanValue($objWorksheet->getCell('G'.$rowIndex)->getValue()),
    			'land_no' => $this->_cleanValue($objWorksheet->getCell('H'.$rowIndex)->getValue()),
    			'pw_no' => $this->_cleanValue($objWorksheet->getCell('I'.$rowIndex)->getValue()),
    			'land_oa' => 1,
    			
    			'sp_jz' => $this->_cleanValue($objWorksheet->getCell('J'.$rowIndex)->getValue()),
    			'sp_ydmj' => floatval($this->_cleanValue($objWorksheet->getCell('K'.$rowIndex)->getValue())),
    			'sp_jzzdmj' => floatval($this->_cleanValue($objWorksheet->getCell('L'.$rowIndex)->getValue())),
    			'sp_jzmj' => floatval($this->_cleanValue($objWorksheet->getCell('M'.$rowIndex)->getValue())),
    			
    			'jzw_ydmj' => floatval(str_replace(NEED_CL,0,$this->_cleanValue($objWorksheet->getCell('O'.$rowIndex)->getValue()))),
    			'jzw_jzzdmj' => floatval(str_replace(NEED_CL,0,$this->_cleanValue($objWorksheet->getCell('AN'.$rowIndex)->getValue()))),
    			'jzw_jzmj' => floatval(str_replace(NEED_CL,0,$this->_cleanValue($objWorksheet->getCell('AO'.$rowIndex)->getValue()))),
    			
    			'remark' => '主体建筑建造时间'.$this->_cleanValue($objWorksheet->getCell('AP'.$rowIndex)->getValue()).','.$this->_cleanValue($objWorksheet->getCell('BK'.$rowIndex)->getValue()),
    		);
    		
    		
    		
    		$jzInfoFields = array(
    			'主建筑物' => array(
    				'建筑占地面积' => 'p',
    				'房屋结构' => 'Q',
    				'建筑信息' => 'R',
    				'建筑面积' => 'S',
    			),
    			'附属建筑物1' => array(
    				'建筑占地面积' => 'T',
    				'房屋结构' => 'U',
    				'建筑信息' => 'V',
    				'建筑面积' => 'W',
    			),
    			'附属建筑物2' => array(
    				'建筑占地面积' => 'X',
    				'房屋结构' => 'Y',
    				'建筑信息' => 'Z',
    				'建筑面积' => 'AA',
    			),
    			'附属建筑物3' => array(
    				'建筑占地面积' => 'AB',
    				'房屋结构' => 'AC',
    				'建筑信息' => 'AD',
    				'建筑面积' => 'AE',
    			),
    			'附属建筑物4' => array(
    				'建筑占地面积' => 'AF',
    				'房屋结构' => 'AG',
    				'建筑信息' => 'AH',
    				'建筑面积' => 'AI',
    			),
    			'棚房' => array(
    				'建筑占地面积' => 'AJ',
    				'房屋结构' => 'AK',
    				'建筑信息' => 'AL',
    				'建筑面积' => 'AM',
    			)
    		);
    		
    		
    		//房屋信息
    		$jzInfoArray = array();
    		$fwCount = 0;
    		
    		foreach($jzInfoFields as $jzItemKey => $jzInfoItem){
    			$fwjg = $this->_cleanValue($objWorksheet->getCell($jzInfoItem['房屋结构'].$rowIndex)->getValue());
    			if($fwjg){
    				$fwCount++;
    				
    				$hasNeedCL = $this->_cleanValue($objWorksheet->getCell($jzInfoItem['建筑占地面积'].$rowIndex)->getValue());
    				
    				$tempHouseItem = array(
    					'owner_id' => $houseList[$houseItem['refno']]['owner_id'],
    					'house_id' => $houseList[$houseItem['refno']]['hid'],
    					'jzw_jzzdmj' => floatval(str_replace(NEED_CL,0,$hasNeedCL)),
    					'jzw_jg' => $fwjg,
    					'jzw_plies' => $this->_cleanValue($objWorksheet->getCell($jzInfoItem['建筑信息'].$rowIndex)->getValue()),
    					'jzw_jzmj' => floatval(str_replace(NEED_CL,0,$this->_cleanValue($objWorksheet->getCell($jzInfoItem['建筑面积'].$rowIndex)->getValue()))),
    				);
    				
    				if(strpos($hasNeedCL,NEED_CL) !== false){
    					$tempHouseItem['remark'] = NEED_CL;
    				}
    				
    				$jzInfoArray[] = $tempHouseItem;
    			}
    		}
    		
    		$fwJgCombine = array();
    		$fwJz = array();
    		
    		foreach($jzInfoArray as $jzItem){
    			$fwJgCombine[] = $jzItem['jzw_jg'];
    			$fwJz[] = $jzItem['jzw_plies'];
    		}
    		
    		$houseItem['jzw_jg'] = implode(',',$fwJgCombine);
    		$houseItem['jzw_plies'] = implode(',',$fwJz);
    		$houseItem['cate'] = 1;// 1 民宅类  2 公益类 3 经营类 4 农民公寓
    		
    		
    		
    		
    		$qwj = $this->_cleanValue($objWorksheet->getCell('AU'.$rowIndex)->getValue());
    		$qwj2 = $this->_cleanValue($objWorksheet->getCell('AV'.$rowIndex)->getValue());
    		$qwj3 = $this->_cleanValue($objWorksheet->getCell('AW'.$rowIndex)->getValue());
    		
    		
    		$houseItem['illegal'] = 0;
    		
    		if($qwj){
    			//全非法
    			$houseItem['illegal'] = 3;
    			$houseItem['wf_ydmj'] = $houseItem['jzw_ydmj'];
    			$houseItem['wf_jzzdmj'] = $houseItem['jzw_jzzdmj'];
    			$houseItem['wf_jzmj'] = $houseItem['jzw_jzmj'];
    			
    			$houseItem['deal_way'] = 4;
    			
    		}else if($qwj2){
    			//部分违建
    			$houseItem['illegal'] = 2;
    			
    			if($houseItem['jzw_ydmj'] >= $houseItem['sp_ydmj']){
    				$houseItem['wf_ydmj'] = $houseItem['jzw_ydmj'] - $houseItem['sp_ydmj'];
    			}
    			
    			if($houseItem['jzw_jzzdmj'] >= $houseItem['sp_jzzdmj']){
    				$houseItem['wf_jzzdmj'] = $houseItem['jzw_jzzdmj'] - $houseItem['sp_jzzdmj'];
    			}
    			
    			if($houseItem['jzw_jzmj'] >= $houseItem['sp_jzmj']){
    				$houseItem['wf_jzmj'] = $houseItem['jzw_jzmj'] - $houseItem['sp_jzmj'];
    			}
    			
    			$houseItem['deal_way'] = 4;
    			
    		}else if($qwj3){
    			$houseItem['illegal'] = 1;
    		}
    		
    		//print_r($houseItem);
    		
    		$match1 = preg_match('/(\d+)/i',$houseItem['land_no'],$match1Ar);
    		if($match1){
    			$houseItem['sp_new'] = $match1Ar[1].'-01-01';
    		}
    		
    		$match2 = preg_match('/(\d+)/i',$houseItem['pw_no'],$match2Ar);
    		if($match2){
    			$houseItem['sp_ycyj'] = $match2Ar[1].'-01-01';
    		}
    		
    		
    		if($personList[$houseItem['refno']]){
    			$houseItem['owner_id'] = $personList[$houseItem['refno']]['id'];
    			$houseItem['owner_name'] = $personList[$houseItem['refno']]['qlr_name'];
    			$houseItem['owner_village_id'] = $personList[$houseItem['refno']]['village_id'];
    			$houseItem['owner_village'] = $personList[$houseItem['refno']]['village'];
    			$houseItem['add_uid'] = $personList[$houseItem['refno']]['add_uid'];
    			$houseItem['edit_uid'] = $personList[$houseItem['refno']]['edit_uid'];
    			$houseItem['add_username'] = $personList[$houseItem['refno']]['add_username'];
    			$houseItem['edit_username'] = $personList[$houseItem['refno']]['edit_username'];
    			$houseItem['gmt_create'] = $personList[$houseItem['refno']]['gmt_create'];
    			$houseItem['gmt_modify'] = $personList[$houseItem['refno']]['gmt_modify'];
    		}
    		
    		$houseItem['photos'] = '[]';
    		
    		
    		if($subPart == 'house'){
    			//房屋信息
    			echo '<div>'.$this->db->insert_string($this->House_Model->getTableRealName(),$houseItem).';</div>';
    		}
    		
    		if($subPart == 'house_detail'){
	    		//房屋详情记录
	    		foreach($jzInfoArray as $fwInfo){
	    			$fwInfo['refno'] = $houseItem['refno'];
	    			echo '<div>'.$this->db->insert_string($this->House_Detail_Model->getTableRealName(),$fwInfo).';</div>';
	    		}
    		}
    		
    	}
    	
    	
    	
    }
    
    
    
    /**
     * 图片处理
     */
    public function imageJson(){
    	
    	
    	$destDir = ROOTPATH.'/static/attach/2017/08/03';
    	
    	$jsonArray = array();
        $houseList = $this->House_Model->getList(array(),'refno');
    	
    	//echo count($houseList);
    	$cp = array();
    	$cut = array();
    	
    	foreach (glob($destDir."/*",GLOB_ONLYDIR) as $filename) {
    		$dirname = basename($filename);
    		$refNo = $dirname;
    		
    		/*
    		if(preg_match('/^.*?_(.*)$/i',$dirname,$match)){
    			$refNo = $match[1];
    		}
    		*/
    		
    		
    		foreach (glob("{$filename}/*.JPG") as $imgFileName) {
    			$imgList = array();
    			
    			$imgList[] = str_replace(ROOTPATH.'/','',$imgFileName);
    			$imgList[] = str_replace(ROOTPATH.'/','',str_replace('.JPG','_b.JPG',$imgFileName));
    			$imgList[] = str_replace(ROOTPATH.'/','',str_replace('.JPG','_m.JPG',$imgFileName));
    			
    			if($imgList){
	    			$cut[] = "convert -resize 800x1200 {$imgList[0]} ".str_replace('.JPG','_b.JPG',"{$imgList[0]}");
		    		$cut[] = "convert -resize 300x400 {$imgList[0]} ".str_replace('.JPG','_m.JPG',"{$imgList[0]}");
	    		}
	    		
    			if($houseList['A'.$refNo]){
	    			$item = array(
		    			'person_id' => $houseList['A'.$refNo]['owner_id'],
		    			'house_id' => $houseList['A'.$refNo]['hid'],
		    			'image_url' => $imgList[0],
		    			'image_url_b' => $imgList[1],
		    			'image_url_m' => $imgList[2],
		    			'add_uid' =>  $houseList['A'.$refNo]['add_uid'],
		    			'add_username' =>  $houseList['A'.$refNo]['add_username'],
		    			'gmt_create' =>  $houseList['A'.$refNo]['gmt_create'],
		    			'gmt_modify' =>  $houseList['A'.$refNo]['gmt_modify']
		    		);
		    		
		    		echo '<div>'.$this->db->insert_string($this->House_Images_Model->getTableRealName(),$item).';</div>';
	    		}else{
	    			//echo '<div style="color:red;">'.$refNo.'</div>';
	    		}
    		}
    		//print_r($imgList);
		}
    	
    	file_put_contents("cut.txt",implode("\r\n",$cut));
    }
    
    
    /**
     * 房屋 图片信息更新
     */
    public function makeHouseImageJson(){
    	
    	set_time_limit(0);
    	$houseList = $this->House_Model->getList();
    	
    	foreach($houseList as $houseInfo){
    		$imageList = $this->House_Images_Model->getList(array(
				'select' => 'id,image_url,image_url_b,image_url_m',
				'where' => array(
					'house_id' => $houseInfo['hid']
				)
			));
			
			$affect = $this->House_Model->update(array('photos' => json_encode($imageList)),array('hid' => $houseInfo['hid']));
			
			if($affect){
				echo '<div>'.$houseInfo['refno'].'更新成功</div>';
			}else{
				
				echo '<div>'.$houseInfo['refno'].'更新失败</div>';
			}
    	}
    }
    
    
    /**
     * 房屋 详情信息关联
     */
    public function makeHouseDetail(){
    	
    	set_time_limit(0);
    	$houseList = $this->House_Model->getList(array(),'refno');
    	$houseDetailList = $this->House_Detail_Model->getList();
    	
    	
    	foreach($houseDetailList as $detailInfo){
    		
    		$affect = 0;
    		
    		if($houseList['A'.$detailInfo['refno']]){
    			$affect = $this->House_Detail_Model->update(array('owner_id' => $houseList['A'.$detailInfo['refno']]['owner_id'],'house_id'=>$houseList['A'.$detailInfo['refno']]['hid']),array('id' => $detailInfo['id']));
    		}
    		
			
			if($affect){
				echo '<div>更新成功</div>';
			}else{
				
				echo '<div>更新失败</div>';
			}
    	}
    }
    
    
}
