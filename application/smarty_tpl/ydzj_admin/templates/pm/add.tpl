{include file="common/main_header_navs.tpl"}
  {form_open(site_url($uri_string),'id="pmForm"')}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation">对方邮箱地址:</label></td>
        </tr>
        <tr>
          <td class="vatop rowform"><input type="text" name="email" class="txt" value="{$info['email']|escape}"/></td>
          <td class="vatop tips"><label class="errtip" id="error_email"></label>{form_error('email')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">消息标题:</label></td>
        </tr>
        <tr>
          <td class="vatop rowform"><input type="text" name="title" class="txt" value="{$info['title']|escape}"/></td>
          <td class="vatop tips"><label class="errtip" id="error_title"></label>{form_error('title')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">消息正文:</label><label class="errtip" id="error_content"></label>{form_error('content')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform">
            <textarea name="content" style="width:100%;height:400px;" rows="6" class="tarea">{$info['content']}</textarea>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  {include file="common/ke.tpl"}
  <script type="text/javascript">
    var editor1;
    
    {if $redirectUrl}
    	setTimeout(function(){
    		location.href="{$redirectUrl}";
    	},2000);
    {/if}
  </script>
  <script type="text/javascript" src="{resource_url('js/pm/admin_pm.js',true)}"></script>
{include file="common/main_footer.tpl"}