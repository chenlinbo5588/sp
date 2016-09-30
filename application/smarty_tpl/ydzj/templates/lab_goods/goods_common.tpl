<div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	<li><a {if $action == 'index'}class="current"{/if} href="{site_url('goods/index')}"><span>{#manage#}</span></a></li>
      	<li><a {if $action == 'add'}class="current"{/if} href="{site_url('goods/add')}"><span>{#add#}</span></a></li>
      	{if $info['id']}<li><a {if $action == 'edit'}class="current"{/if} href="{site_url('goods/edit?id='|cat:$info['id'])}"><span>{#edit#}</span></a></li>{/if}
      	<li><a {if $action == 'import'}class="current"{/if} href="{site_url('goods/import')}"><span>{#import#}</span></a></li>
      	<li><a {if $action == 'empty_goods'}class="current"{/if} href="{site_url('goods/empty_goods')}"><span>{#empty_goods#}</span></a></li>
      </ul>
    </div>
  </div>
  