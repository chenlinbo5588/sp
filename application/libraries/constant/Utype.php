<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 缴费方式
 */
class Utype
{
	//未缴费
	public static $unpaid = 0;
	
	//自付
	public static $seifpaid = 1;
	
	//家庭成员支付
	public static $housepaid = 2;
	
	//其他人支付
	public static $otherpaid = 3;
	
	//手工单
	public static $handwork = 4;
	
	
	public static $statusName = array(
		'未缴费',
		'自付',
		'家庭成员支付',
		'其他人支付',
		'手工单',
	);
}