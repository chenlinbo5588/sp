{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
    {config_load file="budongchan.conf"}
    <div id="bdcWrap">
    	{$stepHTML}
	    {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="addForm"')}
		<input type="hidden" name="id" value="{$info['id']}"/>
			<fieldset>
	   	   		<legend>{if $info['id']}编辑{else}添加{/if}-不动产业务登记</legend>
		        <table class="fulltable formtable">
		        	<tbody>
		        		{if $info['lsno']}
		        		<tr>
		        			<td class="w120">流水号</td>
		        			<td><span class="lsno">{$info['lsno']}</span></td>
		        		</tr>
		        		{/if}
		        		<tr>
		        			<td class="w120"><label class="required">{#bdc_name#}</label></td>
		        			<td>{$info['name']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_address#}</label></td>
		        			<td>{$info['address']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_contact_name#}</label></td>
		        			<td>{$info['contactor']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_mobile#}</label></td>
		        			<td>{$info['mobile']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_id_type#}</label></td>
		        			<td>
		        				{foreach from=$id_type item=item key=key}
		        				<label {if $info['id_type'] == $item || $info['id_type'] == $key}{else}style="display:none;"{/if}>{$item}</label>
		        				{/foreach}
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_id_no#}</label></td>
		        			<td>{$info['id_no']|escape}</td>
		        		</tr>
		        		<tr>
		        			<td class="vtop"><label>{#cm_remark#}</label></td>
		        			<td>{$info['remark']|escape}</td>
		        		</tr>
		        		<tr>
				   			<td class="vtop">附件列表</td>
				   			<td>
				   				<div class="field-box">
		                            <div id="UploaderProgress_1"></div>
		                            <div id="UploaderFeedBack_1"></div>
		                        </div>
				       			{foreach from=$fileList item=deptFileList key=key}
				       			<table id="ftb{$key}">
		                        	<thead>
		                        		<tr>
		                        			<th colspan="4">上传单位:{$deptList[$key]['name']}</th>
		                        		</tr>
		                        		<tr>
		                        			<th>文件名称</th>
		                        			<th>上传人</th>
		                        			<th>上传时间</th>
		                        		</tr>
		                        	</thead>
		                        	<tbody>
				       				{foreach from=$deptFileList item=subitem}
						       			<tr id="file{$subitem['id']}">
						       				<td>{if $info['id']}<a href="{site_url('budongchan/getfile?id='|cat:$info['id']|cat:'&fid='|cat:$subitem['id'])}">【{$subitem['orig_name']|escape}】</a>{else}<strong>{$subitem['orig_name']|escape}</strong>{/if}</td>
						       				<td>{$subitem['username']|escape}</td>
						       				<td>{$subitem['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
						       			</tr>
				       				{/foreach}
				       				</tbody>
				       			</table>
				       			{/foreach}
				   			</td>
				   		</tr>
				   		{if $orgList}
				   		<tr>
				   			<td>发送至</td>
				   			<td>
				   				<ul class="senderList">
				   					{foreach from=$orgList item=item}
				   					<li><label><input type="radio" name="to_dept" value="{$item['id']}"/>&nbsp;{$item['name']|escape}</label></li>
				   					{/foreach}
				   				</ul>
				   			</td>
				   		</tr>
				   		{/if}
		        	</tbody>
		        	<tfoot>
		        		<tr>
		        			<td></td>
		        			<td>
		        				<div class="fixbar">
		        					{if $showSenderBtn}
		        					<input type="submit" name="tijiao" class="master_btn" value="发送"/>
		        					{/if}
		        					<a href="{site_url('budongchan/edit?id='|cat:$info['id'])}" class="master_btn grayed">返回</a>
		        				</div>
		        			</td>
		        		</tr>
		        	</tfoot>
		        </table>
		      </fieldset>
	    </form>
    </div>
    <script>
	   
    	$(function(){
	    	$("input[name=to_dept]").bind('click',function(){
	    		var checked = $(this).prop('checked');
	    		
	    		$(".senderList li").removeClass('selected');
	    		
	    		if(checked){
	    			$(this).parents('li').addClass('selected');
	    		}
	    	});
	    	
	    	$.loadingbar({ urls: [ new RegExp('{site_url('budongchan/nextstep')}') ],text:"操作中,请稍后..." ,container : '#bdcWrap' });
	    	bindAjaxSubmit("#addForm");
    	})
    </script>
{include file="common/my_footer.tpl"}

