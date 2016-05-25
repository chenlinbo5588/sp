{include file="./site_common.tpl"}
<style type="text/css">
body {
	background:#e3e3e3;
}

{include file="./pop_css_common.tpl"}

</style>
	<div id="wrap">
		<div>
			<div class="hide">自助开户：3分钟开立超级账户</div>
   			<img class="responed" src="{resource_url('img/new_pg14/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg14/pic2.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg14/pic3.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">预约开户：迅速，当天预约，当天受理，工作人员上门服务 体贴 。省心: 足不出户，轻松开户</div>
   			<img class="responed" src="{resource_url('img/new_pg14/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg14/pic5.jpg')}"/>
   		</div>
   		<div>
   			<a href="javascript:void(0);" class="cv"><img class="responed" src="{resource_url('img/new_pg14/pic6.jpg')}"/></a>
   		</div>
   		<div>
   			<div class="hide">手机开户： 扫一扫 即可轻松开户</div>
   			<img class="responed" src="{resource_url('img/new_pg14/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg14/pic8.jpg')}"/>
   		</div>
	   	<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg14/pic9.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg14/pic10.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	
	{assign var="popWinTitle" value="预约开户"}
	{assign var="popBtnTitle" value="立即预约开户"}
	{include file="./pop_html_common.tpl"}
	<script type="text/javascript">
		var authCodeURL ="{site_url('api/register/authcode')}";
		{include file="./site_alert.tpl"}
		$(function(){
			{include file="./mobile_mcode_validation.tpl"}
			{include file="./pop_js_common.tpl"}
		});
	</script>
	{include file="./js_mobile_authcode.tpl"}
</body>
</html>