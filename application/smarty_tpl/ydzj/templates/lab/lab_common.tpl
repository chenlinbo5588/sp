<div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	<li><a {if $action == 'index'}class="current"{/if} href="{site_url('lab/index')}"><span>{#manage#}</span></a></li>
      	<li><a {if $action == 'add'}class="current"{/if} href="{site_url('lab/add')}"><span>{#add#}</span></a></li>
      	{if $info['id']}<li><a {if $action == 'edit'}class="current"{/if} href="{site_url('lab/edit?id='|cat:$info['id'])}"><span>{#edit#}</span></a></li>{/if}
      	<li><a {if $action == 'export'}class="current"{/if} href="{site_url('lab/export')}"><span>{#export#}</span></a></li>
      </ul>
    </div>
  </div>
  