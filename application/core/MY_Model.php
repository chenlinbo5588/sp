<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 自定义Model  
 */

class MY_Model extends CI_Model {
	
	public $_tablePre = 'sp_';
	public $_tableRealName = '';
	

    public function __construct(){
        parent::__construct();
        
        $this->_tableRealName = $this->getTableRealName();
    }
    
    /**
     * 获得表实体
     * @return type 
     */
    protected function getTableMeta(){
        return $this->load->entity($this->_tableRealName);
    }
    
    /**
     * 获得表真实名称
     * @return type 
     */
    public function getTableRealName(){
    	return $this->_tablePre.$this->_tableName;
    }
    
    public function sumByCondition($condition){
        
        $this->db->select_sum($condition['field']);
        $this->db->where($condition['where']);
        $query =  $this->db->get($this->_tableRealName);
        
        $data = $query->result_array();
        return $data;
    }
    
    public function getCount($condition = array()){
        if($condition['where']){
            $this->db->where($condition['where']);
        }
        
        return $this->db->count_all_results($this->_tableRealName);
    }
    
    public function getFirstByKey($id,$key = 'id'){
        $query = $this->db->get_where($this->_tableRealName,array($key => $id));
        $data = $query->result_array();
        if($data[0]){
            return $data[0];
        }else{
            return false;
        }
    }
    
    public function getById($condition){
        if($condition['where']){
            
            if($condition['select']){
                $this->db->select($condition['select']);
            }
            
            $this->db->where($condition['where']);
            $query = $this->db->get($this->_tableRealName);
            $data = $query->result_array();
            
            if($data[0]){
                return $data[0];
            }
        }
        
        return false;
    }
    
    
    protected function _setCondition($condition){
    	if($condition['where']){
            $this->db->where($condition['where']);
        }
        
        if($condition['where_in']){
            foreach($condition['where_in'] as $val){
                $this->db->where_in($val['key'],$val['value']);
            }
        }
        
        if($condition['where_not_in']){
            foreach($condition['where_not_in'] as $val){
                $this->db->where_not_in($val['key'],$val['value']);
            }
        }
    }
    
    public function deleteByCondition($condition){
    	if(empty($condition)){
    		return 0;
    	}
    	
    	$this->_setCondition($condition);
    	$this->db->delete($this->_tableRealName);
        return $this->db->affected_rows();
    	
    }
    
    
    public function deleteByWhere($where){
        $this->db->delete($this->_tableRealName,$where);
        return $this->db->affected_rows();
    }
    
    public function updateByWhere($data,$where){
        $this->db->update($this->_tableRealName,$data,$where);
        return $this->db->affected_rows();
    }
    
    public function batchInsert($data){
        return $this->db->insert_batch($this->_tableRealName, $data); 
    }
    
    public function batchUpdate($data,$key = 'id'){
        return $this->db->update_batch($this->_tableRealName, $data,$key); 
    }
    
    /**
     * 添加
     */
    public function _add($param){
    	$now = time();
        $fields = $this->_metaData();

        $data = array();
        
        foreach($param as $key => $value){
        	if(in_array($key,$fields)){
        		$data[$key] = $value;
        	}
        }
        
        foreach(array('gmt_create','gmt_modify') as $value){
            if(in_array($value,$fields)){
                $data[$value] = $now;
            }
        }
        
    	$this->db->insert($this->_tableRealName, $data);
        
        return $this->db->insert_id();

    }
    
    /**
     * 更新
     */
    public function update($param, $where){
        $fields = $this->_metaData();
        
        $now = time();
        
        foreach($param as $key => $value){
        	if(in_array($key,$fields)){
        		$data[$key] = $value;
        	}
        }
        
        if(in_array('gmt_modify',$fields)){
            $data['gmt_modify'] = $now;
        }
        
        $this->db->update($this->_tableRealName, $param, $where);
        return $this->db->affected_rows();
    }
    
    
    public function getMaxByWhere($field,$where = array()){
        if($where){
            $this->db->where($where);
        }
        
        $this->db->select_max($field);
        $query = $this->db->get($this->_tableRealName);
        
        $data = $query->result_array();
        
        return $data[0][$field];
    }
    
    
    public function getList($condition = array()){
        
        $data = array();
        
        if($condition['select']){
            $this->db->select($condition['select']);
        }
        
        if($condition['like']){
            $this->db->like($condition['like']);
        }
        
        if($condition['where']){
            $this->db->where($condition['where']);
        }
        
        if($condition['where_in']){
            foreach($condition['where_in'] as $val){
                $this->db->where_in($val['key'],$val['value']);
            }
        }
        
        if($condition['where_not_in']){
            foreach($condition['where_not_in'] as $val){
                $this->db->where_not_in($val['key'],$val['value']);
            }
        }
        
        if($condition['group_by']){
            $this->db->group_by($condition['group_by']); 
        }
        
        if($condition['order']){
            $this->db->order_by($condition['order']);
        }
        
        if($condition['pager']){
            $query = $this->db->get($this->_tableRealName,$condition['pager']['page_size'],($condition['pager']['current_page'] - 1) * $condition['pager']['page_size']);
        }else{
            if($condition['limit']){
                if(is_array($condition['limit'])){
                    $this->db->limit($condition['limit'][0],$condition['limit'][1]);
                }else{
                    $this->db->limit($condition['limit']);
                }
            }
            
            $query = $this->db->get($this->_tableRealName);
        }
        
        
        /**
         * 先获得数据 
         */
        if($condition['pager']){
        	$data['data'] = $query->result_array();
        	
            if($condition['where']){
                $this->db->where($condition['where']);
            }
            
            if($condition['where_in']){
                foreach($condition['where_in'] as $val){
                    $this->db->where_in($val['key'],$val['value']);
                }
            }
            
            if($condition['like']){
                $this->db->like($condition['like']);
            }
            
            $config['total_rows'] = $this->db->count_all_results($this->_tableRealName);
            $pager = pageArrayGenerator($_GET['page'],$condition['pager']['page_size'],$config['total_rows'],$condition['pager']['query_param']);
            $data['pager'] = $pager['pager'];
        }else{
        	$data = $query->result_array();
        }
        
        return $data;

    }
}