{form_open(site_url($uri_string),'id="verifyForm"')}
	<input type="hidden" name="id" value="{$id}"/>
	<table class="table">
		<tbody>
			<tr>
				<td>备注</td>
			</tr>
			<tr>
				<td>
					<textarea name="reason" style="width:100%;height:100px;"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" class="msbtn" name="verifyOK" value="审核通过"/>  
					<input type="submit" class="salvebtn" name="verifyFail" value="退回"/>  
				</td>
			</tr>
		</tbody>
	</table>
</form>