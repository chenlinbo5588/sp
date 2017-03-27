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
  <div class="feedback">{$feedback}</div>
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
      <li {if $code_id == 102}class="current"{/if}><a href="{admin_site_url('web/screenpic?code_id=102')}">全屏(背景)焦点大图</a></li>
      {*<li {if $code_id == 122}class="current"{/if}><a href="{admin_site_url('web/screenpic?code_id=122')}">关于我们焦点图</a></li>
      <li {if $code_id == 123}class="current"{/if}><a href="{admin_site_url('web/screenpic?code_id=123')}">推荐商品</a></li>*}
    </ul>
    <form id="upload_screen_form" name="upload_screen_form"  method="post" action="{admin_site_url('web/screenpic')}">
      <input type="hidden" name="code_id" value="{$code_id}">
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
      <div><input type="submit" name="submit" value="更新" class="msbtn"/></div>
    </form>
  </div>
<script>
$(function(){
    bindDeleteEvent();
});
</script>
{include file="common/main_footer.tpl"}