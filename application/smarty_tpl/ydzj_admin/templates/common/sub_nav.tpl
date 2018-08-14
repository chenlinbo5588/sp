 		   {if $subNavs}
           <div class="fixed-bar">
            <div class="item-title">
              <h3>{$moduleTitle}</h3>
              <ul class="tab-base clearfix">
                {foreach from=$subNavs key=key item=item}
                {if isset($permission[$item['checkUrl']])}
                <li><a {if $item['checkUrl'] == $permitUri}class="current"{/if} href="{admin_site_url($item['url'])}"><span>{$item['title']|escape}</span></a></li>
                {/if}
                {/foreach}
              </ul>
            </div>
          </div>
          {/if}
          <div class="fixed-empty"></div>
          <div class="feedback">{$feedback}</div>
