<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['navs'] = array(
	'main' => array(
		'lab' => array(
			'title' => '实验室',
			'url' => 'lab/index'
		),
		'lab_user' => array(
			'title' => '实验员',
			'url' => 'lab_user/index'
		),
		'lab_goods' => array(
			'title' => '货品',
			'url' => 'member/index'
		),
		'lab_goodscate' => array(
			'title' => '货品分类',
			'url' => 'goods/index'
		),
		'lab_measure' => array(
			'title' => '度量单位',
			'url' => 'lab_measure/index'
		),
		'lab_authority' => array(
			'title' => '权限',
			'url' => 'lab_authority/role'
		),
	),
	'side' => array(
		'lab' => array(
			array(
				'title' => '实验室',
				'url' => 'lab/index'
			)
		),
		'lab_user' => array(
			array(
				'title' => '实验员',
				'url' => 'lab_user/index'
			)
		),
		'lab_goods' => array(
			array(
				'title' => '货品',
				'url' => 'member/index'
			)
			
		),
		'lab_goodscate' => array(
			array(
				'title' => '货品分类',
				'url' => 'lab_goodscate/index'
			)
			
		),
		'lab_measure' => array(
			array(
				'title' => '度量单位',
				'url' => 'lab_measure/index'
			)
		),
		'lab_authority' => array(
			array(
				'title' => '角色',
				'url' => 'lab_authority/index'
			)
		)
	),
	
	'sub' => array (
		'lab' => array(
			array('title' => '管理','url' => 'lab/index'),
			array('title' => '添加','url' => 'lab/add'),
			array('title' => '导出','url' => 'lab/export'),
		),
		'lab_user' => array(
			array('title' => '管理','url' => 'lab_user/index'),
			array('title' => '添加','url' => 'lab_user/add'),
			array('title' => '导出','url' => 'lab_user/export'),
		),
		'lab_goods' => array(
			array('title' => '管理','url' => 'lab_goods/index'),
			array('title' => '添加','url' => 'lab_goods/add'),
			array('title' => '导入','url' => 'lab_goods/import'),
			array('title' => '清空','url' => 'lab_goods/empty_goods'),
		),
		'lab_goodscate' => array(
			array('title' => '管理','url' => 'lab_goodscate/index'),
			array('title' => '添加','url' => 'lab_goodscate/add'),
		),
		'lab_measure' => array(
			array('title' => '管理','url' => 'lab_measure/index'),
			array('title' => '添加','url' => 'lab_measure/add'),
		),
		'lab_authority' => array(
			array('title' => '管理','url' => 'lab_authority/role'),
			array('title' => '添加','url' => 'lab_authority/role_add')
		)
	)
);
