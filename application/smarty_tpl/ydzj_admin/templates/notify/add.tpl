{include file="common/main_header_navs.tpl"}
  {config_load file="member.conf"}
  {form_open(site_url($uri_string),'id="notifyForm"')}
    <table class="table tb-type2">
      <tbody>
      	<tr class="noborder">
          <td colspan="2" class="required"><label class="validation">消息类型: </label></td>
        </tr>
      	<tr class="noborder">
      		<td class="vatop rowform">
      			<lable><input type="radio" name="msg_type"  {if $inDetail}{else}{set_checkbox('msg_type','前台消息')}{/if} value="前台消息" />前台消息</lable>&nbsp;
      			<lable><input type="radio" name="msg_type" {if $inDetail}{else}{set_checkbox('msg_type','后台消息')}{/if}  value="后台消息" />后台消息</lable>
      		</td>
      		<td class="vatop tips"><label class="errtip" id="error_msg_type"></label>{form_error('msg_type')}</td>
      	</tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">目标组: </label><label><input type="checkbox" name="ckall" value="全选"/>全选</label><label class="errtip" id="error_send_group"></label>{form_error('send_group[]')}</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform" colspan="2">
          	  <ul class="ulListStyle1 clearfix" id="groupList">
              {foreach from=$group item=item}
              	<li><label><input type="checkbox" value="{$item['id']}" group="ckall" name="send_group[]" {if $inDetail}{if in_array($item['id'],$info['groups']) }checked{/if}{else}{set_checkbox('send_group[]',$item['id'])}{/if}><span>{$item['name']|escape}</span></label></li>
              {/foreach}
              </ul>
          </td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>发送模式: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              {foreach from=$msgMode key=key item=item}
              <label><input type="radio" {if $info['msg_mode'] == $key}checked{/if} value="{$key}" name="msg_mode" >{$item|escape}</label>
              {/foreach}
          </td>
          <td class="vatop tips"><label class="errtip" id="error_msg_mode"></label>{form_error('msg_mode')} 白名单:表示只有在列表中的能够收到消息，黑名单：表示除了以上会员列表，其他会员将会收到消息。</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation">发送方式: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              {foreach from=$sendWays key=key item=item}
              <label><input type="checkbox" {if in_array($item,$info['send_ways'])}checked{/if} value="{$item}" name="send_ways[]" >{$item|escape}</label>
              {/foreach}
          </td>
          <td class="vatop tips"><label class="errtip" id="error_send_ways"></label>{form_error('send_ways[]')}。</td>
        </tr>
        <tr class="userlist">
          <td colspan="2" class="required"><label class="validation" for="username_list">会员列表: </label></td>
        </tr>
        <tr class="noborder userlist">
          <td class="vatop rowform"><textarea id="username_list" name="users" rows="6" class="tarea">{$info['users']}</textarea></td>
          <td class="vatop tips"><label class="errtip" id="error_users"></label>{form_error('users')} 每行填写一个会员登陆名，表示选择这个组中只有这些会员将接受到消息</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">消息标题:</label></td>
        </tr>
        <tr>
          <td class="vatop rowform"><input type="text" name="title" class="txt" value="{$info['title']|escape}"/></td>
          <td class="vatop tips"><label class="errtip" id="error_title"></label>{form_error('title')} 可以插入替换符号 {#username_placeholder#} 表示会员名 </td>
        </tr>
        <tr>
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
          <td colspan="2">{if !$detail}<input type="submit" name="submit" value="保存" class="msbtn"/>{/if}</td>
        </tr>
      </tfoot>
    </table>
  </form>
  {include file="common/ke.tpl"}
  <script type="text/javascript">
    var editor1,submitUrl = [new RegExp("{$uri_string}")];
    
    {if $redirectUrl}
    	setTimeout(function(){
    		location.href="{$redirectUrl}";
    	},2000);
    {/if}
  </script>
  <script type="text/javascript" src="{resource_url('js/notify/notify_add.js',true)}"></script>
{include file="common/main_footer.tpl"}