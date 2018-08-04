{include file="common/main_header_navs.tpl"}
  {config_load file="order.conf"}
  {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="infoform"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  <input type="hidden" name="gobackUrl" value="{$gobackUrl}"/>
    <table class="tb-type2 mgbottom">
   		<tr>
   		  <td class="required">{#order_id#}: </td>
          <td class="vatop rowform">{$info['order_id']|escape}</td>      
       	</tr>
       	{include file="order/detail_common.tpl"}
		<tr>
			<td class="required">审核备注: </td>
			<td>{$info['extra_info']['verify_remark']|escape}</td>
		</tr>
		<tr>
			<td class="required">验证码</td>
			<td>
				<input name="auth_code" type="text" class="txt-short" id="captcha" placeholder="输入验证" title="验证码为4个字符" autocomplete="off" value="" >
				<label class="errtip" id="error_auth_code"></label>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><div class="code-img" id="authImg"></div></td>
		</tr>
    </table>
    <div class="fixedOpBar">
    	{if $showSubmit}
    	<input type="submit" name="tijiao" value="退款" class="msbtn"/>
    	{/if}
    	{if $lastUrl}
    	<a href="{$lastUrl}" class="salvebtn">返回</a>
    	{/if}
    </div>
  </form>
  <div id="avatarDlg"></div>
  {if $showSubmit}
  <script type="text/javascript">
	var submitUrl = [new RegExp("{site_url($uri_string)}")];
	
	$(function(){
		$.loadingbar({ text: "正在提交..." , urls: submitUrl , container : "#infoform" });
		
		bindAjaxSubmit("#infoform");
		
		var imgCode1 = $.fn.imageCode({ wrapId: "#authImg", captchaUrl : "{site_url('captcha/index')}" });
	    setTimeout(imgCode1.refreshImg,500);
	    
	});
  </script>
  {/if}
{include file="common/main_footer.tpl"}