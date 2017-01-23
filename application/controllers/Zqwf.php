<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zqwf extends Ydzj_Controller {

	private $_resident;

    public function __construct(){
        parent::__construct();
        $this->load->model(array('Zqwf_Model','Scd_Model'));
        
        
        $this->_resident = array(
        	'东埠头村' => array('330282104204','0574-63772418'),
        	'五姓点村' => array('330282104202','0574-63755708'),
        	'任佳溪村' => array('330282104208','0574-63759689'),
        	'厉家村' => array('330282104205','0574-63779820'),
        	'古窑浦村' => array('330282104215','0574-63663628'),
        	'叶家村' => array('330282104206','0574-63742010'),
        	'周家段村' => array('330282104210','0574-63756505'),
        	'巴里村' => array('330282104203','0574-63773503'),
        	'戎家村' => array('330282104207','0574-63750996'),
        	'柴家村' => array('330282104212','0574-63747913'),
        	'洪魏村' => array('330282104211','0574-63752110'),
        	'裘家村' => array('330282104213','0574-63757076'),
        	'长溪村' => array('330282104201','0574-63758807'),
        	'陈家村' => array('330282104209','0574-63740139'),
        	'鹤凤村' => array('330282104214','0574-63754136'),
        
        );
    }
    
    
    
    private function _prepareBuildInfo($huzhu){
    	$building['bh'] = $huzhu['bh'];
    	$building['year'] = 0;
    	$building['year2'] = 2000;
		$building['jzw_plies'] = -1;
		$building['td_mj'] = '';
		$building['jz_mj'] = '';
		$building['room_num']  = '';
		$building['jzw_jg'] = -1;
		
		
    	//有土地权证 或者有审批时间
		if(!empty($huzhu['land_no']) || !empty($huzhu['sp_new']) || !empty($huzhu['sp_ycyj'])){
			$year = 0;
			$year2 = 0;
			
			if(!empty($huzhu['sp_new'])){
				$year = intval(trim($huzhu['sp_new']));
			}
			
			if(!empty($huzhu['sp_ycyj'])){
				$year = intval(trim($huzhu['sp_ycyj']));
			}
			
			//最近一次有政府补助的修缮加固年份
			//$building['year2'] = $year;
			
			if($year != 0){
				if($year <= 1949){
					$building['year'] = 1;
				}else if($year >= 1950 && $year <= 1959){
					$building['year'] = 2;
				}else if($year >= 1960 && $year <= 1969){
					$building['year'] = 3;
				}else if($year >= 1970 && $year <= 1979){
					$building['year'] = 4;
				}else if($year >= 1980 && $year <= 1989){
					$building['year'] = 5;
				}else if($year >= 1990 && $year <= 1999){
					$building['year'] = 6;
				}else if($year >= 2000 && $year <= 2009){
					$building['year'] = 7;
					$building['year2'] = 2010;
					
				}else if($year >= 2010 && $year <= 2019){
					$building['year'] = 8;
					$building['year2'] = $year + 1;
					
				}
			}
			
			
			$building['jzw_plies'] = intval($huzhu['jzw_plies']);
			
			//现状建筑物面积  宅基地面积
			$building['td_mj'] = floatval(trim($huzhu['jzw_ydmj']));
			
			$building['jzw_jzzdmj'] = floatval(trim($huzhu['jzw_jzzdmj']));
			
			//用于计算间数
			if(!empty($huzhu['sp_ydmj'])){
				$spmj = floatval(trim($huzhu['sp_ydmj']));
				if($building['jzw_jzzdmj'] >= $spmj){
					$building['jz_mj'] = $building['jzw_plies'] * $spmj;
				}else{
					$building['jz_mj'] = $building['jzw_plies'] * $building['jzw_jzzdmj'];
				}
			}else{
				$building['jz_mj'] = $building['jzw_plies'] * $building['jzw_jzzdmj'];
			}
			
		
			if($building['jzw_jzzdmj'] >= 250){
				$building['room_num']  = intval(floor($building['jzw_jzzdmj'] / 50));
			}else if($building['jzw_jzzdmj'] >=200 && $building['jzw_jzzdmj'] < 250){
				$building['room_num'] = 5;
			}else if($building['jzw_jzzdmj'] >=150 && $building['jzw_jzzdmj'] < 200){
				$building['room_num'] = 4;
			}else if($building['jzw_jzzdmj'] >=90 && $building['jzw_jzzdmj'] < 150){
				$building['room_num'] = 3;
			}else if($building['jzw_jzzdmj'] >=50 && $building['jzw_jzzdmj'] < 90){
				$building['room_num'] = 2;
			}else{
				$building['room_num'] = 1;
			}
			
			/*
			$baseInfo = array(
				'茅草房' => 1,
				'泥草房' => 2,
				'土窑' => 3,
				'土坯、夯土房（无立柱）' => 4,
				'砖、石等简易砌体结构（无砖柱、构造柱，无圈梁等）' => 5,
				'竹木、木结构' => 6,
				'砖木、石木、土木结构（木框架）' => 7,
				'砖混结构（有砖柱或构造柱，有圈梁等）' => 8,
				'钢筋混凝土结构' => 9,
				'轻钢结构' => 10,
				'其它结构' => 11,
			);
			*/
			
			$huzhu['jzw_jg'] = trim($huzhu['jzw_jg']);
			
			
			if(in_array($huzhu['jzw_jg'],array('钢','钢棚'))){
				$building['jzw_jg'] = 10;
			}else if(in_array($huzhu['jzw_jg'],array('混','混合','砖','砖混','混砖','砖混、其他','混合、其他'))){
				$building['jzw_jg'] = 8;
			}else if(in_array($huzhu['jzw_jg'],array('砖木','砖砖木','混砖木','砖木混','砖混木','砖木1','砖简'))){
				$building['jzw_jg'] = 7;
			}else if(in_array($huzhu['jzw_jg'],array('木混','混砖木','木砖','混木','砖木混','砖混木','砖木1','砖简'))){
				$building['jzw_jg'] = 7;
			}else if(in_array($huzhu['jzw_jg'],array('木','混砖木','混木'))){
				$building['jzw_jg'] = 6;
			}else if(in_array($huzhu['jzw_jg'],array('棚','破','简'))){
				$building['jzw_jg'] = 11;
			}
			
			return $building;
			
		}else{
			
			return false;
		}
    	
    	
    	
    }
    
    
    private function _cleanValue($value){
    	$value = preg_replace( '/[\x00-\x1F]/','',$value);
    	return trim(sbc_to_dbc(str_replace(array("\r\n","\n","\r",'  ','　',' '),'',$value)));
    }
    
    
    public function index(){
    	$callbackFn = $this->input->get('jsoncallback');
    	$bh = $this->input->get('bh');
    	
    	
    	//header("Content-Type:application/json; charset=utf-8");
    	header("Content-Type: text/html; charset=utf-8");
    	
    	$huzhu = $this->Zqwf_Model->getById(array(
    		'where' => array('bh' => $bh),
    		'order' => 'id ASC'
    	));
    	
    	$jsonData = array(
    		'resident' => '-1',
    		'group' => '',
    		'name' => '',
    		'id_card' => '239798535629514103',
    		'tel' => '35629514103',
    		'people_num' => '',
    		'building' => array()
    	);
    	
    	if($huzhu){
    		
    		//原名称  如  罗国君（小）
    		$yuanName = $huzhu['name2'];
    		foreach($huzhu as $hzKey => $hzValue){
    			$huzhu[$hzKey] = $this->_cleanValue($hzValue);
    		}
    		
    		$realName = $huzhu['name2'];
    		if(strpos($realName,'(') !== false){
    			$realName = substr($realName,0,strpos($realName,'('));
    		}
    		
    		
    		//找到一宅
    		$jsonData = array_merge($jsonData, array(
    			'resident' => $this->_resident[$huzhu['village']][0],
    			'name' => $realName,
    			'tel' => $this->_resident[$huzhu['village']][1],
    			'people_num' => empty($huzhu['people_num']) ? 1 : intval($huzhu['people_num']),
    		));
    		
    		if(!empty($huzhu['id_card'])){
    			$jsonData['id_card'] = $huzhu['id_card'];
    		}
    		
    		//找生产队
    		$scdInfo = $this->Scd_Model->getById(array(
    			'where' => array(
    				'name' => $realName,
    				'village' => $huzhu['village'],
    			),
    			'order' => 'id ASC'
    		));
    		
    		//print_r($scdInfo);
    		
    		if($scdInfo){
    			$jsonData['group'] = $scdInfo['scd'];
    			if(!empty($scdInfo['people'])){
    				$jsonData['people_num'] = $scdInfo['people'];
    			}
    		}
    		
    		$buildingInfo = $this->_prepareBuildInfo($huzhu);
    		if($buildingInfo){
    			$jsonData['building'][] = $buildingInfo;
    		}
    		
    		// 继续找是否多宅
    		$list = $this->Zqwf_Model->getList(array(
    			'where' => array(
    				'name2' => $yuanName,
    				'village' => $huzhu['village'],
    				'bh != ' => $huzhu['bh']
    			),
    			'order' => 'id ASC'
    		));
    		
    		//print_r($list);
    		
    		if($list){
    			foreach($list as $oneBuild){
    				$tempBuild = $this->_prepareBuildInfo($oneBuild);
    				if($tempBuild){
    					$jsonData['building'][] = $tempBuild;
    				}
    			}
    		}
    	}
    	
    	$json = json_encode($jsonData);
    	
    	echo "{$callbackFn}({$json})";
    	
    }
    
    
    /*
    public function index(){
    	$callbackFn = $this->input->get('jsoncallback');
    	$bh = $this->input->get('bh');
    	
    	
    	//header("Content-Type:application/json; charset=utf-8");
    	header("Content-Type: text/html; charset=utf-8");
    	
    	$list = $this->Zqwf_Model->getList(array(
    		'select' => 'bh,name',
    		'order' => 'id ASC'
    	));
    	
    	
    	foreach($list['data'] as $ming){
    		$ming['name'] = str_replace(array("\r\n","\r","\n"),"",trim($ming['name']));
    		
    		$fields = explode('-',$ming['name']);
    		
    		echo $fields[0].'<br/>';
    		
    		//echo "UPDATE tb_zqwf set name2 = '{$fields[0]}' where id = '{$ming['id']}'; <br/>";
    		
    	}
    	
    	
    	
    	//echo "{$callbackFn}()";
    	
    }
    
    */
}

/* End of file printer.php */
/* Location: ./application/controllers/printer.php */