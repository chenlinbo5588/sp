<script type="text/javascript" src="{js_url('js/dialog/dialog.js')}" id="dialog_js"></script>
<script type="text/javascript" src="{js_url('js/jquery-ui/jquery.ui.js')}"></script>
<script type="text/javascript" src="{js_url('js/ajaxfileupload/ajaxfileupload.js')}"></script>
<script type="text/javascript" src="{js_url('js/jquery.Jcrop/jquery.Jcrop.js')}"></script>
<link href="{css_url('js/jquery.Jcrop/jquery.Jcrop.min.css')}" rel="stylesheet" type="text/css"/>
<script type="text/javascript">

//裁剪图片后返回接收函数
function call_back(resp){
    formhash = resp.formhash;
    $("input[name=formhash]").val(formhash);
    
    $('#member_avatar').val(resp.picname);
    $('#view_img').attr('src',resp.url);
}
$(function(){
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
        ajaxFileUpload();
    }
    
    function ajaxFileUpload()
    {
        $.ajaxFileUpload
        (
            {
                url:'{admin_site_url("common/pic_upload")}',
                secureuri:false,
                fileElementId:'_pic',
                dataType: 'json',
                data: { formhash : formhash , id : $("input[name=avatar_id]").val() , size : "big" },
                success: function (resp, status)
                {
                    if (resp.status == 1){
                        $("input[name=avatar_id]").val(resp.id);
                        ajax_form('cutpic','裁剪','{admin_site_url("member/pic_cut")}?type=member&x=200&y=200&resize=0&ratio=1&url='+resp.url,800);
                    }else
                    {
                        alert(resp.msg);
                    }
                    $('input[class="type-file-file"]').bind('change',uploadChange);
                },
                error: function (resp, status, e)
                {
                    alert('upload failed');
                    $('input[class="type-file-file"]').bind('change',uploadChange);
                }
            }
        )
    }
});
</script>