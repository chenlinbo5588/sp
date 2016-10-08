<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 自定义Model  
 */

class MY_Model extends CI_Model {
	
	private $_tablePre = 'sp_';
	//private $_tableRealName = '';
	private $_tableId = '';
	
	
	//用户自动记录操作人
	public static $operator;
	//数据来源  比如后台 , 手机网页版 等等
	public static $channel;
	
	
    public function __construct(){
        parent::__construct();
        
        //$this->_tableRealName = $this->getTableRealName();
    }
    
	
    /**
     * 获得表实体
     * @return type 
     */
    protected function getTableMeta(){
        return $this->load->entity("{$this->_tablePre}{$this->_tableName}");
    }
    
    /**
     * 设置分表id
     */
    public function setTableId($tableId){
    	$this->_tableId = $tableId;
    }
    
    /**
     * 获得分表id
     */
    public function getTableId(){
    	return $this->_tableId;
    }
    
    /**
     * 获得表真实名称
     * @return type 
     */
    public function getTableRealName(){
    	return "{$this->_tablePre}{$this->_tableName}{$this->_tableId}";
    }
    
    /**
     * 获得db 对象
     */
    public function getDb(){
    	return $this->db;
    }
    
    
    /**
     * statictis
     */
    public function sumByCondition($condition){
        
        $this->db->select_sum($condition['field']);
        $this->db->where($condition['where']);
        $query =  $this->db->get($this->getTableRealName());
        
        $data = $query->result_array();
        return $data;
    }
    
    public function getCount($condition = array()){
        if($condition['where']){
            $this->db->where($condition['where']);
        }
        
        return $this->db->count_all_results($this->getTableRealName());
    }
    
    public function isUnqiueByKey($value,$key){
    	$count = $this->getCount(array(
    		'where' => array(
    			$key => $value
    		)
    	));
    	
    	if($count > 0){
    		return false;
    	}else{
    		return true;
    	}
    	
    }
    
    
    
    public function getMaxByWhere($field,$where = array()){
        if($where){
            $this->db->where($where);
        }
        
        $this->db->select_max($field);
        $query = $this->db->get($this->getTableRealName());
        
        $data = $query->result_array();
        
        return $data[0][$field];
    }
    
