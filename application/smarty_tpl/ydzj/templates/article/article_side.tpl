			<aside>
			<div class="sideNav">
				<ul class="sideItemWrap">
					{foreach from=$sideNavs item=item key=key}
					<li class="oneItem{if $item == $currentSideUrl} current{/if}">
						<a class="itemTitle" href="{base_url('article/plist/'|cat:$item['ac_id']|cat:'.html')}">{if $currentLang == 'english'}{$item['name_en']|escape}{else}{$item['name_cn']|escape}{/if}</a>
						{if !empty($item['children'])}
						{foreach from=$item['children'] item=subItem key=subKey}
						<a class="level1" href="{base_url('article/plist/'|cat:$subItem['ac_id']|cat:'.html')}">{if $currentLang == 'english'}{$subItem['name_en']|escape}{else}{$subItem['name_cn']|escape}{/if}</a>
						{/foreach}
						{/if}
					</li>
					{/foreach}
				</ul>
				<div class="search sideItemWrap">
					<h3 class="itemTitle">{$cm_search}</h3>
					<div><input type="text" name="keyword" value="{$keyword}" placeholder="{$input_keyword}"/></div>
					<div><input type="submit" class="orangeBtn btn" name="search" value="{$cm_search}"/></div>
				</div>
			</div>
			</aside>
