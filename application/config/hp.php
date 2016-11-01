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
		'price_status',
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
			'rules' => 'required|alpha_dash|min_length[1]|max_length[15]'
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
			'rules' => 'required|min_length[1]|max_length[10]'
		),
		'quantity' => array(
			'title' => '数量',
			'rules' => 'required|is_natural_no_zero|less_than[10000]'
		),
		'sex' => array(
			'title' => '性别',
			'rules' => 'required|in_list[1,2,男,女,通用]'
		),
		'price_status' => array(
			'title' => '显示价格',
			'rules' => 'required|in_list[0,1,是,否]'
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


$config['inventory_col'] = array(
	array(
		'col' => 'A',
		'name' => '货号',
		'width' => 20,
		'db_key' => 'goods_code'
	),
	array(
		'col' => 'B',
		'name' => '货名',
		'width' => 20,
		'db_key' => 'goods_name'
	),
	array(
		'col' => 'C',
		'name' => '尺码',
		'width' => 10,
		'db_key' => 'goods_size'
	),
	array(
		'col' => 'D',
		'name' => '颜色',
		'width' => 30,
		'db_key' => 'goods_color'
	),
	array(
		'col' => 'E',
		'name' => '性别',
		'width' => 8,
		'db_key' => 'sex'
	),
	array(
		'col' => 'F',
		'name' => '库存数量',
		'width' => 10,
		'db_key' => 'quantity'
	),
	array(
		'col' => 'G',
		'name' => '可接受最低价',
		'width' => 10,
		'db_key' => 'price_min'
	)
);

$config['hp_col'] = array(
	array(
		'col' => 'A',
		'name' => '货号',
		'width' => 20,
		'db_key' => 'goods_code'
	),
	array(
		'col' => 'B',
		'name' => '货名',
		'width' => 20,
		'db_key' => 'goods_name'
	),
	array(
		'col' => 'C',
		'name' => '尺码',
		'width' => 10,
		'db_key' => 'goods_size'
	),
	array(
		'col' => 'D',
		'name' => '颜色',
		'width' => 30,
		'db_key' => 'goods_color'
	),
	array(
		'col' => 'E',
		'name' => '性别',
		'width' => 8,
		'db_key' => 'sex'
	),
	array(
		'col' => 'F',
		'name' => '求货数量',
		'width' => 10,
		'db_key' => 'quantity'
	),
	array(
		'col' => 'G',
		'name' => '可接受最低价',
		'width' => 10,
		'db_key' => 'price_max'
	),
	array(
		'col' => 'H',
		'name' => '是否显示价格',
		'width' => 10,
		'db_key' => 'price_status'
	),
	array(
		'col' => 'I',
		'name' => '发货地址',
		'width' => 10,
		'db_key' => 'send_zone'
	),
	array(
		'col' => 'J',
		'name' => '要求发货时间',
		'width' => 10,
		'db_key' => 'send_day'
	)
);
		
