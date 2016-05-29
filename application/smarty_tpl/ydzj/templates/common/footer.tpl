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
    								<li class="first"><span>客服热线:</span><strong>{$siteSetting['site_phone']|escape}</strong><li>
    								<li><span>移动电话:</span><strong>{$siteSetting['site_mobile']|escape}</strong><li>
    								<li><span>固定电话:</span><strong>{$siteSetting['site_tel']|escape}</strong><li>
    								<li><span>传真: </span><strong>{$siteSetting['site_faxno']|escape}</strong><li>
    							</ul>
    						</td>
    					</tr>
    					<tr>
    						<td>邮箱:&nbsp; <a href="mailto:{$siteSetting['email_addr']}">{$siteSetting['email_addr']|escape}</a></td>
    					</tr>
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
    <script>
    $(function(){
    	{if $isMobile}
    	$("#naviText").bind("click",function(){
			$("#homeNav").slideToggle();
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