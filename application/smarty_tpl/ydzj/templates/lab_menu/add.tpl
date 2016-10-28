{include file="common/my_header.tpl"}
    {form_open(site_url($uri_string),'name="menuForm"')}
	{if $info['id']}
		<input type="hidden" name="id" value="{$info['id']}"/>
	{/if}
		<input type="hidden" name="pid" value="{if $info['pid']}{$info['pid']}{else}0{/if}"/>
	<table class="fulltable style1">
      <tbody>
        <tr class="noborder">
          <td class="required w120"><label class="validation"><em></em>名称:</label></td>
          <td class="vatop rowform">
                <input type="text" value="{$info['name']|escape}" name="name" class="w40pre txt" placeholder="菜单名称">
                <label class="errtip" id="error_name"></label>
          </td>
        </tr>
        <tr class="noborder">
          <td><label for="displayorder">排序:</label></td>
          <td class="vatop rowform">
          	<input type="text" value="{$info['displayorder']|escape}" name="displayorder" id="displayorder" class="txt">
          	<label class="errtip" id="error_displayorder"></label><span>大于等于0的自然数,最大9999，数字越小越靠前</span>
          </td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation" for="parent">父级:</label></td>
          <td class="vatop rowform">
          	  <span id="parent_id_tip" class="hightlight">{form_error('parent_id')}</span></span>
	          <select name="parent_id">
	          <option value="0">请选择</option>
	          {foreach from=$lab_menu item=item}
	          <option value="{$item['id']}">{$item['sep']}{$item['name']|escape}</option>
	          {/foreach}
	          </select>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" name="submit" value="保存" class="master_btn"/></td>
        </tr>
       </tbody>
      </table>
   </form>
   <script>
		$(function(){
			$("form").each(function(){
				var name = $(this).prop("name");
				formLock[name] = false;
			});
			
			bindAjaxSubmit('form');
		});
		
	 </script>
{include file="common/my_footer.tpl"}