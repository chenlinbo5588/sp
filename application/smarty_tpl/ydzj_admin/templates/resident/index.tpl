{include file="common/main_header.tpl"}
  {include file="common/sub_nav.tpl"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search">
	    <tbody>
	        <tr>
	          <th><label for="search_brand_name">小区名称</label></th>
	          <td><input class="txt" name="search_brand_name" id="search_brand_name" value="{$smarty.post['search_brand_name']|escape}" type="text"></td>
	          <th><label for="search_brand_class">地址</label></th>
	          <td><input class="txt" name="search_brand_class" id="search_brand_class" value="{$smarty.post['search_brand_class']|escape}" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="w48">排序</th>
          <th class="w270">小区名称</th>
          <th class="w150">小区地址</th>
          <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input value="{$item['brand_id']}" class="checkitem" group="chkVal" type="checkbox" name="del_id[]"></td>
          <td class="sort"><span class="editable">{$item['brand_sort']}</span></td>
          <td class="name"><span class="editable">{$item['brand_name']}</span></td>
          <td>{$item['brand_class']}</td>
          <td class="picture"><div class="brand-picture">{if $item['brand_pic']}<img src="{resource_url($item['brand_pic'])}"/>{/if}</div></td>
          <td class="align-center yes-onoff">
          	<a href="JavaScript:void(0);" {if $item['brand_recommend']}class="enabled"{else}class="disabled"{/if} data-id="{$item['brand_id']}" data-fieldname="brand_recommend"><img src="{resource_url('img/transparent.gif')}"></a>
          </td>
          <td class="align-center"><a href="{admin_site_url('brand/edit')}?brand_id={$item['brand_id']}">编辑</a>&nbsp;|&nbsp;<a href="javascript:void(0)" class="delete" data-url="{admin_site_url('brand/delete')}" data-id="{$item['brand_id']}">删除</a></td>
        </tr>
        {/foreach}
      </tbody>
      
    </table>
    <div class="fixedBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
        {include file="common/pagination.tpl"}
        
    </div>
    
    
          	
  </form>
<script>
$(function(){
    bindDeleteEvent();
});
</script>
{include file="common/main_footer.tpl"}