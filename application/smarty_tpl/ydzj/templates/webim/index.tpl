<!doctype html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0" />
    <title>{$SEO_title}</title>
    <script type="text/javascript" src="{base_url('webim/sdk/dist/strophe.js')}"></script>
	<script type="text/javascript" src="{base_url('webim/sdk/dist/websdk-1.1.2.js')}"></script>
	<script type="text/javascript" src="{base_url('webim/demo/javascript/dist/webim.config.js')}"></script>
</head>
<body>
    <p>正在登陆。。。。</p>
    <script>
    	var conn = new WebIM.connection({
			  https: WebIM.config.https,
			  url: WebIM.config.xmppURL,
			  isAutoLogin: WebIM.config.isAutoLogin,
			  isMultiLoginSessions: WebIM.config.isMultiLoginSessions
		});
		
		conn.listen({
			  onOpened: function ( message ) {          //连接成功回调
			    //如果isAutoLogin设置为false，那么必须手动设置上线，否则无法收消息
			    //console.log(message);
			    conn.setPresence();
			    setTimeout(function(){
			    	location.href="{base_url('webim/index.html?token=')}" + message.accessToken + "&username={$profile['chat']['username']}"; 
			    },500)
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
			  user: "{$profile['chat']['username']}",
			  pwd: "{$profile['chat']['password']}",
			  appKey: WebIM.config.appkey
		};
		
		conn.open(options);
    </script>
</body>
</html>