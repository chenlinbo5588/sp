<div class="websiteoper"><label><input type="checkbox" name="ck" class="checkall"/><span>全选/取消全选</span></label>&nbsp;<label><input type="checkbox" name="ck" class="rcheckall"/><span>反选</span></div>
<ul class="websitelist clearfix">
					{foreach from=$websiteList item=item}
        			<li><label><input type="checkbox" group="ck" name="site_ids[]" {if is_array($info['site_ids']) && in_array($item['site_id'],$info['site_ids'])}checked{/if} value="{$item['site_id']}"/><span>{$item['site_name']|escape}</span></label></li>
        			{/foreach}
        		</ul>