			<div class="sideNav">
				<ul class="sideItemWrap">
					<li class="itemTitle"><h3><a href="{$sideTitleUrl}">{$sideTitle|escape}</a></h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li  class="oneItem{if str_replace($idReplacement,$item['id'],$item[$urlKey]) == $currentSideUrl} current{/if}"><a href="{str_replace($idReplacement,$item['id'],$item[$urlKey])}">{$item[$nameKey]|escape}</a></li>
					{/foreach}
				</ul>
			</div>