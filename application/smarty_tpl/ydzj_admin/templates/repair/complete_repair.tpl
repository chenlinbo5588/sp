  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="ajaxForm"')}
	<input type="hidden" name="id" value="{$id}"/>
	<table class="table">
		<tbody>
			<tr>
				<td>请输入人员信息</td>
			</tr>
		    <tr class="noborder">
		      <td colspan="2" class="required"><label class="validation" for="worker_name">{#worker_name#}:</label><label id="error_worker_name"></label></td>
		    </tr>
		    <tr class="noborder">
		      <td class="vatop rowform"><input type="text" value="{$info['worker_name']|escape}" name="worker_name" id="worker_name" class="txt"></td>
		    </tr>
		    <tr class="noborder">
		       <td colspan="2" class="required"><label class="validation" for="worker_mobile">{#worker_mobile#}:</label><label id="error_worker_mobile"></label></td>
		    </tr>
		    <tr class="noborder">
			  <td class="vatop rowform"><input type="text" value="{$info['worker_mobile']|escape}" name="worker_mobile" id="worker_mobile" class="txt"></td>
			</tr>
		    
		</tbody>
	</table>
	<input type="submit" class="msbtn" name="tijiao" value="提交"/>

</form>
