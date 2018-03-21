<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['mapGroup'] = 'CGCS2000';

$config['arcgis_server_ip'] = '127.0.0.1';
//$config['arcgis_server_ip'] = '192.168.5.120';
$config['arcgis_server'] = 'http://'.$config['arcgis_server_ip'].'/arcgis/rest/services/';



$config['basemapType'] = 'ArcGISDynamicMapServiceLayer';
//$config['basemapType'] = 'ArcGISTiledMapServiceLayer';

$config['CGCS2000'] = array(
	'工具' => array(
		'几何' => 'Utilities/Geometry/GeometryServer'
	),
	'基本要素' => array(
		'底图' => 'basemapfb/MapServer',
		//'村界' => 'zqwj/cljz/MapServer/4',
		'村界' => 'towncljz/xzqfb/MapServer/0',
		//'存量建筑要素' => 'towncljz/jzd/MapServer'
		'存量建筑要素' => 'towncljz/jzdfb/MapServer'
	),
	'编辑要素' => array(
		//'存量建筑' => 'zqwj/cljz/FeatureServer/1/',
		//'标准建筑点' => 'towncljz/jzd/FeatureServer/1'
		//'标准建筑点' => 'towncljz/jzdkd/FeatureServer/1'
		'标准建筑点' => 'towncljz/jzdfb/FeatureServer/0'
	)
);

