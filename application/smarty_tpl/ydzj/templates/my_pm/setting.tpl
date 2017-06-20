{include file="common/my_header.tpl"}
	{form_open(site_url($uri_string),"id='editForm'")}
	<div class="w-tixing clearfix"><b>温馨提醒：</b>
	    <p>用于设置接受系统消息，用户消息通道。</p>
	  </div>
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td class="w18pre">接受消息设置</td>
				<td>
					{foreach  from=$sendWays item=item}
					<label><input type="checkbox" name="notify_ways[]" {if strpos($currentWays,$item) !== false}checked{/if} value="{$item}"/>{$item}</label>
					{/foreach}
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" class="master_btn" name="tijiao" value="保存"/></td>
			</tr>
		</tbody>
	</table>
	</form>
{include file="common/my_footer.tpl"}

