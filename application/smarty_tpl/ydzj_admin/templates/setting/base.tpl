{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>站点设置</h3>
      <ul class="tab-base">
      	<li><a class="current"><span>站点设置</span></a></li>
      	<li><a href="{admin_site_url('setting/dump')}" ><span>防灌水设置</span></a></li>
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {form_open_multipart(admin_site_url('setting/base'),'name="form1"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">网站中文全称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_name" name="site_name" value="{$currentSetting['site_name']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('site_name')}<span class="vatop rowform">网站名称，将显示在前台顶部欢迎信息等位置</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_shortname">网站中文简称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_shortname" name="site_shortname" value="{$currentSetting['site_shortname']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('site_shortname')}<span class="vatop rowform">网站名称，将显示在前台顶部欢迎信息等位置</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name_en">网站英文全称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_name_en" name="site_name_en" value="{$currentSetting['site_name_en']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('site_name_en')}<span class="vatop rowform">网站名称，将显示在前台顶部欢迎信息等位置</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_shorten">网站英文简称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_shorten" name="site_shorten" value="{$currentSetting['site_shorten']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('site_shorten')}<span class="vatop rowform">网站名称，将显示在前台顶部欢迎信息等位置</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="site_logo">网站Logo:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if !empty($currentSetting['site_logo'])}<img src="{base_url($currentSetting['site_logo']['value'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="site_logo" type="file" class="type-file-file" id="site_logo" size="30" hidefocus="true" nc_type="change_site_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">180px * 50px</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="icp_number">ICP证书号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="icp_number" name="icp_number" value="{$currentSetting['icp_number']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入你的授权码，它将显示在前台页面底部，如果没有请留空</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="company_address">公司地址:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="company_address" name="company_address" value="{$currentSetting['company_address']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform"></span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="site_phone">招商电话:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_phone" name="site_phone" value="{$currentSetting['site_phone']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面下侧可以显示，方便访问者遇到问题时咨询，多个请用半角逗号 "," 隔开</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="site_tel">客服电话:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_tel" name="site_tel" value="{$currentSetting['site_tel']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面下侧可以显示，方便访问者遇到问题时咨询，多个请用半角逗号 "," 隔开</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="site_mobile">联系移动电话:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_mobile" name="site_mobile" value="{$currentSetting['site_mobile']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面下侧可以显示，方便访问者遇到问题时咨询，多个请用半角逗号 "," 隔开</span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="site_faxno">传真号码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_faxno" name="site_faxno" value="{$currentSetting['site_faxno']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面右下侧可以显示，方便访问者遇到问题时咨询，多个请用半角逗号 "," 隔开</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="site_qq">QQ号码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_qq" name="site_qq" value="{$currentSetting['site_qq']['value']}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面右下侧可以显示，方便卖家遇到问题时咨询</span></td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label for="site_email">电子邮件:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_email" name="site_email" value="{$currentSetting['site_email']['value']}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面右下侧可以显示，方便卖家遇到问题时咨询</span></td>
        </tr>
         <tr>
          <td colspan="2" class="required"><label for="statistics_code">第三方流量统计代码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="statistics_code" rows="6" class="tarea" id="statistics_code">{$currentSetting['statistics_code']['value']|escape}</textarea></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面底部可以显示第三方统计</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="time_zone"> 默认时区:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          		<select id="time_zone" name="time_zone">
	          	  {foreach from=$timezone item=item key=key}
	              <option value="{$key}" {if $currentSetting['time_zone']['value'] == $key}selected{/if}>{$item}</option>
	              {/foreach}
            	</select>
            </td>
          <td class="vatop tips">{form_error('time_zone')}<span class="vatop rowform">设置系统使用的时区，中国为+8</span></td>
        </tr>   
        <tr>
          <td colspan="2" class="required">站点状态:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="site_status1" {if $currentSetting['site_status']['value'] == 1}class="cb-enable selected"{else}class="cb-enable"{/if} ><span>开启</span></label>
            <label for="site_status0" {if $currentSetting['site_status']['value'] == 1}class="cb-disable"{else}class="cb-disable selected"{/if} ><span>关闭</span></label>
            <input id="site_status1" name="site_status" {if $currentSetting['site_status']['value'] == 1}checked="checked"{/if} value="1" type="radio">
            <input id="site_status0" name="site_status" {if $currentSetting['site_status']['value'] == 0}checked="checked"{/if} value="0" type="radio"></td>
          <td class="vatop tips"><span class="vatop rowform">可暂时将站点关闭，其他人无法访问，但不影响管理员访问后台</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="closed_reason">关闭原因:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="closed_reason" rows="6" class="tarea" id="closed_reason" >{$currentSetting['closed_reason']['value']|escape}</textarea></td>
          <td class="vatop tips"><span class="vatop rowform">当网站处于关闭状态时，关闭原因将显示在前台</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" name="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script type="text/javascript">
$(function(){
	$("#site_logo").change(function(){
		$("#textfield1").val($(this).val());
	});
	
	
// 上传图片类型
$('input[class="type-file-file"]').change(function(){
	var filepatd=$(this).val();
	var extStart=filepatd.lastIndexOf(".");
	var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();		
		if(ext!=".JPG"&&ext!=".JPEG"){
			alert("图片限于jpeg,jpg格式");
				$(this).attr('value','');
			return false;
		}
	});
});
</script>
{include file="common/main_footer.tpl"}