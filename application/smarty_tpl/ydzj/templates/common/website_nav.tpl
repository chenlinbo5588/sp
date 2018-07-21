			<nav>
				<ul id="homeNav">
					{foreach from=$siteNavs item=item key=key}
					{if $item['nav_location'] == 1}
	       			<li class="level0 {if !empty($currentModule) && strpos($item[$urlKey],base_url($currentModule)) !== false}selected{elseif $currentModule == 'index' && $item['name'] == '首页'}selected{/if}"><a class="link0" href="{str_replace($idReplacement,$item['id'],$item['url'])}" {if $item['jump_type'] == 1}target="_blank"{/if}>{$item[$nameKey]|escape}</a>
	       				{if $item['children']}
	       				<ul class="sublist">
	       				{foreach from=$item['children'] item=item2 key=key2}
	       					<li><a class="link1" href="{str_replace($idReplacement,$item2['id'],$item2[$urlKey])}" {if $item2['jump_type'] == 1}target="_blank"{/if}>{$item2[$nameKey]|escape}</a></li>
	       				{/foreach}
	       				</ul>
	       				{/if}
	       			</li>
	       			{/if}
	       			{/foreach}
	       		</ul>
       		</nav>