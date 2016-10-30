<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['navs'] = array(
	'main' => array(
		'my' => array(
			'title' => '个人中心',
			'url' => 'my/index'
		),
		'goods' => array(
			'title' => '求货区',
			'url' => 'goods/index'
		),
	),
	'side' => array(
		'my' => array(
			array('title' => '基本资料','url' => 'my/index'),
			array('title' => '消息通知','url' => 'pm/index'),
			array('title' => '修改密码','url' => 'my/change_psw'),
			array('title' => '修改手机','url' => 'my/change_mobile','hidden' => true),
			array('title' => '提醒设置','url' => 'notify/index'),
			array('title' => '卖家认证','url' => 'my/seller_verify'),
		),
		'pm' => 'my',
		'goods' => array(
			array('title' => '货品查询','url' => 'goods/index')
		),
	),
	'sub' => array (
		
	)
);

$config['permission'] = array(
	'my/change_mobile' => true,
	'my/change_psw' => true,
	'my/index' => true,
	'my/nopermission' => true,
	'my/seller_verify' => true,
	'my/set_email' => true,
	'my/set_avatar' => true,
	'my/trade_upload' => true,
	'my/upload_avatar' => true,
	'my/verify_email' => true,
	
	'my_pm/check_newpm' => true,
	'my_pm/delete' => true,
	'my_pm/detail' => true,
	'my_pm/index' => true,
	'my_pm/sendpm' => true,
	'my_pm/setread' => true,
	'my_pm/setting' => true,
	
	'lab/orglist' => true,
	
);
