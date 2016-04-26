{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>文章分类</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('article_class/category')}"><span>管理</span></a></li>
      	<li><a class="current"><span>{if $info['ac_id']}编辑{else}新增{/if}</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {if $info['ac_id']}
  {form_open(admin_site_url('article_class/edit'),'id="article_class_form"')}
  {else}
  {form_open(admin_site_url('article_class/add'),'id="article_class_form"')}
  {/if}
  	<input type="hidden" name="ac_id" value="{$info['ac_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="gc_name">分类名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['ac_name']|escape}" name="ac_name" id="ac_name" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('ac_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="ac_parent_id">上级分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="ac_parent_id" id="category">
	          <option value="">请选择...</option>
	          {foreach from=$list item=item}
	          <option {if $info['ac_parent_id'] == $item['ac_id']}selected{/if} value="{$item['ac_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['ac_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('ac_parent_id')}如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['ac_sort']}" name="ac_sort" id="ac_sort" class="txt"></td>
          <td class="vatop tips">{form_error('ac_sort')} 数字范围为0~255，数字越小越靠前</td>
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