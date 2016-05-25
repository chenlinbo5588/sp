{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#001b4a;
}

{include file="./pop_css_common.tpl"}
.auth_code .getCode {
	
}

.cv {
	position:relative;
}

.cv a {
	position: absolute;
    width: 45%;
    text-indent: -1000em;
    right: 0;
    top: 0;
    height: 40px;
    display: block;
}

</style>
	<div id="wrap">
		<div>
   			<img class="responed" src="{resource_url('img/new_pg7/pic1.jpg')}"/>
   			<img class="responed" src="{resource_url('img/new_pg7/pic2.jpg')}"/>
   		</div>
   		<div class="cv">
   			<img class="responed" src="{resource_url('img/new_pg7/pic3.jpg')}"/>
   			<a href="javascript:void(0);">&nbsp;</a>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg7/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg7/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg7/pic6.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg7/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg7/pic8.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg7/pic9.jpg')}"/>
   		</div>
   		<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg7/pic10.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg7/pic11.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	{assign var="popWinTitle" value="下载"}
	{assign var="popBtnTitle" value="免费下载"}
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