<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'int',
'week' => 'int',
'month' => 'int',
'year' => 'int',
'resident_id' => 'int',
'resident_name' => 'varchar',
'feetype_name' => 'varchar',
'collection_rate' => 'float',
'amount_real' => 'float',
'amount_payed' => 'float',
'amount_increment' => 'float',
'old_amount_payed' => 'float',
'all_hushu' => 'int',
'paid_hushu' => 'int',
'selfpaid_hushu' => 'int',
'otherpaid_hushu' => 'int',
'paid_increment' => 'int',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
