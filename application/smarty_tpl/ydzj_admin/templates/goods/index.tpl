{include file="common/main_header_navs.tpl"}
{config_load file="goods.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}

  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="search_goods_name">{#goods_name#}</label></th>
          <td><input type="text" value="{$searchMap['search_goods_name']|escape}" name="search_goods_name" id="search_goods_name" class="txt"></td>
          <th><label>{#goods_verify#}</label></th>
          <td>
          	<select name="goods_verify">
              <option value="">请选择...</option>
       	      {foreach from=$goodsVerify key=key item=item}
	          <option value="{$key}" {if $searchMap['goods_verify'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <th><label>{#goods_state#}</label></th>
          <td>
          	<select name="goods_state">
              <option value="">请选择...</option>
       	      {foreach from=$goodsState key=key item=item}
	          <option value="{$key}" {if $searchMap['goods_state'] == $key}selected{/if}>{$item}</option>
              {/foreach}
            </select>
          </td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
        <tr>
        	<td>{#brand_id#}:</td>
        	<td>
        		<select name="brand_id" id="brandId">
		          <option value="">请选择...</option>
		          {foreach from=$brandList item=item}
		          <option {if $searchMap['brand_id'] == $item['brand_id']}selected{/if} value="{$item['brand_id']}">{$item['brand_name']}</option>
		          {/foreach}
		        </select>
	        </td>
	        <td>{#gc_id#}:</td>
	        <td colspan="3">
	        	<select name="gc_id" id="goodsClassId">
		          <option value="">请选择...</option>
		          {foreach from=$goodsClassList item=item}
		          <option {if $searchMap['gc_id'] == $item['gc_id']}selected{/if} value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['name']}</option>
		          {/foreach}
		        </select>
	        </td>
	        <th><label>{#goods_commend#}</label></th>
          <td>
          	<select name="goods_commend">
              <option value="">请选择...</option>
       	      {foreach from=$goodsCommend key=key item=item}
	          <option value="{$key}" {if $searchMap['goods_commend'] == $key}selected{/if}>{$item}</option>
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
          <th>商品代码</th>
          <th>品牌&分类</th>
          <th class="align-center">商品状态</th>
          <th class="align-center">推荐状态</th>
          <th class="align-center">审核状态</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['goods_id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['goods_id']}" class="checkitem"></td>
          <td class="w60 picture"><img class="size-100x100" src="{if $item['goods_pic_m']}{resource_url($item['goods_pic_m'])}{else if $item['goods_pic_b']}{resource_url($item['goods_pic_b'])}{else if $item['goods_pic']}{resource_url($item['goods_pic'])}{else}{resource_url('img/default.jpg')}{/if}"/></td>
          <td class="goods-name w270">
          	<p><span>{$item['goods_name']|escape}</span></p>
            {*<p class="store">所属店铺:官方店铺</p>*}
         </td>
         <td>{$item['goods_code']|escape}</td>
         <td>
          	<p>{$brandList[$item['brand_id']]['brand_name']}</p>
            <p>{$goodsClassList[$item['gc_id']]['name']}</p>
          </td>
          <td class="align-center">{if $item['goods_state'] == 2}正常{else}下架{/if}</td>
          <td class="align-center">{if $item['goods_commend'] == 2}已{else}未{/if}推荐</td>
          <td class="align-center">{if $item['goods_verify'] == 2}审核通过{else}未审核{/if}</td>
          <td class="align-center">
          	<p><a href="{site_url('product/detail')}?gc_id={$item['gc_id']}&id={$item['goods_id']}" target="_blank">查看</a> | <a href="{admin_site_url('goods/edit')}?goods_id={$item['goods_id']}">编辑</a></p>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
    	<a href="javascript:void(0);" class="btn opBtn" data-title="确定提交审核吗?" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/handle_verify')}"><span>提交审核</span></a>
    	<a href="javascript:void(0);" class="btn opBtn" data-title="确定推荐吗" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_verify')}" data-ajaxformid="#verifyForm"><span>推荐</span></a>
    	<a href="javascript:void(0);" class="btn opBtn" data-title="确定上架吗?" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_published')}"><span>上架</span></a>
    	<a href="javascript:void(0);" class="btn opBtn" data-title="确定下架吗?" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/batch_offline')}"><span>下架</span></a>
        <a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>
        {include file="common/pagination.tpl"}
    </div>
    
  </form>
  <div id="verifyDlg"></div>
    <script type="text/javascript" src="{resource_url('js/service/staff_index.js',true)}"></script>
{include file="common/main_footer.tpl"}