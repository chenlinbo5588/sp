        </div><!-- //end of main-content -->
        <div id="footer">
    		<div class="row1">
    			<div class="boxz clearfix">
    				<div class="qrcode">
    					<img src="{resource_url('img/qrcode.png')}" style="width:120px;height:120px;"/>
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
    					<tr>
    						<td class="center"><a target="_blank" class="qqchat" href="http://wpa.qq.com/msgrd?v=3&uin={$siteSetting['site_qq']}&site=qq&menu=yes" alt="点击这里给我发消息" title="点击这里给我发消息">官方QQ</a>:{$siteSetting['site_qq']}
    						</td>
    					</tr>
    				</table>
		        </div>
    		</div>
    		<div class="row2">
    			<div class="boxz">
    				<div>Coppyright &copy; {$smarty.now|date_format:"%Y"} {config_item('site_name')} ALL rights reserved. </div>
    				<div>{$siteSetting['icp_number']|escape} {*<a class="fr" href="{site_url('member/admin_login')}">管理页面</a>*}</div>
    				<div>{$siteSetting['statistics_code']}</div>
		        </div>
    		</div>
    	</div><!-- //end of foot -->
    </div><!-- //end of wrap -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="{resource_url('js/respond.min.js')}"></script>
	<script type="text/javascript" src="{resource_url('js/html5shiv.js')}"></script>
	<![endif]-->
	<!--[if lte IE 9]>
	<script src="{resource_url('js/jquery.placeholder.min.js')}"></script>
	<script>
	    $(function(){
	        $('input, textarea').placeholder();
	    });
	</script>
	<![endif]-->
	<script>
		$(function(){
		   $("#navtoggle").bind('click',function(){
		        $("#mobilenav .mynav").slideToggle();
		   });
		});
	</script>