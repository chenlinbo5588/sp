{include file="common/website_header.tpl"}
	<div class="linePg">
		<div class="commonPg {$pgClass}"></div>
		<div class="boxz clearfix mg10">
			{if !$isMobile}{include file="common/side_nav.tpl"}{/if}
			<div class="contentArea">
				<div class="breadcrumb">{$breadcrumb}</div>
				<div class="bd" id="articleInfo" style="border:1px solid #dcdcdc;">
					<h3>{$sug_tip}</h3>
					<a name="formmap">&nbsp;</a>
					{form_open(base_url('service/suggestion.html#formmap'),'id="suggestion_form"')}
						<table class="suggest">
							<tr><td style="width:120px;"><span>{$sug_username}:</span><em>*</em></td><td><input type="text" name="username" value="{$info['username']|escape}" placeholder="{$sug_please}{$sug_username}"/>{form_error('username')}</td></tr>
							<tr><td><span>{$sug_company}:</span><em>*</em></td><td><input type="text" name="company_name" value="{$info['company_name']|escape}" placeholder="{$sug_please}{$sug_company}"/>{form_error('company_name')}</td></tr>
							<tr><td><span>{$sug_mobile}:</span><em>*</em></td><td><input type="text" name="mobile" value="{$info['mobile']|escape}" placeholder="{$sug_please}{$sug_mobile}"/>{form_error('mobile')}</td></tr>
							<tr><td><span>{$sug_city}:</span><em>*</em></td><td><input type="text" name="city" value="{$info['city']|escape}" placeholder="{$sug_please}{$sug_city}"/>{form_error('city')}</td></tr>
							<tr><td><span>{$sug_tel}:</span><em>:</em></td><td><input type="text" name="tel" value="{$info['tel']|escape}" placeholder="{$sug_please}{$sug_tel}"/>{form_error('tel')}</td></tr>
							<tr><td><span>{$sug_email}:</span><em></em></td><td><input type="text" name="email" value="{$info['email']|escape}" placeholder="{$sug_please}{$sug_email}"/>{form_error('email')}</td></tr>
							<tr><td><span>{$sug_wechat}:</span><em></em></td><td><input type="text" name="weixin" value="{$info['weixin']|escape}" placeholder="{$sug_please}{$sug_wechat}"/>{form_error('weixin')}</td></tr>
							<tr><td><span>{$sug_docno}:</span><em>*</em></td><td><input type="text" name="doc_no" value="{$info['doc_no']|escape}" placeholder="{$sug_please}{$sug_docno}"/>{form_error('doc_no')}</td></tr>
							<tr><td><span>{$sug_remark}:</span><em>*</em></td><td><textarea style="width:200px;height:80px;" name="remark">{$info['remark']|escape}</textarea>{form_error('remark')}</td></tr>
							<tr><td><span>{$sug_captcha}:</span><em>*</em></td><td><input type="text" name="auth_code" value="" placeholder="{$sug_please}{$sug_captcha}"/>{form_error('auth_code')}</td></tr>
							<tr><td></td><td><img id="auth_code" src="{site_url('captcha/index')}"/>&nbsp;<a id="refreshBtn" href="javascript:void(0);">{$sug_tip2}</a></td></tr>
							<tr><td></td><td><input class="link_btn" type="submit" name="tj" value="{$sug_submit}"/></td></tr>
						</table>
					</form>
				</div>
			</div>
		</div>
		{if $isMobile}{include file="common/side_nav.tpl"}{/if}
	</div>
	<script>
		var imgUrl = "{site_url('captcha/index')}";
		$(function(){
			{if $alertMsg}
			alert("{$alertMsg}");
			{/if}
			$("#refreshBtn").bind("click",function(){
				$("#auth_code").attr("src",imgUrl + "?t=" + Math.random());
			});
		});
	</script>
{include file="common/website_footer.tpl"}