{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品分类</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('goods_class/category')}"><span>管理</span></a></li>
      	<li><a class="current"><span>新增</span></a></li>
      	<li><a href="{admin_site_url('goods_class/export')}"><span>导出</span></a></li>
      	<li><a href="{admin_site_url('goods_class/import')}"><span>导入</span></a></li>
      	<li><a href="{admin_site_url('goods_class/tag')}"><span>TAG管理</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  
  {if $info['gc_id']}
  {form_open(admin_site_url('goods_class/edit'),'id="goods_class_form"')}
  {else}
  {form_open(admin_site_url('goods_class/add'),'id="goods_class_form"')}
  {/if}
  	<input type="hidden" name="gc_id" value="{$info['gc_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="gc_name">分类名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['gc_name']|escape}" name="gc_name" id="gc_name" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('gc_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_parent_id">上级分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="gc_parent_id" id="category">
	          <option value="">请选择...</option>
	          {foreach from=$list item=item}
	          <option {if $info['gc_parent_id'] == $item['gc_id']}selected{/if} value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['gc_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('gc_parent_id')}如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['gc_sort']}" name="gc_sort" id="gc_sort" class="txt"></td>
          <td class="vatop tips">数字范围为0~255，数字越小越靠前</td>
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