			<div class="sideNav">
				<ul class="sideItem">
					<li class="itemTitle"><h3><a href="{$sideTitleUrl}">{$sideTitle|escape}</a></h3></li>
					{foreach from=$sideNavs item=item key=key}
					<li  {if str_replace($idReplacement,$item['id'],$item['url_cn']) == $currentSideUrl }class="current"{/if}><a href="{str_replace($idReplacement,$item['id'],$item['url_cn'])}">{$item['name_cn']|escape}</a></li>
					{/foreach}
				</ul>
			</div>