{include file="common/main_header.tpl"}
{config_load file="sport.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#cate_title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('sports_cate/index')}"><span>{#manage#}</span></a></li>
      	<li><a class="current"><span>{if $info['id']}编辑{else}新增{/if}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  
  {if $info['id']}
  {form_open_multipart(admin_site_url('sports_cate/edit'),'id="add_form"')}
  {else}
  {form_open_multipart(admin_site_url('sports_cate/add'),'id="add_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name">{#title#}名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">LOGO: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="hidden" name="aid" value="{$info['aid']}"/>
          	<input type="hidden" name="logo_url" value="{$info['logo_url']}"/>
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if !empty($info['logo_url'])}<img src="{resource_url($info['logo_url'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='logo_show_url' value="{if $info['logo_url']}{$info['logo_url']}{/if}" id='logo_show_url' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="sports_logo" type="file" class="type-file-file" id="sports_logo" size="30" hidefocus="true" nc_type="change_sports_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">LOGO尺寸要求宽度为 最大 80x80、比例为1:1的图片；支持格式jpg,png</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required">开启状态: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="status1" {if $info['status'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="status0" {if $info['status'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="status1" name="status" {if $info['status'] == 1}checked{/if} value="1" type="radio">
            <input id="status0" name="status" {if $info['status'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('status')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required">{#teamwork#}: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
          	<select name="teamwork">
          		<option value="1" {if $info['teamwork'] == 1}selected{/if}>团体</option>
          		<option value="2" {if $info['teamwork'] == 2}selected{/if}>团体或个人</option>
          		<option value="3" {if $info['teamwork'] == 3}selected{/if}>个人</option>
          	</select>
          </td>
          <td class="vatop tips">{form_error('teamwork')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['cate_sort']}{$info['cate_sort']}{else}255{/if}" name="cate_sort" id="cate_sort" class="txt"></td>
          <td class="vatop tips">{form_error('cate_sort')} 数字范围为0~255，数字越小越靠前</td>
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
		$("#sports_logo").change(function(){
			$("#logo_show_url").val($(this).val());
		});
	})
 </script>
{include file="common/main_footer.tpl"}