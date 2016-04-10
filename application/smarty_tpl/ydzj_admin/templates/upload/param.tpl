{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>上传设置</h3>
      	<ul class="tab-base">
	      	<li><a class="current"><span>上传参数</span></a></li>
	      	<li><a href="{admin_site_url('upload/default_thumb')}" ><span>默认图片</span></a></li>
	      	<li><a href="{admin_site_url('upload/login')}" ><span>登录主题图片</span></a></li>
      	</ul>
      </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post" enctype="multipart/form-data" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">图片存放类型:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul class="nofloat">
              <li>
                <input id="image_dir_type_0" name="image_dir_type" type="radio" style="margin-bottom:6px;" value="1" checked="checked"/>
                <label for="image_dir_type_0">按照文件名存放 (例:/店铺id/图片)</label>
              </li>
              <li>
                <input id="image_dir_type_1" name="image_dir_type" type="radio" style="margin-bottom:6px;" value="2"/>
                <label for="image_dir_type_1">按照年份存放 (例:/店铺id/年/图片)</label>
              </li>
              <li>
                <input id="image_dir_type_2" name="image_dir_type" type="radio" style="margin-bottom:6px;" value="3"/>
                <label for="image_dir_type_2">按照年月存放 (例:/店铺id/年/月/图片)</label>
              </li>
              <li>
                <input id="image_dir_type_3" name="image_dir_type" type="radio" style="margin-bottom:6px;" value="4"/>
                <label for="image_dir_type_3">按照年月日存放 (例:/店铺id/年/月/日/图片)</label>
              </li>
            </ul></td>
          <td class="vatop tips"></td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label for="image_max_filesize">图片文件大小:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">大小:
            <input id="image_max_filesize" name="image_max_filesize" type="text" class="txt" style="width:30px;" value="1024"/>
            KB&nbsp;(1024 KB = 1MB)</td>
          <td class="vatop tips">当前服务器环境，最大允许上传2MB 的文件，您的设置请勿超过该值。</td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label for="image_allow_ext">图片扩展名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="image_allow_ext" name="image_allow_ext" value="gif,jpg,jpeg,bmp,png,swf" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">图片扩展名，用于判断上传图片是否为后台允许，多个后缀名间请用半角逗号 "," 隔开。</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.settingForm.submit()"><span>提交</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script type="text/javascript">
//<!CDATA[
$(function(){
	$('#form').validate({
		rules : {
			image_max_size : {
				number : true,
				maxlength : 4
			},
			image_allow_ext : {
				required : true
			}
		},
		messages : {
			image_max_size : {
				number : '图片文件大小仅能为数字',
				maxlength : '图片文件大小最多四位数'
			},
			image_allow_ext : {
				required : '图片扩展名不能为空'
			}
		}
	});
});
//]]>
</script>
{include file="common/main_footer.tpl"}