{include file="./my_header.tpl"}
<div class="row warning">照片格式JPG,不大于4M,最小尺寸800x800</div>
<div class="handle_area">
    {form_open_multipart(site_url('my/set_avatar'),"id='setAvatarForm'")}
    <input type="hidden" name="returnUrl" value="{$returnUrl}"/>
    <input type="hidden" name="inviteFrom" value="{$inviteFrom}"/>
    <input type="hidden" name="avatar_id" value="{$avatar_id}" />
    <input type="hidden" name="new_avatar" value="{if $new_avatar}{$new_avatar}{/if}" />
    <input type="hidden" name="default_avatar" value="{$default_avatar}"/>
    <input type="hidden" id="x1" name="x1" />
    <input type="hidden" id="y1" name="y1" />
    <input type="hidden" id="x2" name="x2" />
    <input type="hidden" id="y2" name="y2" />
    <input type="hidden" id="w" name="w" />
    <input type="hidden" id="h" name="h" />    
    <div id="profile_avatar">
        <div class="row" style="position:relative;">
            <label class="side_lb" for="_pic"><em>*</em>用户头像：</label>
            <input type="file" class="type-file-file" id="_pic" name="_pic" value=""  hidefocus="true"/>
            <input type='text' name='member_avatar' id='member_avatar' class='type-file-text' />
            <input type='button' name='button' id='button' value='' />
        </div>
        <div id="wait_loading" style="text-align:center;display:none;">
	        <div><img class="nature" src="{resource_url('img/loading/loading.gif')}"/></div>
	        <div>正在上传,请稍等..</div>
	    </div>
	    {$avatar_error}
        <div class="row" id="save" style="display:none;">
            <input type="submit" id="save_btn" name="submit" class="master_btn" value="保存"/>
        </div>
        <div id="cropBox"></div>
    </div>
    </form>
</div>
<link href="{resource_url('js/jquery.Jcrop/jquery.Jcrop.min.css')}" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{resource_url('js/jquery.Jcrop/jquery.Jcrop.js')}"></script>
{include file="common/ke.tpl"}
<script>
function showPreview(coords)
{
    $('#x1').val(coords.x);
    $('#y1').val(coords.y);
    $('#x2').val(coords.x2);
    $('#y2').val(coords.y2);
    $('#w').val(coords.w);
    $('#h').val(coords.h);
}

$(function(){
	
	
	/*
    $('input[class="type-file-file"]').change(uploadChange);
    function uploadChange(){
        var filepatd=$(this).val();
        var extStart=filepatd.lastIndexOf(".");
        var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();     
        if(ext!=".JPG"&&ext!=".JPEG"){
            alert("file type error");
            $(this).attr('value','');
            return false;
        }
        if ($(this).val() == '') return false;
        
        $("#wait_loading").show();
        $("#save").hide();
        ajaxFileUpload();
    }
    
    function ajaxFileUpload()
    {
        
        $.ajaxFileUpload
        (
            {
                url:'{site_url("common/pic_upload")}?mod=avatar',
                secureuri:false,
                fileElementId:'_pic',
                dataType: 'json',
                data: { formhash : formhash , id : $("input[name=avatar_id]").val() , size:'big'},
                success: function (resp, status)
                {
                    $("#wait_loading").hide();
                    $("#save").show();
                    
                    formhash = resp.formhash;
                    $("input[name=formhash]").val(resp.formhash);
                    
                    if (resp.error == 0){
                        $("input[name=avatar_id]").val(resp.id);
                        $("input[name=new_avatar]").val(resp.url);
                        
                        $('#cropBox').html('<img id="avatar" src="' + resp.url + '"/>');

                        $('#avatar').Jcrop({
						      bgColor:"white",
						      fixedSupport:1,
						      touchSupport : 1,
						      aspectRatio: 1,
						      setSelect: [ 0, 0, 200, 200 ],
						      minSize:[200, 200],
						      allowSelect:0,
						      allowResize:0,
						      onChange: showPreview
					    });
					    
                    }else
                    {
                        alert(resp.msg);
                    }
                    $('input[class="type-file-file"]').bind('change',uploadChange);
                },
                error: function (resp, status, e)
                {
                    $("#wait_loading").hide();
                    alert('上传失败');
                    $('input[class="type-file-file"]').bind('change',uploadChange);
                }
            }
        )
    }
	*/
});
</script>
{include file="./my_footer.tpl"}