<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['navs'] = array(
	'main' => array(
		'dashboard' => array(
			'title' => '控制台',
			'url' => 'dashboard/welcome'
		),
		'setting' => array(
			'title' => '设置',
			'url' => 'setting/base'
		),
		'member' => array(
			'title' => '会员',
			'url' => 'member/index'
		),
		'goods' => array(
			'title' => '货品',
			'url' => 'goods/index'
		),
		/*
		'weixin' => array(
			'title' => '微信',
			'url' => 'weixin/index'
		),*/
		'authority' => array(
			'title' => '权限',
			'url' => 'authority/role'
		),
	),
	'side' => array (
		'index' => 'dashboard',
		'dashboard' => array(
			array('title' => '欢迎页面','url' => 'dashboard/welcome'),
			array('title' => '关于我们','url' => 'dashboard/aboutus')
		),
		'setting' => array(
			array('title' => '站点设置','url' => 'setting/base'),
			array('title' => '上传设置','url' => 'upload/param'),
			array('title' => 'SEO设置','url' => 'seo/index'),
			array('title' => '消息通知','url' => 'message/email'),
		),
		'goods' => array(
			array('title' => '今日求货','url' => 'goods/index'),
			array('title' => '历史求货','url' => 'goods/history'),
			array('title' => '品牌管理','url' => 'brand/index'),
			array('title' => '颜色管理','url' => 'color/index'),
			array('title' => '货品分类','url' => 'goods_class/category'),
		),
		'brand' => 'goods',
		'goods_class' => 'goods',
		'color' => 'goods',
		'message' => 'setting',
		'upload' => 'setting',
		'seo' => 'setting',
		
		'member' => array(
			array('title' => '会员管理','url' => 'member/index'),
		),
		'authority' => array(
			array('title' => '角色','url' => 'authority/role'),
		)
	)
);

