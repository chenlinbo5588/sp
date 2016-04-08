{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>站点设置</h3>
      <ul class="tab-base"><li><a  class="current"><span>站点设置</span></a></li><li><a href="{admin_site_url('setting/dump')}" ><span>防灌水设置</span></a></li></ul>    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">网站名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_name" name="site_name" value="我的电商" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">网站名称，将显示在前台顶部欢迎信息等位置</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="site_logo">网站Logo:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="http://www.b2b2c.com/admin/templates/default/images/preview.png">
            <div class="type-file-preview"><img src="http://www.b2b2c.com/data/upload/shop/common/logo.png"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="site_logo" type="file" class="type-file-file" id="site_logo" size="30" hidefocus="true" nc_type="change_site_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">180px * 50px</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="site_logo">会员中心Logo:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="http://www.b2b2c.com/admin/templates/default/images/preview.png">
            <div class="type-file-preview"><img src="http://www.b2b2c.com/data/upload/shop/common/user_center.png"></div>
            </span><span class="type-file-box"><input type='text' name='textfield2' id='textfield2' class='type-file-text' /><input type='button' name='button2' id='button2' value='' class='type-file-button' />
            <input name="member_logo" type="file" class="type-file-file" id="member_logo" size="30" hidefocus="true" nc_type="change_member_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">默认为网站Logo，在会员中心头部显示，建议使用180px * 50px</span></td>
        </tr>
        <!-- 商家中心logo -->
        <tr>
          <td colspan="2" class="required"><label for="seller_center_logo">商家中心Logo:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><span class="type-file-show"><img class="show_image" src="http://www.b2b2c.com/admin/templates/default/images/preview.png">
            <div class="type-file-preview"><img src="http://www.b2b2c.com/data/upload/shop/common/seller_center_logo.png"></div>
            </span><span class="type-file-box"><input type='text' name='textfield' id='textfield3' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="seller_center_logo" type="file" class="type-file-file" id="seller_center_logo" size="30" hidefocus="true" nc_type="change_seller_center_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">150px * 40px</span></td>
        </tr>
        <!-- 商家中心logo -->
        <tr>
          <td colspan="2" class="required"><label for="icp_number">ICP证书号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="icp_number" name="icp_number" value="" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入你的授权码，它将显示在前台页面底部，如果没有请留空</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="site_phone">客服联系电话:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_phone" name="site_phone" value="23456789,88997788" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台卖家中心页面右下侧可以显示，方便卖家遇到问题时咨询，多个请用半角逗号 "," 隔开</span></td>
        </tr>
        <!--
        平台付款帐号，前台暂时无调用
        <tr>
          <td colspan="2" class="required"><label for="site_bank_account">平台汇款帐号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_bank_account" name="site_bank_account" value="银行:中国银行,币种:人民币,账号:xxxxxxxxxxx,姓名:ShopNC,开户行:中国银行天津分行" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">用半角逗号","分隔项目，用半角冒号":"分隔标题和内容，例："银行:中国银行,币种:人民币,账号:xxxxxxxxxxx,姓名:ShopNC,开户行:中国银行天津分行"</span></td>
        </tr>
        -->
        <tr>
          <td colspan="2" class="required"><label for="site_email">电子邮件:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_email" name="site_email" value="abc@shopnc.net" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台卖家中心页面右下侧可以显示，方便卖家遇到问题时咨询</span></td>
        </tr>
         <tr>
          <td colspan="2" class="required"><label for="statistics_code">第三方流量统计代码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="statistics_code" rows="6" class="tarea" id="statistics_code"></textarea></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面底部可以显示第三方统计</span></td>
        </tr> 
        <tr>
          <td colspan="2" class="required"><label for="time_zone"> 默认时区:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><select id="time_zone" name="time_zone">
              <option value="-12">(GMT -12:00) Eniwetok, Kwajalein</option>
              <option value="-11">(GMT -11:00) Midway Island, Samoa</option>
              <option value="-10">(GMT -10:00) Hawaii</option>
              <option value="-9">(GMT -09:00) Alaska</option>
              <option value="-8">(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana</option>
              <option value="-7">(GMT -07:00) Mountain Time (US &amp; Canada), Arizona</option>
              <option value="-6">(GMT -06:00) Central Time (US &amp; Canada), Mexico City</option>
              <option value="-5">(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito</option>
              <option value="-4">(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz</option>
              <option value="-3.5">(GMT -03:30) Newfoundland</option>
              <option value="-3">(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is</option>
              <option value="-2">(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena</option>
              <option value="-1">(GMT -01:00) Azores, Cape Verde Islands</option>
              <option value="0">(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</option>
              <option value="1">(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome</option>
              <option value="2">(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa</option>
              <option value="3">(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi</option>
              <option value="3.5">(GMT +03:30) Tehran</option>
              <option value="4">(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi</option>
              <option value="4.5">(GMT +04:30) Kabul</option>
              <option value="5">(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
              <option value="5.5">(GMT +05:30) Bombay, Calcutta, Madras, New Delhi</option>
              <option value="5.75">(GMT +05:45) Katmandu</option>
              <option value="6">(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk</option>
              <option value="6.5">(GMT +06:30) Rangoon</option>
              <option value="7">(GMT +07:00) Bangkok, Hanoi, Jakarta</option>
              <option value="8">(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei</option>
              <option value="9">(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</option>
              <option value="9.5">(GMT +09:30) Adelaide, Darwin</option>
              <option value="10">(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok</option>
              <option value="11">(GMT +11:00) Magadan, New Caledonia, Solomon Islands</option>
              <option value="12">(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island</option>
            </select></td>
          <td class="vatop tips"><span class="vatop rowform">设置系统使用的时区，中国为+8</span></td>
        </tr>              
        <tr>
          <td colspan="2" class="required">站点状态:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="site_status1" class="cb-enable selected" ><span>开启</span></label>
            <label for="site_status0" class="cb-disable " ><span>关闭</span></label>
            <input id="site_status1" name="site_status" checked="checked"  value="1" type="radio">
            <input id="site_status0" name="site_status"  value="0" type="radio"></td>
          <td class="vatop tips"><span class="vatop rowform">可暂时将站点关闭，其他人无法访问，但不影响管理员访问后台</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="closed_reason">关闭原因:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="closed_reason" rows="6" class="tarea" id="closed_reason" >升级中……</textarea></td>
          <td class="vatop tips"><span class="vatop rowform">当网站处于关闭状态时，关闭原因将显示在前台</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.form1.submit()"><span>提交</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script type="text/javascript">
// 模拟网站LOGO上传input type='file'样式
$(function(){
	$("#site_logo").change(function(){
		$("#textfield1").val($(this).val());
	});
	$("#member_logo").change(function(){
		$("#textfield2").val($(this).val());
	});
	$("#seller_center_logo").change(function(){
		$("#textfield3").val($(this).val());
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
$('#time_zone').attr('value','8');	
});
</script>
{include file="common/main_footer.tpl"}