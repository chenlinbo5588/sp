			<ul id="homeNav">
				{foreach from=$topNavs item=item key=key}
       			<li class="level0 {if $currentModule == 'home'}current{/if}"><a class="link0" href="{$item['url_cn']}" {if $item['jump_type'] == 1}target="_blank"{/if}>{$item['name_cn']|escape}</a>
       				{if $item['children']}
       				<ul class="sublist">
       				{foreach from=$item['children'] item=item2 key=key2}
       					<li><a class="link1" href="{$item2['url_cn']}" {if $item2['jump_type'] == 1}target="_blank"{/if}>{$item2['name_cn']|escape}</a></li>
       				{/foreach}
       				</ul>
       				{/if}
       			</li>
       			{/foreach}
       		</ul>