<div class="pagination" data-formid="{$page['form_id']}">
    {if ($page['pageLastNum'] > 1)}
        {if ($page['pageNow'] != 1)}
           	<a {if $page['call_js']}href="javascript:void(0)" onclick="{$page['call_js']}(1,'{$page['form_id']}');return false;"{else}href="{$page['base_link']}&page=1#{$page['anchor']}"{/if}>{$cm_pagefirst}</a>
            <a {if $page['call_js']}href="javascript:void(0)" onclick="{$page['call_js']}({$page['pageNow'] - 1},'{$page['form_id']}');return false;"{else}href="{$page['base_link']}&page={$page['pageNow'] - 1}#{$page['anchor']}"{/if}>{$cm_pageprev}</a>
        {else}
            <a {if $page['call_js']}href="javascript:void(0)" onclick="{$page['call_js']}(1);return false;"{else}href="{$page['base_link']}&page=1"{/if}>{$cm_pagefirst}</a>
        {/if}
		
		
		{section name=a loop=($page['pageAe'] - $page['pageAb'] + 1)}
            {if ($page['pageNow'] == ($smarty.section.a.index + $page['pageAb']))}
                <a class="active" href="javascript:void(0)">{$smarty.section.a.index + $page['pageAb']}</a>
            {else}
                <a href="javascript:void(0)" onclick="{$page['call_js']}({{$smarty.section.a.index + $page['pageAb']}},'{$page['form_id']}');return false;">{{$smarty.section.a.index + $page['pageAb']}}</a>
            {/if}
        {/section}
        
        
        {if $page['pageNow'] != $page['pageLastNum']}
            <a {if $page['call_js']}href="javascript:void(0)" onclick="{$page['call_js']}({$page['pageNow'] + 1},'{$page['form_id']}');return false;"{else}href="{$page['base_link']}&page={$page['pageNow'] + 1}#{$page['anchor']}"{/if}>{$cm_pagenext}</a>
            <a {if $page['call_js']}href="javascript:void(0)" onclick="{$page['call_js']}({$page['pageLastNum']},'{$page['form_id']}');return false;"{else}href="{$page['base_link']}&page={$page['pageLastNum']}#{$page['anchor']}"{/if}>{$cm_pagelast}</a>
		{else}
			<a class="deactive" href="javascript:void(0)">{$cm_alreadylast}</a>
        {/if}
        {if !$isMobile}<strong><label>{$page['pageSum']}{$cm_pageunit}{$cm_records}</label>{if $page['shortStyle'] == false}<input type="text" style="width:40px" name="jumpPage" value="{$page['pageNow']}" />&nbsp;<input type="button" name="jumpBtn" value="{$cm_jump}" class="btn orangeBtn jumpBtn" />&nbsp;{/if}
        <span>{$page['pageNow']}/{$page['pageLastNum']}</span></strong>{/if}
    {else}
        {if $page['pageSum'] > 0}<strong><label>{$cm_get}{$page['pageSum']}{$page['pageUnit']}{$page['pageTit']}</label>{/if}
    {/if}
</div>