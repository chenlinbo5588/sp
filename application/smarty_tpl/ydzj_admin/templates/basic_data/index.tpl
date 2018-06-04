{include file="common/main_header.tpl"}
  {include file="common/sub_nav.tpl"}
  {form_open(admin_site_url($moduleClassName|cat:'/index'),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search">
	    <tbody>
	        <tr>
	          <th><label for="search_brand_class">名称</label></th>
	          <td><input class="txt" name="search_name" id="search_name" value="{$smarty.get['search_name']|escape}" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	</table>
	  
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="w48">排序</th>
          <th class="w270">显示名称</th>
          <th class="align-center">启用</th>
          <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="del_id[]"></td>
          <td class="sort"><span class="editable" data-id="{$item['id']}" data-fieldname="displayorder">{$item['displayorder']}</span></td>
          <td class="name"><span class="editable" data-id="{$item['id']}" data-fieldname="show_name">{$item['show_name']}</span></td>
          <td class="align-center yes-onoff">
          	<a href="javascript:void(0);" {if $item['enable']}class="enabled"{else}class="disabled"{/if} data-id="{$item['id']}" data-fieldname="enable"><img src="{resource_url('img/transparent.gif')}"></a>
          </td>
          <td class="align-center"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>&nbsp;|&nbsp;<a href="javascript:void(0)" class="delete" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" data-id="{$item['id']}">删除</a></td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="7">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="del_id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
       </tfoot>
    </table>
  </form>
  
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
<script>
$(function(){
    bindDeleteEvent();
    bindOnOffEvent();
    
    $("span.editable").inline_edit({ 
    	url: "{admin_site_url($moduleClassName|cat:'/inline_edit')}"
    });
});
</script>
{include file="common/main_footer.tpl"}