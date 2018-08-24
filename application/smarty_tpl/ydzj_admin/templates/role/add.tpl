{include file="common/main_header_navs.tpl"}
  {if $info['id']}
  {form_open(site_url($uri_string),'id="add_form"')}
  <input type="hidden" name="id" value="{$info['id']}"/>
  {else}
  {form_open(site_url($uri_string),'id="add_form"')}
  {/if}
  	<table class="table tb-type2">
		<tbody>
	  		<tr class="noborder">
	          <td class="required"><label class="validation" for="name">角色名称:</label><label id="error_name" class="errortip"></label>{form_error('name')}</td>
	        </tr>
	        <tr class="noborder">
	          <td>
	          	<input class="w200" type="text" id="name" value="{$info['name']|escape}" name="name" class="txt">&nbsp;
	          	<input type="submit" name="submit" value="保存" class="msbtn"/>
	          	{if $gobackUrl}
		    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
		    	{/if}
	          </td>
	        </tr>
	        <tr class="noborder">
	          <td class="required"><label class="validation">状态:</label>{form_error('status')}</td>
	        </tr>
	        <tr class="noborder">
	          <td>
	          	<select name="enable">
	          		<option value="1" {if 1 == $info['enable']}selected{/if}>开启</option>
	          		<option value="0" {if 0 == $info['enable']}selected{/if}>关闭</option>
	          	</select>
	          </td>
	        </tr>
	        <tr class="noborder">
	          <td class="required"><label>到期日期:</label>{form_error('expire')}</td>
	        </tr>
	        <tr class="noborder">
	          <td class="vatop rowform"><input type="text" id="expire"  value="{if $info['expire']}{date('Y-m-d',$info['expire'])}{/if}" name="expire" class="datepicker txt-short"></td>
	          <td class="vatop tips"><label id="error_expire" class="errtip"></label>{form_error('expire')} 不填表示永不过期</td>
	        </tr>
	    </tbody>
  	</table>
    <table class="table tb-type2">
      <thead>
      	<tr class="thead">
      		<th style="width:120px;" >模块 <label><input type="checkbox" name="limitAll" id="limitAll" />全选</label></th>
      		<th>子模块/功能点 {form_error('permission[]')} <label id="error_permission" class="errortip"></label></th>
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
$(function(){

	$(".datepicker").datepicker();
	$.loadingbar({ text: "正在提交..." , urls: [ new RegExp("{$uri_string}") ] , container : "#add_form" });
  		
  	bindAjaxSubmit("#add_form");
  		
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