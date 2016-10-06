<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'mediumint',
'uid' => 'mediumint',
'from_uid' => 'mediumint',
'site_msgid' => 'mediumint',
'msg_type' => 'tinyint',
'readed' => 'tinyint',
'msg_direction' => 'tinyint',
'title' => 'varchar',
'content' => 'text',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
