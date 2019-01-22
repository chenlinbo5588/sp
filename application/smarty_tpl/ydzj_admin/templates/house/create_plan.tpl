  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="ajaxForm"')}
	<input type="hidden" name="id" value="{$id}"/>
	<table class="table tb-type2">
		<tbody>
        <tr>
        <td>
	      	<ul class="ulListStyle1 clearfix">
	      	<label><input type="radio"  name="year" value="{$year}"/><span>按{$year}年单价生成收费计划</span></label>
	      	<label><input type="radio"  name="year" value="{$year+1}"/><span>创建{$year+1}年单价生成收费计划</span></label>
 			</ul>
 		</td>
 		</tr>
		</tbody>
	</table>
	<input type="submit" class="msbtn" name="tijiao" value="确定"/>
</form>