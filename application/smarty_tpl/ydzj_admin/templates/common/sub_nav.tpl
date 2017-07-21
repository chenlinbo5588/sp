	   {if $subNavs}
	   <div class="fixed-bar">
	    <div class="item-title">
	      <h3>{$modulName}</h3>
	      <ul class="tab-base">
	        {foreach from=$subNavs key=key item=item}
	        <li><a {if $funcUrl == $item}class="current"{/if} href="{admin_site_url($item)}"><span>{$key|escape}</span></a></li>
	        {/foreach}
	      </ul>
	    </div>
	  </div>
	  {/if}
	  <div class="fixed-empty"></div>
	  <div class="feedback">{$feedback}</div>