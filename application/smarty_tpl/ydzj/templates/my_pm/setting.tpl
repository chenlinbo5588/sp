{include file="common/my_header.tpl"}
	{form_open(site_url($uri_string),"id='editForm'")}
	<span class="tip">求货或库存匹配时得提醒方式,建议电脑版在线时只勾选站内信，离线时选择邮件，可降低由于多路径消息重复问题对您引起的干扰。</span>
	<table class="fulltable style1">
		<tbody>
			<tr>
				<td class="w18pre">求货或库存匹配时提醒设置</td>
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

