{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>商品分类</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('goods/category')}"><span>管理</span></a></li>
      	<li><a class="current"><span>新增</span></a></li>
      	<li><a href="{admin_site_url('goods/category_export')}"><span>导出</span></a></li>
      	<li><a href="{admin_site_url('goods/category_import')}"><span>导入</span></a></li>
      	<li><a href="{admin_site_url('goods/category_tag')}"><span>TAG管理</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  {form_open_multipart(admin_site_url('goods/category_add'),'id="goods_class_form"')}
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="gc_name">分类名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="" name="gc_name" id="gc_name" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('gc_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="parent_id">上级分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="category" id="category">
	          <option value="">请选择...</option>
	          {foreach from=$list item=item}
	          {if $item['level'] < 2}
	          <option value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['gc_name']}</option>
	          {/if}
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="0" name="gc_sort" id="gc_sort" class="txt"></td>
          <td class="vatop tips">数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span>提交</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
{include file="common/main_footer.tpl"}