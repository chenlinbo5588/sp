<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    
    public function reset_validation_withrules()
	{
		$this->_field_data = array();
		//$this->_config_rules = array();
		$this->_error_array = array();
		$this->_error_messages = array();
		$this->error_string = '';
		return $this;
	}
	
	
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

	
    public function numeric_dash($str)
	{
        return ( ! preg_match("/^([-0-9_-])+$/i", $str)) ? FALSE : TRUE;
	}
	
	

}
// END Form Validation Class

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/TZ_Form_validation.php */
