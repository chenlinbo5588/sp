        </div><!-- end of panel -->
    </div><!-- // end of my -->
    {*
    <div class="service-entry">
        <div class="entry-wrap">
            <a href="javascript:void(0);" class="kf-chat"><span class="icon"></span>站内聊天</a> 
            <a href="javascript:void(0);" class="kf-biz"><span class="icon"></span>商务咨询</a>
        </div>
        <div class="visitor">
            <form>
                <div class="form-group">
                    <input class="form-control name" placeholder="姓名" type="text">
                </div>
                <div class="form-group">
                    <input class="form-control phone" placeholder="手机" type="telephone">
                </div>
                <button type="button" class="submit btn btn-primary btn-block">马上咨询</button>
                <button type="button" class="cancel btn btn-primary-outline btn-block">取消</button> 
            </form>
        </div>
    </div>
    *}
    {include file="common/share_footer.tpl"}
<div id="soundDiv"></div>
<a class="backToTop" href="#top"></a>
<script type="text/javascript" src="{resource_url('js/swfobject/swfobject.js')}"></script>
<script type="text/javascript" src="{resource_url('js/my/notify.js')}"></script>
{if $currentGroupId == 2}
<div id="walkthrough-content" style="display:none;">
    <div id="walkthrough-1">
        <h3>欢迎来到{$siteSetting['site_name']}</h3>
        <p>您还没有进行卖家认证，<br/>通过卖家认证后可维护库存,<br/>并获得系统及时的匹配提醒<br/></p>
        <p></p>
        <p><a class="link_btn" href="{site_url('my/seller_verify?remind=false')}">马上去认证</a></p>
    </div>
</div>
<link href="{resource_url('css/pagewalk/jquery.pagewalkthrough.css')}" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{resource_url('js/jquery.pagewalkthrough.js')}"></script>
{/if}
<script>
var soundSwfUrl = "{resource_url('js/swfobject/sound.swf')}",
    expressSwfUrl = "{resource_url('js/swfobject/expressInstall.swf')}",
    sellerRemind = "",
    pmUrl = "{site_url('my_pm/check_newpm')}";
    
var notify = $.fn.myNotify.initSWF('soundDiv',soundSwfUrl,expressSwfUrl);
notify.setSound('{resource_url('sound/girl.mp3')}');
notify.setPmUrl("{site_url('my_pm/index')}");
notify.updatePm(pmUrl);

$(function(){
    {if $newPm || !empty($smarty.get.pm)}
    notify.showToast();
    setTimeout(function(){
        notify.playSound(1);
    },2000);
    {/if}
    
    notify.updatePm(pmUrl);
    
	{if !empty($feedback)}
	setTimeout(function(){
		$(".feedback").slideToggle(1000,"linear");
	},3000);
	{/if}
	
	{if $currentGroupId == 2}
	   {if $uri_string != 'my/seller_verify'}
	sellerRemind = getcookie('svr');
    $('body').pagewalkthrough({
        name: 'introduction',
        steps: [ 
            { 
                popup: { content: '#walkthrough-1',type: 'modal'} 
            }
        ],
        onClose:function(){
            setcookie('svr','no',{$smarty.const.CACHE_ONE_DAY});
        },
        buttons: {
		    jpwClose: {
		      i18n: '关闭',
		      show: true
		    },
		    jpwFinish: {
		      show: true,
		      i18n:'不再提示'
	        }
	    }
    });
    // Show the tour
    if(!sellerRemind){
        $('body').pagewalkthrough('show');
    }
        {/if}
    {/if}
	
});
</script>
</body>
</html>
