{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#7d7d7d;
}

{include file="./pop_css_common.tpl"}

.cv {
	
}


</style>
	<div id="wrap">
		<div>
   			<a href="javascript:void(0);" class="cv"><img class="responed" src="{resource_url('img/new_pg8/pic1.jpg')}"/></a>
   		</div>
   		<div>
   			<a href="javascript:void(0);" class="cv"><img class="responed" src="{resource_url('img/new_pg8/pic2.jpg')}"/></a>
   		</div>
   		<div>
   			<a href="javascript:void(0);" class="cv"><img class="responed" src="{resource_url('img/new_pg8/pic3.jpg')}"/></a>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg8/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg8/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg8/pic6.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg8/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg8/pic7_1.jpg')}"/>
   		</div>
	   	<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg8/pic10.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg8/pic11.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	{assign var="popWinTitle" value="申请资金"}
	{assign var="popBtnTitle" value="申请资金"}
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