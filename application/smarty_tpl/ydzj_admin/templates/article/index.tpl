{include file="common/main_header.tpl"}
{config_load file="article.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="{admin_site_url('article/add')}"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('article/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="search_article_title">文章标题</label></th>
          <td><input type="text" value="{$smarty.post['search_article_title']|escape}" name="search_article_title" id="search_article_title" class="txt"></td>
          <th><label>文章分类</label></th>
          <td>
        	<select name="ac_id" id="articleClassId">
	          <option value="">请选择...</option>
	          {foreach from=$articleClassList item=item}
	          <option {if $smarty.post['ac_id'] == $item['ac_id']}selected{/if} value="{$item['ac_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['ac_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
      </tbody>
   </table>
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
          <th>序号</th>
          <th class="w48">排序</th>
          <th>标题</th>
          <th>文章分类</th>
          <th class="align-center">显示</th>
          <th class="align-center">添加时间</th>
          <th class="w60 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['article_id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['article_id']}" class="checkitem"></td>
          <td>{$item['article_id']}</td>
          <td>{$item['article_sort']}</td>
          <td>{$item['article_title']|escape}</td>
          <td>{$articleClassList[$item['ac_id']]['ac_name']}</td>
          <td class="align-center">{if $item['article_show']}是{else}否{/if}</td>
          <td class="nowrap align-center">{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td class="align-center">
            {if $articleClassList[$item['ac_id']]['ac_code']}
            <a href="{base_url($articleClassList[$item['ac_id']]['ac_code']|cat:'/'|cat:$item['article_id']|cat:'.html')}" target="_blank">查看</a>
            {else}
            <a href="{base_url('index/article/'|cat:$item['article_id']|cat:'.html')}" target="_blank">查看</a>
            {/if}
            |
          	<a href="{admin_site_url('article/edit')}?article_id={$item['article_id']}">编辑</a>
          </td>
        </tr>
      	{/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="8">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url('article/delete')}"><span>删除</span></a>
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