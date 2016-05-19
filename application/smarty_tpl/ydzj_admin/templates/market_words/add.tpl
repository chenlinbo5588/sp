{include file="common/main_header.tpl"}
{config_load file="words.conf"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>{#title#}</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('market_words/index')}" ><span>{#manage#}</span></a></li>
      	{include file="common/sub_topnav.tpl"}
      	<li><a href="{admin_site_url('market_words/import')}"><span>导入</span></a></li>
      	<li><a href="{admin_site_url('market_words/export')}"><span>导出</span></a></li>
      </ul>
     </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['word_id']}
  {form_open(admin_site_url('market_words/edit'),'name="form1"')}
  {else}
  {form_open(admin_site_url('market_words/add'),'name="form1"')}
  {/if}
  	<input type="hidden" name="word_id" value="{$info['word_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="word_name">{#word_name#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="word_name" name="word_name" value="{$info['word_name']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('word_name')}<span class="vatop rowform">推广关键字</span></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="site_url">{#word_url#}:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input id="word_url" name="word_url" value="{$info['word_url']|escape}" class="txt" type="text" /></td>
          <td class="vatop tips">{form_error('word_url')}<span class="vatop rowform">推广链接 请输入http:// 或者 https:// 开头的URL地址 例如 </span><span class="tip-yellowsimple">http://s1.txcf188.com/#PC-gp-cs-xin-00001</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['word_sort']}" name="word_sort" class="txt"></td>
          <td class="vatop tips">{form_error('word_sort')}</td>
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