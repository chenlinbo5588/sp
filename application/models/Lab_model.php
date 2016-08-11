<?php


class Lab_Model extends MY_Model {
    
    public $_tableName = 'lab';
    public $_menuTree = array() ;
    public $_fullTree = array();
    public $_parentList = array();
    
    
    public static $_tableMeta = null;
    
    public function __construct(){
        parent::__construct();
        
        self::$_tableMeta = $this->getTableMeta();
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    
    public function saveTree($tree){
        $this->_fullTree = $tree;
    }
    
    public function clearMenuTree(){
        $this->_menuTree = array();
    }
    
    
    /**
     * 获得祖先列表
     * @param $selfid 
     */
    public function getParents($selfid = 0,$field = '*'){
        
        $condition['select'] = $field;
        $condition['where'] = array(
            'status' => '正常',
            'id' => $selfid
        );
        
        $result = $this->getById($condition);
        if($result){
            $this->_parentList[$result['id']] = $result;
            $this->getParents($result['pid'],$field);
        }
        
        return $this->_parentList;
    }
    
    
    
    /**
	 * 获取指定的菜单
	 *
	 * @return array
	 */
	 
	 /*
	public function getMenusArray($menuId = 0) {
		$array = array();
		try {
			$menuId = intval($menuId);
			$para = array();
			$result = $this->getList(array(
				'where' => array(
					'status' => '正常'
				),
				'order' => 'pid ASC , displayorder DESC'
			));
			
			$tree = $this->_getMenusTreeArray($result['data'], $menuId);

			if (0 == $menuId) {
				$array = $tree;
			} else {
				foreach ($result['data'] as $menu) {
					if ($menu['id'] == $menuId) {
						$array = $menu;
					}
				}
				$array['subTree'] = $tree;
			}
		} catch (Exception $e) {
			
		}

		return $array;
	}
    
    private function _getMenusTreeArray($meuns, $parentid) {
		$subTree = array();
		foreach ($meuns as $key => $menu) {
			if ($parentid == $menu['pid']) {
				unset($meuns[$key]);
				$menu['subTree'] = $this->_getMenusTreeArray($meuns, $menu['id']);
				$subTree[] = $menu;
			}
		}
		return $subTree;
	}
	*/
	
	
	
    /**
     * 获得树形
     */
    public function getRealTree($data, $pid){
    	$tree = '';
		foreach($data as $k => $v)
		{
		   if($v['pid'] == $pid)
		   {    //父亲找到儿子
		    	$v['pid'] = $this->getRealTree($data, $v['id']);
		    	$tree[] = $v;
		   }
		}
		
		return $tree;
    }
    
    
    public function toXML($tree)
	{
		$xml = '';
		foreach($tree as $t)
		{
		   if($t['pid'] == 0)
		   {
		   		$xml .= '<item text="'.$t['address'].'" id="'.$t['id'].'" open="0"></item>';
		   }
		   else
		   {
			    $xml .= '<item text="'.$t['address'].'" id="'.$t['id'].'" open="0">';
			    $xml .= $this->toXML($t['pid']);
			    $xml = $xml."</item>";
		   }
		}
		
		return $xml;

	}
    
    
    public function getListByTree($parentId = 0,$selfid = 0,$separate = '----',$level = 0) {
        
        $parentId = $parentId < 0 ? 0 : intval($parentId);
        //$selfId   = $selfId < 0 ? 0 : intval($selfId);
        $condition = array(
          'where' => array(
              'status' => '正常',
              'pid' => $parentId,
          ),
          'order' => 'pid ASC, id ASC , displayorder DESC'
        );
        
        if(is_array($selfid)){
            $condition['where_not_in'] = array(
                array('key' => 'id' ,'value' => $selfid)
                );
        }else{
            $condition['where']['id !='] =  $selfid;
        }
        
        $childrenList = $this->getList($condition);
        if(is_array($childrenList)){
            foreach ($childrenList as $item){
                
                $sepA = array();
                
                //$repeatChar = str_repeat("&nbsp;", strlen($separate));
                
                for($i = 0; $i < $level; $i++){
                    $sepA[] = $separate;
                }
     
                $item['sep'] = implode('',$sepA);
               
                //$item['sep'] = str_replace($repeatChar,$separate,$item['sep'],$level );
               
                
                $item['level'] = $level;
                
                $this->_menuTree[$item['id']] = $item;
                
                $this->getListByTree($item['id'],$selfid, $separate,$level + 1);
            }
        }
		
		return $this->_menuTree;
	}
    

    public function add($param){
        $now = time();
        $data = array(
            'id' => NULL,
            'address' => empty($param['address']) ? '' : $param['address'],
            'pid' => empty($param['pid']) ? 0 : intval($param['pid']),
            'creator' => $param['creator'],
            'updator' => $param['creator'],
        );
        
        if(empty($param['displayorder'])){
        	$data['displayorder'] = 0;
        }else{
        	$data['displayorder'] = intval($param['displayorder']);
        }
        
        
        return $this->_add($data);
    }
    
    public function delete($param){
        if(is_array($param)){
           	$this->db->where_in('id',$param);
            $this->db->delete($this->_tableName); 
        }else{
            
            $where = array(
                'id' => $param
            );
            $this->db->delete($this->_tableName, $where);
        }
        return $this->db->affected_rows();
    }
    
    public function fake_delete($param){
        
        if(is_array($param['id'])){
             $data = array();
           
            foreach($param['id'] as $v){
                $data[] = array(
                    'id' => $v,
                    'status' => '已删除',
                    'updator' => $param['updator'],
                    'gmt_modify' => time()
                );
            }
            
            $this->db->update_batch($this->_tableName, $data,'id'); 
        }else{
            $data = array(
                'status' => '已删除',
                'updator' => $param['updator'],
                'gmt_modify' => time()
            );
            
            $where = array(
                'id' => $param['id']
            );
            $this->db->update($this->_tableName, $data,$where);
        }
        return $this->db->affected_rows();
    }
    
    
    
    public function update($param){
        $data = array(
            'updator' => $param['updator']
        );
        
        if(empty($param['displayorder'])){
        	$data['displayorder'] = 0;
        }else{
        	$data['displayorder'] = intval($param['displayorder']);
        }
        
        if(!empty($param['pid'])){
            $data['pid'] = $param['pid'];
        }
        
        if(!empty($param['address'])){
            $data['address'] = $param['address'];
        }
        
        $where = array(
            'id' => $param['id']
        );
        
        return $this->update($data, $where);
    }
    
    
    public function updateDisplayorder($param){
    	$data = array(
    		'displayorder' => $param['displayorder'],
            'updator' => $param['updator'],
        );
        
        $where = array(
            'id' => $param['id']
        );
        
        return $this->update($data, $where);
    }
    
}