<ul>
	{if is_array($navs['side'][$modulName])}
                    {foreach from=$navs['side'][$modulName] item=item}
                    <li {if strpos($item['url'],$moduleUrl) !== false}class="actived"{/if}><a href="{admin_site_url($item['url'])}">{$item['title']|escape}</a></li>
                    {/foreach}
    {else if is_string($navs['side'][$modulName])}
     {foreach from=$navs['side'][$navs['side'][$modulName]] item=item}
                    <li {if strpos($item['url'],$moduleUrl) !== false}class="actived"{/if}><a href="{admin_site_url($item['url'])}">{$item['title']|escape}</a></li>
                    {/foreach}
    
    {/if}
                  </ul>