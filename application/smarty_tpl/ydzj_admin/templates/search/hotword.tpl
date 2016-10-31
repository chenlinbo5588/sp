{include file="common/main_header.tpl"}
  {form_open(admin_site_url('search/hotword'),'name="form1"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="hotword">热门搜索:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="hotwords" name="hotwords" value="{$currentSetting['hotwords']['value']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('hotwords')}<span class="vatop rowform">显示在站点首页的热门搜索词,前台点击时直接作为关键词进行搜索，多个关键词间请用半角逗号 "," 隔开</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" name="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}