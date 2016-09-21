        </div><!-- end of panel -->
    </div><!-- // end of my -->
    <div class="service-entry">
        <div class="entry-wrap">
            <a href="javascript:void(0);" class="kf-chat"><span class="icon"></span>聊天窗口</a> 
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
<script>
$(function(){
	$("a.kf-chat").bind("click",function(){
		window.open ("{site_url('webim/index')}",'{$siteSetting['site_name']} 聊天窗口','height=750,width=1024,top=0,left=0,toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') 
	});
	
	/*
	var conn = new WebIM.connection({
	  https: WebIM.config.https,
	  url: WebIM.config.xmppURL,
	  isAutoLogin: WebIM.config.isAutoLogin,
	  isMultiLoginSessions: WebIM.config.isMultiLoginSessions
	});
	
	
	conn.listen({
	  onOpened: function ( message ) {          //连接成功回调
	    //如果isAutoLogin设置为false，那么必须手动设置上线，否则无法收消息
	    
	    console.log(message);
	    conn.setPresence();             
	  },  
	  onClosed: function ( message ) {},         //连接关闭回调
	  onTextMessage: function ( message ) {},    //收到文本消息
	  onEmojiMessage: function ( message ) {},   //收到表情消息
	  onPictureMessage: function ( message ) {}, //收到图片消息
	  onCmdMessage: function ( message ) {},     //收到命令消息
	  onAudioMessage: function ( message ) {},   //收到音频消息
	  onLocationMessage: function ( message ) {},//收到位置消息
	  onFileMessage: function ( message ) {},    //收到文件消息
	  onVideoMessage: function ( message ) {},   //收到视频消息
	  onPresence: function ( message ) {},       //收到联系人订阅请求、处理群组、聊天室被踢解散等消息
	  onRoster: function ( message ) {},         //处理好友申请
	  onInviteMessage: function ( message ) {},  //处理群组邀请
	  onOnline: function () {},                  //本机网络连接成功
	  onOffline: function () {},                 //本机网络掉线
	  onError: function ( message ) {}           //失败回调
	});
	
	
	var options = { 
	  apiUrl: WebIM.config.apiURL,
	  user: "{$profile['basic']['mobile']}",
	  pwd: "{$profile['basic']['password']}",
	  appKey: WebIM.config.appkey
	};  
	 
	conn.open(options);
	
	*/
})

</script>
