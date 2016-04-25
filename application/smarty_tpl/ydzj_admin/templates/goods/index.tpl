{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="{admin_site_url('goods/add')}"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('goods/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="search_goods_name">商品名称</label></th>
          <td><input type="text" value="{$smarty.post['search_goods_name']|escape}" name="search_goods_name" id="search_goods_name" class="txt"></td>
          <th><label>审核</label></th>
          <td>
          	<select name="goods_verify">
              <option value="全部">请选择...</option>
              {foreach from=$searchMap['goods_verify'] item=item key=key}
              <option value="{$key}" {if $smarty.post['goods_verify'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <th><label>商品发布状态</label></th>
          <td>
          	<select name="goods_state">
              <option value="全部">请选择...</option>
              {foreach from=$searchMap['goods_state'] item=item key=key}
              <option value="{$key}" {if $smarty.post['goods_state'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
        <tr>
        	<td>品牌:</td>
        	<td>
        		<select name="brand_id" id="brandId">
		          <option value="">请选择...</option>
		          {foreach from=$brandList item=item}
		          <option {if $smarty.post['brand_id'] == $item['brand_id']}selected{/if} value="{$item['brand_id']}">{$item['brand_name']}</option>
		          {/foreach}
		        </select>
	        </td>
	        <td>商品分类:</td>
	        <td colspan="3">
	        	<select name="gc_id" id="goodsClassId">
		          <option value="">请选择...</option>
		          {foreach from=$goodsClassList item=item}
		          <option {if $smarty.post['gc_id'] == $item['gc_id']}selected{/if} value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['gc_name']}</option>
		          {/foreach}
		        </select>
	        </td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div>
        </th>
      </tr>
      <tr>
        <td>
        	<ul>
              <li>上架，当商品处于非上架状态时，前台将不能浏览该商品，管理员可控制商品上架状态</li>
            </ul>
        </td>
      </tr>
    </tbody>
  </table>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th colspan="2">商品名称</th>
          <th>品牌&分类</th>
          <th class="align-center">商品状态</th>
          <th class="align-center">审核状态</th>
          <th class="align-center">操作 </th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['goods_id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['goods_id']}" class="checkitem"></td>
          <td class="w60 picture"><img class="size-56x56" src="{resource_url($item['goods_pic'])}"/></td>
          <td class="goods-name w270">
          	<p><span>{$item['goods_name']|escape}</span></p>
            {*<p class="store">所属店铺:官方店铺</p>*}
         </td>
         <td>
          	<p>{$brandList[$item['brand_id']]['brand_name']}</p>
            <p>{$goodsClassList[$item['gc_id']]['gc_name']}</p>
          </td>
          <td class="align-center">{$searchMap['goods_state'][$item['goods_state']]}</td>
          <td class="align-center">{$searchMap['goods_verify'][$item['goods_verify']]}</td>
          <td class="align-center">
          	<p><a href="{site_url('goods/detail')}?goods_id={$item['goods_id']}" target="_blank">查看</a> | <a href="{admin_site_url('goods/edit')}?goods_id={$item['goods_id']}">编辑</a></p>
          </td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="7">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn" id="deleteBtn" data-checkbox="id[]" data-url="{admin_site_url('goods/delete')}"><span>删除</span></a>
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
      </tfoot>
    </table>
  </form>
  
<script>
$(function(){
    bindDeleteEvent();
    //bindOnOffEvent();
});
</script>

{include file="common/main_footer.tpl"}