{include file="common/main_header.tpl"}
  {include file="common/sub_nav.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search" >
	    <tbody>
	        <tr>
	          <th><label for="name">{#building_name#}</label></th>
	          <td><input class="txt" name="name" id="name" value="{$smarty.get['name']|escape}" type="text"></td>
	          <th><label for="address">{#address#}</label></th>
	          <td><input class="txt" name="address" id="address" value="{$smarty.get['address']|escape}" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th>{#displayorder#}</th>
          <th>{#building_name#}</th>
          <th>{#address#}</th>
          <th>{#building_nickname#}</th>
          <th>{#unit_num#}</th>
          <th>{#yezhu_num#}</th>
          <th>{#total_num#}</th>
          <th>{#max_plies#}</th>
          <th>{#floor_plies#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['id']}" class="checkitem" group="chkVal" type="checkbox" name="del_id[]"></td>
          <td class="sort"><span class="editable" data-id="{$item['id']}" data-fieldname="displayorder">{$item['displayorder']}</span></td>
          <td class="name"><span class="editable" data-id="{$item['id']}" data-fieldname="name">{$item['name']|escape}</span></td>
          <td>{$item['address']|escape}</td>
          <td>{$item['nickname']|escape}</td>
          <td>{$item['unit_num']}</td>
          <td>{$item['yezhu_num']}</td>
          <td>{$item['total_num']}</td>
          <td>{$item['max_plies']}</td>
          <td>{$item['floor_plies']}</td>
          <td>
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