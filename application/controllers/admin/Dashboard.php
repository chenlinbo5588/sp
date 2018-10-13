<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Ydzj_Admin_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array('Wuye_service','Basic_data_service'));
		
				$this->wuye_service->setDataModule($this->_dataModule);
		$this->_moduleTitle = '报表';
		$this->_className = strtolower(get_class());
		
		
		$this->assign(array(
			'moduleTitle' => $this->_moduleTitle,
			'moduleClassName' => $this->_className
		));
		$this->_subNavs = array(
			array('url' => $this->_className.'/welcome','title' => '管理'),
		);
	}
	
	
	/**
	 * 生成报表
	 */
	public function welcome(){
		$residentId = $this->input->get_post('resident_id');
		if(empty($residentId)){
			$residentList = $this->wuye_service->getOwnedResidentList(array());
			$residentId = $residentList[0]['id'];
		}
		$residentInfo = $this->Resident_Model->getFirstByKey($residentId,'id');
		$residentName = $residentInfo['name'];
		$year = date('Y',time());
		$week = date('W',time());
		$month = date('m',time());
		
		//定死的日期 ，time应为当前日期
		$time = '2018-1-7';
		$date = strtotime("$time -7 day");

		$dateList = $this->Payment_Report_Date_Model->getList(array(
			'where' => array(
				'date >' => $date,
				'resident_id' => $residentId,
			)
		));
		$WeekList = $this->Payment_Report_Week_Model->getList(array(
			'where' => array(
				'week <' => $week,
				'resident_id' => $residentId,
			)
		));
		$MonthList = $this->Payment_Report_Month_Model->getList(array(
			'where' => array(
				'month <' => $month,
				'resident_id' => $residentId,
			)
		));
		$YearList = $this->Payment_Report_Year_Model->getList(array(
			'where' => array(
				'year >' => $year-5,
				'resident_id' => $residentId,
			)
		));
		$wuyeWeekjson = array();
		$nenghaoWeekjson = array();
		foreach($dateList as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyeDatejson[] = $item ['collection_rate'];
				$dateJson[] = date('m-d',$item['date']);
			}else if('能耗费' == $item['feetype_name']){
				$nenghaoDatejson[] = $item ['collection_rate'];	
			}
		}
		foreach($WeekList as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyeWeekjson[] = $item ['collection_rate'];
				$weekJson[] ='第'. $item['week'].'周';
			}else if('能耗费' == $item['feetype_name']){
				$nenghaoWeekjson[] = $item ['collection_rate'];	
			}
		}
		foreach($MonthList as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyeMonthjson[] = $item ['collection_rate'];
				$monthJson[] =$item['month'].'月';
			}else if('能耗费' == $item['feetype_name']){
				$nenghaoMonthjson[] = $item ['collection_rate'];	
			}
		}
		foreach($YearList as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyeYearjson[] = $item ['collection_rate'];
				$yearJson[] =$item['year'].'月';
			}else if('能耗费' == $item['feetype_name']){
				$nenghaoYearjson[] = $item ['collection_rate'];	
			}
		}
		$this->assign(array(
			'wuyeDatejson' => json_encode($wuyeDatejson),
			'dateJson' => json_encode($dateJson),
			'nenghaoDatejson' => json_encode($nenghaoDatejson),
			'wuyeWeekjson' => json_encode($wuyeWeekjson),
			'weekJson' => json_encode($weekJson),
			'nenghaoWeekjson' => json_encode($nenghaoWeekjson),
			'wuyeMonthjson' => json_encode($wuyeMonthjson),
			'monthJson' => json_encode($monthJson),
			'nenghaoMonthjson' => json_encode($nenghaoMonthjson),
			'wuyeYearjson' => json_encode($wuyeYearjson),
			'yearJson' => json_encode($yearJson),
			'nenghaoYearjson' => json_encode($nenghaoYearjson),
    		'residentList' => $this->wuye_service->getOwnedResidentList(array(
			'select' => 'id,name,address,lng,lat'),'id'),
			'residentId' => $residentId,
			'residentName' => $residentName,
		));

		$this->display();
	}
	
	
	
	public function aboutus(){
		
		$this->display('dashboard/aboutus');
	}
	
	/**
	 * 生成报表所需的记录
	 */
	public function createReport($time,$residentId){
	//public function welcome(){
		//$time = '2018-1-7';
		//$residentId = 4;
		//if($this->isPostRequest()){
		$dailyReportList = array();
		$lastDateList = $this->Payment_Report_Date_Model->getList(array(
			'where' => array(
				'resident_id' => $residentId,
				'date' => strtotime("$time -1 day"),
			)
		));
		$residentItem = $this->Resident_Model->getFirstByKey($residentId,'id');
		$dailyReportList = array(
			'date' => strtotime($time),
			'week' => date('W',strtotime($time)),
			'month' => date('m',strtotime($time)),
			'year' => date('Y',strtotime($time)),
			'resident_id' => $residentItem['id'],
			'resident_name' => $residentItem['name'],
			
		);
		$countHouseholds = $this->House_Model->getList(array(
			'select' => 'count(*)',
			'where' => array(
				'yezhu_id >' => 0,
				'resident_id' => $residentId			
			)
		));
		$countSelfPaid = $this->getSelfPaid($residentId);
		$realReportList = $this->getReportList($dailyReportList,$residentId);
		$realReportList['物业费']['count_households'] = $countHouseholds[0]['count(*)'];
		$realReportList['物业费']['self_paid_households'] = $countSelfPaid;
		$realReportList['物业费']['other_paid_households'] = $realReportList['物业费']['paid_households'] - $countSelfPaid;
		//如果明天是新的一年生成年度记录
		if(date('Y',strtotime("$time +1 day")) > $realReportList['物业费']['year']){

			$lastYearList = $this->Payment_Report_Year_Model->getList(array(
				'where' => array(
					'resident_id' => $residentId,
					'year' => date('Y',strtotime("$time -1 year"))
				)
			));
			if(!empty($lastYearList)){
				foreach($lastYearList as $key => $item){
					if('物业费' == $item['typename']){
						$realReportList['物业费']['old_amount_payed'] = $item['count_amount_payed'];
						$realReportList['物业费']['amount_increment'] = $realReportList['物业费']['count_amount_payed'] - $realReportList['物业费']['old_amount_payed'];
						$realReportList['物业费']['paid_increment'] = $realReportList['物业费']['paid_households'] - $item['paid_households'];
						$realReportList['物业费']['collection_rate'] = $realReportList['物业费']['paid_households'] / $realReportList['物业费']['count_households'] * 100;
					}else if('能耗费' == $item['typename']){
						$realReportList['能耗费']['old_amount_payed'] = $item['count_amount_payed'];
						$realReportList['能耗费']['amount_increment'] = $realReportList['能耗费']['count_amount_payed'] - $realReportList['能耗费']['old_amount_payed'];
						$realReportList['能耗费']['collection_rate'] = $realReportList['能耗费']['count_amount_payed'] / $realReportList['能耗费']['count_amount_real'] * 100;
					}
				}
			}
			print_r($realReportList);
			$this->Payment_Report_Year_Model->_add($realReportList['物业费']);
			$this->Payment_Report_Year_Model->_add($realReportList['能耗费']);
		}
		//明天是新的一周生成周记录
		if(date('W',strtotime("$time +1 day")) != $realReportList['物业费']['week']){
			if(1 != date('W',strtotime("$time +1 day"))){
				$lastWeekList = $this->Payment_Report_Week_Model->getList(array(
					'where' => array(
						'resident_id' => $residentId,
						'week' => date('W',strtotime("$time -7 day"))
					)
				));
				foreach($lastWeekList as $key => $item){
					if('物业费' == $item['typename']){
						$realReportList['物业费']['old_amount_payed'] = $item['count_amount_payed'];
						$realReportList['物业费']['amount_increment'] = $realReportList['物业费']['count_amount_payed'] - $realReportList['物业费']['old_amount_payed'];
						$realReportList['物业费']['paid_increment'] = $realReportList['物业费']['paid_households'] - $item['paid_households'];
						$realReportList['物业费']['collection_rate'] = $realReportList['物业费']['paid_households'] / $realReportList['物业费']['count_households'] * 100;
					}else if('能耗费' == $item['typename']){
						$realReportList['能耗费']['old_amount_payed'] = $item['count_amount_payed'];
						$realReportList['能耗费']['amount_increment'] = $realReportList['能耗费']['count_amount_payed'] - $realReportList['能耗费']['old_amount_payed'];
						$realReportList['能耗费']['collection_rate'] = $realReportList['能耗费']['count_amount_payed'] / $realReportList['能耗费']['count_amount_real'] * 100;
					}
				}
			}
			//print_r($realReportList);
			$this->Payment_Report_Week_Model->_add($realReportList['物业费']);
			$this->Payment_Report_Week_Model->_add($realReportList['能耗费']);
		}
		//明天是新的一月生成月度记录
		if(date('m',strtotime("$time +1 day")) != $realReportList['物业费']['month']){
				if(1 != date('m',strtotime("$time +1 day"))){
				$lastMonthList = $this->Payment_Report_Month_Model->getList(array(
					'where' => array(
						'resident_id' => $residentId,
						'month' => date('m',strtotime("$time -1 month"))
					)
				));
				foreach($lastMonthList as $key => $item){
					if('物业费' == $item['typename']){
						$realReportList['物业费']['old_amount_payed'] = $item['count_amount_payed'];
						$realReportList['物业费']['amount_increment'] = $realReportList['物业费']['count_amount_payed'] - $realReportList['物业费']['old_amount_payed'];
						$realReportList['物业费']['paid_increment'] = $realReportList['物业费']['paid_households'] - $item['paid_households'];
						$realReportList['物业费']['collection_rate'] = $realReportList['物业费']['paid_households'] / $realReportList['物业费']['count_households'] * 100;
					}else if('能耗费' == $item['typename']){
						$realReportList['能耗费']['old_amount_payed'] = $item['count_amount_payed'];
						$realReportList['能耗费']['amount_increment'] = $realReportList['能耗费']['count_amount_payed'] - $realReportList['能耗费']['old_amount_payed'];
						$realReportList['能耗费']['collection_rate'] = $realReportList['能耗费']['count_amount_payed'] / $realReportList['能耗费']['count_amount_real'] * 100;
				}
				}
			}
			//print_r($realReportList);
			$this->Payment_Report_Month_Model->_add($realReportList['物业费']);
			$this->Payment_Report_Month_Model->_add($realReportList['能耗费']);
		}
		//生成今天的记录
		foreach($lastDateList as $key => $item){
			if('物业费' == $item['typename']){
				$realReportList['物业费']['old_amount_payed'] = $item['count_amount_payed'];
				$realReportList['物业费']['amount_increment'] = $realReportList['物业费']['count_amount_payed'] - $realReportList['物业费']['old_amount_payed'];
				$realReportList['物业费']['paid_increment'] = $realReportList['物业费']['paid_households'] - $item['paid_households'];
				$realReportList['物业费']['collection_rate'] = $realReportList['物业费']['paid_households'] / $realReportList['物业费']['count_households'] * 100;
			}else if('能耗费' == $item['typename']){
				$realReportList['能耗费']['old_amount_payed'] = $item['count_amount_payed'];
				$realReportList['能耗费']['amount_increment'] = $realReportList['能耗费']['count_amount_payed'] - $realReportList['能耗费']['old_amount_payed'];
				$realReportList['能耗费']['collection_rate'] = $realReportList['能耗费']['count_amount_payed'] / $realReportList['能耗费']['count_amount_real'] * 100;
			}
		}
		//print_r($realReportList);
		$this->Payment_Report_Date_Model->_add($realReportList['物业费']);
		$this->Payment_Report_Date_Model->_add($realReportList['能耗费']);

/*		}else{
			$this->display();
		}*/
	}
	
	/**
	 * 获得插入的数组
	 */
	private function getReportList($dailyReportList,$residentId){
		$wuyeReportList = array();
		$nenghaoReportList = array();
		$wuyeMoney = 0;
		$nenghaoMoney = 0;
		$wuyeReportList['count_amount_real'] = 0;
		$wuyeReportList['paid_households'] = 0;
		$this->Plan_Model->setTableId($dailyReportList['year']);
		$planList = $this->Plan_Model->getList(array('whrer' => array('resident_id' => $residentId)));
		foreach($planList as $key => $item){
			if('物业费' == $item['feetype_name']){
				$wuyeReportList['count_amount_real'] += $item['amount_real'];
				$wuyeMoney += $item['amount_payed'];
				if($item['amount_real'] == $item['amount_payed']){
					$wuyeReportList['paid_households'] += 1;
				}
			}else if('能耗费' == $item['feetype_name']){
				$nenghaoReportList['count_amount_real'] += $item['amount_real'];
				$nenghaoMoney += $item['amount_payed'];
			}
		}
		$wuyeReportList['feetype_name'] = '物业费';
		$wuyeReportList['count_amount_payed'] = $wuyeMoney;
		
		$nenghaoReportList['feetype_name'] = '能耗费';
		$nenghaoReportList['count_amount_payed'] = $nenghaoMoney;
		$wuyeReportList = array_merge($wuyeReportList,$dailyReportList);
		$nenghaoReportList = array_merge($nenghaoReportList,$dailyReportList);
		if(empty($nenghaoMoney)){
			return $wuyeReportList;
		}else{
			$realReportList['物业费'] = $wuyeReportList;
			$realReportList['能耗费'] = $nenghaoReportList;
		}
		return $realReportList;
		
	}
	
	/**
	 * 获得自己支付的户数
	 */
	private function getSelfPaid($residentId){
		
		$countSelfPaid = 0;
		$getUid = array();
		$houseList = $this->House_Model->getList(array(
			'where' => array(
				'resident_id' => $residentId
			)
		));
		$odrerList = $this->Order_Model->getList(array(
			'select' => 'uid,goods_id',
			'where' => array(
				'order_typename' => '物业费',
				'status' => 2
			)
		));
		foreach($odrerList as $key => $orderItem){
			foreach($houseList as $key1 => $houseItem){
				if($orderItem['goods_id'] == $houseItem['id']){
					$getUid[] = $odrerList[$key];
				}
			}
		}
		
		$houseYezhuList = $this->House_Yezhu_Model->getList(array(
			'where' => array(
				'resident_id' => $residentId
			)
		));
		foreach($houseYezhuList as $key => $item){
			foreach($getUid as $key1 => $valus){
				if($item['house_id'] == $valus['goods_id'] && $item['uid'] == $valus['uid']){
					$countSelfPaid += 1;
				}
			}
			
		}
		return $countSelfPaid;
	}
	
}
