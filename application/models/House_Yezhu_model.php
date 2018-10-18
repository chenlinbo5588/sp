<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class House_Yezhu_Model extends MY_Model {
    
    public $_tableName = 'house_yezhu';
    public static $_tableMeta = null;


    public function __construct(){
        parent::__construct();
        self::$_tableMeta = $this->getTableMeta(); 
    }
    
    protected function _metaData(){
    	return self::$_tableMeta;
    }
    
    /**
     * 是否删除原家庭成员，是否同时录入新家庭成员
     */
    public function deleteAndAdd($yezhuId,$residentId,$houseId,$changeStatus,$who){
		$houseYezhuList = $this->getList(array(
			'where' => array(
				'yezhu_id' => $yezhuId,
				'resident_id' => $residentId,
			)
		));
		if($houseYezhuList){
			foreach($houseYezhuList as $key => $item){
				$houseIdList[] = $item['house_id'];
			}
		}
		
		foreach($changeStatus as $key =>$item){
			if('delete' == $item){
				$this->deleteByCondition(array(
					'where' => array(
						'house_id' => $houseId
					)
				));
			}
		}
		if($houseIdList){
			$familyLIst = $this->getList(array(
				'where' => array('resident_id' => $residentId,),
				'where_in' => array(
					array('key' => 'house_id' ,'value' => $houseIdList)
				)
			));
			foreach($familyLIst as $key => $item){
				$addList[$item['yezhu_id']]['house_id'] = $houseId;
				$addList[$item['yezhu_id']]['yezhu_id'] = $item['yezhu_id'];
				$addList[$item['yezhu_id']]['resident_id'] = $item['resident_id'];
				$addList[$item['yezhu_id']]['uid'] = $item['uid'];
				$addList[$item['yezhu_id']] = array_merge($addList[$item['yezhu_id']],$who);
			}
			foreach($changeStatus as $key =>$item){
				if('add' == $item){
					$this->batchInsert($addList);
					return true;
				}
			}
		}
		return false;
    }
}