{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
	        <tr>
	          <th><label for="name">{#parking_name#}</label></th>
	          <td><input class="txt" name="name" id="name" value="{$smarty.get['name']|escape}" type="text"></td>
	          <th><label for="resident_name">{#resident_name#}</label></th>
	          <td><input class="txt" name="resident_name" id="resident_name" value="{$smarty.get['resident_name']|escape}" type="text"></td>
	          <td>{#fee_expire#}:</td>
	    	  <td>
	    		<input type="text" autocomplete="off"  value="{$search['expire_s']}" name="expire_s" id="expire_s"  class="datepicker txt-short"/>
	    		-
	    		<input type="text" autocomplete="off"  value="{$search['expire_e']}" name="expire_e" id="expire_e" class="datepicker txt-short"/>
          	</td>
	        </tr>
	        <tr>
	        	<th><label for="name">{#yezhu_name#}</label></th>
		        <td><input class="txt" name="yezhu_name" value="{$smarty.get['yezhu_name']|escape}" type="text"></td>
		        <th><label for="name">{#mobile#}</label></th>
		        <td><input class="txt" name="mobile" value="{$smarty.get['mobile']|escape}" type="text"></td>
		        <td colspan="2"><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th>{#displayorder#}</th>
          <th>{#parking_name#}</th>
          <th>{#address#}</th>
          <th>{#jz_area#}</th>
          <th>{#yezhu_name#}</th>
          <th>{#mobile#}</th>
          <th>{#fee_expire#}</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="id[]"></td>
          <td class="sort"><span class="editable" data-id="{$item['id']}" data-fieldname="displayorder">{$item['displayorder']}</span></td>
          <td class="name"><span class="editable" data-id="{$item['id']}" data-fieldname="name">{$item['name']|escape}</span></td>
          <td>{$item['address']|escape}</td>
          <td>{$item['jz_area']|escape}</td>
          <td>{if $item['yezhu_id']}{$item['yezhu_name']}{else}暂未入驻{/if}</td>
          <td>{$item['mobile']|escape}</td>
          <td>{if $item['expire']}{$item['expire']|date_format:"%Y-%m-%d"}{else}无缴费记录{/if}</td>
          <td class="align-center">
          	{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if}&nbsp;
          	{if isset($permission[$moduleClassName|cat:'/yezhu_change'])}<a href="{admin_site_url($moduleClassName|cat:'/yezhu_change')}?id={$item['id']}">业主{if $item['yezhu_id']}变更{else}入驻{/if}</a>{/if}&nbsp;
          	{if isset($permission[$moduleClassName|cat:'/delete'])}<a href="javascript:void(0)" class="delete" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" data-id="{$item['id']}">删除</a>{/if}
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        {if isset($permission[$moduleClassName|cat:'/delete'])}<a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>{/if}
        {include file="common/pagination.tpl"}
        
    </div>
  </form>
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
<script>
$(function(){

	{if isset($permission[$moduleClassName|cat:'/delete'])}
    bindDeleteEvent();
    {/if}
    
   
     $( ".datepicker" ).datepicker({
    	changeYear: true
    });
    
    
    {if isset($permission[$moduleClassName|cat:'/inline_edit'])}
    $("span.editable").inline_edit({ 
    	url: "{admin_site_url($moduleClassName|cat:'/inline_edit')}",
    	clickNameSpace:'inlineEdit'
    });
    {/if}
	    
});
</script>
{include file="common/main_footer.tpl"}