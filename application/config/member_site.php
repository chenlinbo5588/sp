<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['navs'] = array(
	'main' => array(
		'lab' => array('url' => 'lab/index','title' => '实验室'),
		'lab_user' => array('url' => 'lab_user/index','title' => '实验员'),
		'lab_goods' => array('url' => 'lab_goods/index','title' => '货品'),
		'lab_gcate' => array('url' => 'lab_gcate/index','title' => '货品分类'),
		'lab_measure' => array('url' => 'lab_measure/index','title' => '度量单位'),
		'lab_authority' => array('url' => 'lab_authority/index','title' => '权限'),
	),
	'side' => array(
		'lab' => array(
			array('url' => 'lab/index' , 'title'=> '实验室管理'),
			array('url' => 'lab_setting/index' , 'title'=> '实验室设置'),
		),
		'lab_user' => array(
			array('url' => 'lab_user/index' , 'title'=> '实验员管理'),
		),
		'lab_goods' => array(
			array('url' => 'lab_goods/index' , 'title'=> '货品管理'),
		),
		'lab_gcate' => array(
			array('url' => 'lab_gcate/index' , 'title'=> '货品分类管理'),
		),
		'lab_measure' => array(
			array('url' => 'lab_measure/index' , 'title'=> '度量单位管理'),
		),
		'lab_authority' => array(	
			array('url' => 'lab_authority/index' , 'title'=> '角色管理'),
		)
	),
	
	'sub' => array (
		'lab' => array(
			'lab/index' => '管理',
			'lab/add' => '添加',
			'lab/export' => '导出'
		),
		'lab_user' => array(
			'lab_user/index' => '管理',
			'lab_user/add' => '添加',
			'lab_user/export' => '导出'
		),
		'lab_goods' => array(
			'lab_goods/index' => '管理',
			'lab_goods/add' => '添加',
			'lab_goods/import' => '导入',
			'lab_goods/empty_goods' => '清空',
		),
		'lab_gcate' => array(
			'lab_gcate/index' => '管理',
			'lab_gcate/add' => '添加',
		),
		'lab_measure' => array(
			'lab_measure/index' => '管理',
			'lab_measure/add' => '添加',
		),
		'lab_authority' => array(
			'lab_authority/role' => '管理',
			'lab_authority/role_add' => '添加',
		)
	)
);
