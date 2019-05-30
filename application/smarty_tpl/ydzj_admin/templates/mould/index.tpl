{include file="common/main_header_navs.tpl"}
  
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
  <div class="fixedBar">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
			<th>模板名称</th>
			<td><input class="txt" name="name" id="name" value="{$smarty.get['name']|escape}" type="text"></td>
			<th>模板类型</th>
			<td><input class="txt" name="type" id="type" value="{$smarty.get['type']|escape}" type="text"></td>
	      <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
      </tbody>
    </table>
  </div>
  </form>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th>模板名称</th>
          <th>模块分类</th>
      	  <th class="w72 align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td>{($item['name'])|escape}</td>
          <td>{($item['type'])|escape}</td>
          <td class="align-center">
          	{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if}&nbsp;
          	{if isset($permission[$moduleClassName|cat:'/delete'])}<a href="javascript:void(0)" class="delete" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" data-id="{$item['id']}">删除</a>{/if}&nbsp;
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </form>
  <div id="pmDetailDlg" title="站内信详情"></div>
  <script type="text/javascript" src="{resource_url('js/pm/admin_pm_index.js',true)}"></script>
{include file="common/main_footer.tpl"}