    	<div id="footer">
    		<div class="row1">
    			<div class="boxz clearfix">
    				<div class="qrcode">
    					<img src="{resource_url('img/cmp/ma.png')}" style="width:120px;height:120px;"/>
    					<br/><span>扫描二维码:访问手机版</span>
    				</div>
    				<table>
    					<tr>
    						<td>地址:&nbsp; {$siteSetting['company_address']|escape} </td>
    					</tr>
    					<tr>
    						<td>
    							<ul id="contact_way">
    								{*<li class="first"><span>客服热线:</span><strong>{$siteSetting['site_phone']|escape}</strong><li>
    								<li><span>移动电话:</span><strong>{$siteSetting['site_mobile']|escape}</strong><li>*}
    								<li><span>招商电话:</span><strong>{$siteSetting['site_tel']|escape}</strong><li>
    								{*<li><span>传真: </span><strong>{$siteSetting['site_faxno']|escape}</strong><li>*}
    							</ul>
    						</td>
    					</tr>
    					<tr>
    						<td>邮箱:&nbsp; <a href="mailto:{$siteSetting['site_email']}">{$siteSetting['site_email']|escape}</a></td>
    					</tr>
    					{*
    					<tr>
    						<td class="center"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=173151624&site=qq&menu=yes"><img class="nature" border="0" src="http://wpa.qq.com/pa?p=2:173151624:51" alt="点击这里给我发消息" title="点击这里给我发消息"/></a>
    						</td>
    					</tr>*}
    				</table>
		        </div>
    		</div>
    		<div class="row2">
    			<div class="boxz">
    				<div>Coppyright &copy {$smarty.now|date_format:"%Y"} {config_item('site_name')} ALL rights reserved. </div>
    				<div>{$siteSetting['icp_number']|escape} {*<a class="fr" href="{site_url('member/admin_login')}">管理页面</a>*}</div>
    				<div>{$siteSetting['statistics_code']}</div>
		        </div>
    		</div>
    	</div>
    </div><!-- //end of wrap -->
    {if !$isMobile}
    {*
    <div class="qqchat">
    	<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=173151624&site=qq&menu=yes"><img border="0" src="http://wpa.qq.com/pa?p=2:173151624:53" alt="点击这里给我发消息" title="点击这里给我发消息"/></a>
    </div>*}
    {/if}
    <script>
    $(function(){
    	{if $isMobile}
    	$("#naviText").bind("click",function(){
    		$("#homeNav").slideToggle('fast');
		});
		{else}
		$("li.level0").bind("mouseenter",function(){
			$(this).addClass("current");
			/*
			$(this).addClass("current").siblings(".sublist").css({ opacity: 0}).show().animate({
				speed:500,
				opacity:1,
				height:"100%"
			});
			*/
		}).bind("mouseleave",function(){
			$(this).removeClass("current");
		});
		{/if}
    });
    </script>
</body>
</html>