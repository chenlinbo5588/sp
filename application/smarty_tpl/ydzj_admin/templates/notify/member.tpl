{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#member_notify#}</h3>
      <ul class="tab-base"><li><a class="current"><span>发送通知</span></a></li>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('notify/member'),'name="form1"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>发送类型: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul class="nofloat">
              <li>
                <label><input type="radio" checked="" value="1" name="send_type">指定会员</label>
              </li>
              <li>
                <label><input type="radio" value="2" name="send_type" />全部会员</label>
              </li>
            </ul>
          </td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tbody id="user_list">
        <tr>
          <td colspan="2" class="required"><label class="validation" for="user_name">会员列表: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea id="user_name" name="user_name" rows="6" class="tarea" >shopnc</textarea></td>
          <td class="vatop tips">每行填写一个会员名</td>
        </tr>
      </tbody>
      <tbody id="msg">
        <tr>
          <td colspan="2" class="required"><label class="validation">通知内容: </label></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform"><textarea name="content1" rows="6" class="tarea"></textarea></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}