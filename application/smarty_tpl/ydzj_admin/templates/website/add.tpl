{include file="common/main_header.tpl"}
{config_load file="webiste.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	{if isset($permission['admin/website/index'])}<li><a href="{admin_site_url('website/index')}" ><span>{#manage#}</span></a></li>{/if}
      	{include file="common/sub_topnav.tpl"}
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['site_id']}
  {form_open(admin_site_url('website/edit'),'name="form1"')}
  {else}
  {form_open(admin_site_url('website/add'),'name="form1"')}
  {/if}
  	<input type="hidden" name="site_id" value="{$info['site_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="site_name">网站名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_name" name="site_name" value="{$info['site_name']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('site_name')}<span class="vatop rowform">推广网站名称</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="site_url">网站地址:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="site_url" name="site_url" value="{$info['site_url']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('site_url')}<span class="vatop rowform">推广网站链接地址 以 http:// 或者 https:// 开头的网站地址</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['site_sort']}" name="site_sort" class="txt"></td>
          <td class="vatop tips">{form_error('site_sort')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>SEO title</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['seo_title']}" name="seo_title" class="txt"></td>
          <td class="vatop tips">{form_error('seo_title')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>SEO keywords:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['seo_keywords']|escape}" name="seo_keywords" class="txt"></td>
          <td class="vatop tips">{form_error('seo_keywords')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>SEO Description:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['seo_description']}" name="seo_description" class="txt"></td>
          <td class="vatop tips">{form_error('seo_description')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="icp_number">ICP证书号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="icp_number" name="icp_number" value="{$info['icp_number']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面底部可以显示 ICP 备案信息，如果网站已备案，在此输入你的授权码，它将显示在前台页面底部，留空将显示全局设置中的ICP备案信息</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="statistics_code">第三方流量统计代码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><textarea name="statistics_code" rows="6" class="tarea" id="statistics_code">{$info['statistics_code']|escape}</textarea></td>
          <td class="vatop tips"><span class="vatop rowform">前台页面底部可以显示第三方统计,如果留空则使用全局设置中的统计代码</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          {if $action != 'detail'}<td colspan="2" ><input type="submit" name="submit" value="保存" class="msbtn"></td>{/if}
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}