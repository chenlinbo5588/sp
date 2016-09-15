{include file="common/main_header.tpl"}
  {form_open(admin_site_url('color/index'),'id="formSearch"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
	 <table class="tb-type1 noborder search">
	    <tbody>
	        <tr>
	          <th><label for="search_color_name">颜色名称</label></th>
	          <td><input class="txt" name="search_color_name" id="search_color_name" value="{$smarty.post['search_color_name']|escape}" type="text"></td>
	          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
	        </tr>
	    </tbody>
	  </table>
	  <ul class="colorlist">
	  {foreach from=$list item=item}
	    <li><a href="{admin_site_url('color/edit?id='|cat:$item['color_id'])}">{$item['color_name']|escape}</a></li>
      {/foreach}
      </ul>
  </form>
<script>
$(function(){
    bindDeleteEvent();
    bindOnOffEvent();
});
</script>
{include file="common/main_footer.tpl"}