    /**
     * 设置条件
     */
    protected function _setCondition($condition){
    	
    	/*
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
        
        if($condition['limit']){
        	$this->db->limit($condition['limit']);
        }
        */
        
        
        if($condition['select']){
            $this->db->select($condition['select']);
        }
        
        if($condition['like']){
            $this->db->like($condition['like']);
        }
        
        if($condition['where']){
            $this->db->where($condition['where']);
        }
        
        if($condition['or_where']){
            $this->db->or_where($condition['or_where']);
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
        
        
        if($condition['limit']){
            if(is_array($condition['limit'])){
                $this->db->limit($condition['limit'][0],$condition['limit'][1]);
            }else{
                $this->db->limit($condition['limit']);
            }
        }
    }
    
    
    /**
     * 删除
     */
    public function deleteByCondition($condition){
    	if(empty($condition)){
    		return 0;
    	}
    	
    	$this->_setCondition($condition);
    	$this->db->delete($this->getTableRealName());
        return $this->db->affected_rows();
    	
    }
    
    
    public function deleteByWhere($where){
        $this->db->delete($this->getTableRealName(),$where);
        return $this->db->affected_rows();
    }
    
    
    
    /**
     * 添加
     */
    public function _add($param,$replace = false){
    	$data = $this->_fieldsDecorator($param,'add');
        
        if(!$replace){
        	$this->db->insert($this->getTableRealName(), $data);
        	return $this->db->insert_id();
        }else{
        	$this->db->replace($this->getTableRealName(), $data);
        	return $this->db->affected_rows();
        }
    }
    
    
    /**
     * 字段保护
     */
    private function _fieldsDecorator($param, $action = 'add'){
    	$fields = $this->_metaData();
        
        $data = array();
        $now = time();
        
        foreach($param as $key => $value){
        	if(array_key_exists($key,$fields)){
        		$data[$key] = $value;
        	}
        }
        
        if($action == 'add'){
        	foreach(array('gmt_create','gmt_modify') as $value){
	            if(empty($data[$value]) && array_key_exists($value,$fields)){
	                $data[$value] = $now;
	            }
	        }
        }else if ($action == 'update'){
        	if(empty($data['gmt_modify']) && array_key_exists('gmt_modify',$fields)){
	            $data['gmt_modify'] = $now;
	        }
        }
        
        return $data;
    }
    
    /**
     * 更新
     */
    public function update($param, $where = null){
        $data = $this->_fieldsDecorator($param,'update');
        
        if($data){
        	$this->db->update($this->getTableRealName(), $data, $where);
        	return $this->db->affected_rows();
        }else{
        	return false;
        }
    }
    
    
    /**
     * 增加或者 减少 字段数值
     */
    public function increseOrDecrease($param, $where = null){
    	foreach($param as $p){
			$this->db->set($p['key'],$p['value'],false);
		}
    	
    	$this->db->where($where);
    	$this->db->update($this->getTableRealName());
    	
    	return $this->db->affected_rows();
    }
    
    
    public function updateByCondition($data,$condition){
    	$this->_setCondition($condition);
    	
    	$data = $this->_fieldsDecorator($data,'update');
    	$this->db->update($this->getTableRealName(), $data);
        return $this->db->affected_rows();
    }
    
    public function updateByWhere($data,$where = null){
    	$data = $this->_fieldsDecorator($data,'update');
    	
        $this->db->update($this->getTableRealName(),$data,$where);
        return $this->db->affected_rows();
    }
    
    public function batchInsert($data){
    	$filterData = array();
    	foreach($data as $key => $item){
    		$filterData[] = $this->_fieldsDecorator($item,'add');
    	}
    	
        return $this->db->insert_batch($this->getTableRealName(), $filterData); 
    }
    
    public function batchUpdate($data,$key = 'id'){
    	$filterData = array();
    	foreach($data as $item){
    		$filterData[] = $this->_fieldsDecorator($item,'update');
    	}
    	
        return $this->db->update_batch($this->getTableRealName(), $filterData,$key); 
    }
    
    
    /**
     * 校验
     */
    public function checkExists($value,$key){
		if(!$this->getCount(array('where' => array($key => $value)))){
			return true;
		}else{
			return false;
		}
	}
	
    /**
     * 查询
     */
    public function getFirstByKey($id,$key = 'id'){
        $query = $this->db->get_where($this->getTableRealName(),array($key => $id));
        $data = $query->result_array();
        if($data[0]){
            return $data[0];
        }else{
            return false;
        }
    }
    
    /**
     * 获得
     */
    public function getById($condition){
        if($condition['where']){
            
            if($condition['select']){
                $this->db->select($condition['select']);
            }
            
            $this->db->where($condition['where']);
            $query = $this->db->get($this->getTableRealName());
            $data = $query->result_array();
            
            if($data[0]){
                return $data[0];
            }
        }
        
        return false;
    }
    
    
    public function execSQL($sql){
    	$this->db->query($sql);
    	
    	return $this->db->affected_rows();
    }
    
    /**
     * 直接查询
     */
    public function query($sql){
    	
    	$this->db->query($sql);
		$data = $query->result_array();
		
		return $data;
    }
    
    
    public function getList($condition = array()){
        
        $data = array();
        $total_rows = 0;
        
        $this->_setCondition($condition);
        
        if($condition['pager']){
        	$total_rows = $this->db->count_all_results($this->getTableRealName());
        	
        	//总页数
        	$totalPage = ceil($total_rows/$condition['pager']['page_size']);
            if($condition['pager']['current_page'] > $totalPage){
            	//不能大于最大页数
            	$condition['pager']['current_page'] = $totalPage == 0 ? 1 : $totalPage;
            }
            
            $pager = pageArrayGenerator($condition['pager'],$total_rows);
            //print_r($condition);
            $this->_setCondition($condition);
            $query = $this->db->get($this->getTableRealName(),$condition['pager']['page_size'],($condition['pager']['current_page'] - 1) * $condition['pager']['page_size']);
        	$data['data'] = $query->result_array();
        	$data['pager'] = $pager['pager'];
        }else{
            $query = $this->db->get($this->getTableRealName());
            $data = $query->result_array();
        }
        
        return $data;

    }
}