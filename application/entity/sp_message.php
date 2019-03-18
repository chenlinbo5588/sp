<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'int',
'message_from' => 'int',
'message_to' => 'int',
'message_type' => 'tinyint',
'title' => 'varchar',
'content' => 'varchar',
'read_status' => 'tinyint',
'add_uid' => 'int',
'add_username' => 'varchar',
'edit_uid' => 'int',
'edit_username' => 'varchar',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
