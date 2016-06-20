{include file="common/main_header.tpl"}
{config_load file="member.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	{if isset($permission['admin/member/index'])}<li><a href="{admin_site_url('member/index')}"><span>{#manage#}</span></a></li>{/if}
        {if isset($permission['admin/member/export'])}<li><a href="javascript:void(0);" class="current" ><span>{#export#}</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th class="nobg" colspan="12"><div class="title"><h5>操作提示</h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>导出内容为会员资料.csv文件</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <script type="text/javascript" src="{resource_url('js/My97DatePicker/WdatePicker.js')}"></script>
  {form_open(admin_site_url('member/export'),'id="member_form"')}
    <input type="hidden" name="if_convert" value="1" />
    <table class="table tb-type2">
    <thead>
        <tr class="thead">
          <th>导出你的会员资料数据?</th>
      </tr>
     </thead>
     <tbody>
     	<tr>
     		<td><label><strong>注册日期开始</strong><input type="text" name="sdate" id="sdate" class="Wdate" readonly {literal}onclick="WdatePicker({ maxDate:'#F{$dp.$D(\'edate\')}'})"{/literal} value="{$smarty.post.sdate}"/>{form_error('sdate')}</label>
            <label><strong>注册日期结束</strong><input type="text" name="edate" id="edate" class="Wdate" readonly {literal}onclick="WdatePicker({ minDate:'#F{$dp.$D(\'sdate\')}'})"{/literal} value="{$smarty.post.edate}"/>{form_error('edate')}</label>
           </td>
      	</tr>
     </tbody>
      <tfoot><tr class="tfoot">
        <td><input type="submit" name="submit" value="开始导出" class="msbtn"/></td>
      </tr></tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}