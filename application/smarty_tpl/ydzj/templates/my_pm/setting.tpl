{include file="common/my_header.tpl"}
	{form_open(site_url($uri_string),"id='editForm'")}
	<div class="w-tixing clearfix"><b>温馨提醒：</b>
	    <p>系统提醒方式,系统将通过下面的方式告知您。</p>
	  </div>
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

