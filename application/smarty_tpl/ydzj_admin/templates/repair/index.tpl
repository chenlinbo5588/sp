{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
	        <tr>
	          <td>{#repair_type#}:</td>
	          <td>
	          	<select name="repair_type" id="id_type">
		          <option value="">请选择...</option>
		          {foreach from=$repairType key=key item=item}
		          <option value="{$key}" {if $search['repair_type'] == $key}selected{/if}>{$item}</option>
	              {/foreach}
		        </select>
	          </td>
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
          <th>{#repair_type#}</th>
          <th>{#yezhu_name#}</th>
          <th>{#mobile#}</th>
          <th>{#address#}</th>
          <th>{#remark#}</th>
          <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="id[]"></td>
          <td>{if $item['repair_type'] == 1}管道报修{elseif $item['repair_type'] == 2}电路报修{elseif $item['repair_type'] == 3}电器报修{elseif $item['repair_type'] == 4}网络报修{elseif $item['repair_type'] == 5}其他报修{/if|escape}</span></td>
          <td>{$item['yezhu_name']|escape}</span></td>
          <td><span class="editable" data-id="{$item['id']}" data-fieldname="mobile">{$item['mobile']|escape}</span></td>
		  <td><span class="editable" data-id="{$item['id']}" data-fieldname="address">{$item['address']|escape}</span></td>
          <td>{if $item['remark']}{$item['remark']|escape}{else}无{/if}</td>
          <td class="align-center">
          	<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>&nbsp;|&nbsp;
          	<a href="javascript:void(0)" class="delete" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" data-id="{$item['id']}">删除</a>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
<script>
$(function(){
    bindDeleteEvent();
    
    $("span.editable").inline_edit({ 
    	url: "{admin_site_url($moduleClassName|cat:'/inline_edit')}",
    	clickNameSpace:'inlineEdit'
    });
	    
});
</script>
{include file="common/main_footer.tpl"}