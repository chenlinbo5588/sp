

        </div><!-- end of panel -->
    </div><!-- //end of my -->
    
    <script type="text/javascript">
    var data = {};
var nim = NIM.getInstance({
    // debug: true,
    appKey: 'a54919705abd0863a8d3cc49cf8eb14e',
    account: '{$profile['push']['accid']}',
    token: '{$profile['push']['token']}',
    onconnect: onConnect,
    onwillreconnect: onWillReconnect,
    ondisconnect: onDisconnect,
    onerror: onError
});
function onConnect() {
    console.log('连接成功');
}
function onWillReconnect(obj) {
    // 此时说明 SDK 已经断开连接, 请开发者在界面上提示用户连接已断开, 而且正在重新建立连接
    console.log('即将重连');
    console.log(obj.retryCount);
    console.log(obj.duration);
}
function onDisconnect(error) {
    // 此时说明 SDK 处于断开状态, 开发者此时应该根据错误码提示相应的错误信息, 并且跳转到登录页面
    console.log('丢失连接');
    console.log(error);
    if (error) {
        switch (error.code) {
        // 账号或者密码错误, 请跳转到登录页面并提示错误
        case 302:
            break;
        // 被踢, 请提示错误后跳转到登录页面
        case 'kicked':
            break;
        default:
            break;
        }
    }
}
function onError(error) {
    console.log(error);
}

    </script>
{include file="common/footer.tpl"}