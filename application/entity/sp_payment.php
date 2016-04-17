<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'payment_id' => 'tinyint',
'payment_code' => 'char',
'payment_name' => 'char',
'payment_config' => 'text',
'payment_state' => 'enum',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
