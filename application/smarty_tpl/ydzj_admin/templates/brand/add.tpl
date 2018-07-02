{include file="common/main_header.tpl"}
  <div class="fixed-bar">
    <div class="item-title">
      <h3>品牌</h3>
      <ul class="tab-base">
      	<li><a href="{admin_site_url('brand/index')}"><span>管理</span></a></li>
      	<li><a {if empty($info['brand_id'])}class="current"{/if} href="{admin_site_url('brand/add')}"><span>新增</span></a></li>
      	{if $info['brand_id']}<li><a class="current"><span>编辑</span></a></li>{/if}
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="feedback">{$feedback}</div>
  
  {if $info['brand_id']}
  {form_open_multipart(admin_site_url('brand/edit'),'id="brand_form"')}
  {else}
  {form_open_multipart(admin_site_url('brand/add'),'id="brand_form"')}
  {/if}
  	<input type="hidden" name="brand_id" value="{$info['brand_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="brand_name">品牌名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['brand_name']|escape}" name="brand_name" id="brand_name" class="txt"></td>
          <td class="vatop tips">{form_error('brand_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="goodsClassId">所属分类:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<select name="class_id" id="goodsClassId">
	          <option value="">请选择...</option>
	          {foreach from=$list item=item}
	          <option {if $info['class_id'] == $item['gc_id']}selected{/if} value="{$item['gc_id']}">{str_repeat('......',$item['level'])}{$item['level']+1} {$item['gc_name']}</option>
	          {/foreach}
	        </select>
          </td>
          <td class="vatop tips">{form_error('class_id')}如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</td>
        </tr>
        <tr>
          <td colspan="2" class="required">品牌图片标识: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input type="hidden" name="old_pic" value="{if $info['brand_pic']}{$info['brand_pic']}{/if}"/>
          	<span class="type-file-show">
          		<img class="show_image" src="{resource_url('img/preview.png')}">
          		<div class="type-file-preview">{if !empty($info['brand_pic'])}<img src="{resource_url($info['brand_pic'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='brand_pic' value="{if $info['brand_pic']}{$info['brand_pic']}{/if}" id='brand_pic' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="brand_logo" type="file" class="type-file-file" id="brand_logo" size="30" hidefocus="true" nc_type="change_brand_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">品牌LOGO尺寸要求宽度为150像素，高度为50像素、比例为3:1的图片；支持格式gif,jpg,png</span></td>
        </tr>
        <tr>
          <td colspan="2">是否推荐: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff"><label for="brand_recommend1" {if $info['brand_recommend']}class="cb-enable selected"{else}class="cb-enable"{/if}><span>是</span></label>
            <label for="brand_recommend0" {if $info['brand_recommend']}class="cb-disable"{else}class="cb-disable selected"{/if}><span>否</span></label>
            <input id="brand_recommend1" name="brand_recommend" {if $info['brand_recommend']}checked{/if} value="1" type="radio">
            <input id="brand_recommend0" name="brand_recommend" {if $info['brand_recommend'] == 0}checked{/if} value="0" type="radio"></td>
          <td class="vatop tips">{form_error('brand_recommend')} 选择被推荐的图片将在所有品牌列表页“推荐品牌”位置展现。</td>
        </tr>
        <tr>
          <td colspan="2"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['brand_sort']}{$info['brand_sort']}{else}255{/if}" name="brand_sort" id="brand_sort" class="txt"></td>
          <td class="vatop tips">{form_error('brand_sort')} 数字范围为0~255，数字越小越靠前</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"><input type="submit" name="submit" value="保存" class="msbtn"/></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <script type="text/javascript">
	$(function(){
		$("#brand_logo").change(function(){
			$("#brand_pic").val($(this).val());
		});
	})
 </script>
{include file="common/main_footer.tpl"}