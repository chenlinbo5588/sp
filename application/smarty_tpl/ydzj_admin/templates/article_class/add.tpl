{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>文章分类</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('article_class/category')}"><span>管理</span></a></li>
      	<li><a {if empty($info['ac_id'])}class="current"{/if} href="{admin_site_url('article_class/add')}"><span>新增</span></a></li>
      	{if $info['ac_id']}<li><a class="current"><span>编辑</span></a></li>{/if}
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
          <td colspan="2" class="required"><label class="validation" for="name">文章分类中文名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['name']|escape}" name="name" id="name" class="txt"></td>
          <td class="vatop tips">{form_error('name')}</td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="ac_code">文章分类目录代码:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['ac_code']|escape}" name="ac_code" id="ac_code" class="txt"></td>
          <td class="vatop tips">{form_error('ac_code')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="ac_parent_id">上级分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="ac_parent_id" id="category">
	          <option value="">请选择...</option>
	          {foreach from=$list item=item}
	          <option {if $info['ac_parent_id'] == $item['ac_id']}selected{/if} value="{$item['ac_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['name']}</option>
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