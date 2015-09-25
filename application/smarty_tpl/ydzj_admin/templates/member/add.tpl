{include file="common/main_header.tpl"}
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>会员管理</h3>
      <ul class="tab-base">
        <li><a href="{admin_site_url('member')}" ><span>管理</span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open_multipart(admin_site_url('member/add'),'id="user_form"')}
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="member_name">会员:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="member_name" id="member_name" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="member_passwd">密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="member_passwd" name="member_passwd" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="member_email">电子邮箱:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" id="member_email" name="member_email" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="member_truename">真实姓名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" id="member_truename" name="member_truename" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label> 性别:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul>
              <li>
                <label>
                  <input type="radio" checked="checked" value="0" name="member_sex">
                  保密</label>
              </li>
              <li>
                <label>
                  <input type="radio" value="1" name="member_sex">
                  男</label>
              </li>
              <li>
                <label>
                  <input type="radio" value="2" name="member_sex">
                  女</label>
              </li>
            </ul></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="member_qq">QQ:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" id="member_qq" name="member_qq" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="member_ww">阿里旺旺:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" id="member_ww" name="member_ww" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>头像:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <span class="type-file-show">
            <img class="show_image" src="http://www.nzbestprice.com/admin/templates/default/images/preview.png">
            <div class="type-file-preview" style="display: none;"><img id="view_img"></div>
            </span>
            <span class="type-file-box">
              <input type='text' name='member_avatar' id='member_avatar' class='type-file-text' />
              <input type='button' name='button' id='button' value='' class='type-file-button' />
              <input name="_pic" type="file" class="type-file-file" id="_pic" size="30" hidefocus="true" />
            </span>
            </td>
          <td class="vatop tips">支持格式jpg,jpeg</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span>提交</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="{js_url('js/dialog/dialog.js')}" id="dialog_js"></script>
<script type="text/javascript" src="{js_url('js/jquery-ui/jquery.ui.js')}"></script>
<script type="text/javascript" src="{js_url('js/ajaxfileupload/ajaxfileupload.js')}"></script>
<script type="text/javascript" src="{js_url('js/jquery.Jcrop/jquery.Jcrop.js')}"></script>
<link href="{css_url('js/jquery.Jcrop/jquery.Jcrop.min.css')}" rel="stylesheet" type="text/css"/>
<script type="text/javascript">

//裁剪图片后返回接收函数
function call_back(picname){
    $('#member_avatar').val(picname);
    $('#view_img').attr('src','http://www.nzbestprice.com/data/upload/shop/avatar/'+picname);
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
                data: { formhash : formhash },
                success: function (resp, status)
                {
                    if (resp.status == 1){
                        var url = '{admin_site_url("common/pic_cut")}?type=member&x=200&y=200&resize=0&ratio=1&url='+resp.url;
                        console.log(url);
                        ajax_form('cutpic','裁剪','{admin_site_url("common/pic_cut")}?type=member&x=200&y=200&resize=0&ratio=1&url='+resp.url,690);
                    }else
                    {
                        alert(resp.message);
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
    };
    
    $("#submitBtn").click(function(){
	    if($("#user_form").valid()){
	       $("#user_form").submit();
	    }
    });
    
    $('#user_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            member_name: {
                required : true,
                minlength: 3,
                maxlength: 20,
                remote   : {
                    url :'index.php?act=member&op=ajax&branch=check_user_name',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#member_name').val();
                        },
                        member_id : ''
                    }
                }
            },
            member_passwd: {
                maxlength: 20,
                minlength: 6
            },
            member_email   : {
                required : true,
                email : true,
                remote   : {
                    url :'index.php?act=member&op=ajax&branch=check_email',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#member_email').val();
                        },
                        member_id : ''
                    }
                }
            },
            member_qq : {
                digits: true,
                minlength: 5,
                maxlength: 11
            }
        },
        messages : {
            member_name: {
                required : '会员名不能为空',
                maxlength: '用户名必须在3-20字符之间',
                minlength: '用户名必须在3-20字符之间',
                remote   : '会员名有重复，请您换一个'
            },
            member_passwd : {
                maxlength: '密码长度应在6-20个字符之间',
                minlength: '密码长度应在6-20个字符之间'
            },
            member_email  : {
                required : '电子邮箱不能为空',
                email   : '请您填写有效的电子邮箱',
                remote : '邮件地址有重复，请您换一个'
            },
            member_qq : {
                digits: '请输入正确的QQ号码',
                minlength: '请输入正确的QQ号码',
                maxlength: '请输入正确的QQ号码'
            }
        }
    });
});
</script>
{include file="common/main_footer.tpl"}