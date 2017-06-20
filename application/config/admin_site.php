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
			'title' => '操作员',
			'url' => 'member/index'
		),
		'building' => array(
			'title' => '存量建筑',
			'url' => 'building/index'
		),
		/*
		'goods' => array(
			'title' => '商品',
			'url' => 'goods/index'
		),
		'goods_class' => array(
			'title' => '商品分类',
			'url' => 'goods_class/category'
		),*/
		'article' => array(
			'title' => '网站',
			'url' => 'article/index'
		),
		'cms_article' => array(
			'title' => 'CMS管理',
			'url' => 'cms_article/index'
		),
		
		'role' => array(
			'title' => '权限',
			'url' => 'role/index'
		),
	),
	
	'side' => array (
		'dashboard' => array(
			array('title' => '欢迎页面','url' => 'dashboard/welcome')
		),
		'setting' => array(
			array('title' => '站点设置','url' => 'setting/base'),
			array('title' => '上传设置','url' => 'upload/param'),
			array('title' => 'SEO设置','url' => 'seo/index'),
			array('title' => '消息通知','url' => 'message/email'),
		),
		'message' => 'setting',//表示边栏菜单显示数据源一样
		'upload' => 'setting',
		'seo' => 'setting',
		'member' => array(
			array('title' => '操作员管理','url' => 'member/index'),
			array('title' => '操作员通知','url' => 'notify/index'),
			//array('title' => '操作员认证','url' => 'seller/index'),
		),
		'notify' => 'member',
		'seller' => 'member',
		'building' => array(
			array('title' => '地图浏览','url' => 'building/index'), 
			array('title' => '权利人','url' => 'person/index'), 
			array('title' => '房屋建筑','url' => 'house/index'), 
		),
		'person' => 'building',
		'house' => 'building',
		'goods' => array(
			array('title' => '商品','url' => 'goods/index'),
			array('title' => '商品分类','url' => 'goods_class/category'),
			array('title' => '品牌','url' => 'brand/index'),
		),
		'goods_class' => 'goods',
		'brand' => 'goods',
		'article' => array(
			array('title' => '文章管理','url' => 'article/index'),
			array('title' => '文章分类','url' => 'article_class/category'),
		),
		'article_class' => 'article',
		'cms_article' =>  array(
			array('title' => 'CMS文章','url' => 'cms_article/index'),
			array('title' => 'CMS文章分类','url' => 'cms_article_class/category'),
			array('title' => 'CMS设置','url' => 'cms/index'),
		),
		'cms_article_class' => 'cms_article',
		'cms' => 'cms_article',
		'role' => array(
			array('title' => '角色','url' => 'role/index'),
			array('title' => '管理员','url' => 'user/index'),
		),
		'user' => 'role'
	),
	
	/* 子导航的父级 */
	'sub_parent' => array(
		'setting/base' => array('url' => 'setting/base','title' => '站点设置'),
		'setting/dump' => array('url' => 'setting/base','title' => '站点设置'),
		'message/email' => array('url' => 'message/email','title' => '站点设置'),
		'message/email_tpl' => array('url' => 'message/email','title' => '站点设置'),
		'upload/param' => array('url' => 'upload/param','title' => '站点设置'),
		'upload/default_image' => array('url' => 'upload/param','title' => '站点设置'),
	),
	'sub' => array (
		'dashboard' => array(
			'dashboard/welcome' => '欢迎页面'
		),
		'setting' => array(
			'setting/base' => '基本设置',
			'setting/dump' => '防灌水设置',
		),
		'message' => array(
			'message/email' => '邮件设置',
			'message/email_tpl' => '邮件模版',
		),
		'upload' => array(
			'upload/param' => '上传参数',
			'upload/default_image' => '默认图片',
		),
		'seo' => array(
			
		),
		'member' => array(
			'member/index' => '管理',
			'member/add' => '添加',
		),
		'notify' => array(
			'notify/index' => '通知管理',
			'notify/add' => '添加通知'
		),
		
		'goods' => array(
			'goods/index' => '管理',
			'goods/add' => '添加'
		),
		'goods_class' => array(
			'goods_class/category' => '管理',
			'goods_class/add' => '添加',
			'goods_class/export' => '导出',
			'goods_class/import' => '导入',
			//'goods_class/tag' => 'TAG管理'
		),
		'brand' => array(
			'brand/index' => '管理',
			'brand/add' => '添加',
		),
		'article' => array(
			'article/index' => '管理',
			'article/add' => '添加',
		),
		'article_class' => array(
			'article_class/category' => '管理',
			'article_class/add' => '添加',
		),
		'cms_article' => array(
			'cms_article/index' => '管理',
			'cms_article/add' => '添加',
		),
		'cms_article_class' => array(
			'cms_article_class/category' => '管理',
			'cms_article_class/add' => '添加',
		),
		'cms' => array(
			'cms/index' => 'CMS设置',
		),
		'role' => array(
			'role/index' => '管理',
			'role/add' => '添加'
		),
		'user' => array(
			'user/index' => '管理',
			'user/add' => '添加'
		)
	)
);

