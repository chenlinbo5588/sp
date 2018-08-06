{include file="common/main_header_navs.tpl"}
  {form_open(admin_site_url('user/index'),'id="formSearch" class="formSearch"')}
  	<input type="hidden" name="page" value=""/>
  	<input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>管理员名称</td>
          <td><input type="text" value="{$smarty.post['username']}" name="username" class="txt"></td>
          <td>管理员邮箱</td>
          <td><input type="text" value="{$smarty.post['email']}" name="email" class="txt"></td>
          <td>
            <input type="submit" class="msbtn" name="tijiao" value="查询"/>
          </td>
        </tr>
      </tbody>
    </table>
    
    <table class="table tb-type2">
      <thead>
        <tr class="space">
          <th colspan="7" class="nobg">列表</th>
        </tr>
        <tr class="thead">
          <th></th>
          <th>登录名</th>
          <th class="align-center">真实名称</th>
          <th class="align-center">状态</th>
          <th class="align-center">上次登录</th>
          <th class="align-center">权限组</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover">
          <td class="w24"><input name="del_id[]" group="chkVal" type="checkbox" value="{$item['uid']}"></td>
          <td>{$item['email']}</td>
          <td class="align-center">{$item['username']|escape}</td>
          <td class="align-center">{$item['status']}</td>
          <td class="align-center">{$item['last_login']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center">{if $item['group_id'] == 0}无权限组{else}{$roleList[$item['group_id']]}{/if}</td>
          <td class="align-center"><a href="{admin_site_url('user/edit')}?uid={$item['uid']}">编辑</a></td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="7">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('开启');go();"><span>开启</span></a>
          	<a href="javascript:void(0);" class="btn" onclick="$('#submit_type').val('关闭');go();"><span>关闭</span></a>
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
       </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	function go(){
		$("#formSearch").submit();
	}
  </script>
{include file="common/main_footer.tpl"}