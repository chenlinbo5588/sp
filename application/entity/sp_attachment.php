<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'mediumint',
'file_name' => 'varchar',
'file_type' => 'varchar',
'file_ext' => 'varchar',
'file_url' => 'varchar',
'raw_name' => 'char',
'file_size' => 'int',
'is_image' => 'tinyint',
'image_width' => 'int',
'image_height' => 'int',
'image_type' => 'varchar',
'status' => 'tinyint',
'expire_time' => 'int',
'uid' => 'mediumint',
'from_bg' => 'tinyint',
'gmt_create' => 'int',
'gmt_modify' => 'int',
'ip' => 'varchar'
);
