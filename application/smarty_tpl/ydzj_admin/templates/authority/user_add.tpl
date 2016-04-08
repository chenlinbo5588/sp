{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>权限设置</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('authority/user')}"><span>管理员</span></a></li>
      	<li><a class="current" ><span>添加管理员</span></a></li>
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="admin_name">登录名:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="admin_name" name="admin_name" class="txt"></td>
          <td class="vatop tips">请输入登录名</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="admin_password">密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="password" id="admin_password" name="admin_password" class="txt"></td>
          <td class="vatop tips">请输入密码</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="admin_password">确认密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="password" id="admin_rpassword" name="admin_rpassword" class="txt"></td>
          <td class="vatop tips">请输入密码</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="gadmin_name">权限组:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="gid"></select>
          </td>
          <td class="vatop tips">请选择一个权限组，如果还未设置，<a href="{admin_site_url('authority/add_role')}">点击马上设置</a></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span>提交</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script>
//按钮先执行验证再提交表
$(document).ready(function(){
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
	    if($("#add_form").valid()){
	     $("#add_form").submit();
		}
	});
	
	$("#add_form").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            admin_name : {
                required : true,
				minlength: 3,
				maxlength: 20,
				remote	: {
                    url :'index.php?act=admin&op=ajax&branch=check_admin_name',
                    type:'get',
                    data:{
                    	admin_name : function(){
                            return $('#admin_name').val();
                        }
                    }
                }
            },
            admin_password : {
                required : true,
				minlength: 6,
				maxlength: 20
            },
            admin_rpassword : {
                required : true,
                equalTo  : '#admin_password'
            },
            gid : {
                required : true
            }        
        },
        messages : {
            admin_name : {
                required : '登录名不能为空',
				minlength: '登录名长度为3-20',
				maxlength: '登录名长度为3-20',
				remote	 : '该名称已存在'
            },
            admin_password : {
                required : '密码不能为空',
				minlength: '密码长度为6-20',
				maxlength: '密码长度为6-20'
            },
            admin_rpassword : {
                required : '密码不能为空',
                equalTo  : '两次输入的密码不一致，请重新输入'
            },
            gid : {
                required : '请选择一个权限组',
            }
        }
	});
});
</script>

{include file="common/main_footer.tpl"}