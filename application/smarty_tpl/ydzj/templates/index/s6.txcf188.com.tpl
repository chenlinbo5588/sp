{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#a60000;
}


.cv {
	position:relative;
}

.cv a {
	position: absolute;
    width: 30%;
    height: 60px;
    top: 40px;
    right: 0px;
    text-indent: -1000em;
}

{include file="./pop_css_common.tpl"}

</style>
	<div id="wrap">
		
		<div class="cv">
			<a href="javascript:void(0);">立即免费订阅</a>
   			<img class="responed" src="{resource_url('img/new_pg6/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic2.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic3.jpg')}"/>
   		</div>
   		<div class="cv">
   			<a href="javascript:void(0);" style="top:0px;">免费订阅</a>
   			<img class="responed" src="{resource_url('img/new_pg6/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic6.jpg')}"/>
   		</div>
		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg6/pic8.jpg')}"/>
   		</div>
   		<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg6/pic9.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg6/pic10.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	{assign var="popWinTitle" value="订阅"}
	{assign var="popBtnTitle" value="免费订阅"}
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