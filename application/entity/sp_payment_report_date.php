<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$entity = array(
'id' => 'int',
'date' => 'int',
'week' => 'int',
'month' => 'int',
'year' => 'int',
'resident_id' => 'int',
'resident_name' => 'varchar',
'feetype_name' => 'varchar',
'collection_rate' => 'float',
'count_amount_real' => 'float',
'count_amount_payed' => 'float',
'amount_increment' => 'float',
'old_amount_payed' => 'float',
'count_households' => 'int',
'paid_households' => 'int',
'self_paid_households' => 'int',
'other_paid_households' => 'int',
'paid_increment' => 'int',
'gmt_create' => 'int',
'gmt_modify' => 'int'
);
