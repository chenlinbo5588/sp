<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['mapGroup'] = 'CGCS2000';

$config['arcgis_server_ip'] = '127.0.0.1';
//$config['arcgis_server_ip'] = '192.168.5.120';
$config['arcgis_server'] = 'http://'.$config['arcgis_server_ip'].'/arcgis/rest/services/';

$config['feature_url'] = array(
	//'sample' => 'sample/FeatureServer/0/'
	'wzd' => 'zqwj/cljz/FeatureServer/1/'
);


$config['CGCS2000'] = array(
	'工具' => array(
		'几何' => 'Utilities/Geometry/GeometryServer'
	),
	'基本要素' => array(
		'底图' => 'basemapzq/MapServer',
		'村界' => 'zqwj/cljz/MapServer/4',
		'存量建筑要素' => 'towncljz/jzd/MapServer'
	),
	'编辑要素' => array(
		//'存量建筑' => 'zqwj/cljz/FeatureServer/1/',
		'标准建筑点' => 'towncljz/jzd/FeatureServer/1'
	)
);

