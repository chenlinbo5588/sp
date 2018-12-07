<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_service extends Base_service {

	public function __construct(){
		parent::__construct();
		
		self::$CI->load->model(array('Report_Day_Model','Report_Week_Model',
				'Report_Month_Model','Report_Year_Model','Resident_Model','Plan_Model'
				,'Plan_Detail_Model','House_Model','Order_Model'));
		
		$this->_reportDateModel = self::$CI->Report_Day_Model;
		$this->_reportWeekModel = self::$CI->Report_Week_Model;
		$this->_reportMonthModel = self::$CI->Report_Month_Model;
		$this->_reportYearModel = self::$CI->Report_Year_Model;
		$this->_residentModel = self::$CI->Resident_Model;
		$this->_planModel = self::$CI->Plan_Model;
		$this->_planDetailModel = self::$CI->Plan_Detail_Model;
		$this->_houseModel = self::$CI->House_Model;
		$this->_orderModel = self::$CI->Order_Model;
		
		
		self::$CI->load->library(array('constant/OrderStatus','constant/Utype'));
		
		
		$this->_objectMap = array(
			'每日报表' => $this->_reportDateModel,
			'每周报表' => $this->_reportWeekModel,
			'每月报表' => $this->_reportMonthModel,
			'每年报表' => $this->_reportYearModel,
		);
	}
	
	
	private function _seedReportData($modelName,$condition,$residentId,$time){
		$lastDateList =$this->_objectMap[$modelName]->getList($condition);
		$reportData = $this->getReportData($time,$residentId);
		$reportRecord = $this->getReportExtraInfo($lastDateList,$reportData);
		if($reportRecord['物业费']){
			$this->_objectMap[$modelName]->_add($reportRecord['物业费']);
		}
		if($reportRecord['能耗费']){
			$this->_objectMap[$modelName]->_add($reportRecord['能耗费']);
		}
		
		
	}
	
	/**
	 * 生成日报表
	 */
	public function sendReportDay($residentId,$day){
		
		$this->_seedReportData('每日报表',array(
			'where' => array(
				'resident_id' => $residentId,
				'date_key' => strtotime("{$day} -1 day")-1,
			)
		),$residentId,strtotime($day)-1);
		
	}
	
	/**
	 * 生成周报表
	 */
	public function sendReportWeek($residentId,$date){
		$this->_seedReportData('每周报表',array(
			'where' => array(
				'resident_id' => $residentId,
				'week' => date('W',strtotime("{$date} -1 week")-1),
			)
		),$residentId,strtotime($date)-1);
		
	}
	/**
	 * 生成月报表
	 */
	public function sendReportMonth($residentId,$date){
		$this->_seedReportData('每月报表',array(
			'where' => array(
				'resident_id' => $residentId,
				'month' => date('m',strtotime("{$date} -2 month")),
			)
		),$residentId,strtotime($date)-1);
	}
	/**
	 * 生成年报表
	 */
	public function sendReportYear($residentId,$date){
		$this->_seedReportData('每年报表',array(
			'where' => array(
				'resident_id' => $residentId,
				'year' => date('Y',strtotime("{$date} -2 year")),
			)
		),$residentId,strtotime($date)-1);
	}
	
	/**
	 * 生成报表所需的记录
	 */
	public function getReportData($time,$residentId){
		$dailyReportList = array();
		$residentItem = $this->_residentModel->getFirstByKey($residentId,'id');
		
		$param = array(
			'date_key' => $time,
			'week' => date('W',$time),
			'month' => date('m',$time),
			'year' => date('Y',$time),
			'resident_id' => $residentItem['id'],
			'resident_name' => $residentItem['name'],0
		);
		
		$countHouseholds = $this->_houseModel->getCount(array(
			'where' => array(
				'resident_id' => $residentId			
			)
		));
		
		$reportDate = $this->getReportInsert($param,$residentId,$time);
		if($reportDate['物业费']){
			$reportDate['物业费']['all_hushu'] = $countHouseholds;
			$reportDate['物业费']['otherpaid_hushu'] = $reportDate['物业费']['paid_hushu'] - $reportDate['物业费']['selfpaid_hushu'];	
		}
		if($reportDate['能耗费']){
			$reportDate['能耗费']['all_hushu'] = $countHouseholds;
			$reportDate['能耗费']['otherpaid_hushu'] = $reportDate['能耗费']['paid_hushu'] - $reportDate['能耗费']['selfpaid_hushu'];
		}	
		return $reportDate;

	}
	
	/**
	 * 获得插入的数组
	 */
	private function getReportInsert($param,$residentId,$time){
		$wuyeReportList = array();
		$nenghaoReportList = array();
		$wuyeMoney = 0;
		$nenghaoMoney = 0;
		
		$temp = $this->_planDetailModel->sumByCondition(array(
			'field' => 'amount_payed',
			'where' =>	array(
				'resident_id' => $residentId,
				'order_status' => OrderStatus::$payed,
				'pay_time <=' => $time,
				'feetype_name' => '能耗费'
			)
		));
		
		$planList = $this->_planModel->getList(array(
			'where' => array(
				'resident_id' => $residentId,
		)));
		//@TODO 浮点数加减精度丢失
		if(!empty($planList)){
			foreach($planList as $key => $item){
				if('物业费' == $item['feetype_name']){
					$wuyeReportList['amount_real'] += $item['amount_real'];
					if($item['pay_time'] <= $time &&$item['order_status'] == OrderStatus::$payed){
						$wuyeMoney += $item['amount_payed'];
						if(abs($item['amount_real'] - $item['amount_payed']) < 0.1){
							$wuyeReportList['paid_hushu'] += 1;
						}
						if(Utype::$otherpaid != $item['utype'] && Utype::$unpaid != $item['utype'])
						{
							$wuyeReportList['selfpaid_hushu'] += 1;
						}
					}
				}else if('能耗费' == $item['feetype_name']){
					$nenghaoReportList['amount_real'] += $item['amount_real'];
					if($item['pay_time'] <= $time &&$item['order_status'] == OrderStatus::$payed){
						$nenghaoMoney += $item['amount_payed'];
						if(abs($item['amount_real'] - $item['amount_payed']) < 0.1){
							$nenghaoReportList['paid_hushu'] += 1;
							if(Utype::$otherpaid != $item['utype'] && Utype::$unpaid != $item['utype'])
								{
									$nenghaoReportList['selfpaid_hushu'] += 1;
								}
						}
					}
				}
			}			
		}
		if($wuyeReportList){
			$wuyeReportList['feetype_name'] = '物业费';
			$wuyeReportList['amount_payed'] = $wuyeMoney;			
			$wuyeReportList = array_merge($wuyeReportList,$param);
			$ReportDate['物业费'] = $wuyeReportList;
		}
		
		if($nenghaoReportList){
			$nenghaoReportList['feetype_name'] = '能耗费';
			$nenghaoReportList['amount_payed'] = $nenghaoMoney;			
			$nenghaoReportList = array_merge($nenghaoReportList,$param);
			$ReportDate['能耗费'] = $nenghaoReportList;
		}
		
		return $ReportDate;
		
	}
	
	/**
	 * 获得缴费率等（物业费按缴费户数算能耗费按缴费金额算）
	 */
	private function getReportExtraInfo($lastList,$reportData){
		if(!empty($lastList) && !empty($reportData)){
			foreach($lastList as $key => $item){
				$key = $item['feetype_name'];		
				$reportData[$key]['old_amount_payed'] = $item['amount_payed'];
				$reportData[$key]['paid_increment'] =$reportData[$key]['paid_hushu'] - $item['paid_hushu'];
			}
		}
		if (!empty($reportData)){
			foreach($reportData as $key => $item){
				$key = $item['feetype_name'];
				$reportData[$key]['amount_increment'] = $reportData[$key]['amount_payed'] - $reportData[$key]['old_amount_payed'];
				$reportData[$key]['collection_rate'] = $reportData[$key]['paid_hushu'] / $reportData[$key]['all_hushu'] * 100;
				if($key != '物业费'){
					
					$reportData[$key]['collection_rate'] = $reportData[$key]['amount_payed'] / $reportData[$key]['amount_real'] * 100;
				}
			}
		}
		return $reportData;
	}
	
	
/*	public function _searchCondition($search){
		if($search['feetype_name']){
			$condition['where']['feetype_name'] = $search['feetype_name'];
		}
		if($search['date_s']){
			$weeks = date('W',strtotime($search['date_s']));
			$months = date('m',strtotime($search['date_s']));
			$years = date('Y',strtotime($search['date_s']));
		}
		
		if($search['date_e']){
			$weeke = date('W',strtotime($search['date_e']));
			$monthe = date('m',strtotime($search['date_e']));
			$yeare = date('Y',strtotime($search['date_e']));
		}
		
		if('每日报表' == $search['report_mode'] || empty($search['report_mode'])){
			if($search['date_s']){
				$condition['where']['date_key >='] = strtotime($search['date_s']);
			}else{
				$condition['where']['date_key >='] =mktime(0,0,0,1,1,date('Y'));
			}
			if($search['date_e']){
				$condition['where']['date_key <='] = strtotime($search['date_e']);
			}else{
				$condition['where']['date_key <='] = strtotime(date('Y'));
			}
			
		}else if('每周报表' == $search['report_mode']){
			if($weeks){
				$condition['where']['week >='] = $weeks;
			}else{
				$condition['where']['week >='] = 1;
			}
			if($weeke){
				$condition['where']['week <='] = $weeke;
			}else{
				$condition['where']['week <='] = date('W');
			}
		}
		else if('每月报表' == $search['report_mode']){
			if($months){
				$condition['where']['month >='] = $months;
			}else{
				$condition['where']['month >='] = 1;
			}
			if($monthe){
				$condition['where']['month <='] = $monthe;
			}else{
				$condition['where']['month <='] = date('m');
			}
		}else if('每年报表' == $search['report_mode']){
			if($years){
				$condition['where']['year >='] = $years;
			}else{
				$condition['where']['year >='] = date('Y')-5;
			}
			if($yeare){
				$condition['where']['year <='] = $yeare;
			}else{
				$condition['where']['year <='] = date('Y');
			}
		}
		return $condition;
		
	}*/

	
}
