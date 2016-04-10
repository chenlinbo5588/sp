{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>消息通知</h3>
      <ul class="tab-base"><li><a  class="current"><span>邮件设置</span></a></li><li><a href="index.php?act=message&op=email_tpl" ><span>邮件模板</span></a></li></ul>    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" id="form_email" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="email_type">邮件功能开启:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="email_enabled_1" class="cb-enable " title="开启"><span>开启</span></label>
            <label for="email_enabled_0" class="cb-disable selected" title="关闭"><span>关闭</span></label>
            <input type="radio"  value="1" name="email_enabled" id="email_enabled_1" />
            <input type="radio" checked="checked" value="0" name="email_enabled" id="email_enabled_0" />
            <input type="hidden" name="email_type" value="1" /></td>
          <td class="vatop tips">&nbsp;</td>
        </tr>
        <!--<tr>
          <td colspan="2" class="required">
				<label for="email_type">邮件发送方式:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
					<label><input type="radio" checked="checked" value="1" name="email_type" id="email_type_1" />&nbsp;采用其他的SMTP服务</label>&nbsp;
					<label><input type="radio"  value="0" name="email_type" id="email_type_0" />&nbsp;采用服务器内置的Mail服务</label>&nbsp;
					<label class="field_notice">如果您选择服务器内置方式则无须填写以下选项</label>
				</td>
          <td class="vatop tips">&nbsp;</td>
        </tr>-->
        <tr>
          <td colspan="2" class="required">SMTP 服务器:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="email_host" id="email_host" class="txt"></td>
          <td class="vatop tips"><label class="field_notice">设置 SMTP 服务器的地址，如 smtp.163.com</label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">SMTP 端口:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="25" name="email_port" id="email_port" class="txt"></td>
          <td class="vatop tips"><label class="field_notice">设置 SMTP 服务器的端口，默认为 25</label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">发信人邮件地址:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="email_addr" id="email_addr" class="txt"></td>
          <td class="vatop tips"><label class="field_notice">使用SMTP协议发送的邮件地址，如 shopnc@163.com</label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">SMTP 身份验证用户名:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="email_id" id="email_id" class="txt"></td>
          <td class="vatop tips"><label class="field_notice">如 shopnc</label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">SMTP 身份验证密码:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="password" value="" name="email_pass" id="email_pass" class="txt"></td>
          <td class="vatop tips"><label class="field_notice">shopnc@163.com邮件的密码，如 123456</label></td>
        </tr>
        <tr>
          <td colspan="2" class="required">测试接收的邮件地址:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="email_test" id="email_test" class="txt"></td>
          <td class="vatop tips"><input type="button" value="测试" name="send_test_email" class="btn" id="send_test_email"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" class="btn" onclick="document.settingForm.submit()"><span>提交</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script>
$(document).ready(function(){
	
	$('#send_test_email').click(function(){
		$.ajax({
			type:'POST',
			url:'index.php',
			data:'act=message&op=email_testing&email_host='+$('#email_host').val()+'&email_port='+$('#email_port').val()+'&email_addr='+$('#email_addr').val()+'&email_id='+$('#email_id').val()+'&email_pass='+$('#email_pass').val()+'&email_type='+1+'&email_test='+$('#email_test').val(),
			error:function(){
					alert('测试邮件发送失败，请重新配置邮件服务器');
				},
			success:function(html){
				alert(html.msg);
			},
			dataType:'json'
		});
	});
});
</script>
{include file="common/main_footer.tpl"}