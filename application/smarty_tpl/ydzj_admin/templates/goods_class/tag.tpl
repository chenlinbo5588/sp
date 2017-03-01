{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品分类</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('goods_class/category')}"><span>管理</span></a></li>
      	<li><a href="{admin_site_url('goods_class/add')}"><span>新增</span></a></li>
      	<li><a href="{admin_site_url('goods_class/export')}"><span>导出</span></a></li>
      	<li><a href="{admin_site_url('goods_class/import')}"><span>导入</span></a></li>
      	<li><a class="current" ><span>TAG管理</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title">
            <h5>操作提示</h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        	<ul>
            	<li>TAG值是分类搜索的关键字，请精确的填写TAG值。TAG值可以填写多个，每个值之间需要用逗号隔开。</li>
            	<li>导入/重置TAG功能可以根据商品分类重新更新TAG，TAG值默认为各级商品分类值。</li>
          	</ul>
         </td>
      </tr>
    </tbody>
  </table>
  {form_open(admin_site_url('goods_class/tag'),'id="formSearch"')}
    <input type="hidden" value="" id="submit_type" name="submit_type" />
    <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="w33pre">TAG名称</th>
          <th>TAG值</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['gc_tag_id']}">
          <td><input class="checkitem" group="chkVal" type="checkbox" value="{$item['gc_tag_id']}" name="tag_id[]"></td>
          <td class="name">{$item['gc_tag_name']}</td>
          <td class="tag">{$item['gc_tag_value']|escape}</td>
          <td><a href="{admin_site_url('goods_class/tag_edit')}?gc_tag_id={$item['gc_tag_id']}">编辑</a> | <a class="delete" href="javascript:void(0);" data-id="{$item['gc_tag_id']}" data-url="{admin_site_url('goods_class/tag_delete')}">删除</a></td>
        </tr>
      	{/foreach}
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="4">
          	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
            <a href="JavaScript:void(0);" class="btn deleteBtn" data-checkbox="tag_id[]" data-url="{admin_site_url('goods_class/tag_delete')}"><span>删除</span></a>
            <a class="btn" href="JavaScript:void(0);" id="refreshTagBtn" onclick="$('#dialog').show();location.href='{admin_site_url('goods_class/tag_update')}?page={$currentPage}'"><span>更新TAG名称</span></a>
            <a class="btn" href="JavaScript:void(0);" onclick="if(confirm('您确定要重新导入TAG吗？重新导入将会重置所有TAG值信息。')){ location.href='{admin_site_url('goods_class/tag_reset')}?page={$currentPage}'; }"> <span>导入/重置TAG</span> </a>
          	{include file="common/pagination.tpl"}
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  <div id="dialog" style="display: none; top: 344px; left: 430px;">更新TAG名称需要话费较长的时间，请耐心等待。</div>
<script>
$(function(){
    bindDeleteEvent();
});
</script>
{include file="common/main_footer.tpl"}