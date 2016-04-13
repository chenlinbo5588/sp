{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>消息通知</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('message/email')}"><span>邮件设置</span></a></li>
      	<li><a class="current"><span>邮件模板</span></a></li>
      </ul>
    </div>
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
<div class="fixed-bar">
    <div class="item-title">
      <h3>消息通知</h3>
      <ul class="tab-base"><li><a href="index.php?act=message&op=email" ><span>邮件设置</span></a></li><li><a  class="current"><span>邮件模板</span></a></li></ul>    </div>
  </div>
  <div class="fixed-empty"></div>
  <form name='form1' method='post'>
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="submit_type" id="submit_type" value="" />
    <table class="table tb-type2">
      <thead>
        <tr class="space">
          <th colspan="15" class="nobg">列表</th>
        </tr>
        <tr class="thead">
          <th>&nbsp;</th>
          <th>模板描述</th>
          <th class="align-center">开启</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
      	<tr class="hover">
          <td class="w24"><input type="checkbox" name="del_id[]" value="email_touser_find_password" class="checkitem"></td>
          <td class="w50pre"><strong>[给用户]</strong>用户找回密码的邮件通知</td>
          <td class="align-center power-onoff">否</td>
          <td class="w60 align-center"><a href="index.php?act=message&op=email_tpl_edit&code=email_touser_find_password">编辑</a></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16"><label for="checkallBottom">全选</label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="$('#submit_type').val('mail_switchON');go();"><span>开启</span></a><a href="JavaScript:void(0);" class="btn" onclick="$('#submit_type').val('mail_switchOFF');go();"><span>关闭</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script type="text/javascript" src="http://www.nzbestprice.com/data/resource/js/jquery.edit.js" charset="utf-8"></script> 
<script type="text/javascript">
function go(){
	var url="index.php?act=message&op=email_tpl_ajax";
	document.form1.action = url;
	document.form1.submit();
}
</script>
{include file="common/main_footer.tpl"}