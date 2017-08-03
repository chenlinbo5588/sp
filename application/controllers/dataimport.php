<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('NEED_CL','需测量');


class Dataimport extends Ydzj_Controller {

	private $_resident;

    public function __construct(){
        parent::__construct();
        
        $this->load->model(array('Person_Model', 'House_Model','House_Images_Model','House_Detail_Model'));
        
    }
    
    
    private function _cleanValue($value){
    	$value = preg_replace( '/[\x00-\x1F]/','',$value);
    	return trim(sbc_to_dbc(str_replace(array("\r\n","\n","\r",'  ','　',' '),'',$value)));
    }
    
    
    public function index(){
    	
    	
    	header("Content-Type: text/html;charset=utf8");

        
        
        
        $personList = $this->Person_Model->getList(array(),'refno');
        
        $this->load->file(PHPExcel_PATH.'PHPExcel.php');
        

        //$this->load->library('image_lib');
        
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM; 
        $cacheSettings = array( 'dir'  => ROOTPATH.'/temp' );
        PHPExcel_Settings::setLocale('zh_CN');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        
        $objPHPexcel =  PHPExcel_IOFactory::load(ROOTPATH.'/house.xls');
        $objPHPexcel->setActiveSheetIndex(0);
    	
    	$objWorksheet = $objPHPexcel->getActiveSheet(0); 
    	$highestRow = $objWorksheet->getHighestRow();
    	
    	$startRow = 0;
		$readAddOn = 5;	
		
		/*
		echo '<table>';
		echo '<tr>';
		echo '<td>编号</td><td>户主(权利人)</td><td>身份证号</td><td>建筑物座落地址</td><td>宗地号</td><td>土地使用权证号</td><td>批文号</td>';
		echo '<td>建筑信息（间*层）</td><td>用地面积（发证面积）</td><td>建筑占地面积</td><td>建筑面积</td><td>土地权属性质</td>';
		
		
		echo '<td>用地面积</td><td>房屋结构</td><td>建筑物占地总面积</td><td>建筑总面积</td>';
		
		echo '</tr>';
		*/
		
    	for($rowIndex = ($startRow + $readAddOn); $rowIndex <= $highestRow; $rowIndex++){
    		
    		echo '<tr>';
    		
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
    			
    			'remark' => $this->_cleanValue($objWorksheet->getCell('BK'.$rowIndex)->getValue()),
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
    				$jzInfoArray[] = array(
    					'jzw_jzzdmj' => floatval(str_replace(NEED_CL,0,$this->_cleanValue($objWorksheet->getCell($jzInfoItem['建筑占地面积'].$rowIndex)->getValue()))),
    					'jzw_jg' => $fwjg,
    					'jzw_plies' => $this->_cleanValue($objWorksheet->getCell($jzInfoItem['建筑信息'].$rowIndex)->getValue()),
    					'jzw_jzmj' => floatval(str_replace(NEED_CL,0,$this->_cleanValue($objWorksheet->getCell($jzInfoItem['建筑面积'].$rowIndex)->getValue()))),
    				);
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
    		$houseItem['cate'] = 1;
    		
    		
    		
    		
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
    		
    		
    		//房屋信息
    		//echo '<div>'.$this->db->insert_string($this->House_Model->getTableRealName(),$houseItem).';</div>';
    		
    		//房屋详情
    		foreach($jzInfoArray as $fwInfo){
    			$fwInfo['refno'] = $houseItem['refno'];
    			echo '<div>'.$this->db->insert_string($this->House_Detail_Model->getTableRealName(),$fwInfo).';</div>';
    		}
    		
    		//echo '</tr>';
    	}
    	
    	
    	//echo '</table>';
    	
    }
    
    
    
    /**
     * 图片处理
     */
    public function imageJson(){
    	$txt = file(ROOTPATH.'/1.txt');
    	
    	$jsonArray = array();
    	
    	
        
        $houseList = $this->House_Model->getList(array(),'refno');
    	
    	$cp = array();
    	$cut = array();
    	
    	
    	foreach($txt as $line){
    		$line = $this->_cleanValue($line);
    		
    		$fields = explode('/',$line);
    		
    		if($houseList['A'.$fields[0]]){
    			$item = array(
	    			'person_id' => $houseList['A'.$fields[0]]['owner_id'],
	    			'house_id' => $houseList['A'.$fields[0]]['hid'],
	    			'image_url' => 'static/attach/2017/08/03/' .$fields[1],
	    			'image_url_b' => 'static/attach/2017/08/03/' .str_replace('.jpg','_b.jpg',$fields[1]),
	    			'image_url_m' => 'static/attach/2017/08/03/' .str_replace('.jpg','_m.jpg',$fields[1]),
	    		);
	    		
	    		$cp[] = "cp {$line} /f/code/CodeIgniter-3.0.0/static/attach/2017/08/03";
	    		$cut[] = "convert -resize 800x1200 {$fields[1]} ".str_replace('.jpg','_b.jpg',$fields[1]);
	    		$cut[] = "convert -resize 300x400 {$fields[1]} ".str_replace('.jpg','_m.jpg',$fields[1]);
	    		
	    		echo '<div>'.$this->db->insert_string($this->House_Images_Model->getTableRealName(),$item).';</div>';
    		}
    	}
    	
    	
    	file_put_contents("cp.txt",implode("\r\n",$cp));
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
