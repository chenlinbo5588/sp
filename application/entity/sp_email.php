<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'int',
'msg_type' => 'tinyint',
'uid' => 'int',
'username' => 'varchar',
'email' => 'varchar',
'title' => 'varchar',
'content' => 'text',
'retry' => 'tinyint',
'is_send' => 'tinyint',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
