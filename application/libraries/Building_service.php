<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Building_service extends Base_service {
	
	private $_arcgisApiClient ;
	
	private $_villageModel;
	private $_personModel ;
	private $_houseModel;
	private $_houseImagesModel;
	private $_houseDetailModel;
	
	
	
	
	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Village_Model', 'Person_Model','House_Model','House_Images_Model','House_Detail_Model'));
		self::$CI->load->library('arcgis/FeatureRest');
		
		
		$this->_villageModel = self::$CI->Village_Model;
		$this->_personModel = self::$CI->Person_Model;
		$this->_houseModel = self::$CI->House_Model;
		$this->_houseImagesModel = self::$CI->House_Images_Model;
		$this->_houseDetailModel = self::$CI->House_Detail_Model;
		
		$this->_arcgisApiClient = self::$CI->featurerest;
	}
	
	
	public function setArcgisUrl($baseUrl,$featureUrl){
		$this->_arcgisApiClient->setUrl($baseUrl,$featureUrl);
	}
	
	
	public function autoSetXY($houseInfo){
		//print_r($houseInfo);
		
		if($houseInfo['object_id'] && $houseInfo['x'] < 1 ){
			$result = $this->_arcgisApiClient->query(array(
				'objectIds' => $houseInfo['object_id']
			));
			
			if($result['features'][0]){
				$this->_houseModel->update($result['features'][0]['geometry'],array('hid' => $houseInfo['hid']));
			}
			
			$houseInfo = array_merge($houseInfo,$result['features'][0]['geometry']);
		}
		
		return $houseInfo;
		
		
	}
	
	public function getTownVillageList($townName){
		$list = $this->_villageModel->getList(array(
			'where' => array(
				'xzmc' => $townName
			)
			
		));
		
		return $this->_villageModel->dataToAssoc($list,'id');
	}
	
	/**
	 * 给每个房屋点编号
	 */
	private function _formatHouseNo($personInfo,$prefix = ''){
        
        
        $houseCount = $this->_houseModel->getCount(array(
			'where' => array(
				'owner_village_id' => $personInfo['village_id'],
				'owner_id' => $personInfo['id']
			)
		));
		
		
		$villageInfo = $this->_villageModel->getFirstByKey($personInfo['village_id'],'id', 'xzqdm');
		
		
        if($personInfo['id'] < 10000){
            $masterSerial = str_pad($personInfo['id'], 5,'0', STR_PAD_LEFT);
        }
        
        
        if($houseCount < 100){
            $regionSerial = str_pad($houseCount + 1, 3,'0', STR_PAD_LEFT);
        }
        
        return $prefix.$villageInfo['xzqdm'].$masterSerial.$regionSerial;
    }
    
	
	
	public function updatePersonHouseByPersonId($personId){
		
		
		//更新person 表统计信息
		$houseList = $this->_houseModel->getList(array(
			'where' => array('owner_id' => $personId)
		));
		
		$personUpdate = array(
			'yhdz' => count($houseList) > 1 ? 1 : 0,
			'housecnt' => count($houseList),
			'total_ydmj' => 0,
			'total_jzzdmj' => 0,
			'total_jzmj' => 0,
		);
		
		foreach($houseList as $houseItem){
			$personUpdate['total_ydmj'] += $houseItem['wf_ydmj'];
			$personUpdate['total_jzzdmj'] += $houseItem['wf_jzzdmj'];
			$personUpdate['total_jzmj'] += $houseItem['wf_jzmj'];
		}
		
		return $this->_personModel->update($personUpdate,array(
			'id' => $personId
		));
		
	}
	
	
	/**
	 * 
	 */
	public function addPersonBuilding($personInfo,$pBuildingParam, $fileIds = array()){
		$pBuildingParam['owner_village_id'] = $personInfo['village_id'];
		$pBuildingParam['owner_village'] = $personInfo['village'];
		$pBuildingParam['owner_id'] = $personInfo['id'];
		$pBuildingParam['owner_name'] = $personInfo['qlr_name'];
		$pBuildingParam['id_no'] = $personInfo['id_no'];
		$pBuildingParam['bh'] = $this->_formatHouseNo($personInfo);
		$pBuildingParam['photos'] = json_encode(array());
		
		$houseId = $this->_houseModel->_add($pBuildingParam);
		if($houseId){
			//几何
			$pointParam = array(
				array(
					"geometry" => array('x' => $pBuildingParam['x'] ,'y' => $pBuildingParam['y']),
					"attributes" => array(
						'name' => $personInfo['qlr_name'],
						'id_no' => $personInfo['id_no'],
						'person_id' => $personInfo['id'],
						'village_id' => $personInfo['village_id'],
						'illegal' => $pBuildingParam['illegal'],
						'hid' => $houseId
					)
				)
			);
			
			$result = $this->_arcgisApiClient->addFeatures($pointParam);
			if($result['addResults'][0]['success']){
				$this->_houseModel->update(array(
					'object_id' => $result['addResults'][0]['objectId']
				),array(
					'hid' => $houseId
				));
			}
			
			
			//更新图片归属
			// @todo 可能不安全，如果改变id 值，可以将文件变到
			if($fileIds){
				$fileUpdate = array();
				foreach($fileIds as $fileId){
					$fileUpdate[] = array(
						'id' => $fileId,
						'person_id' => $personInfo['id'],
						'house_id' => $houseId
					);
				}
				
				$this->_houseImagesModel->batchUpdate($fileUpdate);
			}
			
			//将图片更新的house表photos 字段中
			$imageList = $this->_houseImagesModel->getList(array(
				'select' => 'id,image_url,image_url_b,image_url_m',
				'where' => array(
					'house_id' => $houseId
				)
			));
			
			$this->_houseModel->update(array('photos' => json_encode($imageList)),array('hid' => $houseId));
			$this->updatePersonHouseByPersonId($personInfo['id']);
			
			
			return array(
				'hid' => $houseId,
				'pointResult' => $result
			);
			
		}else{
			return false;
		}
	}
	
	
	/**
	 * 更新
	 */
	public function editPersonBuilding($personInfo,$pBuildingParam){
		$pBuildingParam['owner_village_id'] = $personInfo['village_id'];
		$pBuildingParam['owner_village'] = $personInfo['village'];
		$pBuildingParam['owner_id'] = $personInfo['id'];
		$pBuildingParam['owner_name'] = $personInfo['qlr_name'];
		$pBuildingParam['id_no'] = $personInfo['id_no'];
		
		
		$pointParam = array(
			array(
				"geometry" => array('x' => $pBuildingParam['x'] ,'y' => $pBuildingParam['y']),
				"attributes" => array(
					'name' => $personInfo['qlr_name'],
					'id_no' => $personInfo['id_no'],
					'illegal' => $pBuildingParam['illegal']
				)
			)
		);
		
		if($pBuildingParam['object_id']){
			//如果已经关联了点了
			$pointParam[0]['attributes']['OBJECTID'] = intval($pBuildingParam['object_id']);
			$result = $this->_arcgisApiClient->updateFeatures($pointParam);
			if(!$result['updateResults'][0]['success']){
				log_message('error',$result['updateResults'][0]['error']);
			}
		}else{
			$result = $this->_arcgisApiClient->addFeatures($pointParam);
			if($result['addResults'][0]['success']){
				$pBuildingParam['object_id'] = $result['addResults'][0]['objectId'];
			}
		}
		
		$affectRow = $this->_houseModel->update($pBuildingParam,array('hid' => $pBuildingParam['hid']));
		
		if($affectRow >= 0){
			//将图片更新的house表photos 字段中
			$imageList = $this->_houseImagesModel->getList(array(
				'select' => 'id,image_url,image_url_b,image_url_m',
				'where' => array(
					'house_id' => $pBuildingParam['hid']
				)
			));
			
			$this->_houseModel->update(array('photos' => json_encode($imageList)),array('hid' => $pBuildingParam['hid']));
			
			
			
			$this->updatePersonHouseByPersonId($personInfo['id']);
			
		}
		
		return true;
		
	}
	
	
	/**
	 * 删除人员
	 */
	public function deletePersonById($personId){
		
		$houseList = $this->_houseModel->getList(array(
			'where' => array(
				'owner_id' => $personId
			)
		));
		
		
		if(!empty($houseList)){
			
			$houseId = array();
			$fileIds = array();
			$fileUrl = array();
			
			$objectIds = array();
			
			
			foreach($houseList as $houseItem){
				$houseId[] = $houseItem['hid'];
				if($houseItem['object_id']){
					$objectIds[] = $houseItem['object_id'];
				}
				
				$photoList = json_decode($houseItem['photos'],true);
				if($photoList){
					foreach($photoList as $photo){
						$fileIds[] = $photo['id'];
						$fileUrl[] = $photo['image_url'];
						$fileUrl[] = $photo['image_url_b'];
						$fileUrl[] = $photo['image_url_m'];
					}
				}
			}
			
			if($objectIds){
				$results = $this->_arcgisApiClient->deleteFeature(array(
					'objectIds' => implode(',',$objectIds)
				));
			}
			
			if($fileIds){
				$this->_houseImagesModel->deleteByCondition(array(
					'where_in' => array(
						array(
							'key' => 'id' , 'value' => $fileIds
						)
					)
				));
			}
			
			
			if($fileUrl){
				//删除文件
				self::$CI->attachment_service->deleteByFileUrl($fileUrl);
			}
			
			$this->_houseModel->deleteByCondition(array(
				'where_in' => array(
					array(
						'key' => 'hid' , 'value' => $houseId
					)
				)
			));
		}
		
		return $this->_personModel->deleteByWhere(array('id' => $personId));
	}
	
	
	/**
	 * 删除
	 */
	public function deleteHouseById($houseId){
		$houseInfo = $this->_houseModel->getFirstByKey($houseId,'hid');
		
		if($houseInfo['object_id']){
			$results = $this->_arcgisApiClient->deleteFeature(array(
				'objectIds' => $houseInfo['object_id']
			));
			
			
			if($results['deleteResults'][0]['success']){
				$imageList = json_decode($houseInfo['photos'],true);
				if($imageList){
					$fileUrl = array();
					$fileIds = array();
					
					foreach($imageList as $imageItem){
						$fileIds[] = $imageItem['id'];
						
						$fileUrl[] = $imageItem['image_url'];
						$fileUrl[] = $imageItem['image_url_b'];
						$fileUrl[] = $imageItem['image_url_m'];
					}
					
					
					if($fileIds){
						$this->_houseImagesModel->deleteByCondition(array(
							'where_in' => array(
								array(
									'key' => 'id' , 'value' => $fileIds
								)
							)
						));
					}
					
					if($fileUrl){
						//删除文件
						self::$CI->attachment_service->deleteByFileUrl($fileUrl);
					}
					
				}
				
				$affectRow = $this->_houseModel->deleteByWhere(array('hid' => $houseInfo['hid']));
				
				$this->updatePersonHouseByPersonId($houseInfo['owner_id']);
				
				return $affectRow;
			}else{
				return false;
			}
		}
	}
	
	
	/**
	 * 获得详情
	 */
	public function getHouseInfo($pHouseId){
		
		$info = $this->_houseModel->getFirstByKey($pHouseId,'hid');
		
		$info['photos'] = json_decode($info['photos'],true);
		$info['houseDetail'] = $this->_houseDetailModel->getList(array(
			'where'=> array(
				'house_id' => $info['hid']
			)
		));
		
		
		return $info;
	}
	
}
