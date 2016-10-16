{include file="common/my_header.tpl"}
    <form action="{site_url($uri_string)}" method="get" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="goods_search">
            <ul class="search_con clearfix">
                <li>
                    <label class="ftitle">颜色名称</label>
                    <input type="text" class="mtxt" name="color_name" value="{$smarty.get.color_name}"/>
                </li>
                <li>
                    <input class="master_btn" type="submit" name="search" value="查询"/>
                    <input class="master_btn" type="button" name="addcolor" value="添加颜色"/>
                </li>
             </ul>
             
        </div>
        <ul class="color_list clearfix">
            {foreach from=$list item=item}
            <li class="list_item" id="row{$item['id']}">
                <label><input type="checkbox" name="id[]" group="id" value="{$item['id']}"/>{$item['color_name']|escape}&nbsp;&nbsp;&nbsp;<a class="edit" data-title="{$item['color_name']}"  href="javascript:void(0)" data-url="{site_url('my_color/edit')}" data-id="{$item['id']}">修改</a>&nbsp;<a class="delete" data-title="删除"  href="javascript:void(0)" data-url="{site_url('my_color/delete')}" data-id="{$item['id']}">删除</a></label>
            </li>
            {foreachelse}
            <span class="tip">您还没有定义颜色</span>
            {/foreach}
        </ul>
        <div class="pd5">
            {if $list}<label><input type="checkbox" class="checkall" name="id" /><strong>全选/取消&nbsp;</strong></label><input type="button" class="action deleteBtn" data-checkbox="id[]" data-title="删除" data-url="{site_url('my_color/delete')}" name="delete" value="删除" />{/if}
        </div>
        <div>{include file="common/pagination.tpl"}</div>
    </form>
    <div id="addColorDlg" title="添加颜色" style="display:none;">
        <div class="loading_bg" style="display:none;">发送中...</div>
        <form id="colorAddForm" action="{site_url('my_color/add')}" method="post">
            <table class="fulltable noborder">
                <tr><td><input type="text" class="at_txt" name="color_name" value="" placeholder="请输入颜色名称"/></td></tr>
                <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="保存"/></td></tr>
            </table>
        </form>
    </div>
    <div id="editColorDlg" title="修改颜色" style="display:none;">
        <div class="loading_bg" style="display:none;">发送中...</div>
        <form id="colorEditForm" action="{site_url('my_color/edit')}" method="post">
        	<input type="hidden" name="id" value="" />
            <table class="fulltable noborder">
                <tr><td><input type="text" class="at_txt" name="color_name" value="" placeholder="请输入颜色名称"/></td></tr>
                <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="保存"/></td></tr>
            </table>
        </form>
    </div>
    <script>
        $(function(){
            bindDeleteEvent();
        });
    </script>
    {include file="common/jquery_validation.tpl"}
    <script type="text/javascript" src="{resource_url('js/my/color.js')}"></script>
{include file="common/my_footer.tpl"}

