<ul>
	{if is_array($navs['side'][$fnKey])}
                    {foreach from=$navs['side'][$fnKey] item=item}
                    <li><a href="{admin_site_url($item['url'])}">{$item['title']|escape}</a></li>
                    {/foreach}
    {else if is_string($navs['side'][$fnKey])}
     {foreach from=$navs['side'][$navs['side'][$fnKey]] item=item}
                    <li><a href="{admin_site_url($item['url'])}">{$item['title']|escape}</a></li>
                    {/foreach}
    
    {/if}
                  </ul>