<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'mediumint',
'msg_type' => 'tinyint',
'title' => 'varchar',
'content' => 'text',
'msg_mode' => 'tinyint',
'send_ways' => 'varchar',
'groups' => 'text',
'users' => 'text',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
