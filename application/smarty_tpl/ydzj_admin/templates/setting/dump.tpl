{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>站点设置</h3>
      <ul class="tab-base">
      	{if isset($permission['admin/setting/base'])}<li><a href="{admin_site_url('setting/base')}" ><span>站点设置</span></a></li>{/if}
      	{if isset($permission['admin/setting/dump'])}<li><a  class="current"><span>防灌水设置</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {form_open(admin_site_url('setting/dump'),'name="settingForm"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label>允许游客咨询:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
          	<label for="guest_comment_enable" {if $currentSetting['guest_comment']['value'] == '1'}class="cb-enable selected"{else}class="cb-enable"{/if} title="是"><span>是</span></label>
            <label for="guest_comment_disabled" {if $currentSetting['guest_comment']['value'] == '1'}class="cb-disable "{else}class="cb-disable selected"{/if} title="否"><span>否</span></label>
            <input id="guest_comment_enable" name="guest_comment" {if $currentSetting['guest_comment']['value'] == '1'}checked="checked"{/if} value="1" type="radio">
            <input id="guest_comment_disabled" name="guest_comment" {if $currentSetting['guest_comment']['value'] != '1'}checked="checked"{/if} value="0" type="radio"></td>
          <td class="vatop tips">允许游客在商品的详细展示页面，对当前商品进行咨询</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required">使用验证码:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><ul class="nofloat">
              <li>
                <input type="checkbox" value="1" name="captcha_status_login" id="captcha_status1" {if $currentSetting['captcha_status_login']['value']}checked="checked"{/if} />
                <label for="captcha_status1">前台登录</label>
              </li>
              <li>
                <input type="checkbox" value="1" name="captcha_status_register" id="captcha_status2" {if $currentSetting['captcha_status_register']['value']}checked="checked"{/if} />
                <label for="captcha_status2">前台注册</label>
              </li>
              <li>
                <input type="checkbox" value="1" name="captcha_status_goodsqa" id="captcha_status3" {if $currentSetting['captcha_status_goodsqa']['value']}checked="checked"{/if} />
                <label for="captcha_status3">商品咨询</label>
              </li>
            </ul></td>
          <td class="vatop tips" >&nbsp;</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2" ><input type="submit" name="submit" value="保存" class="msbtn"></td>
        </tr>
      </tfoot>
    </table>
  </form>

{include file="common/main_footer.tpl"}