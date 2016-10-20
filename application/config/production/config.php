<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['cache_driver'] = 'redis';

$config['log_threshold'] = 1;

$config['base_url'] = '';
$config['site_domain'] = $_SERVER['SERVER_NAME'];

//$config['csrf_protection'] = FALSE;
$config['page_size'] = 30;


// 货品 90 分钟自动过期
$config['hp_expired'] = 5400;


// 发布求货 冻结时间间隔 秒数
$config['hp_pub_freezen'] = 300;

// 库存更新冻结时间间隔
$config['inventory_freezen'] = 300;

// 库存 过期时间  180 分钟过期
$config['inventory_expired'] = 10800;


// 消息更新时间间隔 秒数   这个配合比较重要，建议不要小于60, 过小会造成系统负荷过重
$config['pmcheck_interval'] = 60;


$config['compress_output'] = true;

