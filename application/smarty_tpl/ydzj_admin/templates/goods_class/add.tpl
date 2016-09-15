{include file="common/main_header.tpl"}
  {if $info['gc_id']}
  {form_open_multipart(admin_site_url('goods_class/edit?gc_id='|cat:$info['gc_id']),'id="goods_class_form"')}
  {else}
  {form_open_multipart(admin_site_url('goods_class/add'),'id="goods_class_form"')}
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
          <td colspan="2" class="required"><label>分类图片(用于在列表页显示，如果不传则使用默认封面):</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input type="hidden" name="old_pic" value="{if $info['gc_pic']}{$info['gc_pic']}{/if}"/>
            <span class="type-file-show">
                <img class="show_image" src="{resource_url('img/preview.png')}">
                <div class="type-file-preview">{if !empty($info['gc_pic'])}<img src="{resource_url($info['gc_pic'])}">{/if}</div>
            </span>
            <span class="type-file-box"><input type='text' name='gc_pic' value="{if $info['gc_pic']}{$info['gc_pic']}{/if}" id='gc_pic' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />
            <input name="gc_logo" type="file" class="type-file-file" id="gc_logo" size="30" hidefocus="true" nc_type="change_gc_logo">
            </span></td>
          <td class="vatop tips"><span class="vatop rowform">商品分类图片LOGO尺寸要求宽度为120像素，高度为120像素、比例为1:1的图片；支持格式jpg</span></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['gc_sort']}" name="gc_sort" id="gc_sort" class="txt"></td>
          <td class="vatop tips">{form_error('gc_sort')} 数字范围为0~255，数字越小越靠前</td>
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
        $("#gc_logo").change(function(){
            $("#gc_pic").val($(this).val());
        });
    })
  </script>
{include file="common/main_footer.tpl"}