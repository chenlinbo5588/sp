{include file="common/my_header.tpl"}
{config_load file="member.conf"}
   {form_open(site_url('lab_user/index'),'id="formSearch"')}
   <input type="hidden" name="page" value=""/>
    <div class="goods_search">
     <ul class="search_con clearfix">
        <li>
            <label class="ftitle">{#account#}</label>
            <input type="text" class="mtxt" name="username" value="{$smarty.post.username}" placeholder="请输入用户登陆名称" />
        </li>
        <li>
            <input class="master_btn" type="submit" name="search" value="查询"/>
        </li>
     </ul>
    </div>
	<table class="fulltable tb-type2">
	    <thead>
	        <tr>
	            <th class="w108"><label><input type="checkbox" class="checkall" name="id" />用户ID</label></th>
	            <th>所在实验室</th>
	            <th>登陆账号</th>
	            <th>邮件地址</th>
	            <th>QQ</th>
	            {*<th>手机号码</th>*}
	            <th>实验室管理员</th>
	            <th>录入人</th>
	            <th>录入时间</th>
	            <th class="last">操作</th>
	        </tr>
	    </thead>
	    <tbody>
	        {foreach from=$list['data'] key=key item=item}
            <tr id="row{$item['uid']}_{$item['lab_id']}">
                <td>{if $isFounder || in_array($item['lab_id'],$manager_labs)}<input type="checkbox" name="id[]" group="id" value="{$item['uid']}_{$item['lab_id']}" />{/if}{$item['uid']}</td>
                <td>{$lab[$item['lab_id']]['name']|escape}</td>
                <td>{$member[$item['uid']]['username']|escape}</td>
                <td>{$member[$item['uid']]['email']|escape}</td>
                <td>{$member[$item['uid']]['qq']|escape}</td>
                {*<td>{$member[$item['uid']]['mobile']|escape}</td>*}
                <td>{if $item['is_manager'] == 'y' || $item['uid'] == $item['oid']}是{else}否{/if}</td>
                <td>{$item['creator']|escape}</td>
                <td>{time_tran($item['gmt_create'])}</td>
                <td>
                    {if $isFounder || in_array($item['lab_id'],$manager_labs)}
                    <a href="{site_url('lab_user/edit?uid=')}{$item['uid']}">编辑</a>&nbsp;
                    <a class="delete" href="javascript:void(0);" data-id="{$item['uid']}_{$item['lab_id']}" data-url="{site_url('lab_user/delete?uid=')}{$item['uid']}" data-title="{$member[$item['uid']]['username']|escape}">删除</a>
                    {/if}
                </td>
            </tr>
            {foreachelse}
            <tr>
            	<td colspan="9">找不到记录</td>
            </tr>
            {/foreach}  
	    </tbody>
        <tfoot>
          <tr>
              <td colspan="9">
                  <div class="pd5">
                  <label><input type="checkbox" class="checkall" name="id">全选</label>&nbsp;
                  <input type="button" class="action deleteBtn" data-checkbox="id[]" data-title="选中的记录" data-url="{site_url('lab_user/delete')}" name="delete" value="删除" />
                 {include file="common/pagination.tpl"}
                 </div>
              </td>
          </tr>
        </tfoot>
	</table>
	</form>
	{include file="common/jquery_ui.tpl"}
	<script>
	$(function(){
		bindDeleteEvent();
	});
  </script>
{include file="common/my_footer.tpl"}