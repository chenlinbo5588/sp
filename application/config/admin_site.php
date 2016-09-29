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
			'title' => '商品',
			'url' => 'goods/index'
		),
		'goods_class' => array(
			'title' => '商品分类',
			'url' => 'goods_class/category'
		),
		'article' => array(
			'title' => '网站',
			'url' => 'article/index'
		),
		'cms_article' => array(
			'title' => 'CMS管理',
			'url' => 'cms_article/index'
		),
		
		'authority' => array(
			'title' => '权限',
			'url' => 'authority/role'
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
			array('title' => '会员管理','url' => 'member/index'),
		),
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
			array('title' => 'CMS文章分类','url' => 'cms_article_class/index'),
			array('title' => 'CMS设置','url' => 'cms/index'),
		),
		'cms_article_class' => 'cms_article',
		'cms' => 'cms_article',
		'authority' => array(
			array('title' => '角色','url' => 'authority/role'),
			array('title' => '管理员','url' => 'authority/user'),
		)
	),
	
	'sub' => array (
		'dashboard' => array(
			array('title' => '欢迎页面','url' => 'dashboard/welcome')
		),
		'setting' => array(
			array('title' => '基本设置','url' => 'setting/base'),
			array('title' => '防灌水设置','url' => 'setting/dump'),
		),
		'message' => array(
			array('title' => '基本设置','url' => 'setting/base'),
			array('title' => '防灌水设置','url' => 'setting/dump'),
		),
		'upload' => array(
			array('title' => '上传参数','url' => 'upload/param'),
			array('title' => '默认图片','url' => 'upload/default_image'),
		),
		'seo' => array(
			
		),
		'message' => array(
			array('title' => '基本设置','url' => 'message/email'),
			array('title' => '防灌水设置','url' => 'message/email_tpl'),
		),
		'member' => array(
			array('title' => '会员管理','url' => 'member/index'),
			array('title' => '会员添加','url' => 'member/add'),
		),
		'goods' => array(
			array('title' => '管理','url' => 'goods/index'),
			array('title' => '添加','url' => 'goods/add')
		),
		'goods_class' => array(
			array('title' => '管理','url' => 'goods_class/category'),
			array('title' => '添加','url' => 'goods_class/add'),
			array('title' => '导出','url' => 'goods_class/export'),
			array('title' => '导入','url' => 'goods_class/import'),
			array('title' => 'TAG管理','url' => 'goods_class/tag'),
		),
		'brand' => array(
			array('title' => '管理','url' => 'brand/index'),
			array('title' => '添加','url' => 'brand/add')
		),
		'article' => array(
			array('title' => '管理','url' => 'article/index'),
			array('title' => '添加','url' => 'article_class/category'),
		),
		'article_class' => array(
			array('title' => '管理','url' => 'article_class/category'),
			array('title' => '添加','url' => 'article_class/add'),
		),
		
		'cms_article' => array(
			array('title' => '管理','url' => 'cms_article/index'),
			array('title' => '添加','url' => 'cms_article/add'),
		),
		'cms_article_class' => array(
			array('title' => '管理','url' => 'cms_article_class/index'),
			array('title' => '添加','url' => 'cms_article_class/add'),
		),
		'cms' => array(
			array('title' => '设置','url' => 'cms/index'),
		),
		'authority' => array(
			array('title' => '角色','url' => 'authority/role'),
		)
	)
);


