{include file="common/main_header_navs.tpl"}
  {config_load file="user.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
	        <tr>
	          <th><label for="name">{#resident_name#}</label></th>
	          <td><input class="txt" name="resident_name" value="{$smarty.get['resident_name']|escape}" type="text"></td>
	          <th><label for="name">{#yezhu_name#}</label></th>
	          <td><input class="txt" name="name" value="{$smarty.get['name']|escape}" type="text"></td>
	          <th><label for="name">{#mobile#}</label></th>
	          <td><input class="txt" name="mobile" value="{$smarty.get['mobile']|escape}" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th>{#name#}</th>
          <th>{#mobile#}</th>
          <th>{#company_name#}</th>
          <th>{#group_name#}</th>
          <th>{#user_type#}</th>
          <th>{#sex#}</th>
          <th>{#user_statuis#}</th>
          <th>{#inviter#}</th>
          <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="id[]"></td>
          <td class="name">{mask_name($item['name'])|escape}</td>
          <td class="mobile">{mask_mobile($item['mobile'])|escape}</td>
          <td class="company_name"><a href="{admin_site_url(company|cat:'/index')}?id={$item['company_id']}">{$item['company_name']|escape}</td>
          <td class="group_name">{$item['group_name']|escape}</td>
          <td>{$userType[$item['user_type']]}</td>
          <td>{if $item['sex'] == 1}男{else}女{/if}</td>
          <td>{$userType[$item['user_type']]}</td>
          <td class="inviter_name">{$item['inviter_name']|escape}</td>
          <td class="align-center">
          	{if isset($permission[$moduleClassName|cat:'/delete'])}<a href="javascript:void(0)" class="deleteBtn" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" data-id="{$item['id']}">删除</a>{/if}
          	{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if}&nbsp;&nbsp;
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
        {if isset($permission[$moduleClassName|cat:'/set_worker'])}<a href="javascript:void(0);" class="btn opBtn" data-title="确定要设置为工作人员吗工作人员默认登录密码为：123456?" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/set_worker')}" data-ajaxformid="#verifyForm"><span>设置工作人员</span></a>{/if}
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
<script>
$(function(){
    {if isset($permission[$moduleClassName|cat:'/delete'])}bindDeleteEvent();{/if}
    
    {if isset($permission[$moduleClassName|cat:'/inline_edit'])}
    $("span.editable").inline_edit({ 
    	url: "{admin_site_url($moduleClassName|cat:'/inline_edit')}",
    	clickNameSpace:'inlineEdit'
    });
    {/if}

});
</script>
  <script type="text/javascript" src="{resource_url('js/user/tdkcuser_index.js',true)}"></script>
{include file="common/main_footer.tpl"}