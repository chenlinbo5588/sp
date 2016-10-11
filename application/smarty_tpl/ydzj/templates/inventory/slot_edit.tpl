{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    <style>
    label.error {
    	display:block;
    }
    </style>
    <div id="pubwrap">
        <div class="tip">温馨提醒:货柜货品清空并保存后，可以对当前货柜的货号进行更改</div>
		<form action="{site_url($uri_string)}" method="post" id="pubForm">
		<input type="hidden" name="id" value="{$slotId}"/>
		    {if $userInventorySlot['goods_code']}
			<div>
	            <input type="button" name="addrow" class="action" value="增加一行"/>
	            <input type="button" name="clearall" class="action" value="清空"/>
	            <input type="submit" name="tijiao" class="master_btn" value="保存"/>
	        </div>
	        {/if}
	        <h1 class="slot_name">{#goods_slot#}标题:{$userInventorySlot['title']}&nbsp;{#goods_code#}:{if $userInventorySlot['goods_code']}{$userInventorySlot['goods_code']}{else}<a id="config_gc" class="warning" href="javascript:void(0);">尚未配置货号，请点击配置</a>{/if}<strong>&nbsp;容量:{$userInventorySlot['max_cnt']}</strong></h1>
	        <table class="fulltable noext">
	           
	            <thead>
	                <tr>
	                    <th>序号</th>
	                    <th><label class="required"><em>*</em>{#goods_color#}(最长5个字)</label></th>
	                    <th><label class="required"><em>*</em>{#goods_size#}(0 - 60)</label></th>
	                    <th><label class="required"><em>*</em>{#quantity#}(1-100)</label></th>
	                    <th><label class="required"><em>*</em>{#sex#}</label></th>
	                    <th><label class="required"><em>*</em>{#accept#}{#price_min#}</label></th>
	                    <th>{#op#}</th>
	                </tr>
	            </thead>
	            <tbody  id="bodyContent">
	            {foreach from=$initRow item=item}
	                <tr>
	                    <td>{$item + 1}</td>
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
			            <td><input type="text" class="w60" name="price_min[]" value="{$postData['price_min'][$item]|escape}" placeholder="{#price_min#}"/>{form_error('price_min'|cat:$item)}</td>
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
    <div id="goodsCodeDlg" title="{#goods_slot#}信息配置" style="display:none;">
        <div class="loading_bg" style="display:none;">发送中...</div>
        <form id="slotForm" action="{site_url('inventory/slot_gc')}" method="post">
            <input type="hidden" name="slot_id" value="{$slotId}"/>
	        <table class="fulltable noborder">
	            <tr><td><input type="text" class="at_txt" name="goods_code" value="" placeholder="请输入{#goods_code#}"/></td></tr>
	            <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="保存"/></td></tr>
	        </table>
        </form>
    </div>
    <script type="text/x-template" id="rowTpl">
        <tr>
        	<td></td>
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
            <td><input type="text" class="w60" name="price_min[]" value="" placeholder="{#price_min#}"/></td>
            <td>
            	<a class="incre copyrow" href="javascript:void(0);">+1码</a>&nbsp;
			    <a class="decre copyrow" href="javascript:void(0);">-1码</a>&nbsp;
            	<a class="deleterow" href="javascript:void(0);">删除</a>
           	</td>
        </tr>
    </script>
    <script>
    	var maxRow = {$maxRowPerSlot};
    	var goodsCodeFirst = "{$goodsCodeFirst}";
    </script>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script type="text/javascript" src="{resource_url('js/my/hp.js')}"></script>
    <script type="text/javascript" src="{resource_url('js/my/inventory.js')}"></script>
{include file="common/my_footer.tpl"}

