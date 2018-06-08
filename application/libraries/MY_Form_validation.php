<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    
    public function is_unique_by_status($str,$fields){
        list($table, $field,$key,$value)=explode('.', $fields);
		$query = $this->CI->db->get_where($table, array($field => $str,$key => $value));
		
		return $query->num_rows() === 0;
    }
    
    
    public function in_db_list($str,$fields){
        list($table, $field)=explode('.', $fields);
		$query = $this->CI->db->get_where($table, array($field => $str));
		
		return $query->num_rows() === 1;
    }
    
    /**
     * sp_member.nickname.uid.182
     */
    public function is_unique_not_self($str,$fields){
        list($table, $field,$key,$value)=explode('.', $fields);
        
        $query = $this->CI->db->get_where($table, array($field => $str));
        $result = $query->result_array();
        
        /*
        print_r($field);
        var_dump($query);
        print_r($result);
        */

        if($query->num_rows() === 0){
            return true;
        }
        
        if($query->num_rows() == 1 && $value == $result[0][$key]){
            return true;
        }else{
            return false;
        }
        
    }
    
    public function valid_password($str){
    	if(preg_match('/^[a-zA-Z0-9~!@#$%^&*()\\\|\\\\-_=+{}\[\];:"\'<,.>?\/]+$/u',$str)){
    		return true;
    	}else{
    		
    		return false;
    	}
    }
    
    
    public function valid_username($str){
    	if(!preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_@]+$/u',$str)){
    		return false;
    	}
    	
    	return true;
    }
    
    public function valid_mobile($mobile){
        if(preg_match("/^(\+?86)?1[0-9][0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$mobile)){   
            return true;
        }else{
            return false;
        }
    }
    
    public function valid_starthttp($val){
        if(preg_match("/^https?:\/\//",$val)){   
            return true;
        }else{
            return false;
        }
    }
    
    
    
    public function valid_telephone($telno){
       if(preg_match("/^(((\d{3}))|(\d{3}-))?((0\d{2,3})|0\d{2,3}-)?[1-9]\d{6,8}$/",$telno)){
           return true;
       }else{
           return false;
       }
    }
    
    
    public function valid_date($datestr,$format = ''){
        //echo $datestr;
        //echo $format;
        
        if(!$format){
            $format = 'yyyy-mm-dd';
        }
        
        switch($format){
            case 'yyyy-mm-dd':
                $info = explode('-',$datestr);
                
                if(count($info) < 3){
                    return false;
                }
                
                return checkdate($info[1],$info[2],$info[0]);
                break;
            default:
                break;
            
        }
        
        return true;
        
    }
    
    
    /**
     * 校验身份证号码
     */
     
    /*
    public function valid_idcard( $id ) { 
		  $id = strtoupper($id); 
		  $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; 
		  $arr_split = array(); 
		  if(!preg_match($regx, $id)) 
		  { 
		    return FALSE; 
		  } 
		  if(15==strlen($id)) //检查15位 
		  { 
		    $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; 
		  
		    @preg_match($regx, $id, $arr_split); 
		    //检查生日日期是否正确 
		    $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
		    if(!strtotime($dtm_birth)) 
		    { 
		      return FALSE; 
		    } else { 
		      return TRUE; 
		    } 
		  } 
		  else      //检查18位 
		  { 
		    $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; 
		    @preg_match($regx, $id, $arr_split); 
		    $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; 
		    if(!strtotime($dtm_birth)) //检查生日日期是否正确 
		    { 
		      return FALSE; 
		    } 
		    else
		    { 
		      //检验18位身份证的校验码是否正确。 
		      //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。 
		      $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
		      $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
		      $sign = 0; 
		      for ( $i = 0; $i < 17; $i++ ) 
		      { 
		        $b = (int) $id{$i}; 
		        $w = $arr_int[$i]; 
		        $sign += $b * $w; 
		      } 
		      $n = $sign % 11; 
		      $val_num = $arr_ch[$n]; 
		      if ($val_num != substr($id,17, 1)) 
		      { 
		        return FALSE; 
		      } //phpfensi.com 
		      else
		      { 
		        return TRUE; 
		      } 
		    } 
		  } 
	  
	}
    */
    
    
    public function valid_idcard($id_card){
	    if(strlen($id_card)==18){
	        return idcard_checksum18($id_card);
	    }elseif((strlen($id_card)==15)){
	        $id_card=idcard_15to18($id_card);
	        return $this->idcard_checksum18($id_card);
	    }else{
	        return false;
	    }
	}
	
	

	
    public function numeric_dash($str)
	{
        return ( ! preg_match("/^([-0-9_-])+$/i", $str)) ? FALSE : TRUE;
	}
	
	

}
// END Form Validation Class

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/TZ_Form_validation.php */
