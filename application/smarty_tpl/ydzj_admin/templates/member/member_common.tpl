{include file="common/ke.tpl"}
{include file="common/jquery_ui.tpl"}
{include file="common/jcrop.tpl"}

<div id="imgCut" title="头像裁切">
	<div id="srcImg"></div>
	<form id="cutForm" action="{admin_site_url('member/set_avatar')}" method="post">
	  <input type="hidden" name="avatar" value=""/>
      <input type="hidden" id="x" name="x"/>
      <input type="hidden" id="y" name="y"/>
      <input type="hidden" id="w" name="w"/>
      <input type="hidden" id="h" name="h"/>
   </form>
</div>

<script type="text/javascript">
	var uploadUrl = '{site_url("common/pic_upload")}?mod=member_avatar';
	var min_width = {$avatarImageSize['m']['width']},min_height = {$avatarImageSize['m']['height']};
		
	var save_avatar = function(){
		$.ajax({
			type:"POST",
			dataType:'json',
			url : "{admin_site_url('member/cut_avatar')}",
			data: $("#cutForm").serialize(),
			success:function(json){
				cutDlg.dialog("close");
			},
			error: function(){
				cutDlg.dialog("close");
			}
		});
	}
	
	var cutDlg = $("#imgCut").dialog({
		autoOpen: false,
		width: 800,
		modal: true,
	      buttons: {
	        "裁切": save_avatar,
	        "关闭": function() {
	        	cutDlg.dialog( "close" );
	        }
	   },
	   
	   open:function(){
		   $('#cropbox').Jcrop({
	           	aspectRatio: 1,
	           	allowResize: false,
	           	setSelect: [ 0, 0, min_width, min_height],
	           	onSelect: updateCoords,
	           	onDblClick:save_avatar
           });
	   }
	});
	
	
	var updateCoords = function(c){
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	}
	
	KindEditor.ready(function(K) {
		var uploadbutton = K.uploadbutton({
			button : K('#uploadButton')[0],
			fieldName : 'Filedata',
			extraParams : { min_width: min_width, min_height:min_height },
			url : uploadUrl,
			afterUpload : function(data) {
				refreshFormHash(data);
				if (data.error === 0) {
					$("input[name=old_avatar]").val($("input[name=avatar]").val());
	            	$("input[name=old_avatar_id]").val($("input[name=avatar_id]").val());
	            	
	                $("input[name=avatar_id]").val(data.id);
	                $("input[name=avatar]").val(data.url);
					
	                $("#srcImg").html('<img id="cropbox" src="' + data.url + '"/>');
	                
	                cutDlg.dialog('open');
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