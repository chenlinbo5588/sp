<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 订单状态
 */
class OrderStatus
{
	public static $deleted = 0;
	
	//未支付
	public static $unPayed = 1;
	
	//已支付
	public static $payed = 2;
	
	//退款中
	public static $refounding = 3;
	
	//退款完成
	public static $refounded = 4;
	
	//关闭
	public static $closed = 5;
	
	
	public static $statusName = array(
		'已删除',
		'未支付',
		'已支付',
		'退款中',
		'退款完成',
		'已关闭',
	);
}