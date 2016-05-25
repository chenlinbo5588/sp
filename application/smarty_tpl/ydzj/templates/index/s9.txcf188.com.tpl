{include file="./site_common.tpl"}
<style type="text/css">

body {
	background:#bababa;
}

{include file="./pop_css_common.tpl"}

.cv {
	
}

</style>
	<div id="wrap">
		<div>
			<div class="hide">年终奖领5万美金 贵金属/白银投资 开户立送2000加油卡</div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic1.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic2.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">好产品 大优惠 强平台 盈利更有保障</div>
   			<a class="cv" href="javascript:void(0);"><img class="responed" src="{resource_url('img/new_pg9/pic3.jpg')}"/></a>
   		</div>
   		<div>
   			<div class="hide">超强抽资利器 业内首家独立开发 全中交大中华去专享</div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic4.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic5.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic6.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">贵金属/白银投资 0佣金 0手续费 22小时可交易 33倍高杠杆 涨跌双向交易</div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic7.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic8.jpg')}"/>
   		</div>
   		<div>
   			<div class="hide">贵金属/白银投资 0元免费开户 立送200美金</div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic9.jpg')}"/>
   		</div>
   		<div>
   			<a class="cv" href="javascript:void(0);"><img class="responed" src="{resource_url('img/new_pg9/pic10.jpg')}"/></a>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic11.jpg')}"/>
   		</div>
   		<div>
   			<img class="responed" src="{resource_url('img/new_pg9/pic12.jpg')}"/>
   		</div>
	   	<div>
   			{include file="./site_f1.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg9/pic13.jpg')}"/></div>
   		</div>
   		<div>
   			{include file="./site_f2.tpl"}
   			<div><img class="responed" src="{resource_url('img/new_pg9/pic14.jpg')}"/></div>
   		</div>
	</div><!-- //end of wrap -->
	{assign var="popWinTitle" value="开户"}
	{assign var="popBtnTitle" value="立即免费开户"}
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