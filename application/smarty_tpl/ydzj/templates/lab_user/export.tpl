{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>导出内容为{#title#}信息的.xls文件</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  {form_open(site_url('lab_user/export'),'id="lab_user_form"')}
    <input type="hidden" name="if_convert" value="1" />
    <table class="table tb-type2">
    <thead>
        <tr class="thead">
          <th>导出您的{#title#}数据?</th>
      </tr></thead>
      <tfoot><tr class="tfoot">
        <td><input type="submit" name="submit" value="保存" class="msbtn"/></td>
      </tr></tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}