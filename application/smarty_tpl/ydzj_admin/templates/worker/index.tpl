{include file="common/main_header.tpl"}
  {config_load file="worker.conf"}
  {include file="common/sub_nav.tpl"}
  {form_open(admin_site_url($moduleClassName|cat:'/index'),'id="formSearch" method="get"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="name">{#name#}</label></th>
          <td><input type="text" value="{$smarty.get['name']|escape}" name="name" id="name" class="txt"></td>
          <th><label>审核</label></th>
          <td>
          	<select name="goods_verify">
              <option value="全部">请选择...</option>
              {foreach from=$searchMap['goods_verify'] item=item key=key}
              <option value="{$key}" {if $smarty.post['goods_verify'] == $key}selected{/if}>{$key}</option>
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
		          <option {if $smarty.post['gc_id'] == $item['gc_id']}selected{/if} value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['name_cn']}</option>
		          {/foreach}
		        </select>
	        </td>
        </tr>
      </tbody>
    </table>
  </form>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th colspan="2">{#name#}</th>
          <th>{#id_type#}</th>
          <th>{#id_no#}</th>
          <th>{#sex#}</th>
          <th>{#age#}</th>
          <th>{#jiguan#}</th>
          <th>{#mobile#}</th>
          <th>{#address#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>
          <td class="w60 picture"><img class="size-100x100" src="{if $item['avatar_s']}{resource_url($item['avatar_s'])}{else if $item['avatar_b']}{resource_url($item['avatar_b'])}{else if $item['avatar_m']}{resource_url($item['avatar_m'])}{else}{resource_url('img/default.jpg')}{/if}"/></td>
          <td class="goods-name w270">
          	<p><span>{$item['name']|escape}</span></p>
         </td>
         <td>{$item['id_type']}</td>
         <td>{$item['id_no']}</td>
         <td>{$item['sex']}</td>
         <td>{$item['age']}</td>
         <td>{$item['jiguan']}</td>
         <td>{$item['mobile']}</td>
         <td>{$item['address']|escape}</td>
         <td class="align-center">
          	<p><a href="{site_url($moduleClassName|cat:'/detail')}?id={$item['id']}" target="_blank">查看</a> | <a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a></p>
          </td>
        </tr>
        {/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="9">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
          	{include file="common/pagination.tpl"}
           </td>
        </tr>
      </tfoot>
    </table>
  </form>
  
<script>
$(function(){
    bindDeleteEvent();
});
</script>

{include file="common/main_footer.tpl"}