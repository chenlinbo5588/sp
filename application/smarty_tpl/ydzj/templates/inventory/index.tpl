{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    
    <form action="{site_url($uri_string)}" method="post" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="goods_search">
             {*
        	 <textarea name="gc" placeholder="输入{#goods_code#}，每行一个货号或者单行按逗号分隔，一次最多可同时50个">{$smarty.post.gc}</textarea>
             <ul class="search_con clearfix">
                <li>
                    <label class="ftitle">{#goods_name#}</label>
                    <input type="text" class="mtxt" name="gn" value="{$smarty.post.gn}"/>
                </li>
                <li>
                    <label class="ftitle">{#goods_size#}</label>
                    <input type="text" name="s1" class="stxt" value="{$smarty.post.s1}" placeholder="尺寸下限"/>
                    <input type="text" name="s2" class="stxt" value="{$smarty.post.s2}" placeholder="尺寸上限"/>
                </li>
                <li>
                    <label class="ftitle">{#sex#}</label>
                    <label><input type="radio" name="sex" value="0" {if $smarty.post.sex == 0}checked{/if}/>不限</label>
                    <label><input type="radio" name="sex" value="1" {if $smarty.post.sex == 1}checked{/if}/>男</label>
                    <label><input type="radio" name="sex" value="2" {if $smarty.post.sex == 2}checked{/if}/>女</label>
                </li>
                <li>
                    <label class="ftitle" style="width:100px;">{#accept#}{#price_min#}</label>
                    <input type="text" name="pr1" class="stxt" value="{if $smarty.post.pr1}{$smarty.post.pr1}{/if}" placeholder="下限"/>
                    <input type="text" name="pr2" class="stxt" value="{if $smarty.post.pr2}{$smarty.post.pr2}{/if}" placeholder="上限"/>
                </li>
                <li>
                    <input class="master_btn" type="submit" name="search" value="查询"/>
                </li>
             </ul>
             {include file="hp/code_tip.tpl"}
             *}
             <div><input type="button" class="master_btn" value="刷新库存"/></div>
             <div class="tip pd5">系统默认分类给了那您10个{#goods_slot#}，每个{#goods_slot#}可以添加50个不同尺寸的货品,如您需要更多{#goods_slot#}，请在网站下发找到我们的联系方式并与我们取得沟通。</div>
	         <ul class="slot_list clearfix">
	         	{foreach from=$list['slot_config'] item=item}
	         	<li class="slot_item">
	         		<div class="title"><a href="{site_url('inventory/slot_edit?id='|cat:$item['id'])}" title="添加货品到货柜"><span class="hightlight">{$item['title']|escape}({$item['cnt']}/{$item['max_cnt']})</span></a>&nbsp;<a href="javascript:void(0);" data-title="{$item['title']|escape}"  class="mtitle">修改</a></div>
	         		<div class="goods_code">{if $item['goods_code']}{#goods_code#}:{$item['goods_code']}{else}<a class="setgc" href="javascript:void(0);" data-title="{$item['title']|escape}" data-id="{$item['id']}">货号设置</a>{/if}</div>
	         	</li>
	         	{/foreach}
	         </ul>
	    </div>
    </form>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script>
    	var setgcUrl = "{site_url('inventory/slot_gc')}";
        $(function(){
            //$( ".datepicker" ).datepicker({ });
        });
    </script>
    {include file="./dlg_code.tpl"}
    <script type="text/javascript" src="{resource_url('js/my/slot_list.js')}"></script>
{include file="common/my_footer.tpl"}
