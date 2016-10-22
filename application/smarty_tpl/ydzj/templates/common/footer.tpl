        </div><!-- //end of main-content -->
        <div class="foot">
		    <div class="part-two boxz">
		        <div class="center-block">
		            <div class="column-one column">
		                <span class="title">联系我们</span>
		                <ul>
		                    <li>联系电话：{$siteSetting['site_tel']}</li>
		                    <li>联系邮箱：<a href="mailto:{$siteSetting['site_email']}">{$siteSetting['site_email']}</a></li>
		                </ul>
		            </div>
		            <div class="column-two column">
		                <span class="title">保持接触</span>
		                <ul>
		                    <li>微信公众号：{$siteSetting['site_weixin']}</li>
		                    <li>QQ：{$siteSetting['site_qq']}</li>
		                </ul>
		            </div>
		            <div class="column-three column">
		                <span class="title">关于我们</span>
		                <ul>
		                    <li><a rel="nofollow" target="_blank" href="/about">公司简介</a></li>
		                </ul>
		            </div>
		            <div class="column-four column">
		                <span class="title">友情链接</span>
		                <ul>
		                    <li><a target="_blank" href="http://www.nike.com/">Nike 中国</a></li>
		                </ul>
		            </div>
		        </div>
		        <div class="info-wrapper">
		            <p class="copyright">{$siteSetting['site_name']}&nbsp;&nbsp;版权所有 @2011-{$smarty.now|date_format:"%Y"}&nbsp;&nbsp;<a href="javascript:void(0)" target="_blank">{$siteSetting['icp_number']}</a></p>
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
</body>
</html>