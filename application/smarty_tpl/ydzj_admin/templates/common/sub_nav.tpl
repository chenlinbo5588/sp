 		   {if $subNavs}
           <div class="fixed-bar">
            <div class="item-title">
              <h3>{$moduleTitle}</h3>
              <ul class="tab-base">
                {foreach from=$subNavs key=key item=item}
                <li><a {if stripos($currentUri,$item['url']) !== false}class="current"{/if} href="{admin_site_url($item['url'])}"><span>{$item['title']|escape}</span></a></li>
                {/foreach}
              </ul>
            </div>
          </div>
          {/if}
          <div class="fixed-empty"></div>
          <div class="feedback">{$feedback}</div>
