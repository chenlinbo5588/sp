{include file="common/main_header.tpl"}
  {form_open_multipart(admin_site_url('upload/default_image'),'name="form1"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="default_goods_image">默认商品图片:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="{resource_url('img/preview.png')}">
            <div class="type-file-preview"><img src="{resource_url($currentSetting['default_goods_image']['value'])}"></div>
            </span><span class="type-file-box">
            <input class="type-file-file" id="default_goods_image" name="default_goods_image" type="file" size="30" hidefocus="true"  nc_type="change_default_goods_image" title="默认商品图片">
            </span></td>
          <td class="vatop tips">300px * 300px</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="default_store_logo">默认店铺标志:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="{resource_url('img/preview.png')}">
            <div class="type-file-preview"><img src="{resource_url($currentSetting['default_store_logo']['value'])}"></div>
            </span><span class="type-file-box">
            <input class="type-file-file" id="default_store_logo" name="default_store_logo" type="file" size="30" hidefocus="true" nc_type="change_default_store_logo" title="默认店铺标志">
            </span></td>
          <td class="vatop tips">100px * 100px</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="default_user_portrait">默认会员头像:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="{resource_url('img/preview.png')}">
            <div class="type-file-preview"><img src="{resource_url($currentSetting['default_user_portrait']['value'])}"></div>
            </span><span class="type-file-box">
            <input class="type-file-file" id="default_user_portrait" name="default_user_portrait" type="file" size="30" hidefocus="true" nc_type="change_default_user_portrait" title="默认会员头像">
            </span></td>
          <td class="vatop tips">128px * 128px</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="default_user_portrait">默认群组头像:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="{resource_url('img/preview.png')}">
            <div class="type-file-preview"><img src="{resource_url($currentSetting['default_group_portrait']['value'])}"></div>
            </span><span class="type-file-box">
            <input class="type-file-file" id="default_group_portrait" name="default_group_portrait" type="file" size="30" hidefocus="true" nc_type="change_default_group_portrait" title="默认群组头像">
            </span></td>
          <td class="vatop tips">128px * 128px</td>
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
$(function(){
	// 模拟默认商品图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />";
	$(textButton).insertBefore("#default_goods_image");
	$("#default_goods_image").change(function(){
		$("#textfield1").val($("#default_goods_image").val());
    });
	// 模拟默认店铺图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button2' value='' class='type-file-button' />";
    $(textButton).insertBefore("#default_store_logo");
    $("#default_store_logo").change(function(){
		$("#textfield2").val($("#default_store_logo").val());
    });
	// 模拟默认用户图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button3' value='' class='type-file-button' />";
    $(textButton).insertBefore("#default_user_portrait");
    $("#default_user_portrait").change(function(){
		$("#textfield3").val($("#default_user_portrait").val());
    });
    // 模拟默认用户图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield4' class='type-file-text' /><input type='button' name='button' id='button4' value='' class='type-file-button' />";
    $(textButton).insertBefore("#default_group_portrait");
    $("#default_group_portrait").change(function(){
		$("#textfield4").val($("#default_group_portrait").val());
    });
	// 上传图片类型
	$('input[class="type-file-file"]').change(function(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("图片限于png,gif,jpeg,jpg格式");
				$(this).attr('value','');
			return false;
		}
	});
});
</script> 
{include file="common/main_footer.tpl"}