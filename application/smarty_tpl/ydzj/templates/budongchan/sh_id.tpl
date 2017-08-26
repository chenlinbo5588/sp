{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
    {if $uploadEnable}
    {include file="common/swfupload.tpl"}
    {/if}
    <div id="bdcWrap">
    	{$stepHTML}
	    {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="addForm"')}
		<input type="hidden" name="id" value="{$info['id']}"/>
			<fieldset>
	   	   		<legend>{$shName}-不动产业务登记</legend>
		        <table class="fulltable formtable">
		        	<tbody>
		        		{include file="./basic_info.tpl"}
		        		{include file="./file_list.tpl"}
		        		{if $info['cur_uid']}
		        			{if '复审' == $shName}
		        		<tr>
		        			<td class="vtop"><label class="required">{#bdc_no#}</label></td>
		        			<td>
		        				<div class="warning">不动产单元号，如有多个，每一行输入28位不动产号码</div>
		        				<textarea class="w100pre" style="height:180px;" name="bdc_no">{$info['bdc_no']|escape}</textarea>
		        				<span class="errtip" id="error_bdc_no">{form_error('bdc_no')}</span>
		        			</td>
		        		</tr>
		        			{/if}
				   		<tr>
		        			<td class="vtop"><label class="required">{$shName}{#cm_remark#}</label></td>
		        			<td>
		        				<textarea class="w100pre" style="height:180px;" name="remark">{if empty($info['statusLog'][$shName]['remark'])}经查资料齐全,准予通过{else}{$info['statusLog'][$shName]['remark']|escape}{/if}</textarea>
		        				<span class="errtip" id="error_remark">{form_error('remark')}</span>
		        			</td>
		        		</tr>
		        		<tr id="reason">
		        			<td class="vtop"><label>退回原因</label></td>
		        			<td>
		        				<textarea class="w100pre" style="height:180px;" name="reason">{$info['statusLog'][$shName]['reason']|escape}</textarea>
		        				<span class="errtip" id="error_reason">{form_error('reason')}</span>
		        			</td>
		        		</tr>
		        		{/if}
		        		
		        		{if $orgList}
		        		{include file="./sender_list.tpl"}
				   		{/if}
		        	</tbody>
		        </table>
		        <div class="fixbar">
					{if $info['cur_dept_id'] == $profile['basic']['dept_id']}
    					{if empty($info['cur_uid']) && in_array($info['status_name'] ,array('初审','复审'))}
    						<input type="submit" name="tijiao" class="master_btn" value="受理"/>
    					{else}
    					
    					{if $showPassButton}
    					<input type="submit" name="tijiao" class="master_btn" value="通过{$shName}"/>
    					{/if}
    					<input type="submit" name="tijiao" class="master_btn" value="退回"/>
    					{/if}
    				{else}
    					{if empty($info['cur_uid']) && $info['status_name'] == '复审'}
    						<input type="submit" name="tijiao" class="master_btn" value="撤销提交"/>
    					{/if}
					{/if}
					<a href="{site_url($uri_string)}" class="master_btn grayed">返回</a>
				</div>
		      </fieldset>
	    </form>
    </div>
    {if $uploadEnable}
    {include file="./file_event.tpl"}
    {/if}
    <script>
    	$(function(){
	    	$("input[name=to_dept]").bind('click',function(){
	    		var checked = $(this).prop('checked');
	    		$(".senderList li").removeClass('selected');
	    		if(checked){
	    			$(this).parents('li').addClass('selected');
	    		}
	    	});
	    	
	    	if($("input[name=to_dept]").size() == 1){
	    		$(".senderList li").addClass('selected');
	    		$("input[name=to_dept]:eq(0)").prop('checked',true);
	    	}
	    	
	    	$.loadingbar({ urls: [ new RegExp('{site_url('budongchan/deleteFile')}'), new RegExp('{site_url('budongchan/cs')}') ],text:"操作中,请稍后..." ,container : '#bdcWrap' });
	    	
	    	bindDeleteEvent({ linkClass:'a.deleteFile',rowPrefix: '#file' });
	    	bindAjaxSubmit("#addForm");
    	});
    </script>
{include file="common/my_footer.tpl"}

