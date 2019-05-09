{include file="common/main_header_navs.tpl"}
  {config_load file="user.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
	        <tr>
	          <th><label for="name">{#yewu_name#}</label></th>
	          <td><input class="txt" name="yewu_name" value="{$smarty.get['yewu_name']|escape}" type="text"></td>
	          <th><label for="name">{#real_name#}</label></th>
	          <td><input class="txt" name="real_name" value="{$smarty.get['real_name']|escape}" type="text"></td>
  	          <th>{#work_category#}:</th>
	          <td>
	          	<select name="work_category" id="id_type">
		          <option value="">请选择...</option>
		          {foreach from=$workCategory key=key item=item}
		          <option value="{$item['id']}" {if $search['work_category'] == $item['id']}selected{/if}>{$key}</option>
	              {/foreach}
		        </select>
	          </td>
	          <th>{#service_area#}:</th>
	          <td>
	          	<select name="service_area" id="id_type">
		          <option value="">请选择...</option>
		          {foreach from=$serviceArea key=key item=item}
		          <option value="{$item['id']}" {if $search['service_area'] == $item['id']}selected{/if}>{$key}</option>
	              {/foreach}
		        </select>
	          </td>
	        </tr>
	        <tr>
	          <th><label for="name">{#worker_name#}</label></th>
	          <td><input class="txt" name="worker_name" value="{$smarty.get['worker_name']|escape}" type="text"></td>
  	          <th><label for="name">{#worker_mobile#}</label></th>
	          <td><input class="txt" name="worker_mobile" value="{$smarty.get['worker_mobile']|escape}" type="text"></td>
	          <th><label for="name">{#user_name#}</label></th>
	          <td><input class="txt" name="user_name" value="{$smarty.get['user_name']|escape}" type="text"></td>
	          <th><label for="name">{#user_mobile#}</label></th>
	          <td><input class="txt" name="user_mobile" value="{$smarty.get['user_mobile']|escape}" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th>{#yewu_name#}</th>
          <th>{#real_name#}</th>
          <th>联系{#mobile#}</th>
          <th>{#worker_name#}</th>
          <th>{#worker_mobile#}</th>
          <th>{#user_name#}</th>
          <th>{#user_mobile#}</th>
          <th>{#work_category#}</th>
          <th>{#service_area#}</th>
          <th>{#initial_group#}</th>
          <th>{#current_group#}</th>
          <th>{#company_name#}</th>
          <th>业务{#status#}</th>
          <th>{#handle_date#}</th>
          <th>{#money#}</th>
          <th>{#order_id#}</th>
          <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="id[]"></td>
          <td class="yewu_name">{$item['yewu_name']|escape}</td>
          <td class="real_name">{mask_name($item['real_name'])|escape}</td>
          <td class="mobile">{mask_mobile($item['mobile'])|escape}</td>
          <td class="worker_name">{mask_name($item['worker_name'])|escape}</td>
          <td class="worker_mobile">{mask_mobile($item['worker_mobile'])|escape}</td>
          <td class="user_name">{mask_name($item['user_name'])|escape}</td>
          <td class="user_mobile">{mask_mobile($item['user_mobile'])|escape}</td>
          <td>{$basicData[$item['work_category']]['show_name']}</td>
          <td>{$basicData[$item['service_area']]['show_name']}</td>
          <td class="initial_group">{$item['initial_group']|escape}</td>
          <td class="current_group">{$item['current_group']|escape}</td>
          <td class="company_name"><a href="{admin_site_url(company|cat:'/index')}?id={$item['company_id']}">{$item['company_name']|escape}</td>
          <td class="status">{$operation[$item['status']]|escape}</td>
          <td class="handle_date">{$item['handle_date']|escape}</td>
          <td class="money">{$item['receivable_money']|escape}</td>
          <td class="order_id">{$item['order_id']|escape}</td>
          <td class="align-center">
          	{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if}&nbsp;&nbsp;
          	{if isset($permission[$moduleClassName|cat:'/delete'])}<a href="javascript:void(0)" class="deleteBtn" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" data-id="{$item['id']}">删除</a>{/if}
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