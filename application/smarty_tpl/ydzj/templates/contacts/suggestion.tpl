{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="{$pgClass}"></div>
		<div class="boxz clearfix mg10">
			{if !$isMobile}{include file="common/side_nav.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb">{$breadcrumb}</div>
				<div class="bd" id="articleInfo" style="border:1px solid #dcdcdc;">
					<h3>请按要求填写您的准确信息，相关人员将在第一时间与您联系</h3>
					<a name="formmap">&nbsp;</a>
					{form_open(site_url('contacts/suggestion/#formmap'),'id="suggestion_form"')}
						<table class="suggest">
							<tr><td style="width:80px;"><span>您的姓名:</span><em>*</em></td><td><input type="text" name="username" value="{$info['username']|escape}" placeholder="请输入您的用户名"/>{form_error('username')}</td></tr>
							<tr><td><span>公司名称:</span><em>*</em></td><td><input type="text" name="company_name" value="{$info['company_name']|escape}" placeholder="请输入您的公司名称"/>{form_error('company_name')}</td></tr>
							<tr><td><span>手机号码:</span><em>*</em></td><td><input type="text" name="mobile" value="{$info['mobile']|escape}" placeholder="请输入您的手机号码"/>{form_error('mobile')}</td></tr>
							<tr><td><span>所在城市:</span><em>*</em></td><td><input type="text" name="city" value="{$info['city']|escape}" placeholder="请输入您的所在城市"/>{form_error('city')}</td></tr>
							<tr><td><span>座机:</span><em>:</em></td><td><input type="text" name="tel" value="{$info['tel']|escape}" placeholder="请输入您的座机号码"/>{form_error('tel')}</td></tr>
							<tr><td><span>联系邮箱:</span><em></em></td><td><input type="text" name="email" value="{$info['email']|escape}" placeholder="请输入您的邮箱"/>{form_error('email')}</td></tr>
							<tr><td><span>微信号:</span><em></em></td><td><input type="text" name="weixin" value="{$info['weixin']|escape}" placeholder="请输入您的微信号"/>{form_error('weixin')}</td></tr>
							<tr><td><span>合同号:</span><em>*</em></td><td><input type="text" name="doc_no" value="{$info['doc_no']|escape}" placeholder="请输入您的合同号"/>{form_error('doc_no')}</td></tr>
							<tr><td><span>备注:</span><em>*</em></td><td><textarea style="width:200px;height:80px;" name="remark">{$info['remark']|escape}</textarea>{form_error('remark')}</td></tr>
							<tr><td><span>验证码:</span><em>*</em></td><td><input type="text" name="auth_code" value="" placeholder="请输入验证码"/>{form_error('auth_code')}</td></tr>
							<tr><td></td><td><div id="authImg"><img src="{site_url('captcha/index')}"/></div><span>看不清,请点击图片刷新</span></td></tr>
							<tr><td></td><td><input class="link_btn" type="submit" name="tj" value="提交"/></td></tr>
						</table>
					</form>
				</div>
			</div>
		</div>
		{if $isMobile}{include file="common/side_nav.tpl"}{/if}
	</div>
	<script>
		$(function(){
		    var imgCode1 = $.fn.imageCode({ wrapId: "#authImg", captchaUrl : captchaUrl });
            setTimeout(imgCode1.refreshImg,500);
            
			{if $alertMsg}
			alert("{$alertMsg}");
			{/if}
			
		});
	</script>
{include file="common/website_footer.tpl"}