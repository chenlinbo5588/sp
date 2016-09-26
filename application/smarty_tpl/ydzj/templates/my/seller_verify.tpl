{include file="./my_header.tpl"}
	<div class="w-step-row">
		<div class="w-step4 {if $step > 1}w-step-past{else if $step == 1} w-step-cur{/if}">上传认证资料</div>
		<div class="w-step4 {if $step > 2}w-step-past-past{else if $step == 2}w-step-past-cur{else}w-step-cur-future{/if}">确认提交</div>	
		<div class="w-step4 w-step-future-future">审核</div>		
		<div class="w-step4 {if $step < 4}w-step-future-future{else}w-step-past-cur{/if}">审核结果</div>
	</div>
	<div class="muted">通过卖家认证之后,将可以获得后台实时的匹配提醒</div>
	
	{form_open_multipart(site_url($uri_string),"id='sellerForm'")}
	<input type="hidden" name="step" value="{$step}"/>
	<table class="fulltable style1">
	    <tbody>
			<tr>
				<td class="w120"><label>网店链接</label></td>
				<td><input class="w50pre" type="text" name="store_url" value="{set_value('store_url')}" placeholder="请输入网店链接地址"/>{form_error('store_url')}</td>
			</tr>
			<tr>
				<td class="w120"><label>卖家最近交易流水</label></td>
				<td><input class="w50pre" type="file" name="trade_pic" /><span>请上传尺寸JPG格式的最近交易流水图片,最小尺寸400x400</span></td>
			</tr>
			<tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="master_btn" name="tijiao" value="下一步"/></a></td>
            </tr>
		</tbody>
	</table>
{include file="./my_footer.tpl"}

