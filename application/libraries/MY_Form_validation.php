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
     * 验证日期 、日期时间
     */
    public function valid_datetime($dataStr){
    	$ts = strtotime($dataStr);
    	
    	if($ts){
    		return true;
    	}else{
    		return false;
    	}
    	
    }
    
    
    /**
     * 校验身份证号码
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
	
	


	public function error_html($prefix = '', $suffix = ''){
		return str_replace("\n",'',$this->error_string($prefix,$suffix));
	}
	
	
	/**
	 * 得到第一个错误
	 */
	public function error_first(){
		$errorList = $this->error_array();
		$keyArray = array_keys($errorList);
		
		if($keyArray){
			return array('key' => $keyArray[0],'message' => $errorList[$keyArray[0]]);
		}else{
			return false;
		}
	}
	
	/**
	 * 获得第一个错误
	 */
	public function error_first_html($prefix = '', $suffix = ''){
		$errorFirst = $this->error_first();
		
		return $this->error($errorFirst['key'],$prefix,$suffix);
	}
	
}
// END Form Validation Class

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/TZ_Form_validation.php */
