<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['cache_driver'] = 'redis';

$config['log_threshold'] = 2;


$config['base_url'] = '';
$config['site_domain'] = $_SERVER['SERVER_NAME'];

//$config['csrf_protection'] = FALSE;
$config['page_size'] = 20;


// 货品  90 分钟过期
$config['hp_expired'] = 5400;

// 库存 过期时间  180 分钟过期
$config['inventory_expired'] = 10800;

// 发布求货 冻结时间间隔 秒数
$config['hp_pub_freezen'] = 15;

// 库存激活冻结时间间隔
$config['inventory_freezen'] = 10;

//站内信更新检查时间间隔
$config['pmcheck_interval'] = 5;