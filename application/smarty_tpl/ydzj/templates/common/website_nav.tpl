			<ul id="homeNav">
				{foreach from=$siteNavs item=item key=key}
       			<li class="level0 {if strpos($item['url_cn'],base_url($currentModule)) !== false}selected{/if}"><a class="link0" href="{str_replace($idReplacement,$item['id'],$item['url_cn'])}" {if $item['jump_type'] == 1}target="_blank"{/if}>{$item['name_cn']|escape}</a>
       				{if $item['children']}
       				<ul class="sublist">
       				{foreach from=$item['children'] item=item2 key=key2}
       					<li><a class="link1" href="{str_replace($idReplacement,$item2['id'],$item2['url_cn'])}" {if $item2['jump_type'] == 1}target="_blank"{/if}>{$item2['name_cn']|escape}</a></li>
       				{/foreach}
       				</ul>
       				{/if}
       			</li>
       			{/foreach}
       		</ul>