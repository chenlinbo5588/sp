{include file="common/main_header_navs.tpl"}
   <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
          <ul>
            <li>当添加文章时可选择文章分类，用户可根据分类查询文章列表</li>
            <li>点击分类名前“+”符号，显示当前分类的下级分类</li>
            <li>通过修改排序数字可以控制前台显示顺序，数字越小越靠前</li>
          </ul>
        </td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url('cms_article_class/category'),'id="searchForm"')}
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="table tb-type2" id="listtable">
      <thead>
        <tr class="thead">
          <th></th>
          <th>排序</th>
          <th>分类名称</th>
          <th>开启状态</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td class="w48">
          	<img fieldid="{$item['id']}" status="open" nc_type="flex" src="{resource_url('img/tv-expandable.gif')}">
          </td>
          <td class="w48 sort"><span class="editable" data-id="{$item['id']}" data-fieldname="ac_sort">{$item['ac_sort']}</span></td>
          <td class="name">
          	<span title="可编辑" class="editable" data-id="{$item['id']}" data-fieldname="name">{$item['name']|escape}</span>
          	<a class="btn-add-nofloat marginleft" href="{admin_site_url($moduleClassName|cat:'/add')}?pid={$item['id']}"><span>新增下级</span></a>
          </td>
          <td class="yes-onoff"><a href="javascript:void(0);" data-url="{admin_site_url($moduleClassName|cat:'/inline_edit')}" {if $item['status']}class="enabled"{else}class="disabled"{/if} data-id="{$item['id']}" data-fieldname="status"><img src="{resource_url('img/transparent.gif')}"></a></td>
          <td class="w84"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a> | <a class="delete" data-url="{admin_site_url($moduleClassName|cat:'/delete')}" href="javascript:void(0);" data-id="{$item['id']}">删除</a></td>
        </tr>
        {/foreach}
      </tbody>
     </table>
  </form>
  <script type="text/javascript" src="{resource_url('js/jquery.edit.js')}"></script>
  <script type="text/javascript">
    var configUrls = {
  	'targetId' : '#listtable',
	  	'dataUrl' : "{admin_site_url($moduleClassName|cat:'/category')}",
	  	'inlineUrl' : "{admin_site_url($moduleClassName|cat:'/inline_edit')}"
	};
	
	
  </script>
  <script type="text/javascript" src="{resource_url('js/service/tree_event.js')}"></script>
{include file="common/main_footer.tpl"}