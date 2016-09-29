{include file="common/main_header.tpl"}
{config_load file="article.conf"}
  {form_open(admin_site_url('cms_article/index'),'id="formSearch"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="article_title">文章标题</label></th>
          <td><input type="text" value="{$smarty.post['article_title']|escape}" name="article_title" id="article_title" class="txt"></td>
          <th><label>文章分类</label></th>
          <td>
        	<select name="id" id="articleClassId">
	          <option value="">请选择...</option>
	          {foreach from=$articleClassList item=item}
	          <option {if $smarty.post['id'] == $item['id']}selected{/if} value="{$item['id']}">{str_repeat('......',$item['level'])}{$item['name']}</option>
	          {/foreach}
	        </select>
          </td>
          <th><label>{#article_state#}</label></th>
          <td>
        	<select name="article_state" id="article_state">
	          <option value="">请选择...</option>
	          {foreach from=$article_state key=key item=item}
	          <option value="{$key}" {if $smarty.post['article_state'] == $key}selected{/if}>{$item}</option>
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
          <th>标题</th>
          <th>{#article_class#}</th>
          <th>{#article_origin#}</th>
          <th>{#origin_address#}</th>
          <th>{#author#}</th>
          <th>{#keyword#}</th>
          <th>{#article_tag#}</th>
          <th>{#article_state#}</th>
          <th>{#publish_time#}</th>
          <th>{#commend_flag#}</th>
          <th>{#comment_flag#}</th>
          <th>{#comment_count#}/{#share_count#}/{#article_click#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>
          <td>{$item['id']}</td>
          <td>{$item['article_title']|escape}</td>
          <td>{$articleClassList[$item['ac_id']]['name']}</td>
          <td>{$item['article_origin']|escape}</td>
          <td>{$item['origin_address']|escape}</td>
          <td>{$item['author']|escape}</td>
          <td>{$item['keyword']|escape}</td>
          <td>{$item['article_tag']|escape}</td>
          <td>{$article_state[$item['article_state']]}</td>
          <td>{$item['publish_time']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
          <td>{if $item['commend_flag'] == 1}已{else}未{/if}推荐</td>
          <td>{if $item['comment_flag'] == 1}允许{else}禁止{/if}评论</td>
          <td>{$item['comment_count']}/{$item['share_count']}/{$item['article_click']}</td>
          <td>
          	<p><a href="{admin_site_url('cms_article/edit')}?id={$item['id']}">编辑</a></p>
          </td>
        </tr>
      	{/foreach}
      </tbody>
      <tfoot>
      	<tr class="tfoot">
          <td colspan="15">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
          	<a href="javascript:void(0);" class="btn" id="deleteBtn" data-checkbox="id[]" data-url="{admin_site_url('cms_article/delete')}"><span>删除</span></a>
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