{include file="common/main_header.tpl"}
  {if $info['id']}
  {form_open(admin_site_url('role/edit'),'id="add_form"')}
  {else}
  {form_open(admin_site_url('role/add'),'id="add_form"')}
  {/if}
  <input type="hidden" name="id" value="{$info['id']}"/>
  <form id="add_form" method="post">
  	<table class="table tb-type2">
		<tbody>
	  		<tr class="noborder">
	          <td class="required"><label class="validation" for="name">权限组:</label>{form_error('name')}</td>
	        </tr>
	        <tr class="noborder">
	          <td><input class="w200" type="text" id="name" maxlength="40" value="{$info['name']|escape}" name="name" class="txt">&nbsp;<input type="submit" name="submit" value="保存" class="msbtn"/></td>
	        </tr>
	        <tr class="noborder">
	          <td class="required"><label class="validation">状态:</label>{form_error('status')}</td>
	        </tr>
	        <tr class="noborder">
	          <td>
	          	<select name="status">
	          		<option value="开启" {if $info['status'] == '开启'}selected{/if}>开启</option>
	          		<option value="关闭" {if $info['status'] == '关闭'}selected{/if}>关闭</option>
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
          <td class=""><label class="top_fn"><input type="checkbox" name="permission[]" {if isset($info['permission'][$topItem['url']])}checked{/if} value="{$topItem['url']}"><b>{$topItem['name']}</b></label></td>
          <td class="fnrow">
          	<table class="table">
              <tbody>
              	  {foreach from=$topItem['children'] item=secondItem}
                  <tr>
                  	<td>
                    	<label class="side_fn"><input type="checkbox" name="permission[]" {if isset($info['permission'][$secondItem['url']])}checked{/if} value="{$secondItem['url']}"><b>{$secondItem['name']}</b>&nbsp;&nbsp;</label>
                    	{foreach from=$secondItem['children'] item=subitem}
                    	<label class="detail_fn"><input type="checkbox" name="permission[]" {if isset($info['permission'][$subitem['url']])}checked{/if} value="{$subitem['url']}">{$subitem['name']}&nbsp;</label>
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