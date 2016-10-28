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
	            <th class="first">用户ID</th>
	            <th>登陆账号</th>
	            <th>邮件地址</th>
	            <th>QQ</th>
	            <th>手机号码</th>
	            <th>所在实验室</th>
	            <th>实验室管理员</th>
	            <th>角色分组</th>
	            <th>录入人</th>
	            <th>录入时间</th>
	            <th class="last">操作</th>
	        </tr>
	    </thead>
	    <tbody>
	        {foreach from=$list['data'] key=key item=item}
            <tr id="row{$item['uid']}">
                <td>{$item['uid']}</td>
                <td>{$member[$item['uid']]['username']|escape}</td>
                <td>{$member[$item['uid']]['email']|escape}</td>
                <td>{$member[$item['uid']]['qq']|escape}</td>
                <td>{$member[$item['uid']]['mobile']|escape}</td>
                <td>{$lab[$item['lab_id']]['name']|escape}</td>
                <td>{if $item['is_manager'] == 'y' || $item['uid'] == $item['oid']}是{else}否{/if}</td>
                <td>{if $item['group_id'] == 0}无权限组{else}{$roleList[$item['group_id']]}{/if}</td>
                <td>{$item['creator']|escape}</td>
                <td>{time_tran($item['gmt_create'])}</td>
                <td>
                    <a href="{site_url('lab_user/edit?id=')}{$item['uid']}">编辑</a>&nbsp;
                    <a class="delete" href="javascript:void(0);" data-id="{$item['id']}" data-url="{site_url('lab_user/delete?uid=')}{$item['uid']}" data-title="{$member[$item['uid']]['username']|escape}">删除</a>
                </td>
            </tr>
            {foreachelse}
            <tr>
            	<td colspan="11">找不到记录</td>
            </tr>
            {/foreach}  
	    </tbody>
	    <tfoot>
            <tr>
                <td colspan="9">{include file="common/pagination.tpl"}</td>
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