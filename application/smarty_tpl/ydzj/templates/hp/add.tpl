{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    <style>
    label.error {
    	display:block;
    }
    </style>
    <div id="pubwrap">
	    <div class="tip">一次最多可发布20个货品,再次发布求货时间间隔<span class="hightlight">3分钟</span>,求货信息默认过期时间<span class="hightlight">90分钟</span>,过期后请用户点击<a class="hightlight" href="{site_url('my_req/index')}">我的求货</a>菜单进行主动刷新</div>
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
	                    <th><label class="required"><em>*</em>{#goods_color#}(最长5个字)</label></th>
	                    <th><label class="required"><em>*</em>{#goods_size#}(0 - 60)</label></th>
	                    <th><label class="required"><em>*</em>{#quantity#}(1-100)</label></th>
	                    <th><label class="required"><em>*</em>{#sex#}</label></th>
	                    <th><label class="required"><em>*</em>{#accept#}{#price_max#}</label></th>
	                    <th>{#send_zone#}</th>
	                    <th>{#send_day#}</th>
	                    <th>{#op#}</th>
	                </tr>
	            </thead>
	            <tbody  id="bodyContent">
	            {foreach from=$initRow item=item}
	                <tr>
	                    <td>{$item + 1}</td>
			            <td><input type="text" name="goods_code[]" value="{$postData['goods_code'][$item]|escape}" placeholder="请输入{#goods_code#}"/>{form_error('goods_code'|cat:$item)}</td>
			            <td><input type="text" name="goods_name[]" value="{$postData['goods_name'][$item]|escape}" placeholder="请输入{#goods_name#}"/>{form_error('goods_name'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="goods_color[]" value="{$postData['goods_color'][$item]|escape}" placeholder="如:白"/>{form_error('goods_color'|cat:$item)}</td>
			            <td>
			             <a class="fa fa-long-arrow-down" href="javascript:void(0)" ></a>
			             <input type="text" class="w36" name="goods_size[]" value="{$postData['goods_size'][$item]|escape}" placeholder="如:41"/>
			             <a href="javascript:void(0)" class="fa fa-long-arrow-up"></a>{form_error('goods_size'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="quantity[]" value="{if $postData['quantity'][$item]}{$postData['quantity'][$item]|escape}{else}1{/if}" placeholder="请输入{#quantity#}"/>{form_error('quantity'|cat:$item)}</td>
			            <td><select class="w60" name="sex[]">
			                    <option value="1" {if $postData['sex'][$item] == 1}selected{/if}>男</option>
			                    <option value="2" {if $postData['sex'][$item] == 2}selected{/if}>女</option>
			            </select>{form_error('sex'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="price_max[]" value="{$postData['price_max'][$item]|escape}" placeholder="{#price_max#}"/>{form_error('price_max'|cat:$item)}</td>
			            <td><input type="text" name="send_zone[]" value="{$postData['send_zone'][$item]|escape}" placeholder="请输入{#send_zone#}"/>{form_error('send_zone'|cat:$item)}</td>
			            <td><input type="text" name="send_day[]" class="datepicker" value="{$postData['send_day'][$item]|escape}" placeholder="请输入{#send_day#}"/>{form_error('send_day'|cat:$item)}</td>
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
    {include file="common/jquery_validation.tpl"}
    <script type="text/x-template" id="rowTpl">
        <tr>
            <td></td>
            <td><input type="text" name="goods_code[]" value="" placeholder="请输入{#goods_code#}"/></td>
            <td><input type="text" name="goods_name[]" value="" placeholder="请输入{#goods_name#}"/></td>
            <td><input type="text" class="w60" name="goods_color[]" value="" placeholder="如:白"/></td>
            <td>
                <a class="fa fa-long-arrow-down" href="javascript:void(0)" ></a>
                <input type="text" class="w36" name="goods_size[]" value="" placeholder="如:41"/>
                <a href="javascript:void(0)" class="fa fa-long-arrow-up"></a>
           </td>
            <td><input type="text" class="w60" name="quantity[]" value="1" placeholder="请输入{#quantity#}"/></td>
            <td><select class="w60" name="sex[]">
	                <option value="1">男</option>
	                <option value="2">女</option>
            </select></td>
            <td><input type="text" class="w60" name="price_max[]" value="" placeholder="{#price_max#}"/></td>
            <td><input type="text" name="send_zone[]" value="" placeholder="请输入{#send_zone#}"/></td>
            <td><input type="text" name="send_day[]" value="" class="datepicker" placeholder="请输入{#send_day#}"/></td>
            <td>
            	<a class="incre copyrow" href="javascript:void(0);">+1码</a>&nbsp;
			    <a class="decre copyrow" href="javascript:void(0);">-1码</a>&nbsp;
            	<a class="deleterow" href="javascript:void(0);">删除</a>
           	</td>
        </tr>
    </script>
    <script>
    	$(function(){
    	
    		
    	});
    	
    </script>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script type="text/javascript" src="{resource_url('js/my/hp.js')}"></script>
{include file="common/my_footer.tpl"}

