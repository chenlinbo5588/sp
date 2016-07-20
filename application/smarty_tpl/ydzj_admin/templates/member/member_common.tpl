{include file="common/ke.tpl"}
{include file="common/dlg.tpl"}
{include file="common/jcrop.tpl"}
<script type="text/javascript">
//裁剪图片后返回接收函数
function call_back(resp){
    refreshFormHash(resp);
    $('#previewWrap').html('<img src="' + resp.url + '"/>');
}

KindEditor.ready(function(K) {
	var uploadbutton = K.uploadbutton({
		button : K('#uploadButton')[0],
		fieldName : 'imgFile',
		extraParams : { formhash : formhash,min_width :{$avatarImageSize['b']['width']},min_height: {$avatarImageSize['b']['height']} },
		url : '{admin_site_url("common/pic_upload")}?mod=member_avatar',
		afterUpload : function(data) {
			refreshFormHash(data);
			if (data.error === 0) {
				$("input[name=old_avatar]").val($("input[name=avatar]").val());
            	$("input[name=old_avatar_id]").val($("input[name=avatar_id]").val());
            	
                $("input[name=avatar_id]").val(data.id);
                $("input[name=avatar]").val(data.url);
				
				ajax_form('cutpic','裁剪','{admin_site_url("member/pic_cut")}?type=member&x={$avatarImageSize['m']['width']}&y={$avatarImageSize['m']['height']}&resize=0&ratio=1&url='+data.url,800);
				
			} else {
				alert(data.msg);
			}
		},
		afterError : function(str) {
			alert('自定义错误信息: ' + str);
		}
	});
	uploadbutton.fileBox.change(function(e) {
		uploadbutton.submit();
	});
});


</script>