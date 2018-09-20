  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="ajaxForm"')}
	<input type="hidden" name="id" value="{$id}"/>
	<table class="table tb-type2">
		<tbody>
		   
		    <tr class="noborder">
		      <td colspan="2" class="required">修改金额</td>
		    </tr>
		    <tr class="noborder">
			  <td colspan="2" class="vatop rowform"><input type="text"  name="modify_money"  class="txt"></td>
			</tr>
		    
		</tbody>
	</table>
	<input type="submit" class="msbtn" name="tijiao" value="提交"/>
</form>