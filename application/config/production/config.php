<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['cache_driver'] = 'redis';

$config['log_threshold'] = 1;

$config['base_url'] = '';
$config['site_domain'] = $_SERVER['SERVER_NAME'];

//$config['csrf_protection'] = FALSE;
$config['page_size'] = 20;


// �����ϱȽ���Ҫ�����鲻ҪС��60, ��С�����ϵͳ���ɹ���
$config['pmcheck_interval'] = 90;


$config['compress_output'] = true;

