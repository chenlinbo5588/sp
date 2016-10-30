{include file="common/my_header.tpl"}
    <div id="handleDiv">
    {form_open(site_url($uri_string),'name="addForm"')}
    {if $info['id']}
        <input type="hidden" name="id" value="{$info['id']}"/>
    {/if}
    <table class="fulltable style1">
      <tbody>
        <tr class="noborder">
          <td class="required w120"><label class="validation"><em></em>名称:</label></td>
          <td class="vatop rowform">
                <input type="text" value="{$info['name']|escape}" name="name" class="w40pre txt" placeholder="分类名称">
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
              <select name="pid">
              <option value="0">请选择</option>
              {foreach from=$list item=item}
              <option value="{$item['id']}" {if $info['pid'] == $item['id']}selected{/if}>{str_repeat('----',$item['level'])}{$item['name']|escape}</option>
              {/foreach}
              </select>
              <label class="errtip" id="error_pid"></label>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" name="submit" value="保存" class="master_btn"/></td>
        </tr>
       </tbody>
      </table>
   </form>
   </div>
   <script>
        $(function(){
            $("form").each(function(){
                var name = $(this).prop("name");
                formLock[name] = false;
            });
            
            bindAjaxSubmit('form');
            
            $.loadingbar({ urls: [ new RegExp($("form[name=addForm]").attr('action')) ], templateData:{ message:"努力加载中..." } ,container: "#handleDiv" });
        });
        
     </script>
{include file="common/my_footer.tpl"}