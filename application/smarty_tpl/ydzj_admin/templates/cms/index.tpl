{include file="common/main_header_navs.tpl"}
  {form_open(site_url($uri_string),'id="cms_form"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>CMS开关：</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
          	<label for="cms_isuse_1" class="cb-enable{if $currentSetting['cms_isuse']['value'] == 1} selected{/if}" title="开启"><span>开启</span></label>
            <label for="cms_isuse_0" class="cb-disable{if $currentSetting['cms_isuse']['value'] == 0} selected{/if}" title="关闭"><span>关闭</span></label>
            <input type="radio" id="cms_isuse_1" name="cms_isuse" value="1" {if $currentSetting['cms_isuse']['value'] == 1}checked=checked{/if}>
            <input type="radio" id="cms_isuse_0" name="cms_isuse" value="0" {if $currentSetting['cms_isuse']['value'] == 0}checked=checked{/if}>
          </td>
          <td class="vatop tips">{form_error('cms_isuse')}</td>
        </tr>
        <!-- 投稿需要审核 -->
        <tr>
          <td colspan="2" class="required"><label>投稿需要审核：</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
          	<label for="cms_submit_verify_flag_1" class="cb-enable{if $currentSetting['cms_submit_verify_flag']['value'] == 1} selected{/if}" title="开启"><span>开启</span></label>
            <label for="cms_submit_verify_flag_0" class="cb-disable{if $currentSetting['cms_submit_verify_flag']['value'] == 0} selected{/if}" title="关闭"><span>关闭</span></label>
            <input type="radio" id="cms_submit_verify_flag_1" name="cms_submit_verify_flag" value="1" {if $currentSetting['cms_submit_verify_flag']['value'] == 1}checked=checked{/if}>
            <input type="radio" id="cms_submit_verify_flag_0" name="cms_submit_verify_flag" value="0" {if $currentSetting['cms_submit_verify_flag']['value'] == 0}checked=checked{/if}></td>
          <td class="vatop tips">{form_error('cms_submit_verify_flag')} 开启后用户投稿后需要管理员审核</td>
        </tr>
        <!-- 允许评论 -->
        <tr>
          <td colspan="2" class="required"><label>允许评论：</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
          	<label for="cms_comment_flag_1" class="cb-enable{if $currentSetting['cms_comment_flag']['value'] == 1} selected{/if}" title="开启"><span>开启</span></label>
            <label for="cms_comment_flag_0" class="cb-disable{if $currentSetting['cms_comment_flag']['value'] == 0} selected{/if}" title="关闭"><span>关闭</span></label>
            <input type="radio" id="cms_comment_flag_1" name="cms_comment_flag" value="1" {if $currentSetting['cms_comment_flag']['value'] == 1}checked=checked{/if}>
            <input type="radio" id="cms_comment_flag_0" name="cms_comment_flag" value="0" {if $currentSetting['cms_comment_flag']['value'] == 0}checked=checked{/if}></td>
          <td class="vatop tips">{form_error('cms_comment_flag')} 评论全局开关</td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label for="cms_seo_title">SEO标题：</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$currentSetting['cms_seo_title']['value']|escape}" name="cms_seo_title" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label for="cms_seo_keywords">SEO关键字：</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$currentSetting['cms_seo_keywords']['value']|escape}" name="cms_seo_keywords" class="txt"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="cms_seo_description">SEO描述：</label></td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <textarea name="cms_seo_description" class="tarea" rows="6">{$currentSetting['cms_seo_description']['value']|escape}</textarea>
            </td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}