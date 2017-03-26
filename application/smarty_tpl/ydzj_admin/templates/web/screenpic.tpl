{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>首页配置</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('web/screenpic')}" class="current"><span>焦点区</span></a></li>
        <li><a href="{admin_site_url('web/screenpic_add')}"><span>添加焦点图</span></a></li>
        {*<li><a href="index.php?act=web_api&op=sale_edit"><span>促销区</span></a></li>*}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5>操作提示</h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li>焦点大图区可设置背景颜色，三张联动区一组三个图片。</li>
            <li>所有相关设置完成，使用底部的“更新板块内容”前台展示页面才会变化。</li>
          </ul>
          </td>
      </tr>
    </tbody>
  </table>
  
  <div class="homepage-focus" id="homepageFocusTab">
    <ul class="tab-menu">
      <li class="current" form="upload_screen_form"><a href="{admin_site_url('web/screenpic')}">全屏(背景)焦点大图</a></li>
      <li form="upload_screen_en_form"><a href="{admin_site_url('web/screenpic')}">关于我们 焦点图</a></li>
    </ul>
    <form id="upload_screen_form" class="tab-content" name="upload_screen_form" enctype="multipart/form-data" method="post" action="{admin_site_url('web/screenpic')}" target="upload_pic">
      <input type="hidden" name="code_id" value="101">
      <div class="full-screen-slides">
	      <ul>
	         {foreach from=$screen_list item=item key=key}
	          <li id="row{$key + 1}" >
	            <div class="focus-thumb" style="background-color:{$item['color']};" title="点击编辑选中区域内容"> <a class="delete del" data-url="{admin_site_url('web/screenpic_delete')}" href="javascript:void(0);" data-id="{$key + 1}">X</a> <img title="{$item['title']|escape}" src="{resource_url($item['pic_url'])}"/></div>
	            <div class="sort"><label>排序:<span>{$item['displayorder']}</span></label> | <a href="{admin_site_url('web/screenpic_edit?screen_id='|cat:($key + 1))}">编辑</a></div>
	          </li>
	          {/foreach}
	      </ul>
      </div>
      <a href="index.php?act=web_api&op=html_update&web_id=101" class="btn"><span>更新板块内容</span></a></div>
    </form>
  </div>
<script>
$(function(){
    bindDeleteEvent();
});
</script>
{include file="common/main_footer.tpl"}