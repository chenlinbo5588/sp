<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'int',
'uid' => 'int',
'from_uid' => 'int',
'site_msgid' => 'int',
'msg_type' => 'tinyint',
'readed' => 'tinyint',
'msg_direction' => 'tinyint',
'title' => 'varchar',
'content' => 'text',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
