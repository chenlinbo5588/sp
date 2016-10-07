{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    <style type="text/css">
    label.error {
        1display:block;    
    }
    </style>
    <div id="pubwrap">
	    <div class="tip">一次最多可发布20个货品,求货信息默认过期时间<span class="hightlight">90分钟</span>,过期后请用户点击<a class="hightlight" href="{site_url('my_req/index')}">我的求货</a>菜单进行主动刷新</div>
		<form action="{site_url($uri_string)}" method="post" id="pubForm">
			<div>
	            <input type="button" name="addrow" class="action" value="增加一行"/>
	            <input type="button" name="sizecopy" class="action" value="尺码复制"/>
	            <input type="button" name="clearall" class="action" value="清空"/>
	            <input type="submit" name="tijiao" class="master_btn" value="发布"/>
	        </div>
	        <table class="fulltable noext">
	            <thead>
	                <tr>
	                    <th>序号</th>
	                    <th>{#goods_code#}</th>
	                    <th>{#goods_name#}</th>
	                    <th>{#goods_color#}</th>
	                    <th>{#goods_size#}</th>
	                    <th>{#quantity#}</th>
	                    <th>{#sex#}</th>
	                    <th>{#price_max#}</th>
	                    <th>{#send_zone#}</th>
	                    <th>{#send_day#}</th>
	                    <th>{#op#}</th>
	                </tr>
	            </thead>
	            <tbody  id="bodyContent">
	            {foreach from=$initRow item=item}
	                <tr>
	                    <td>{$item + 1}</td>
			            <td><input type="text" name="goods_code[]" value="{set_value('goods_code'|cat:$item)}" placeholder="请输入{#goods_code#}"/>{form_error('goods_code'|cat:$item)}</td>
			            <td><input type="text" name="goods_name[]" value="{set_value('goods_name'|cat:$item)}" placeholder="请输入{#goods_name#}"/>{form_error('goods_name'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="goods_color[]" value="{set_value('goods_color'|cat:$item)}" placeholder="如:白色"/>{form_error('goods_color'|cat:$item)}</td>
			            <td>
			             <a class="updown_link down" href="javascript:void(0)" >-</a>
			             <input type="text" class="w36" name="goods_size[]" value="{set_value('goods_size'|cat:$item)}" placeholder="如:41"/>
			             <a href="javascript:void(0)" class="updown_link down">+</a>{form_error('goods_size'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="quantity[]" value="1" placeholder="请输入{#quantity#}"/>{form_error('quantity'|cat:$item)}</td>
			            <td><select class="w60" name="sex[]">
			                    <option value="0" {set_select('sex'|cat:$item,0)}>男</option>
			                    <option value="1" {set_select('sex'|cat:$item,1)}>女</option>
			            </select>{form_error('sex'|cat:$item)}</td>
			            <td><input type="text" class="w60" name="price_max[]" value="{set_value('price_max'|cat:$item)}" placeholder="{#price_max#}"/>{form_error('price_max'|cat:$item)}</td>
			            <td><input type="text" name="send_zone[]" value="{set_value('send_zone'|cat:$item)}" placeholder="请输入{#send_zone#}"/></td>
			            <td><input type="text" name="send_day[]" class="datepicker" value="{set_value('send_day'|cat:$item)}" placeholder="请输入{#send_day#}"/></td>
			            <td>
			                 <a class="copyrow" href="javascript:void(0);">复制</a>&nbsp;
			                 <a class="addsize" href="javascript:void(0);">+1码</a>&nbsp;
			                 <a class="reducesize" href="javascript:void(0);">-1码</a>&nbsp;
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
            <td><input type="text" class="w60" name="goods_color[]" value="" placeholder="如:白色"/></td>
            <td>
                <a class="updown_link down" href="javascript:void(0)" >-</a>
                <input type="text" class="w36" name="goods_size[]" value="" placeholder="如:41"/>
                <a href="javascript:void(0)" class="updown_link down">+</a>
           </td>
            <td><input type="text" class="w60" name="quantity[]" value="1" placeholder="请输入{#quantity#}"/></td>
            <td><select class="w60" name="sex[]">
	                <option value="0">男</option>
	                <option value="1">女</option>
            </select></td>
            <td><input type="text" class="w60" name="price_max[]" value="" placeholder="{#price_max#}"/></td>
            <td><input type="text" name="send_zone[]" value="" placeholder="请输入{#send_zone#}"/></td>
            <td><input type="text" name="send_day[]" value="" class="datepicker" placeholder="请输入{#send_day#}"/></td>
            <td><a class="copyrow" href="javascript:void(0);">复制</a>&nbsp;<a class="deleterow" href="javascript:void(0);">删除</a></td>
        </tr>
    </script>
    <script>
    </script>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script type="text/javascript" src="{resource_url('js/my/hp.js')}"></script>
{include file="common/my_footer.tpl"}

