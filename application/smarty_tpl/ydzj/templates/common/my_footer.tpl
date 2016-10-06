        </div><!-- end of panel -->
    </div><!-- // end of my -->
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
{include file="common/footer.tpl"}
<div id="soundDiv"></div>
<script type="text/javascript" src="{resource_url('js/swfobject/swfobject.js')}"></script>
<script type="text/javascript" src="{resource_url('js/my/notify.js')}"></script>
<script>
var soundSwfUrl = "{resource_url('js/swfobject/sound.swf')}",
    expressSwfUrl = "{resource_url('js/swfobject/expressInstall.swf')}",
    pmUrl = "{site_url('my_pm/check_newpm')}";
    
var notify = $.fn.myNotify.initSWF('soundDiv',soundSwfUrl,expressSwfUrl);
notify.setSound('{resource_url('sound/girl.mp3')}');
notify.setPmUrl("{site_url('my_pm/index')}");
notify.updatePm(pmUrl);

$(function(){
    {if $newPm}
    notify.showToast();
    {/if}
    
    //8秒以后再绑定，防止用户进入过立即离开，避免过多开销
    setTimeout(function(){
        notify.updatePm(pmUrl);
    },8000);
    
	$("a.kf-chat").bind("click",function(){
		window.open ("{site_url('webim/index')}",'{$siteSetting['site_name']} 聊天窗口','height=650,width=1024,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') 
	});
});
</script>