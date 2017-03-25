{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>首页配置</h3>
      <ul class="tab-base">
        {*<li><a href="index.php?act=web_config&op=web_config"><span>板块区</span></a></li>*}
        <li><a href="{admin_site_url('web/home')}" class="current"><span>焦点区</span></a></li>
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
      <li class="current" form="upload_screen_form"><a href="{admin_site_url('web/focuspic')}">全屏(背景)焦点大图 中文版本</a></li>
      <li form="upload_screen_en_form"><a href="{admin_site_url('web/focuspic')}">全屏(背景)焦点大图 英文版本</a></li>
      <li form="upload_focus_form">三张联动焦点组图</li>
    </ul>
    <form id="upload_screen_form" class="tab-content" name="upload_screen_form" enctype="multipart/form-data" method="post" action="{admin_site_url('web/focuspic')}" target="upload_pic">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="web_id" value="101">
      <input type="hidden" name="code_id" value="101">
      <div class="full-screen-slides">
	        <ul>
	          <li screen_id="1" onclick="select_screen(1);" title="可上下拖拽更改显示顺序">
	            <div class="focus-thumb" style="background-color:#2D080F;" title="点击编辑选中区域内容"> <a class="del" href="JavaScript:del_screen(1);" title="删除">X</a> <img title="冬季名品-大牌季节日" src="http://www.nzbestprice.com/data/upload/shop/editor/web-101-101-1.jpg?454"/></div>
	            <input name="screen_list[1][pic_id]" value="1" type="hidden">
	            <input name="screen_list[1][pic_name]" value="冬季名品-大牌季节日" type="hidden">
	            <input name="screen_list[1][pic_url]" value="" type="hidden">
	            <input name="screen_list[1][color]" value="#2D080F" type="hidden">
	            <input name="screen_list[1][pic_img]" value="shop/editor/web-101-101-1.jpg?454" type="hidden">
	          </li>
	                                        <li screen_id="5" onclick="select_screen(5);" title="可上下拖拽更改显示顺序">
	            <div class="focus-thumb" style="background-color:#F6BB3D;" title="点击编辑选中区域内容"> <a class="del" href="JavaScript:del_screen(5);" title="删除">X</a> <img title="全套茶具专场-年终盛典" src="http://www.nzbestprice.com/data/upload/shop/editor/web-101-101-5.jpg?166"/></div>
	            <input name="screen_list[5][pic_id]" value="5" type="hidden">
	            <input name="screen_list[5][pic_name]" value="全套茶具专场-年终盛典" type="hidden">
	            <input name="screen_list[5][pic_url]" value="" type="hidden">
	            <input name="screen_list[5][color]" value="#F6BB3D" type="hidden">
	            <input name="screen_list[5][pic_img]" value="shop/editor/web-101-101-5.jpg?166" type="hidden">
	          </li>
	          <li screen_id="2" onclick="select_screen(2);" title="可上下拖拽更改显示顺序">
	            <div class="focus-thumb" style="background-color:#36142C;" title="点击编辑选中区域内容"> <a class="del" href="JavaScript:del_screen(2);" title="删除">X</a> <img title="女人再忙也要留一天为自己疯抢" src="http://www.nzbestprice.com/data/upload/shop/editor/web-101-101-2.jpg?331"/></div>
	            <input name="screen_list[2][pic_id]" value="2" type="hidden">
	            <input name="screen_list[2][pic_name]" value="女人再忙也要留一天为自己疯抢" type="hidden">
	            <input name="screen_list[2][pic_url]" value="" type="hidden">
	            <input name="screen_list[2][color]" value="#36142C" type="hidden">
	            <input name="screen_list[2][pic_img]" value="shop/editor/web-101-101-2.jpg?331" type="hidden">
	          </li>
	                                        <li screen_id="3" onclick="select_screen(3);" title="可上下拖拽更改显示顺序">
	            <div class="focus-thumb" style="background-color:#f2f2f2;" title="点击编辑选中区域内容"> <a class="del" href="JavaScript:del_screen(3);" title="删除">X</a> <img title="全年爆款-年底清仓" src="http://www.nzbestprice.com/data/upload/shop/editor/web-101-101-3.jpg?249"/></div>
	            <input name="screen_list[3][pic_id]" value="3" type="hidden">
	            <input name="screen_list[3][pic_name]" value="全年爆款-年底清仓" type="hidden">
	            <input name="screen_list[3][pic_url]" value="" type="hidden">
	            <input name="screen_list[3][color]" value="#f2f2f2" type="hidden">
	            <input name="screen_list[3][pic_img]" value="shop/editor/web-101-101-3.jpg?249" type="hidden">
	          </li>
	          <li screen_id="4" onclick="select_screen(4);" title="可上下拖拽更改显示顺序">
	            <div class="focus-thumb" style="background-color:#ECBCB0;" title="点击编辑选中区域内容"> <a class="del" href="JavaScript:del_screen(4);" title="删除">X</a> <img title="清仓年末特优-满99元包邮" src="http://www.nzbestprice.com/data/upload/shop/editor/web-101-101-4.jpg?250"/></div>
	            <input name="screen_list[4][pic_id]" value="4" type="hidden">
	            <input name="screen_list[4][pic_name]" value="清仓年末特优-满99元包邮" type="hidden">
	            <input name="screen_list[4][pic_url]" value="" type="hidden">
	            <input name="screen_list[4][color]" value="#ECBCB0" type="hidden">
	            <input name="screen_list[4][pic_img]" value="shop/editor/web-101-101-4.jpg?250" type="hidden">
	          </li>
	      </ul>
	      <div class="add-focus"><a class="btn-add-nofloat" href="JavaScript:add_screen();">新增图片</a> <span class="s-tips"><i></i>小提示：单击图片选中修改，拖动可以排序，添加最多不超过5个，保存后生效。</span></div>
      </div>
      <table id="upload_screen" class="table tb-type2">
        <tbody>
          <tr>
            <td colspan="2" class="required">文字标题：</td>
          </tr>
          <tr class="noborder">
            <td class="vatop rowform"><input type="hidden" name="screen_id" value="">
              <input class="txt" type="text" name="screen_pic[pic_name]" value=""></td>
            <td class="vatop tips">图片标题文字将作为图片Alt形式显示。</td>
          </tr>
          <tr>
            <td colspan="2" class="required"><label>图片跳转链接：</label></td>
          </tr>
          <tr class="noborder">
            <td class="vatop rowform"><input name="screen_pic[pic_url]" value="" class="txt" type="text"></td>
            <td class="vatop tips">输入图片要跳转的URL地址，正确格式应以"http://"开头，点击后将以"_blank"形式另打开页面。</td>
          </tr>
          <tr>
            <td colspan="2" class="required">广告图片上传：</td>
          </tr>
          <tr class="noborder">
            <td class="vatop rowform"><span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" size="30">
              </span></td>
            <td class="vatop tips">为确保显示效果正确，请选择最小不低于W:776px H:300px、最大不超过W:1920px H:481px的清晰图片作为全屏焦点图。</td>
          </tr>
          <tr>
          
            <td colspan="2" class="required"><label>背景颜色：</label></td>
          </tr>
          <tr class="noborder">
            <td class="vatop"><input id="screen_color" name="screen_pic[color]" value="" class="" type="text"></td>
            <td class="vatop tips">为确保显示效果美观，可设置首页全屏焦点图区域整体背景填充色用于弥补图片在不同分辨率下显示区域超出图片时的问题，可根据您焦点图片的基础底色作为参照进行颜色设置。</td>
          </tr>
          
        </tbody>
      </table>
      <div class="margintop"><a href="JavaScript:void(0);" onclick="$('#upload_screen_form').submit();" class="btn"><span>保存</span></a> 
        <a href="index.php?act=web_api&op=html_update&web_id=101" class="btn"><span>更新板块内容</span></a></div>
    </form>
  </div>
</div>
<iframe style="display:none;" src="" name="upload_pic"></iframe>
{include file="common/main_footer.tpl"}