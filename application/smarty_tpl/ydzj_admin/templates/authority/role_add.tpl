{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>添加角色</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('authority/role')}" ><span>角色管理</span></a></li>
      	<li><a  class="current"><span>添加角色</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="add_form" method="post">
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="admin_name">权限组:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="gname" maxlength="40" name="gname" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        
        <tr>
          <td colspan="2"><table class="table tb-type2 nomargin">
              <thead>
                <tr class="space">
                  <th> <input id="limitAll" id="limitAll" value="1" type="checkbox">&nbsp;&nbsp;设置权限</th>
                </tr>
              </thead>
              <tbody>
              	  {foreach from=$fnTree item=item}
                  <tr>
                  	<td>
                  		<label style="width:100px">&nbsp;</label>
                    	<input id="limit0" type="checkbox" onclick="selectLimit('limit0')">
                    	<label for="limit0"><b>{$item['name']}</b>&nbsp;&nbsp;</label>
                    	{foreach from=$item['children'] item=subitem}
                    	<label><input nctype='limit' class="limit0" type="checkbox" name="permission[]" value="{$subitem['url']}">{$subitem['name']}&nbsp;</label>
                    	{/foreach}
                     </td>
                </tr>
                {/foreach}
               </tbody>
            </table></td>
        </tr>
      </tbody>
    
  </form>
<script>
function selectLimit(name){
    if($('#'+name).attr('checked')) {
        $('.'+name).attr('checked',true);
    }else {
        $('.'+name).attr('checked',false);
    }
}
$(document).ready(function(){
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
	    if($("#add_form").valid()){
	     $("#add_form").submit();
		}
	});

	$('#limitAll').click(function(){
		$('input[type="checkbox"]').attr('checked',$(this).attr('checked') == 'checked');
	});
	
	$("#add_form").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            gname : {
                required : true,
				remote	: {
                    url :'index.php?act=admin&op=ajax&branch=check_gadmin_name',
                    type:'get',
                    data:{
                    	gname : function(){
                            return $('#gname').val();
                        }
                    }
                }
            }
        },
        messages : {
            gname : {
                required : '请输入',
                remote	 : '该名称已存在'
            }
        }
	});
});
</script>
{include file="common/main_footer.tpl"}