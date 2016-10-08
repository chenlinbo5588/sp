{include file="common/my_header.tpl"}
    <div class="tabin_screen">
        <ul class="clearfix">
            <li class="scr_cur"><a href="{site_url('my_req/recent')}">最近3日内求货</a></li>
            <li><a href="{site_url('my_req/history')}">历史求货</a></li>
        </ul>
    </div>
    <form action="{site_url($uri_string)}" method="get" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <input type="hidden" name="read" value="{$read}"/>
        <table class="fulltable">
            <colgroup>
                <col width="50%"/>
                <col width="20%"/>
                <col width="10%"/>
                <col width="10%"/>
                <col width="10%"/>
            </colgroup>
            <thead>
                <tr>
                    <th><label><input type="checkbox" class="checkall" name="pmid" /><strong>全选/取消</strong></label></th>
                    <th>已读</th>
                    <th>创建时间</th>
                    <th>阅读时间</th>
                    <th>操作</th>
                </tr>
            </thead>
	        <tbody>
	           {foreach from=$list item=item}
	           <tr id="row{$item['id']}">
		           <td>
		           <input type="checkbox" name="id[]" group="pmid" value="{$item['id']}"/>
		           <a href="javascript:void(0);" class="popwin" data-url="{site_url('my_pm/detail?id='|cat:$item['id'])}">{if $item['msg_type'] == -1}<strong>【系统消息】</strong>{/if}{$item['title']|escape}</td>
		           <td>{if $item['readed'] == 1}已读{else}未读{/if}</td>
		           <td>{time_tran($item['gmt_create'])}</td>
		           <td>{if $item['readed'] == 1}{time_tran($item['gmt_modify'])}{/if}</td>
		           <td>
		              <a class="delete" title="删除" data-title="删除"  href="javascript:void(0)" data-url="{site_url('my_pm/delete')}" data-id="{$item['id']}">&times;</a>
		           </td>
		        <tr>
		        {/foreach}
		    </tbody>
		    <tfoot>
		      <tr>
		          <td colspan="6">
		              <div class="pd5">
		              {if $type == 'receive' && $read == 0}<input type="button" class="action updateBtn" data-checkbox="id[]" data-title="设为已读" data-url="{site_url('my_pm/setread')}" name="setread" value="设为已读"/>{/if}
		              <input type="button" class="action deleteBtn" data-checkbox="id[]" data-title="删除" data-url="{site_url('my_pm/delete')}" name="delete" value="删除" />
		              </div>
		          </td>
		      </tr>
		    </tfoot>
		 </table>
	     <div>{include file="common/pagination.tpl"}</div>
    </form>
    <script>
        $(function(){
            bindDeleteEvent();
        });
    </script>
    {include file="common/jquery_validation.tpl"}
{include file="common/my_footer.tpl"}
