{form_open(site_url($uri_string),'id="refundForm"')}
	<input type="hidden" name="id" value="{$info['id']}"/>
	{config_load file="order.conf"}
	<div id="tipDiv"></div>
	<table class="table tb-type1">
		<tbody>
			<tr>
	   		  <td>{#order_id#}: </td>
	          <td>{$info['order_id']}</td>      
       		</tr>
			<tr>
	   		  <td>{#order_typename#}: </td>
	          <td >{$info['order_typename']|escape}</td>      
       		</tr>
       		<tr>
	   		  <td>{#customer_name#}: </td>
	          <td >{$info['username']|escape}</td>      
       		</tr>
       		<tr>
	   		  <td>{#amount#}: </td>
	          <td>{$info['amount']/100}</td>
       		</tr>
       		<tr>
	   		  <td>{#refunded_amoun#}: </td>
	          <td>{$info['refund_amount']/100}</td>
       		</tr>
       		<tr>
	   		  <td>{#refund_cnt#}: </td>
	          <td>{$info['refund_cnt']}</td>
       		</tr>
       		<tr>
	   		  <td><label class="validation">{#refund_amount#}:</label></td>
	          <td>
	          	<input type="text" name="refund_amount" value="{$info['amount']/100}"/>
	          	<label class="errtip" id="error_refund_amount"></label>
	          </td>
       		</tr>
        	<tr class="noborder">
        		<td><label class="validation">退款原因:</label></td>
		        <td>
		          	<select name="reason">
			          <option value="">请选择...</option>
			          {foreach from=$reasonList item=info}
			          <option  value="{$info['show_name']}">{$info['show_name']}</option>
			          {/foreach}
			        </select>
			        <label class="errtip" id="error_reason"></label>
		         </td>
		    </tr>
			<tr>
				<td><label class="validation">备注:</label></td>
				<td>
					<label class="errtip" id="error_remark"></label>
					<textarea name="remark" style="width:100%;height:100px;"></textarea>
				</td>
			</tr>
			<tr>
				<td><label class="validation">验证码</label></td>
				<td>
					<input name="auth_code" type="text" class="txt-short" id="captcha" placeholder="输入验证" title="验证码为4个字符" autocomplete="off" value="" >
					<label class="errtip" id="error_auth_code"></label>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><div class="code-img" id="authImg"></div></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" class="msbtn" name="verifyOK" value="创建退款单 "/>  
				</td>
			</tr>
		</tbody>
	</table>
</form>

<script type="text/javascript">
	$(function(){
	    var imgCode1 = $.fn.imageCode({ wrapId: "#authImg", captchaUrl : "{site_url('captcha/index')}" });
	    setTimeout(imgCode1.refreshImg,500);
	});
</script>
