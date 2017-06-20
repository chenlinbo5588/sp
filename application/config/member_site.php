<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['navs'] = array(
	'main' => array(
		'my' => array(
			'title' => '个人中心',
			'url' => 'my/index'
		),
		'hp' => array(
			'title' => '求货区',
			'url' => 'hp/index'
		),
		'inventory' => array(
			'title' => '库存',
			'url' => 'inventory/index'
		),
		'person' => array(
			'title' => '数据管理',
			'url' => 'person/index'
		),
		'house' => array(
			'title' => '数据管理',
			'url' => 'house/index'
		)
	),
	'side' => array(
		'my' => array(
			array('title' => '基本资料','url' => 'my/index'),
			array('title' => '消息通知','url' => 'pm/index'),
			array('title' => '修改密码','url' => 'my/change_psw'),
			array('title' => '修改手机','url' => 'my/change_mobile'),
			array('title' => '卖家认证','url' => 'my/seller_verify'),
		),
		'person' => array(
			array('title' => '权利人管理','url' => 'person/index')
		),
		'house' => array(
			array('title' => '房屋建筑管理','url' => 'house/index'),
			array('title' => '地图浏览','url' => 'house/map'),
		),
		'my_pm' => 'my',
		
		
	),
	'sub' => array (
		
	)
);
