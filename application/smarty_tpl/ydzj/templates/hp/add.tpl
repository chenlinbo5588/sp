{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    <style>
    label.error {
    	display:block;
    }
    </style>
    <div id="pubwrap">
    
    
        <div class="w-tixing clearfix"><b>温馨提醒：</b>
            <p>1. 一次最多可发布{$maxRowPerReq}个货品,再次发布求货时间间隔<span class="hightlight">5分钟</span></p>
            <p>2. 求货信息默认过期时间<span class="hightlight">90分钟</span>,过期后请用户点击<a class="hightlight" href="{site_url('my_req/recent')}">我的求货</a>菜单进行主动刷新</p>
            <p>3. 同一个货号和尺码的如果已发布且未过期的求货，不可重复发布,即使发布提交也将被忽略。</p>
         </div>
		<form action="{site_url($uri_string)}" method="post" id="pubForm">
			<div>
	            <input type="button" name="addrow" class="action" value="增加一行"/>
	            <input type="button" name="clearall" class="action" value="清空"/>
	            <input type="submit" name="tijiao" class="master_btn" value="发布"/>
	        </div>
	        <table class="fulltable noext">
	            <thead>
	                <tr>
	                    <th>序号</th>
	                    <th><label class="required"><em>*</em>{#goods_code#}</label></th>
	                    <th><label class="required"><em>*</em>{#goods_name#}</label></th>
	                    <th><label class="required"><em>*</em>{#goods_color#}</label></th>
	                    <th><label class="required"><em>*</em>{#goods_size#}</label></th>
	                    <th><label class="required"><em>*</em>{#quantity#}</label></th>
	                    <th><label class="required"><em>*</em>{#sex#}</label></th>
	                    <th><label class="required"><em>*</em>{#price_max#}</label></th>
	                    <th>{#price_status#}</th>
	                    <th>{#send_zone#}</th>
	                    <th>{#send_day#}</th>
	                    <th>{#op#}</th>
	                </tr>
	            </thead>
	            <tbody  id="bodyContent">
	            {foreach from=$initRow item=item}
	                <tr>
	                    <td>{$item + 1}</td>
			            <td><input type="text" name="goods_code[]" value="{$postData['goods_code'][$item]|escape}" title="只允许英文字母数字下划线、破折号,最长15个字符" placeholder="请输入货号"/>{form_error('goods_code'|cat:$item)}</td>
			            <td><input type="text" name="goods_name[]" value="{$postData['goods_name'][$item]|escape}" title="可自定义名称,中文或英文,最长15个字符" placeholder="请输入货品名称"/>{form_error('goods_name'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="goods_color[]" value="{$postData['goods_color'][$item]|escape}" placeholder="如:白"/>{form_error('goods_color'|cat:$item)}</td>
			            <td>
			             <a class="fa fa-long-arrow-down" href="javascript:void(0)" ></a>
			             <input type="text" class="w60" name="goods_size[]" title="{#size_example#}" value="{$postData['goods_size'][$item]|escape}" placeholder="{#size_example#}"/>
			             <a href="javascript:void(0)" class="fa fa-long-arrow-up"></a>{form_error('goods_size'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="quantity[]" value="{if $postData['quantity'][$item]}{$postData['quantity'][$item]|escape}{else}1{/if}" placeholder="请输入{#quantity#}"/>{form_error('quantity'|cat:$item)}</td>
			            <td><select class="w60" name="sex[]">
			                    <option value="1" {if $postData['sex'][$item] == 1}selected{/if}>男</option>
			                    <option value="2" {if $postData['sex'][$item] == 2}selected{/if}>女</option>
			            </select>{form_error('sex'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="price_max[]" title="{#price_see#}" value="{$postData['price_max'][$item]|escape}" placeholder="{#price_max#}"/>{form_error('price_max'|cat:$item)}</td>
			            <td><input type="checkbox" name="price_status[]" title="{#price_cansee#}" {if $postData['price_status'][$item] == 1}checked{/if} value="1" placeholder="{#price_status#}"/>{form_error('price_status'|cat:$item)}</td>
			            <td><input type="text" name="send_zone[]" value="{$postData['send_zone'][$item]|escape}" placeholder="请输入{#send_zone#}"/>{form_error('send_zone'|cat:$item)}</td>
			            <td><input type="text" name="send_day[]" class="w72 datepicker" value="{$postData['send_day'][$item]|escape}" placeholder="{#send_day#}"/>{form_error('send_day'|cat:$item)}</td>
			            <td>
			                 <a class="incre copyrow" href="javascript:void(0);">+1码</a>&nbsp;
			                 <a class="decre copyrow" href="javascript:void(0);">-1码</a>&nbsp;
			                 <a class="deleterow" href="javascript:void(0);">删除</a>
			            </td>
			        </tr>
			    {/foreach}
	            </tbody>
	        </table>
	    </form>
    </div>
    <script type="text/x-template" id="rowTpl">
        <tr>
            <td></td>
            <td><input type="text" name="goods_code[]" value="" title="只允许英文字母数字下划线、破折号,最长15个字符" placeholder="请输入货号"/></td>
            <td><input type="text" name="goods_name[]" value="" title="可自定义名称,中文或英文,最长15个字符" placeholder="请输入货品名称"/></td>
            <td><input type="text" class="w60" name="goods_color[]" value="" placeholder="如:白"/></td>
            <td>
                <a class="fa fa-long-arrow-down" href="javascript:void(0)" ></a>
                <input class="w60" type="text" class="" title="{#size_example#}" name="goods_size[]" value="" placeholder="{#size_example#}"/>
                <a href="javascript:void(0)" class="fa fa-long-arrow-up"></a>
           </td>
            <td><input type="text" class="w60" name="quantity[]" value="1" placeholder="请输入{#quantity#}"/></td>
            <td><select class="w60" name="sex[]">
	                <option value="1">男</option>
	                <option value="2">女</option>
            </select></td>
            <td><input type="text" class="w60" name="price_max[]" title="{#price_see#}" value="" placeholder="{#price_max#}"/></td>
            <td><input type="checkbox" name="price_status[]" title="{#price_cansee#}" value="1" placeholder="{#price_status#}"/></td>
            <td><input type="text" name="send_zone[]" value="" placeholder="请输入{#send_zone#}"/></td>
            <td><input type="text" name="send_day[]" value="" class="w72 datepicker" placeholder="{#send_day#}"/></td>
            <td>
            	<a class="incre copyrow" href="javascript:void(0);">+1码</a>&nbsp;
			    <a class="decre copyrow" href="javascript:void(0);">-1码</a>&nbsp;
            	<a class="deleterow" href="javascript:void(0);">删除</a>
           	</td>
        </tr>
    </script>
    <script>
    	var maxRow = {$maxRowPerReq};
    </script>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script type="text/javascript" src="{resource_url('js/my/hp.js')}"></script>
{include file="common/my_footer.tpl"}

