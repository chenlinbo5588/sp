{include file="common/main_header.tpl"}
  {include file="common/colorpicker.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>首页配置</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('web/screenpic')}"><span>焦点区</span></a></li>
        <li><a {if empty($info['screen_id'])}class="current"{/if} href="{admin_site_url('web/screenpic_add')}"><span>添加焦点图</span></a></li>
        {if $info['screen_id']}<li><a class="current"  href="{admin_site_url('web/screenpic_edit/')}?screen_id={$info['screen_id']}" ><span>编辑焦点图</span></a></li>{/if}
        
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
  {if $info['screen_id']}
    <form id="upload_screen_form" name="upload_screen_form" enctype="multipart/form-data" method="post" action="{admin_site_url('web/screenpic_edit?screen_id='|cat:$info['screen_id'])}">
  {else}
    <form id="upload_screen_form" name="upload_screen_form" enctype="multipart/form-data" method="post" action="{admin_site_url('web/screenpic_add')}">
  {/if}
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="web_id" value="101">
      <input type="hidden" name="code_id" value="101">
      
      <table id="upload_screen" class="table tb-type2">
        <tbody>
          <tr>
            <td colspan="2" class="required"><label class="validation">文字标题：</label></td>
          </tr>
          <tr class="noborder">
            <td class="vatop rowform">
              <input type="hidden" name="screen_id" value="{$info['screen_id']}">
              <input class="txt" type="text" name="title" value="{$info['title']|escape}"></td>
            <td class="vatop tips">{form_error('title')}图片标题文字将作为图片Alt形式显示。</td>
          </tr>
          <tr>
            <td colspan="2" class="required"><label class="validation">图片跳转链接：</label></td>
          </tr>
          <tr class="noborder">
            <td class="vatop rowform"><input name="url" value="{$info['url']|escape}" class="txt" type="text"></td>
            <td class="vatop tips">{form_error('url')} 输入图片要跳转的URL地址，正确格式应以"http://"开头，点击后将以"_blank"形式另打开页面。</td>
          </tr>
          <tr>
            <td colspan="2" class="required"><label class="validation">广告图片上传：</label></td>
          </tr>
          <tr class="noborder">
            <td class="vatop rowform">
            <input type="hidden" name="pic_url" value="{$info['pic_url']}">
            <input name="pic" id="pic" type="file"></td>
            <td class="vatop tips">为确保显示效果正确，请选择最小不低于W:776px H:300px、最大不超过W:1920px H:481px的清晰图片作为全屏焦点图。</td>
          </tr>
          <tr>
            <td colspan="2">
                {if $info['pic_url']}
                <img title="{$info['title']|escape}" style="max-width:580px;max-height:120px;"  src="{resource_url($info['pic_url'])}"/>
                {/if}
            </td>
          </tr>
          <tr>
            <td colspan="2" class="required"><label>背景颜色：</label></td>
          </tr>
          <tr class="noborder">
            <td class="vatop"><input id="screen_color" name="color" value="{if $info['color']}{$info['color']}{else}#000000{/if}" type="text"></td>
            <td class="vatop tips">{form_error('color')}为确保显示效果美观，可设置首页全屏焦点图区域整体背景填充色用于弥补图片在不同分辨率下显示区域超出图片时的问题，可根据您焦点图片的基础底色作为参照进行颜色设置。</td>
          </tr>
          <tr>
            <td colspan="2" class="required"><label>排序：</label></td>
          </tr>
          <tr class="noborder">
            <td class="vatop"><input id="displayorder" name="displayorder" value="{if $info['displayorder']}{$info['displayorder']}{else}255{/if}" type="text"></td>
            <td class="vatop tips">{form_error('displayorder')}数字范围为0~255，数字越小越靠前。</td>
          </tr>
        </tbody>
        <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
      </table>
    </form>
	<script>
	    $(function(){
	        $('#screen_color').colorpicker({ showOn:'both' });
	    });
	</script>
{include file="common/main_footer.tpl"}