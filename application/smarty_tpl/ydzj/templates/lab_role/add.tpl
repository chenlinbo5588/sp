{include file="common/my_header.tpl"}
  {form_open(site_url($uri_string),'id="add_form"')}
  {if $info['id']}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {/if}
  <form id="add_form" method="post">
  	<table class="fulltable style1">
		<tbody>
	  		<tr class="noborder">
	          <td class="w120 required"><label class="validation" for="name">角色名称:</label></td>
	          <td><input class="w200" type="text" id="name" maxlength="40" value="{$info['name']|escape}" name="name" class="txt">&nbsp;
	          <input type="submit" name="submit" value="保存" class="master_btn"/>
	          {form_error('name')}
	          </td>
	        </tr>
	        <tr class="noborder">
	          <td class="required"><label class="validation">状态:</label></td>
	          <td>
	          	<select name="status">
	          		<option value="1" {if $info['status'] == '1'}selected{/if}>开启</option>
	          		<option value="-1" {if $info['status'] == '-1'}selected{/if}>关闭</option>
	          	</select>
	          	{form_error('status')}
	          </td>
	        </tr>
	    </tbody>
  	</table>
  	<br/>
    <table class="fulltable style1">
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
     </table>
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
{include file="common/my_footer.tpl"}