{form_open(site_url($uri_string),'id="verifyForm"')}
	<input type="hidden" name="id" value="{$id}"/>
	<table class="table">
		<tbody>
			<tr>
				<td colspan="2"><label class="validation">备注:</label><label class="errtip" id="error_remark"></label></td>
			</tr>
			<tr>
				<td colspan="2" >
					<textarea name="remark" style="width:100%;height:100px;"></textarea>
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