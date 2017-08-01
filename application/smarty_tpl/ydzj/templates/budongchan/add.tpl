{include file="common/my_header.tpl"}
	{config_load file="common.conf"}
    {config_load file="budongchan.conf"}
    {include file="common/swfupload.tpl"}
    <div id="bdcWrap">
    	{$stepHTML}
	    {if $info['id']}
	    {form_open(site_url($uri_string|cat:'?id='|cat:$info['id']),'id="addForm"')}
		<input type="hidden" name="id" value="{$info['id']}"/>
		{else}
		{form_open(site_url($uri_string),'id="addForm"')}
		{/if}
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
		        			<td>
		        				<input type="text" class="w50pre" name="name" value="{$info['name']|escape}"/>
		        				<span class="errtip" id="error_name">{form_error('name')}</span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_address#}</label></td>
		        			<td>
		        				<input type="text" class="w50pre" name="address" value="{$info['address']|escape}" placeholder="如：古塘街道新城大道北路1122号"/>
		        				<span class="errtip" id="error_address">{form_error('address')}</span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_contact_name#}</label></td>
		        			<td>
		        				<input type="text" name="contactor" value="{$info['contactor']|escape}" placeholder="如：张三"/>
		        				<span class="errtip" id="error_contactor" class="tip">如：张三</span>&nbsp;{form_error('contactor')}
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_mobile#}</label></td>
		        			<td>
		        				<input type="text" name="mobile" value="{$info['mobile']|escape}"/>
		        				<span class="errtip" id="error_mobile">{form_error('mobile')}</span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_id_type#}</label></td>
		        			<td>
		        				{foreach from=$id_type item=item key=key}
		        				<label><input type="radio" value="{$key}" name="id_type" {if $info['id_type'] == $item || $info['id_type'] == $key}checked{/if}/>{$item}</label>
		        				{/foreach}
		        				<span class="errtip" id="error_id_type"></span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td><label class="required">{#cm_id_no#}</label></td>
		        			<td>
		        				<input type="text" class="w50pre" name="id_no" value="{$info['id_no']|escape}"/>
		        				<span class="errtip" id="error_id_no">{form_error('id_no')}</span>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td class="vtop"><label>{#cm_remark#}</label></td>
		        			<td>
		        				<textarea class="w50pre" style="height:180px;" name="remark">{$info['remark']|escape}</textarea>
		        				<span class="errtip" id="error_remark">{form_error('remark')}</span>
		        			</td>
		        		</tr>
		        		{if '新增登记' == $info['status_name']}
		        		<tr>
		        			<td>添加附件</td>
				   			<td>
				   				<div>
		                            <span id="UploaderPlaceholder_1"></span>
		                            <span class="Uploader" data-url="{site_url('budongchan/addfile')}"  data-allowsize="1Mb" data-allowfile="*.jpg" ></span>
		                            <span class="hightlight">请选择JPG格式扫描文件，文件大小在1M以内</span>
		                        </div>
				   			</td>
		        		</tr>
		        		{/if}
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
		                        			<th>操作</th>
		                        			<th>文件名称</th>
		                        			<th>上传人</th>
		                        			<th>上传时间</th>
		                        		</tr>
		                        	</thead>
		                        	<tbody>
				       				{foreach from=$deptFileList item=subitem}
						       			<tr id="file{$subitem['id']}">
						       				<td><input type="hidden" name="file_id[]" value="{$subitem['id']}"/>{if $profile['basic']['dept_id'] == $key}<a class="deleteFile" data-id="{$subitem['id']}" href="javascript:void(0);" data-url="{site_url('budongchan/deleteFile?bdc_id='|cat:$info['id'])}" data-title="{$subitem['orig_name']|escape}">删除</a>{/if}</td>
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
		        					{if empty($orgList)}
		        					<input type="submit" name="tijiao" class="master_btn" value="保存"/>
		        					{/if}
		        					{if $showNextUrl}
		        					<a href="{site_url('budongchan/nextstep?id='|cat:$info['id'])}" class="master_btn">下一步</a>
		        					{/if}
		        					<a href="{site_url('budongchan/index')}" class="master_btn grayed">返回</a>
		        				</div>
		        			</td>
		        		</tr>
		        	</tfoot>
		        </table>
		      </fieldset>
	    </form>
    </div>
    <script>
    	function add_uploadedfile(file_data)
	  	{
	  		var delLink = '<a class="deleteFile" data-id="' + file_data.id + '" href="javascript:void(0);" data-url="{site_url('budongchan/deleteFile?bdc_id='|cat:$info['id'])}" data-title="' + file_data.orig_name + '">删除</a>';
	  		var newItem = '<tr id="file' + file_data.id + '"><td><input type="hidden" name="file_id[]" value="' + file_data.id + '"/>' + delLink + '</td><td><strong>' + file_data.orig_name + '</strong></td><td>' + file_data.username + '</td><td>' + file_data.gmt_create + '</td></tr>';
		    $('#ftb{$profile['basic']['dept_id']} tbody').prepend(newItem);
	   	}
	   
    	$(function(){
	    	$(".Uploader").each(function(index){
	            var handler = {
	                success:function(file,serverData){
	                    try {
	                        var progress = new FileProgress(file, this.customSettings.progressTarget);
	                        progress.setComplete();
	                        progress.setStatus("Complete.");
	                        progress.toggleCancel(false);
	
	                        if(typeof(this.customSettings.callback) != "undefined"){
	                            this.customSettings.callback(file,serverData);
	                        }
	                        
	                        var response = $.parseJSON(serverData);
	                        
	                        if(response.data.error == 0){
	                        	add_uploadedfile(response.data);
	                        }
	
	                    } catch (ex) {
	                        this.debug(ex);
	                    }
	                }
	            };
	
	            createSwfUpload(index + 1,$(this).attr("data-url"),{ formhash: formhash, lsno: "{$info['lsno']}",bdc_id: "{$info['id']}" },$(this).attr("data-allowsize"),$(this).attr("data-allowfile"),handler);
	        });
	    	
	    	$("input[name=to_dept]").bind('click',function(){
	    		var checked = $(this).prop('checked');
	    		
	    		$(".senderList li").removeClass('selected');
	    		
	    		if(checked){
	    			$(this).parents('li').addClass('selected');
	    		}
	    	});
	    	
	    	$.loadingbar({ urls: [ new RegExp('{site_url('budongchan/deleteFile')}'), new RegExp('{site_url('budongchan/add')}'), new RegExp('{site_url('budongchan/edit')}') ],text:"操作中,请稍后..." ,container : '#bdcWrap' });
	    	bindDeleteEvent({ linkClass:'a.deleteFile',rowPrefix: '#file' });
	    	bindAjaxSubmit("#addForm");
	    	
	    	
    	})
    </script>
{include file="common/my_footer.tpl"}

