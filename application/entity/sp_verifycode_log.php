<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'int',
'phone' => 'varchar',
'ip' => 'varchar',
'code' => 'varchar',
'expire_time' => 'int',
'send_normal' => 'tinyint',
'send_fail' => 'tinyint',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
