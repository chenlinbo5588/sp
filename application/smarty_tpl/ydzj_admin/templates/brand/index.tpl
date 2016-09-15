{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>品牌</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="{admin_site_url('brand/add')}"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('brand/index'),'id="formSearch"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search">
	    <tbody>
	        <tr>
	          <th><label for="search_brand_name">品牌名称</label></th>
	          <td><input class="txt" name="search_brand_name" id="search_brand_name" value="{$smarty.post['search_brand_name']|escape}" type="text"></td>
	          <th><label for="search_brand_class">所属分类</label></th>
	          <td><input class="txt" name="search_brand_class" id="search_brand_class" value="{$smarty.post['search_brand_class']|escape}" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
	  <table class="table tb-type2" id="prompt">
	    <tbody>
	      <tr class="space odd">
	        <th colspan="2"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
	      </tr>
	      <tr>
	        <td>
		        <ul>
		            <li>管理本网站上商品的品牌</li>
		        </ul>
	        </td>
	      </tr>
	    </tbody>
	  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="w48">排序</th>
          <th class="w270">品牌名称</th>
          <th class="w150">所属分类</th>
          <th>品牌图片标识</th>
          <th class="align-center">推荐</th>
          <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['brand_id']}">
          <td><input value="{$item['brand_id']}" class="checkitem" group="chkVal" type="checkbox" name="del_brand_id[]"></td>
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
      <tfoot>
      	<tr class="tfoot">
          <td colspan="7">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn" id="deleteBtn" data-checkbox="del_brand_id[]" data-url="{admin_site_url('brand/delete')}"><span>删除</span></a>
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
       </tfoot>
    </table>
  </form>
<script>
$(function(){
    bindDeleteEvent();
    bindOnOffEvent();
});
</script>
{include file="common/main_footer.tpl"}