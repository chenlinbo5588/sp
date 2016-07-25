{include file="common/main_header.tpl"}
{config_load file="team.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#team_notify#}</h3>
      <ul class="tab-base"><li><a class="current"><span>发送通知</span></a></li>
    </div>
  </div>
  <div class="fixed-empty"></div>
  {form_open(admin_site_url('notify/team'),'name="form1"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td class="vatop rowform"></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tbody id="user_list">
      	<tr>
          <td colspan="2" class="required"><label class="validation" for="team_title">{#team_name#}</label></td>
        </tr>
      	<tr>
      		<td colspan="2"><input type="text" name="team_title" value=""/></td>
      	</tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="user_name">{#team#}列表: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea id="team_id" name="team_id" rows="6" class="tarea">{$team}</textarea></td>
          <td class="vatop tips">每行填写一个队伍序号</td>
        </tr>
      </tbody>
      <tbody id="msg">
        <tr>
          <td colspan="2" class="required"><label class="validation">通知内容: </label></td>
        </tr>
        <tr>
        	<td colspan="2"><strong>{#team_title_tpl#}</strong><span>表示队伍名称</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="vatop rowform">
          	<textarea name="content1" rows="6" class="tarea"></textarea>
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
{include file="common/main_footer.tpl"}