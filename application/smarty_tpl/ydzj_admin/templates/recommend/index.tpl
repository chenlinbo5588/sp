{include file="common/main_header_navs.tpl"}
{config_load file="article.conf"}
  {form_open(site_url($uri_string),'id="formSearch"')}
  <input type="hidden" name="page" value=""/>
   <table class="table tb-type2" id="prompt">
    <tbody>
    </tbody>
  </table>
  {form_open(admin_site_url('article_class/category'),'id="searchForm"')}
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="table tb-type2" id="listtable">
      <thead>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th>名称</th>
          <th>风格</th>
          <th>显示条数</th>
          <th>时间格式</th>
          <th>缓存时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      		<tr class="hover edit" id="row{$item['id']}">
			<td>{$item['name']|escape}</td>
			<td>{$basicData[$item['style']]['show_name']}</td>
			<td>{$item['show_number']|escape}</td>
			<td>{$basicData[$item['dateformat']]['show_name']}</td>
			<td>{$item['cachetime']|escape}分钟</td>
			<td class="align-center">
				{if isset($permission[$moduleClassName|cat:'/detail'])}<a href="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']};">查看数据</a>{/if}
         		{if isset($permission[$moduleClassName|cat:'/add_detail'])}<a href="{admin_site_url($moduleClassName|cat:'/add_detail')}?id={$item['id']}">添加数据</a>{/if}&nbsp;
				{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if}&nbsp;
			</td>
			</tr>
        {/foreach}
      </tbody>
    </table>
      </tbody>
     </table>
  </form>
  <div id="detailDlg"></div>
{include file="common/main_footer.tpl"}