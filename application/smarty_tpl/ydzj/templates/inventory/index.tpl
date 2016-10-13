{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    
    <form action="{site_url($uri_string)}" method="post" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="goods_search">
             <div><input type="button" class="master_btn" value="刷新库存"/></div>
             <div class="tip pd5">系统默认分类给了那您10个{#goods_slot#}，每个{#goods_slot#}可以添加50个不同尺寸的货品,如您需要更多{#goods_slot#}，请在网站下发找到我们的联系方式并与我们取得沟通。</div>
	         <ul class="slot_list clearfix">
	         	{foreach from=$list['slot_config'] item=item}
	         	<li class="slot_item" data-id="{$item['id']}">
	         		<div class="title"><a href="{site_url('inventory/slot_edit?id='|cat:$item['id'])}" title="添加货品到货柜"><strong>{$item['title']|escape}</strong><span class="hightlight">({$item['cnt']}/{$item['max_cnt']})</span></a>&nbsp;<a href="javascript:void(0);" data-title="{$item['title']|escape}" class="mtitle">改名</a></div>
	         		<div class="goods_code">{if $item['goods_code']}{#goods_code#}:{$item['goods_code']}{else}<a class="setgc" href="javascript:void(0);" data-title="{$item['title']|escape}">设置货号</a>{/if}</div>
	         	</li>
	         	{/foreach}
	         </ul>
	    </div>
    </form>
    <div id="confirmOf">
        <div class="loading_bg" style="display:none;">发送中...</div>
        <div class="confirmtitle"></div>
    </div>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script>
    	var setgcUrl = "{site_url('inventory/slot_gc')}";
    </script>
    {include file="./dlg_code.tpl"}
    <script type="text/javascript" src="{resource_url('js/my/slot_list.js')}"></script>
{include file="common/my_footer.tpl"}
