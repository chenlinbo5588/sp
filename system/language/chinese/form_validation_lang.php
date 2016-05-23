<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['form_validation_required']		= '{field}必填';
$lang['form_validation_isset']			= '{field}必须有一个值';
$lang['form_validation_valid_email']		= '{field}必须是一个有效的邮箱';
$lang['form_validation_valid_emails']		= '{field}必须都是有效的邮箱地址';
$lang['form_validation_valid_url']		= '{field}必须是一个有效的URL';
$lang['form_validation_valid_ip']		= '{field}必须是一个有效的IP地址';
$lang['form_validation_min_length']		= '{field}最小长度{param}个字符';
$lang['form_validation_max_length']		= '{field}最大长度{param}个字符';
$lang['form_validation_exact_length']		= '{field}长度必须等于{param}个字符';
$lang['form_validation_alpha']			= '{field}只能为英文字母';
$lang['form_validation_alpha_numeric']		= '{field}只能包含英文字母和数字';
$lang['form_validation_alpha_numeric_spaces']	= '{field}只能包含英文字母、数字、空格';
$lang['form_validation_alpha_dash']		= '{field}只能包含英文字母、数字、下划线、破折号';
$lang['form_validation_numeric']		= '{field}只能为数字';
$lang['form_validation_is_numeric']		= '{field}只能为数字字符';
$lang['form_validation_integer']		= '{field}只能为整数';
$lang['form_validation_regex_match']		= '{field}格式不正确';
$lang['form_validation_matches']		= '{field}与{param}不匹配';
$lang['form_validation_differs']		= '{field}与{param}不能相同.';
$lang['form_validation_is_unique'] 		= '{field}已被占用';
$lang['form_validation_is_natural']		= '{field}只能是一个大于等于0整数';
$lang['form_validation_is_natural_no_zero']	= '{field}只能是一个大于0的整数';
$lang['form_validation_decimal']		= '{field}必须包含小数的数字';
$lang['form_validation_less_than']		= '{field}必须是小于{param}的数字';
$lang['form_validation_less_than_equal_to']	= '{field}必须是小于等于{param}的数字';
$lang['form_validation_greater_than']		= '{field}必须是大于{param}的数字';
$lang['form_validation_greater_than_equal_to']	= '{field}必须是大于等于{param}的数字';
$lang['form_validation_error_message_not_set']	= 'Unable to access an error message corresponding to your field name {field}.';
$lang['form_validation_in_list']		= '{field}必须为{param}其中之一';


//custome method
$lang['form_validation_validateAuthCode']						= '{field} 输入不正确.';
$lang['form_validation_valid_date']						= '{field}必须是一个有效的日期.';
$lang['form_validation_valid_mobile']					= '{field}必须是一个有效的手机号码.';
$lang['form_validation_valid_telephone']				= '{field}必须是一个有效的固定电话号码.';
$lang['form_validation_is_unique_by_status'] 			= '{field}已被占用 .';
$lang['form_validation_is_unique_not_self'] 			= '{field}已被占用.';
$lang['form_validation_in_db_list']		= '{field}无效';
