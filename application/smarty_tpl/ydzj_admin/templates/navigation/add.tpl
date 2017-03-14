{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>导航管理</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('navigation/category')}"><span>管理</span></a></li>
      	<li><a {if empty($info['id'])}class="current"{/if} href="{admin_site_url('navigation/add')}"><span>新增</span></a></li>
      	{if $info['id']}<li><a class="current"><span>编辑</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['id']}
  {form_open(admin_site_url('navigation/edit'),'id="article_class_form"')}
  {else}
  {form_open(admin_site_url('navigation/add'),'id="article_class_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name_cn">导航中文名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name_cn']|escape}" name="name_cn" id="name_cn" class="txt"></td>
          <td class="vatop tips">{form_error('name_cn')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="name_en">导航英文名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name_en']|escape}" name="name_en" id="name_en" class="txt"></td>
          <td class="vatop tips">{form_error('name_en')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="pid">上级导航:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="pid">
	          <option value="">请选择...</option>
	          {foreach from=$list item=item}
	          <option {if $info['pid'] == $item['id']}selected{/if} value="{$item['id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['name_cn']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('pid')}如果选择父级导航，那么新增的分类则为被选择上级导航的子导航</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="url_cn">导航链接(中文版):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['url_cn']|escape}" name="url_cn" id="url_cn" class="txt"></td>
          <td class="vatop tips">{form_error('url_cn')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="url_en">导航链接(英文版):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['url_en']|escape}" name="url_en" id="url_en" class="txt">{*<input type="button" name="thesame" value="和中文版链接一致"/>*}</td>
          <td class="vatop tips">{form_error('url_en')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="url_en">跳转方式:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><select name="jump_type">
            <option value="0" {if $info['jump_type'] == 0}selected{/if}>当前窗口</option>
            <option value="1" {if $info['jump_type'] == 1}selected{/if}>新窗口</option>
          </select></td>
          <td class="vatop tips">{form_error('jump_type')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['displayorder']}" name="displayorder" id="displayorder" class="txt"></td>
          <td class="vatop tips">{form_error('displayorder')} 数字范围为0~255，数字越小越靠前</td>
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