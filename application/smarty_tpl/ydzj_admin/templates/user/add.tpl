{include file="common/main_header_navs.tpl"}
  {if $info['uid']}
  {form_open(site_url($uri_string),'id="add_form"')}
  <input type="hidden" name="uid" value="{$info['uid']}"/>
  {else}
  {form_open(site_url($uri_string),'id="add_form"')}
  {/if}
  
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="email">登录名(邮箱):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="email" value="{$info['email']}" name="email" class="txt"></td>
          <td class="vatop tips"><label id="error_email" class="errortip"></label> 请输入登录名</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="username">用户真实名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" id="username" value="{$info['username']|escape}" name="username" class="txt"></td>
          <td class="vatop tips"><label id="error_username" class="errortip"></label> 请输入用户真实名称</td>
        </tr>
        <tr class="noborder">
          <td class="required"><label class="validation">状态:</label></td>
        </tr>
        <tr class="noborder">
          <td>
          	<select name="enable">
          		<option value="1" {if 1 == $info['enable']}selected{/if}>开启</option>
          		<option value="0" {if 0 == $info['enable']}selected{/if}>关闭</option>
          	</select>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="admin_password">密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="password" id="admin_password" name="admin_password" class="txt"></td>
          <td class="vatop tips"><label id="error_admin_password" class="errortip"></label> 请输入密码 允许字母、数字、特殊符合 </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="admin_rpassword">确认密码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="password" id="admin_rpassword" name="admin_rpassword" class="txt"></td>
          <td class="vatop tips"><label id="error_admin_rpassword" class="errortip"></label> 请输入确认密码 </td>
        </tr>
        <tr>
          <td colspan="2"><label class="validation"  for="role_id">角色:</label> 如果还未设置， <a href="{admin_site_url('role/add')}">点击马上设置</a></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan="2">
          		<ul class="ulListStyle1 clearfix">
	          	{foreach from=$roleList item=item}
	          		<li {if $info['role_id'] == $item['id']}class="selected"{/if}><label><input type="radio" name="role_id" {if $info['role_id'] == $item['id']}checked="checked"{/if} value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
	          	{/foreach}
	          	</ul>
          </td>
        </tr>
        <tr>
          <td colspan="2"><label class="validation" for="group_id">用户组:</label>请选择一个用户组，如果还未设置， <a href="{admin_site_url('group/add')}">点击马上设置</a></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan="2">
          	<ul class="ulListStyle1 clearfix">
          	{foreach from=$groupList item=item}
          		<li {if $info['group_id'] == $item['id']}class="selected"{/if}><label><input type="radio" name="group_id" {if $info['group_id'] == $item['id']}checked="checked"{/if} value="{$item['id']}"/><span>{$item['name']|escape}</span></label></li>
          	{/foreach}
          	</ul>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2">
          	<input type="submit" name="submit" value="保存" class="msbtn"/>
          
          	{if $gobackUrl}
	    	<a href="{$gobackUrl}" class="salvebtn">返回</a>
	    	{/if}
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script>
  	$(function(){
  		$.loadingbar({ text: "正在提交..." , urls: [ new RegExp("{$uri_string}") ] , container : "#add_form" });
  		
  		bindAjaxSubmit("#add_form");
  	});
  </script>
{include file="common/main_footer.tpl"}