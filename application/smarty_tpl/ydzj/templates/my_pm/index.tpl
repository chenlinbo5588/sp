{include file="common/my_header.tpl"}
    <div class="tabin_screen">
        <ul class="clearfix">
            <li {if $type == 'receive'}class="scr_cur"{/if}><a href="{site_url($uri_string)}?type=receive">收件箱</a></li>
            <li {if $type == 'send'}class="scr_cur"{/if}><a href="{site_url($uri_string)}?type=send">发件箱</a></li>
        </ul>
        <span id="jsUserSendMsg" class="action position_a jsUserSendMsg"><b class="icon_w_pen"></b>发私信</span>
    </div>
    
    {if $type == 'receive'}
    <ul class="tab tab-gap mg10 clearfix">
	    <li class="tab-item {if $read == 0}current{/if}"><a class="link" href="{site_url($uri_string)}?read=0">未读</a></li>
	    <li class="tab-item {if $read == 1}current{/if}"><a class="link" href="{site_url($uri_string)}?read=1">已读</a></li>
	</ul>
	{/if}
	{form_open(site_url($uri_string),'method="get" id="formSearch"')}
        <input type="hidden" name="page" value=""/>
        <input type="hidden" name="read" value="{$read}"/>
        <table class="fulltable bordered">
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
		           <a href="javascript:void(0);" class="popwin" data-url="{site_url('my_pm/detail')}?id={$item['id']}&uid={$item['uid']}">{if $item['msg_type'] == -1}<strong>【系统消息】</strong>{/if}{$item['title']}</a></td>
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
    <div id="privatePmDlg" title="发送私信" style="display:none;">
        <div class="loading_bg" style="display:none;">发送中...</div>
        {form_open(site_url('my_pm/sendpm'),'id="privatePmForm"')}
            <table class="fulltable noborder">
                <tr><td><input type="text" class="at_txt" name="to_username" value="" placeholder="请输入对方登陆账号"/></td></tr>
                <tr><td><input type="text" class="at_txt" name="title" value="" placeholder="请输入消息标题，最大30个字"/></td></tr>
                <tr><td><textarea name="content" class="at_txt" style="height:200px" placeholder="请输入消息正文，最多200个字"></textarea></td></tr>
                <tr><td><input type="submit" class="master_btn at_txt" name="tijiao" value="发送"/></td></tr>
            </table>
        </form>
    </div>
    <div id="pmDetailDlg" title="站内信详情"></div>
    
    <script>
        $(function(){
            bindDeleteEvent();
            {if $read == 0 && $type == 'receive'}bindOpEvent({ selector : "input.updateBtn" } );{/if}
        });
    </script>
    {include file="common/jquery_validation.tpl"}
    <script type="text/javascript" src="{resource_url('js/my/pm.js')}"></script>
{include file="common/my_footer.tpl"}

