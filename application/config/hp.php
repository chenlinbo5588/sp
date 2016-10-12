<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 货品验证规则
 */
$config['hp_validation'] = array(
	/* 发布求货  */
	'hp_req' => array(
		'goods_code',
		'goods_name',
		'goods_color',
		'goods_size',
		'quantity',
		'sex',
		'price_max',
		'send_zone',
		'send_day',
	),
	/* 库存 */
	'inventory' => array(
		'goods_color',
		'goods_size',
		'quantity',
		'sex',
		'price_min',
	),
	'rule_list' => array(
		'goods_code' => array(
			'title' => '货号',
			'rules' => 'required|alpha_dash|min_length[1]|max_length[10]'
		),
		'goods_name' => array(
			'title' => '货名',
			'rules' => 'required|min_length[1]|max_length[10]'
		),
		'goods_color' => array(
			'title' => '颜色',
			'rules' => 'required|min_length[1]|max_length[10]'
		),
		'goods_size' => array(
			'title' => '尺码',
			'rules' => 'required|is_numeric|greater_than[1]|less_than[60]'
		),
		'quantity' => array(
			'title' => '数量',
			'rules' => 'required|is_natural_no_zero|less_than[100]'
		),
		'sex' => array(
			'title' => '性别',
			'rules' => 'required|in_list[1,2]'
		),
		'price_min' => array(
			'title' => '最低价',
			'rules' => 'required|is_numeric|greater_than[0]|less_than[100000]'
		),
		'price_max' => array(
			'title' => '最高价',
			'rules' => 'required|is_numeric|greater_than[0]|less_than[100000]'
		),
		'send_zone' => array(
			'title' => '发货地址',
			'rules' => 'required|max_length[10]'
		),
		'send_day' => array(
			'title' => '发货时间',
			'rules' => 'required|valid_date'
		)
	)
	
);
