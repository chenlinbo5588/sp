{form_open(site_url($uri_string),'id="ajaxForm"')}
	<input type="hidden" name="id" value="{$id}"/>
	<table class="table">
		<tbody>
			<tr>
				<td>请选择发送方式</td>
			</tr>
			<tr>
				<td><label><input type="radio"  name="verifyOK" id="verifyOK" value="{$item}"/><span>短信提醒</span></label>
				<label><input type="radio"  name="verifyOK" id="verifyOK" value="{$item}"/><span>微信提醒</span></label></td>
			</tr>
			<tr>
				<td>提醒信息</td>
			</tr>
			<tr>
				<td>
					
					<textarea name="reason" style="width:100%;height:100px;" ></textarea>
				</td>
			</tr>
			
			<tr>
			</tr>
		</tbody>
	</table>
	<input type="submit" class="msbtn" name="tijiao" value="提交"/>

</form>