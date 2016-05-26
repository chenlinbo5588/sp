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
    						<td>客服热线：{$siteSetting['site_phone']|escape} 移动电话: {$siteSetting['site_mobile']|escape} 固定电话: {$siteSetting['site_tel']|escape} 传真: {$siteSetting['site_faxno']|escape}</td>
    					</tr>
    					<tr>
    						<td>邮箱:&nbsp; <a href="mailto:{$siteSetting['email_addr']}">{$siteSetting['email_addr']|escape}</a></td>
    					</tr>
    				</table>
		        </div>
    		</div>
    		<div class="row2">
    			<div class="boxz">
    				<div>Coppyright &copy {$smarty.now|date_format:"%Y"} {config_item('site_name')} ALL rights reserved. {$siteSetting['icp_number']|escape} {*<a class="fr" href="{site_url('member/admin_login')}">管理页面</a>*}</div>
    				<div>{$siteSetting['statistics_code']}</div>
		        </div>
    		</div>
    	</div>
    </div><!-- //end of wrap -->
</body>
</html>