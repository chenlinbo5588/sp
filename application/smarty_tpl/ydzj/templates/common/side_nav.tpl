			<div class="sideNav">
				<ul class="sideItem">
					<li class="itemTitle"><h3><a href="{$sideTitleUrl}">{$sideTitle|escape}</a></h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li  {if str_replace($idReplacement,$item['id'],$item[$urlKey]) == $currentSideUrl }class="current"{/if}><a href="{str_replace($idReplacement,$item['id'],$item[$urlKey])}">{$item[$nameKey]|escape}</a></li>
					{/foreach}
				</ul>
			</div>