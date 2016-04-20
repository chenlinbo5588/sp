{include file="common/main_header.tpl"}
	<style type="text/css">
		

	</style>
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
  <div class="feedback">{$feedback}</div>
  {form_open(admin_site_url('authority/role_add'),'id="add_form"')}
  <form id="add_form" method="post">
  	<table class="table tb-type2">
		<tbody>
	  		<tr class="noborder">
	          <td class="required"><label class="validation" for="gname">权限组:</label>{form_error('gname')}</td>
	        </tr>
	        <tr class="noborder">
	          <td><input type="text" id="gname" maxlength="40" value="{set_value('gname')}" name="gname" class="txt"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
	        </tr>
	        <tr class="noborder">
	          <td class="required"><label class="validation">权限组状态:</label>{form_error('status')}</td>
	        </tr>
	        <tr class="noborder">
	          <td>
	          	<select name="status">
	          		<option value="开启" {set_select('status','开启')}>开启</option>
	          		<option value="关闭" {set_select('status','关闭')}>关闭</option>
	          	</select>
	          </td>
	        </tr>
	    </tbody>
  	</table>
    <table class="table tb-type2">
      <thead>
      	<tr class="thead">
      		<th style="width:120px;" >模块 <label><input type="checkbox" name="limitAll" id="limitAll" />全选</label></th>
      		<th>子模块/功能点 {form_error('permission[]')}</th>
      	</tr>
      </thead>
      <tbody>
        {foreach from=$fnTree item=topItem}
        <tr class="modelrow">
          <td class=""><label class="top_fn"><input type="checkbox" name="permission[]" value="{$topItem['url']}"><b>{$topItem['name']}</b></label></td>
          <td class="fnrow">
          	<table class="table">
              <tbody>
              	  {foreach from=$topItem['children'] item=secondItem}
                  <tr>
                  	<td>
                    	<label class="side_fn"><input type="checkbox" name="permission[]" value="{$secondItem['url']}"><b>{$secondItem['name']}</b>&nbsp;&nbsp;</label>
                    	{foreach from=$secondItem['children'] item=subitem}
                    	<label class="detail_fn"><input type="checkbox" name="permission[]" value="{$subitem['url']}">{$subitem['name']}&nbsp;</label>
                    	{/foreach}
                    </td>
                </tr>
                {/foreach}
               </tbody>
            </table>
          </td>
        </tr>
        {/foreach}
      </tbody>
  </form>
<script>

$(document).ready(function(){
	$(".top_fn input").bind("click",function(){
		var target = $(this).parents("td").siblings("td").find("input");
		if($(this).prop("checked")){
			target.prop("checked",true);
		}else{
			target.prop("checked",false);
		}
	});
	
	$(".side_fn input").bind("click",function(){
		var target = $(this).parent("label").siblings(".detail_fn").find("input");
		var moduleCheckbox = $(this).parents(".modelrow").find(".top_fn input");
		
		
		
		if($(this).prop("checked")){
			moduleCheckbox.prop("checked",true);
			target.prop("checked",true);
		}else{
			target.prop("checked",false);
			
			var fnChexkbox = $(this).parents(".fnrow").find("input:checked");
			
			if(fnChexkbox.size() == 0){
				moduleCheckbox.prop("checked",false);
			}
		}
		
		
	});
	

	$('#limitAll').click(function(){
		$(this).parents("table").find("tbody input").prop("checked",$(this).prop("checked"));
	});
	
});
</script>
{include file="common/main_footer.tpl"}