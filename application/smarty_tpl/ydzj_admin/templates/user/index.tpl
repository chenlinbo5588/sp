{include file="common/main_header_navs.tpl"}
  {form_open(site_url($uri_string),'id="formSearch" class="formSearch"')}
  	<input type="hidden" name="page" value=""/>
  	<input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>用户名称</td>
          <td><input type="text" value="{$smarty.post['username']}" name="username" class="txt"></td>
          <td>邮箱地址</td>
          <td><input type="text" value="{$smarty.post['email']}" name="email" class="txt"></td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24">选择</th>
          <th class="align-center">登录账号</th>
          <th class="align-center">真实名称</th>
          <th class="align-center">启用状态</th>
          <th class="align-center">上次登录时间</th>
          <th class="align-center">上次登录IP</th>
          <th class="align-center">用户组</th>
          <th class="align-center">所属角色</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover" id="row{$item['uid']}">
      	  <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['uid']}"></td>
          <td class="align-center">{$item['email']}</td>
          <td class="align-center">{$item['username']|escape}</td>
          <td class="align-center">已{if $item['enable'] == 0}禁用{else}启用{/if}</td>
          <td class="align-center">{$item['last_login']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center">{$item['last_loginip']}</td>
          <td class="align-center">{if $item['group_id'] == 0}未挂载任何祖{else}{$groupList[$item['group_id']]['name']|escape}{/if}</td>
          <td class="align-center">{if $item['role_id'] == 0}未挂载角色{else}{$roleList[$item['role_id']]['name']|escape}{/if}</td>
          <td class="align-center">
          	{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?uid={$item['uid']}">编辑</a>{/if} &nbsp;
          	{if isset($permission[$moduleClassName|cat:'/delete'])}<a class="delete" href="javascript:void(0);" data-url="{admin_site_url($moduleClassName|cat:'/delete')}?uid={$item['uid']}" data-id="{$item['uid']}">删除</a>{/if} 
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        {if isset($permission[$moduleClassName|cat:'/turnon'])}<a href="javascript:void(0);" class="btn opBtn" data-checkbox="id[]" data-title="确认要启用吗?" data-url="{admin_site_url($moduleClassName|cat:'/turnon')}" ><span>启用</span></a>{/if}
        {if isset($permission[$moduleClassName|cat:'/turnoff'])}<a href="javascript:void(0);" class="btn opBtn" data-checkbox="id[]" data-title="确认要禁用吗?" data-url="{admin_site_url($moduleClassName|cat:'/turnoff')}"><span>禁用</span></a>{/if}
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <script type="text/javascript">
	$(function(){
		{if isset($permission[$moduleClassName|cat:'/delete'])}bindDeleteEvent();{/if}
	
	    bindOpEvent({ selector : '.opBtn' } ,{ }, function(ids,json){
	    	if(check_success(json.message)){
				showToast('success',json.message);
				
				setTimeout(function(){
					location.reload();
				},2000);
			}else{
				showToast('error',json.message);
			}
	    })
	});
  </script>
{include file="common/main_footer.tpl"}