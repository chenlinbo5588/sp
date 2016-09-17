{include file="common/main_header.tpl"}
  {form_open(admin_site_url('upload/param'),'name="settingForm"')}
    <table class="table tb-type2">
      <tbody>
		<tr>
          <td colspan="2" class="required"><label for="image_max_filesize">图片文件大小:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">大小:
            <input id="image_max_filesize" name="image_max_filesize" type="text" class="txt" style="width:30px;" value="{$currentSetting['image_max_filesize']['value']|escape}"/>
            KB&nbsp;(1024 KB = 1MB)</td>
          <td class="vatop tips">当前服务器环境，最大允许上传{$currentUploadSize} 的文件，您的设置请勿超过该值。</td>
        </tr>
		<tr>
          <td colspan="2" class="required"><label for="image_allow_ext">前台图片扩展名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="forground_image_allow_ext" name="forground_image_allow_ext" value="{$currentSetting['forground_image_allow_ext']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">图片扩展名，用于判断上传图片是否为前台允许，多个后缀名间请用半角逗号 "," 隔开。</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="image_allow_ext">后台图片扩展名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="background_image_allow_ext" name="background_image_allow_ext" value="{$currentSetting['background_image_allow_ext']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">图片扩展名，用于判断上传图片是否为后台允许，多个后缀名间请用半角逗号 "," 隔开。</span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" name="submit" value="保存" class="msbtn"></td>
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