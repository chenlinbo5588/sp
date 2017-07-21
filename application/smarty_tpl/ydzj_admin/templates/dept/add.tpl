{include file="common/main_header.tpl"}
  {if $info['id']}
  {form_open_multipart(admin_site_url('dept/edit?id='|cat:$info['id']),'id="infoform"')}
  {else}
  {form_open_multipart(admin_site_url('dept/add'),'id="infoform"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="code">机构代码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['code']|escape}" name="code" id="code" class="txt"></td>
          <td class="vatop tips">只允许英文字母,最长3位,不可重复,业务登记时流水号中将包含该代码{form_error('code')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="dept_name">全称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="dept_name" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="short_name">简称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['short_name']|escape}" name="short_name" id="short_name" class="txt"></td>
          <td class="vatop tips">{form_error('short_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">LOGO标识: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="hidden" name="old_pic" value="{if $info['logo_pic']}{$info['brand_pic']}{/if}"/>
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if !empty($info['logo_pic'])}<img style="width:150px;height:150px;" src="{resource_url($info['logo_pic'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='logo_pic' value="{if $info['logo_pic']}{$info['logo_pic']}{/if}" id='brand_pic' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="logo_pic" type="file" class="type-file-file" id="brand_logo" size="30" hidefocus="true" nc_type="change_brand_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">LOGO尺寸要求宽度为150像素，高度为150像素、比例为1:1的图片；支持格式jpg,png</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required">是否启用: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="status1" {if $info['status']}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="status0" {if $info['status']}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="status1" name="status" {if $info['status']}checked{/if} value="1" type="radio">
            <input id="status0" name="status" {if $info['status'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('status')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['displayorder']}{$info['displayorder']}{else}255{/if}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	$(function(){
		$("#brand_logo").change(function(){
			$("#brand_pic").val($(this).val());
		});
	})
  </script>
{include file="common/main_footer.tpl"}