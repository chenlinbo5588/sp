<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['cache_driver'] = 'file';

$config['log_threshold'] = 2;


$config['base_url'] = '';
$config['site_domain'] = $_SERVER['SERVER_NAME'];

//$config['csrf_protection'] = FALSE;
$config['page_size'] = 15;



//站内信更新检查时间间隔
$config['pmcheck_interval'] = 5;