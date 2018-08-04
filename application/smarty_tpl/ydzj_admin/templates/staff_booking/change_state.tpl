{form_open(site_url($uri_string),'id="verifyForm"')}
	<input type="hidden" name="id" value="{$id}"/>
	<table class="table">
		<tbody>
			<tr>
				<td>请选择状态</td>
			</tr>
			<tr>
				<td>
				{foreach from=$bookingMeet key=key item=item}
					<label><input type="radio"  name="meetResult" value="{$item}"/><span>{$item|escape}</span></label>
				{/foreach}
				</td>
			</tr>
			<tr>
				<td>备注</td>
			</tr>
			<tr>
				<td>
					<textarea name="reason" style="width:100%;height:100px;"></textarea>
				</td>
			</tr>
			
			<tr>
			</tr>
		</tbody>
	</table>
	<input type="submit" class="msbtn" name="tijiao" value="提交"/>
</form>