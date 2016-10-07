{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  {form_open(site_url($uri_string),'id="notifyForm"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>目标组: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              {foreach from=$group item=item}
              <label><input type="radio" value="{$item['id']}" name="send_group" {if $info['send_group'] == $item['id']}checked{/if} required >{$item['name']}</label>
              {/foreach}
          </td>
          <td class="vatop tips">{form_error('send_group')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>发送模式: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              {foreach from=$msgMode key=key item=item}
              <label><input type="radio" {if $info['msg_mode'] == $key}checked{/if} value="{$key}" name="msg_mode"   required>{$item|escape}</label>
              {/foreach}
          </td>
          <td class="vatop tips">{form_error('msg_mode')} 白名单:表示只有在列表中的能够收到消息，黑名单：表示除了以上会员列表，其他会员将会收到消息。</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label>发送方式: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              {foreach from=$sendWays key=key item=item}
              <label><input type="checkbox" {if in_array($item,$info['send_ways'])}checked{/if} value="{$item}" name="send_ways[]" >{$item|escape}</label>
              {/foreach}
          </td>
          <td class="vatop tips">{form_error('send_ways[]')}。</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="username_list">会员列表: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea id="username_list" name="users" rows="6" class="tarea">{$info['users']}</textarea></td>
          <td class="vatop tips">{form_error('users')} 每行填写一个会员登陆名，表示选择这个组中只有这些会员将接受到消息</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">消息标题:<span class="hightlight">只有发送方式为站内信和邮件时标题有效，其他方式标题不起实际作用</span></label></td>
        </tr>
        <tr>
          <td class="vatop rowform"><input type="text" name="title" class="txt" value="{$info['title']|escape}"/></td>
          <td class="vatop tips">{form_error('title')} 可以插入替换符号 {#username_placeholder#} 表示会员名 </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">消息正文:<span class="hightlight">只有站内信和邮件支持富文本，其他方式消息通知均为纯文本</span></div></label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform">
            <textarea name="content" style="width:100%;height:400px;" rows="6" class="tarea">{$info['content']}</textarea>
            {form_error('content')}
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
  {include file="common/jquery_validation.tpl"}
  <script type="text/javascript">
    var editor1;
  </script>
  <script type="text/javascript" src="{resource_url('js/notify/admin_pm.js')}"></script>
{include file="common/main_footer.tpl"}