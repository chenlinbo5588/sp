{include file="common/main_header.tpl"}
  {if $info['color_id']}
  {form_open_multipart(admin_site_url('color/edit?id='|cat:$info['color_id']),'id="color_form"')}
  {else}
  {form_open_multipart(admin_site_url('color/add'),'id="color_form"')}
  {/if}
  	<input type="hidden" name="id" value="{$info['color_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="color_name">颜色名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['color_name']|escape}" name="color_name" id="color_name" class="txt"></td>
          <td class="vatop tips">{form_error('color_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label>排序:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{if $info['color_sort']}{$info['color_sort']}{else}255{/if}" name="color_sort" id="color_sort" class="txt"></td>
          <td class="vatop tips">{form_error('color_sort')} 数字范围为0~255，数字越小越靠前</td>
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