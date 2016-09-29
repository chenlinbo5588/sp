{include file="common/main_header.tpl"}
  {form_open(admin_site_url('goods_class/tag_edit'),'id="goods_class_form"')}
  	<input type="hidden" name="gc_tag_id" value="{$info['gc_tag_id']}"/>
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label class="validation" for="gc_tag_name">分类TAG名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['gc_tag_name']}" name="gc_tag_name" id="gc_tag_name" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('gc_tag_name')}</td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="gc_tag_value">分类TAG值:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" value="{$info['gc_tag_value']}" name="gc_tag_value" id="gc_tag_value" maxlength="20" class="txt"></td>
          <td class="vatop tips">{form_error('gc_tag_value')}</td>
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