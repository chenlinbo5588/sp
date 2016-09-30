<ul>
	{if is_array($navs['side'][$modulName])}
                    {foreach from=$navs['side'][$modulName] item=item}
                    {if $item['hidden'] == false}<li {if strpos($item['url'],$moduleUrl) !== false}class="actived"{/if}><a href="{admin_site_url($item['url'])}">{$item['title']|escape}</a></li>{/if}
                    {/foreach}
    {else if is_string($navs['side'][$modulName])}
     {foreach from=$navs['side'][$navs['side'][$modulName]] item=item}
                    {if $item['hidden'] == false}<li {if strpos($item['url'],$moduleUrl) !== false}class="actived"{/if}><a href="{admin_site_url($item['url'])}">{$item['title']|escape}</a></li>{/if}
                    {/foreach}
    
    {/if}
                  </ul>