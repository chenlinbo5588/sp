        </div><!-- end of panel -->
    </div><!-- // end of my -->
    {*
    <div class="service-entry">
        <div class="entry-wrap">
            <a href="javascript:void(0);" class="kf-chat"><span class="icon"></span>站内聊天</a> 
            <a href="javascript:void(0);" class="kf-biz"><span class="icon"></span>商务咨询</a>
        </div>
        <div class="visitor">
            {form_open()}
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
{*<script type="text/javascript" src="{resource_url('js/swfobject/swfobject.js')}"></script>*}
<script type="text/javascript" src="{resource_url('js/my/notify.js')}"></script>

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
});
</script>
</body>
</html>
